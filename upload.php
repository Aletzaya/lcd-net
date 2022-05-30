<?php

$name = $_FILES["file"]["tmp_name"][0];
error_log("Document_root" . $_SERVER['DOCUMENT_ROOT']);
move_uploaded_file($name, $_SERVER['DOCUMENT_ROOT'] . "/lcd-net/lib/pdfs/$name");
error_log(print_r($_FILES["file"], true));
error_log("HOLA");
