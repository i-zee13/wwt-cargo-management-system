<?php
$zipFile = 'vendor.zip';
$extractTo = './';
echo "PHP is working!";
die;
// Check if the ZIP file exists
if (!file_exists($zipFile)) {
    die("Error: The file '$zipFile' does not exist.");
}

// Use shell command to unzip
$output = shell_exec("unzip $zipFile -d $extractTo");
if ($output === null) {
    echo "Error: Unable to extract the ZIP file.";
} else {
    echo "Success: The ZIP file has been extracted.";
}
?>
