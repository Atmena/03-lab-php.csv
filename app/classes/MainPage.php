<?php
require_once('DownloadCsv.php');
require_once('SendMail.php');

class MainPage {
    public function generateFileUploadForm() {
        echo '<form id="csvForm" enctype="multipart/form-data" action="" method="POST">
            <div class="form-group mb-3">
                <label for="csvFile">Déposer un fichier CSV :</label>
                <input type="file" class="form-control" id="csvFile" name="csvFile" accept=".csv" required onchange="loadCSV()">
            </div>
            <button type="submit" class="btn btn-primary" name="uploadCsv">Générer le fichier</button>
        </form>';

        // Ajoute la fonction loadCSV()
        echo '<script>
            function loadCSV() {
                // Code pour gérer le chargement du fichier CSV ici
                console.log("Fichier CSV sélectionné");
            }
        </script>';
    }

    public function generateFilters(array $headers, $fileName) {
        echo '<div id="filtersContainer" class="container mt-4 flex-column">';
        
        // Affiche le nom du fichier
        echo '<div class="row mb-3"><div class="col-md-12"><h4>Nom du fichier : ' . $fileName . '</h4></div></div>';
    
        // Ouverture du formulaire
        echo '<form action="" method="post">';
    
        $i = 0;

        foreach ($headers as $header) {
            echo '<div class="row mb-3">
                <div class="col-md-6">
                    <input type="checkbox" id="checkFilter' . $header . '" name="applyFilters[]" value="' . $i . '">
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
            $i++;
        }
    
        // Ajout d'un bouton de téléchargement
        echo '<div class="row d-flex justify-content-start mb-3">
            <div class="col-md-2">
                <input type="hidden" name="csvFileName" value="' . $fileName . '">
                <button type="submit" class="btn btn-primary" name="applyFiltersButton">Télécharger le CSV</button>
            </div>';

        // Ajout d'une zone de texte pour récupérer l'e-mail du destinataire
        echo '<div class="col-md-2">
            <input type="email" class="form-control" placeholder="Insérer un mail" name="email">
        </div>';

        // Ajout du bouton "Envoyer par mail" à côté de l'input email
        echo '<div class="col-md-2">
                <button type="submit" class="btn btn-primary" name="sendMailButton">Envoyer par mail</button>
            </div>
        </div>';

        // Ferme le formulaire pour le téléchargement du CSV
        echo '</form>';

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
                        return $originalFileName; // Retourne le nom du fichier téléchargé
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
            // Créer une instance de DownloadCsv
            $downloadCsv = new DownloadCsv();
            
            // Vérifie si les filtres ont été appliqués
            if (isset($_POST['applyFilters']) && !empty($_POST['applyFilters'])) {
                // Appel de la méthode pour traiter le téléchargement
                $result = $downloadCsv->processDownload($csvFileName, $_POST['applyFilters']);
            } else {
                echo 'Aucun filtre n\'a été appliqué.';
                return;
            }
            
            if ($result['success']) {
                // Redirige ou affichez un message de succès, par exemple
                header('Location: index.php?success=1');
            } else {
                echo 'Erreur : ' . $result['error'];
            }
        }
    }
    
    public function loadMail($csvFileName) {
        if ($csvFileName) {
            // Créer une instance de SendMail
            $sendMail = new SendMail();
    
            // Récupérer l'adresse e-mail du destinataire depuis le formulaire
            $toEmail = isset($_POST['email']) ? $_POST['email'] : '';
    
            // Vérifier si les filtres ont été appliqués
            if (isset($_POST['applyFilters']) && !empty($_POST['applyFilters'])) {
                // Appel de la méthode pour traiter l'envoi par e-mail
                $result = $sendMail->processMail($toEmail, $csvFileName, $_POST['applyFilters'], $_POST['filterTypes'], $_POST['filterValues']);
                
                if ($result['success']) {
                    // Affichez un message de succès si l'envoi a réussi
                    echo 'E-mail envoyé avec succès à ' . $toEmail . ' !';
                } else {
                    echo 'Erreur lors de l\'envoi de l\'e-mail : ' . $result['message'];
                }
            } else {
                echo 'Aucun filtre n\'a été appliqué.';
            }
        }
    }    
}
?>