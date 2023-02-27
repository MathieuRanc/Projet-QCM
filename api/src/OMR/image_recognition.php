<?php
// Chargement de l'image de la feuille de QCM
$qcm = imagecreatefromjpeg('qcm_grayscale.jpg');

// Récupération des dimensions de l'image
$width = imagesx($qcm);
$height = imagesy($qcm);

// Définition des dimensions des bandes noires
$blackStripWidth = 10;
$blackStripHeight = 10;

// Initialisation d'un tableau pour enregistrer les réponses
$answers = array();

// Boucle pour parcourir toutes les cases à cocher
for ($y = $blackStripHeight; $y < $height - $blackStripHeight; $y += $blackStripHeight) {
  for ($x = $blackStripWidth; $x < $width - $blackStripWidth; $x += $blackStripWidth) {
    // Récupération de la couleur du pixel au centre de la zone de case à cocher
    $color = imagecolorat($qcm, $x, $y);

    // Conversion de la couleur en niveaux de gris
    $gray = (int)(($color >> 16) * 0.299 + ($color >> 8 & 255) * 0.587 + ($color & 255) * 0.114);

    // Vérification si la zone est noire
    if ($gray < 128) {
      // Zone noire, case cochée
      $answers[] = 1;
    } else {
      // Zone blanche, case non cochée
      $answers[] = 0;
    }
  }
}

// Enregistrement des réponses dans un fichier ou une base de données
file_put_contents('answers.txt', implode(',', $answers));

// Libération de la mémoire utilisée par l'image
imagedestroy($qcm);
