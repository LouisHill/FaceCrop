<?php

require 'FaceCrop.php';

$imageData = file_get_contents($_FILES["pic"]["tmp_name"]); 
$faceCrop = new FaceCrop("**API KEY**");
$faces = $faceCrop->cropFaces($imageData);
