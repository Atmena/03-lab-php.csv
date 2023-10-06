document.addEventListener("DOMContentLoaded", function () {
    var csvFileInput = document.getElementById("csvFile");
    var csvFileHidden = document.getElementById("csvFileHidden");

    csvFileInput.addEventListener("change", function () {
        var file = csvFileInput.files[0];
        if (file) {
            var reader = new FileReader();

            reader.onload = function (e) {
                var csvContent = e.target.result;

                // Afficher les filtres une fois le fichier chargé
                csvFileHidden.value = csvContent;
                csvFileInput.value = "";

                // Vous pouvez maintenant traiter csvContent côté serveur
                console.log("Fichier CSV chargé automatiquement.");

                // Générez les filtres ici en fonction du contenu du fichier CSV
                var filtersContainer = document.getElementById("filtersContainer");
                var headers = csvContent.split("\n")[0].split(",");

                headers.forEach(function (header) {
                    // Créez un élément de type checkbox
                    var checkbox = document.createElement("input");
                    checkbox.type = "checkbox";
                    checkbox.name = "filters[]";
                    checkbox.value = header;

                    // Créez une étiquette pour la checkbox
                    var label = document.createElement("label");
                    label.textContent = header;

                    // Créez un saut de ligne
                    var br = document.createElement("br");

                    // Ajoutez la checkbox, l'étiquette et le saut de ligne au conteneur de filtres
                    filtersContainer.appendChild(checkbox);
                    filtersContainer.appendChild(label);
                    filtersContainer.appendChild(br);
                });
            };

            // Lire le contenu du fichier en tant que texte
            reader.readAsText(file);
        }
    });
});