<?php
class CsvFilter {
    public static function applyFilters($csvContent) {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['applyFilters'])) {
            $filterTypes = $_POST['filterTypes'];
            $filteredContent = $csvContent;

            foreach ($filterTypes as $filterType) {
                $filteredContent = self::applyFilter($filteredContent, $filterType);
            }

            return $filteredContent;
        }

        return $csvContent;
    }

    private static function applyFilter($csvContent, $filterType) {
        // Récupérer les valeurs du formulaire
        $filterColumn = $_POST['filterColumn' . $filterType];
        $filterValue = $_POST['filterValue' . $filterType];
        
        // Découper le CSV en lignes
        $lines = explode("\n", $csvContent);
        
        // En-têtes de colonnes
        $headers = explode(",", $lines[0]);
        
        // Trouver l'index de la colonne à filtrer'
        $columnIndex = array_search($filterColumn, $headers);
        
        // Initialiser le contenu filtré
        $filteredContent = $lines[0] . "\n";
        
        // Appliquer le filtre en fonction du type
        if ($filterType === ">") {
            foreach ($lines as $line) {
                $values = explode(",", $line);
                if ($values[$columnIndex] > $filterValue) {
                    $filteredContent .= $line . "\n";
                }
            }
        } elseif ($filterType === "<") {
            foreach ($lines as $line) {
                $values = explode(",", $line);
                if ($values[$columnIndex] < $filterValue) {
                    $filteredContent .= $line . "\n";
                }
            }
        } elseif ($filterType === ">=") {
            foreach ($lines as $line) {
                $values = explode(",", $line);
                if ($values[$columnIndex] >= $filterValue) {
                    $filteredContent .= $line . "\n";
                }
            }
        } elseif ($filterType === "<=") {
            foreach ($lines as $line) {
                $values = explode(",", $line);
                if ($values[$columnIndex] <= $filterValue) {
                    $filteredContent .= $line . "\n";
                }
            }
        } elseif ($filterType === "<>") {
            foreach ($lines as $line) {
                $values = explode(",", $line);
                if ($values[$columnIndex] == $filterValue) {
                    $filteredContent .= $line . "\n";
                }
            }
        } elseif ($filterType === "Commence par") {
            foreach ($lines as $line) {
                $values = explode(",", $line);
                if (substr($values[$columnIndex], 0, 1) === $filterValue) {
                    $filteredContent .= $line . "\n";
                }
            }
        }
        
        return $filteredContent;
    }
}
?>