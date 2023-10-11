<?php
define('BASE_DIR', __DIR__);

require_once('classes/MainPage.php');
require_once('classes/CsvFilter.php');

require_once('./classes/Router.php');

$router = new Router();
$router->route($_SERVER['REQUEST_URI']);

$page = new MainPage();

$headers = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['uploadCsv'])) {
        // Le bouton "Générer le fichier" a été soumis
        $csvFileName = $page->processFileUpload();
        if ($csvFileName) {
        }
    } elseif (isset($_POST['applyFiltersButton'])) {
        // Le bouton "Télécharger le CSV" a été soumis
        if (isset($_POST['csvFileName'])) {
            $csvFileName = $_POST['csvFileName'];
            $page->loadDownload($csvFileName);
        } else {
            echo "Le nom du fichier CSV n'est pas défini.";
        }
    } elseif (isset($_POST['sendMailButton'])) {
        // Le bouton "Envoyer par mail" a été soumis
        $csvFileName = $_POST['csvFileName'];
        $page->loadMail($csvFileName);
    }

    if (isset($_SESSION['csvContent'])) {
        $csvContent = $_SESSION['csvContent'];
        $lines = explode("\n", $csvContent);
        $headers = explode(",", $lines[0]);

        // Extrayez le nom du fichier du chemin du fichier
        $csvFileName = basename($_FILES['csvFile']['name']);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de Filtres CSV</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex align-items-start justify-content-between">
            <h1 class="mb-4">Page de Filtres CSV</h1>
            <a href="/generator" class="text-secondary text-decoration-none"><p>Générer un fichier CSV</p></a>
        </div>
        <?php
        $page->generateFileUploadForm();
        ?>

        <div id="filtersContainer" class="container mt-4 flex-column">
            <?php
            if (!empty($headers)) {
                $page->generateFilters($headers, $csvFileName);
            }
            ?>
        </div>
    </div>
    <div id="downloadLinkContainer" style="display: none;"></div>
    <input type="file" id="csvFileHidden" style="display: none;">
</body>
</html>