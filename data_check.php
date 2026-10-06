<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$db = "schoolproject";

$data = mysqli_connect($host, $user, $password, $db);
if ($data === false) {
    die("Connection error: " . mysqli_connect_error());
}

$uploadDir = __DIR__ . '/uploads/students';
$maxUploadBytes = 2 * 1024 * 1024;
$allowedMimes = ['image/jpeg' => 'jpg', 'image/png' => 'png'];

function failWith(string $message): void
{
    $_SESSION['message'] = $message;
    header('location:index.php#admission-form');
    exit;
}

/**
 * Validates the upload and moves it into /uploads/students under a generated
 * name. Returns the stored file name, or null when the field is left empty.
 */
function storeStudentPhoto(array $file, string $uploadDir, int $maxUploadBytes, array $allowedMimes): ?string
{
    if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException(match ($file['error']) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'Photo is too large.',
            UPLOAD_ERR_PARTIAL => 'Photo upload was interrupted.',
            UPLOAD_ERR_NO_TMP_DIR, UPLOAD_ERR_CANT_WRITE => 'Server could not store the photo.',
            default => 'Photo upload failed.',
        });
    }

    if (!is_uploaded_file($file['tmp_name'])) {
        throw new RuntimeException('Invalid photo upload.');
    }

    if ($file['size'] <= 0 || $file['size'] > $maxUploadBytes) {
        throw new RuntimeException('Photo must be 2 MB or smaller.');
    }

    // Trust the file contents, not the browser-supplied name or type.
    $info = new finfo(FILEINFO_MIME_TYPE);
    $mime = $info->file($file['tmp_name']);
    if (!isset($allowedMimes[$mime])) {
        throw new RuntimeException('Only JPG and PNG photos are allowed.');
    }

    $dimensions = @getimagesize($file['tmp_name']);
    if ($dimensions === false) {
        throw new RuntimeException('That file is not a readable image.');
    }

    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
        throw new RuntimeException('Upload folder is not available.');
    }

    // Generated name: never trust the client file name.
    $fileName = date('Ymd_His') . '_' . bin2hex(random_bytes(8)) . '.' . $allowedMimes[$mime];

    if (!move_uploaded_file($file['tmp_name'], $uploadDir . '/' . $fileName)) {
        throw new RuntimeException('Could not save the photo.');
    }

    return $fileName;
}

if (isset($_POST['apply'])) {
    $studentId = trim($_POST['id'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (!preg_match('/^[0-9]{4,15}$/', $studentId)) {
        failWith('ID must be 4 to 15 digits and contain numbers only.');
    }

    if ($name === '' || !preg_match('/^[A-Za-z ]+$/', $name) || mb_strlen($name) > 100) {
        failWith('Please enter a valid name.');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 150) {
        failWith('Please enter a valid email.');
    }

    if (!preg_match('/^(98|97)[0-9]{8}$/', $phone)) {
        failWith('Phone number must start with 98 or 97 and be exactly 10 digits.');
    }

    if ($message === '' || mb_strlen($message) < 10) {
        failWith('Message must contain at least 10 characters.');
    }

    try {
        $photo = storeStudentPhoto($_FILES['photo'] ?? [], $uploadDir, $maxUploadBytes, $allowedMimes);
    } catch (RuntimeException $e) {
        failWith($e->getMessage());
    }

    if ($photo === null) {
        failWith('Please attach your photo.');
    }

    $stmt = mysqli_prepare($data, "INSERT INTO admission(student_id, name, email, phone, message, image)
        VALUES (?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'ssssss', $studentId, $name, $email, $phone, $message, $photo);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = "your aplication sent successfull";
        header('location:index.php');
        exit;
    }

    // Roll back the orphan file so a failed insert does not leave uploads behind.
    @unlink($uploadDir . '/' . $photo);

    $_SESSION['message'] = 'Apply failed: ' . mysqli_error($data);
    header('location:index.php#admission-form');
    exit;
}