<?php
if (isset($_POST['generateCsv'])) {
    // Lire les fichiers texte de noms et prénoms
    $surname = file('nom.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $name = file('prenom.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $city = ['Dijon', 'Besançon', 'Belfort', 'Chalon-sur-Saône', 'Nevers', 'Auxerre', 'Mâcon', 'Montbéliard', 'Sens', 'Dole', ];

    // Créer le contenu du fichier CSV avec 200 lignes supplémentaires
    $csvContent = "Nom,Prénom,Âge,Ville\n";
    for ($i = 0; $i < 200; $i++) {
        $randomSurname = $surname[array_rand($surname)];
        $randomName = $name[array_rand($name)];
        $randomAge = rand(18, 70);
        $randomCity = $city[array_rand($city)];
        $csvContent .= "$randomSurname,$randomName,$randomAge,$randomCity\n";
    }

    // En-têtes HTTP pour le téléchargement du fichier
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="identity.csv"');

    // Sortie du contenu du fichier CSV
    echo $csvContent;
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Génération de fichier CSV</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <a href="../index.php" class="text-secondary text-decoration-none float-end">Retour</a>
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-body">
                        <h1 class="card-title text-center">Génération de fichier CSV</h1>
                        <form method="post" action="">
                            <div class="text-center">
                                <button type="submit" name="generateCsv" class="btn btn-primary">Générer le fichier CSV</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
