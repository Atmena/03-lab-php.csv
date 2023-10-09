<?php
require_once('DownloadCsv.php');

class MainPage {
    public function generateFileUploadForm() {
        echo '<form id="csvForm" enctype="multipart/form-data" action="index.php" method="POST">
            <div class="form-group">
                <label for="csvFile">Déposer un fichier CSV :</label>
                <input type="file" class="form-control" id="csvFile" name="csvFile" accept=".csv" required onchange="loadCSV()">
            </div>
            <button type="submit" class="btn btn-primary" name="uploadCsv">Générer le fichier</button>
        </form>';
    
        // Ajoutez la fonction loadCSV() ici
        echo '<script>
            function loadCSV() {
                // Code pour gérer le chargement du fichier CSV ici
                console.log("Fichier CSV sélectionné");
            }
        </script>';
    }
    
    public function generateFilters(array $headers, $fileName) {
        echo '<div id="filtersContainer" class="container mt-4 flex-column">';
        
        // Affichez le nom du fichier
        echo '<div class="row mb-3"><div class="col-md-12"><h4>Nom du fichier : ' . $fileName . '</h4></div></div>';
    
        // Ouverture du formulaire
        echo '<form action="index.php" method="post">';
    
        foreach ($headers as $header) {
            echo '<div class="row mb-3">
                <div class="col-md-6">
                    <input type="checkbox" id="checkFilter' . $header . '" name="applyFilters[' . $header . ']" value="1">
                    <label for="checkFilter' . $header . '">' . $header . ' :</label>    
                </div>
                <div class="col-md-3">
                    <select class="form-control" id="filterType' . $header . '" name="filterTypes[]">
                        <option value=">">></option>
                        <option value="<"><</option>
                        <option value=">=">>=</option>
                        <option value="<="><=</option>
                        <option value="<>"><></option>
                        <option value="Commence par">Commence par</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="filterValue' . $header . '" name="filterValues[]">
                </div>
            </div>';
        }
    
        // Ajoutez le bouton de téléchargement
        echo '<div class="row">
            <div class="col-md-12">
                <input type="hidden" name="csvFileName" value="' . $fileName . '">
                <button type="submit" class="btn btn-primary" name="applyFiltersButton">Télécharger le CSV</button>
            </div>
        </div>';

        // Fermeture du formulaire
        echo '</form>
            </div>';
    }        

    public function processFileUpload() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_FILES['csvFile']) && $_FILES['csvFile']['error'] === UPLOAD_ERR_OK) {
                $tmpFileName = $_FILES['csvFile']['tmp_name'];
        
                if (mime_content_type($tmpFileName) == 'text/csv') {
                    $csvContent = file_get_contents($tmpFileName);
        
                    session_start();
                    $_SESSION['csvContent'] = $csvContent;
        
                    $uploadDir = 'uploads/';
                    $originalFileName = $_FILES['csvFile']['name'];
                    $uploadPath = $uploadDir . $originalFileName;
        
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
        
                    if (move_uploaded_file($tmpFileName, $uploadPath)) {
                        return $originalFileName; // Retournez le nom du fichier téléchargé
                    } else {
                        echo "Erreur lors du chargement du fichier.";
                    }
                } else {
                    echo "Le fichier doit être de type CSV.";
                }
            } else {
                echo "Aucun fichier n'a été téléchargé.";
            }
        }
        return false;
    }

    public function loadDownload($csvFileName) {
        if ($csvFileName) {
            // Créez une instance de DownloadCsv
            $downloadCsv = new DownloadCsv();
        
            // Appel de la méthode pour traiter le téléchargement
            $result = $downloadCsv->processDownload($csvFileName, array('success' => true, 'error' => ''));
            
            if ($result['success']) {
                // Redirigez ou affichez un message de succès, par exemple
                header('Location: index.php?success=1');
            } else {
                echo 'Erreur : ' . $result['error'];
            }
        }
    }    
}
?>