<?php
class MainPage {
    public function generateFileUploadForm() {
        echo '<form id="csvForm" enctype="multipart/form-data" action="index.php" method="post">
            <div class="form-group">
                <label for="csvFile">Déposer un fichier CSV :</label>
                <input type="file" class="form-control" id="csvFile" name="csvFile" accept=".csv" required onchange="loadCSV()">
            </div>
            <button type="submit" class="btn btn-primary">Générer le fichier</button>
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
    
        // Ajoutez le bouton de téléchargement
        echo '<div class="row">
            <div class="col-md-12">
                <form action="classes/DownloadCsv.php" method="post">
                    <input type="hidden" name="csvFileName" value="' . $fileName . '">
                    <button type="submit" class="btn btn-primary">Télécharger le CSV</button>
                </form>
            </div>
        </div>';
    
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