document.addEventListener("DOMContentLoaded", function() {
    var csvFileInput = document.getElementById("csvFile");
    var csvFileHidden = document.getElementById("csvFileHidden");

    csvFileInput.addEventListener("change", function() {
        var file = csvFileInput.files[0];
        if (file) {
            var reader = new FileReader();

            reader.onload = function(e) {
                var csvContent = e.target.result;

                // Afficher les filtres une fois le fichier chargé
                csvFileHidden.value = csvContent;
                csvFileInput.value = "";

                // Vous pouvez maintenant traiter csvContent côté serveur
                console.log("Fichier CSV chargé automatiquement.");
            };

            // Lire le contenu du fichier en tant que texte
            reader.readAsText(file);
        }
    });
});