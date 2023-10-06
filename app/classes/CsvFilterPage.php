<?php
class CsvFilterPage {
    public function generateFileUploadForm() {
        echo '<form id="csvForm" enctype="multipart/form-data" action="index.php" method="post">
            <div class="form-group">
                <label for="csvFile">Déposer un fichier CSV :</label>
                <input type="file" class="form-control" id="csvFile" name="csvFile" accept=".csv" required>
            </div>
            <button type="submit" class="btn btn-primary">Charger le fichier</button>
        </form>';
    }
    
    public function generateFilters(array $headers) {
        echo '<div id="filtersContainer" class="container mt-4 flex-column">';
        foreach ($headers as $header) {
            echo '<div class="row mb-3">
                <div class="col-md-6">
                    <input type="checkbox" id="filter' . $header . '" name="filters[]" value="' . $header . '">
                    <label for="filter' . $header . '">' . $header . ' :</label>
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
        var_dump($headers);
        echo '</div>';
    }

    public function processFileUpload() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_FILES['csvFile']) && $_FILES['csvFile']['error'] === UPLOAD_ERR_OK) {
                $tmpFileName = $_FILES['csvFile']['tmp_name'];
        
                if (mime_content_type($tmpFileName) == 'text/csv') {
                    $csvContent = file_get_contents($tmpFileName);
        
                    session_start();
                    $_SESSION['csvContent'] = $csvContent;
        
                    // Analysez le fichier CSV pour extraire les en-têtes
                    $lines = explode("\n", $csvContent);
                    $headers = str_getcsv($lines[0], ',');
        
                    $uploadDir = 'uploads/';
                    $originalFileName = $_FILES['csvFile']['name'];
                    $uploadPath = $uploadDir . $originalFileName;
        
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
        
                    if (move_uploaded_file($tmpFileName, $uploadPath)) {
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
    }    
}
?>
