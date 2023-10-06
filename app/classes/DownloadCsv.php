<?php
require_once('CsvFilter.php');

if (isset($_POST['applyFilters']) && isset($_POST['csvFileName'])) {
    $csvFileName = $_POST['csvFileName'];
    $csvContent = file_get_contents('../uploads/' . $csvFileName);

    // Appliquer les filtres
    $filteredContent = CsvFilter::applyFilters($csvContent);

    // Modifiez la ligne pour créer le nom du fichier de sortie
    $outputFileName = '../uploads/' . pathinfo($csvFileName, PATHINFO_FILENAME) . 'Update.csv';

    // Enregistrer le fichier filtré
    file_put_contents($outputFileName, $filteredContent);

    // Ne pas télécharger automatiquement, mais renvoyer le nom du fichier généré
    echo json_encode(array('success' => true, 'outputFileName' => basename($outputFileName)));
}

// Rediriger vers la page d'origine (index.php)
header('Location: ../index.php');
?>
