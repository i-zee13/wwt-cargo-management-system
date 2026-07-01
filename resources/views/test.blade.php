<?php
$zip = new ZipArchive;
$res = $zip->open('wwc.zip');
if ($res === TRUE) {
    $zip->extractTo('.');
    $zip->close();
    echo 'Extraction successful!';
} else {
    echo 'Failed to extract.';
}
?>
