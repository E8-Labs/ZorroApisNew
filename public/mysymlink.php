<?php

$targetFolder = $_SERVER['DOCUMENT_ROOT'].'/zorro/storage/app/public';
$linkFolder = $_SERVER['DOCUMENT_ROOT'].'/zorro/public/storage';
symlink($targetFolder,$linkFolder);
// echo 'Symlink completed';


echo $targetFolder;
echo "<br>";
echo $linkFolder;