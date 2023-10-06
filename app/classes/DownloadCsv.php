<?php
require_once('CsvFilter.php');

class DownloadCsv {
    public function processDownload($csvFileName) {
        if (isset($_POST['applyFilters']) && isset($csvFileName)) {
            $csvContent = file_get_contents('../uploads/' . $csvFileName);

            // Appliquer les filtres
            $filteredContent = CsvFilter::applyFilters($csvContent);

            // Modifiez la ligne pour créer le nom du fichier de sortie
            $outputFileName = '../uploads/' . pathinfo($csvFileName, PATHINFO_FILENAME) . 'Update.csv';

            // Enregistrer le fichier filtré
            file_put_contents($outputFileName, $filteredContent);

            // Télécharger le fichier généré
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename="' . basename($outputFileName) . '"');
            readfile($outputFileName);
            exit;
        }
        
        return array('success' => false, 'error' => 'Les filtres n\'ont pas été appliqués.');
    }
}
?>