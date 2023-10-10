<?php
require_once('CsvFilter.php');
class DownloadCsv {
    public function processDownload($csvFileName, $applyFilters) {
        if (isset($csvFileName)) {
            $csvContent = file_get_contents('uploads/' . $csvFileName);
    
            // Récupérez les filtres depuis le formulaire
            $filterTypes = isset($_POST['filterTypes']) ? $_POST['filterTypes'] : [];
            $filterValues = isset($_POST['filterValues']) ? $_POST['filterValues'] : [];
    
            // Appliquez les filtres en fonction des cases cochées et des filtres sélectionnés
            $filteredContent = CsvFilter::applyFilters($csvContent, $applyFilters, $filterTypes, $filterValues);
    
            // Modifiez la ligne pour créer le nom du fichier de sortie
            $outputFileName = 'uploads/' . pathinfo($csvFileName, PATHINFO_FILENAME) . 'Update.csv';
    
            // Enregistrez le fichier filtré
            file_put_contents($outputFileName, $filteredContent);
    
            // Téléchargez le fichier généré
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename="' . basename($outputFileName) . '"');
            readfile($outputFileName);
            exit;
        }
    
        return array('success' => false, 'error' => 'Les filtres n\'ont pas été appliqués.');
    }    
}
?>