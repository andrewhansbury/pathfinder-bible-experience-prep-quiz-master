<?php

// A bunch of code here is from http://php.net/manual/en/features.file-upload.php

session_start();

$errorMessage = "";
$displayName = $_POST["display-name"];
// Undefined | Multiple Files | $_FILES Corruption Attack
// If this request falls under any of them, treat it invalid.
if (!isset($_FILES['file-upload']['error']) || is_array($_FILES['upfile']['error'])) {
    $errorMessage = "Invalid parameters";
    die($errorMessage);
}

// Check $_FILES['file-upload']['error'] value.
switch ($_FILES['file-upload']['error']) {
    case UPLOAD_ERR_OK:
        break;
    case UPLOAD_ERR_NO_FILE:
        $errorMessage = "No file sent";
        break;
    case UPLOAD_ERR_INI_SIZE:
    case UPLOAD_ERR_FORM_SIZE:
        $errorMessage = "Exceeded filesize limit";
        break;
    default:
        $errorMessage = "Unknown error";
        break;
}

if ($errorMessage !== "") {
    die($errorMessage);
}

// Max upload size is 10 MB
if ($_FILES['file-upload']['size'] > 10 * 1024 * 1024 * 1024) {
    $errorMessage = "Exceeded filesize limit";
    die($errorMessage);
}

// DO NOT TRUST $_FILES['file-upload']['mime'] VALUE !!
// Check MIME Type by yourself.
$finfo = new finfo(FILEINFO_MIME_TYPE);
if (false === $ext = array_search($finfo->file($_FILES['file-upload']['tmp_name']), array('pdf' => 'application/pdf'), true)) {
    $errorMessage = "Invalid file type";
    die($errorMessage);
}

// You should name it uniquely.
// DO NOT USE $_FILES['file-upload']['name'] WITHOUT ANY VALIDATION!!
// On this example, obtain safe unique name from its binary data.
$fileName = generate_uuid() . '.pdf';
$filePath = 'Uploads/' . $fileName;
if (!move_uploaded_file($_FILES['file-upload']['tmp_name'], $filePath)) {
    $errorMessage = "Failed to move uploaded file";
    die($errorMessage);
}

// upload success? insert information into the database
// use try/catch to make sure we can delete the file if this fails
try {
    $params = [
        $filePath,
        $displayName
    ];
    $query = 'INSERT INTO StudyGuides (FilePath, DisplayName) VALUES (?, ?)';
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
}
catch (PDOException $e) {
    unlink($filePath);
    $errorMessage = "Error inserting database information";
    die($errorMessage);
}

// if we get here, we did everything right!
header("Location: upload-study-guide.php?success");

?>