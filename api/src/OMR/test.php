<?php
// Chargement de l'image de la feuille de QCM
$qcm = imagecreatefromjpeg('qcm_grayscale.jpg');

// Récupération des dimensions de l'image
$width = imagesx($qcm);
$height = imagesy($qcm);

// Définition des dimensions des bandes noires
$blackStripWidth = 10;
$blackStripHeight = 10;

// Initialisation d'un tableau pour enregistrer les emplacements des cases à cocher
$checkboxLocations = array();

// Boucle pour parcourir toutes les cases à cocher
for ($y = $blackStripHeight; $y < $height - $blackStripHeight; $y += $blackStripHeight) {
    for ($x = $blackStripWidth; $x < $width - $blackStripWidth; $x += $blackStripWidth) {
        // Récupération de la couleur du pixel au centre de la zone de case à cocher
        $color = imagecolorat($qcm, $x, $y);
        
        // Conversion de la couleur en niveaux de gris
        $gray = (int)(($color >> 16) * 0.299 + ($color >> 8 & 255) * 0.587 + ($color & 255) * 0.114);
        
        // Vérification si la zone est noire
        if ($gray < 128) {
            // Zone noire, enregistrement de l'emplacement de la case à cocher
            $checkboxLocations[] = array($x, $y);
        }
    }
}

// Enregistrement des emplacements des cases à cocher dans un fichier ou une base de données
file_put_contents('checkbox_locations.txt', json_encode($checkboxLocations));

// Libération de la mémoire utilisée par l'image
imagedestroy($qcm);
?>
