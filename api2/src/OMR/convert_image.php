<?php
// Récupération de l'image originale
$originalImage = imagecreatefromjpeg('qcm.jpg');

// Création d'une image en niveaux de gris
$grayScale = imagecreatetruecolor(imagesx($originalImage), imagesy($originalImage));

// Conversion de l'image en niveaux de gris
imagefilter($originalImage, IMG_FILTER_GRAYSCALE);
imagecopy($grayScale, $originalImage, 0, 0, 0, 0, imagesx($originalImage), imagesy($originalImage));

// Enregistrement de l'image en niveaux de gris
imagejpeg($grayScale, 'qcm_grayscale.jpg');

// Libération de la mémoire utilisée par les images
imagedestroy($originalImage);
imagedestroy($grayScale);
