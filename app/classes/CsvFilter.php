<?php
class CsvFilter {
    public static function applyFilters($csvContent, $applyFilters, $filterTypes, $filterValues) {
        // Vérifiez si les filtres ont été appliqués
        if (!empty($applyFilters)) {
            $filteredContent = $csvContent;

            foreach ($applyFilters as $filterIndex) {
                $filterType = $filterTypes[$filterIndex];
                $filterValue = $filterValues[$filterIndex];

                $filteredContent = self::applyFilter($filteredContent, $filterType, $filterValue);
            }

            return $filteredContent;
        }

        return $csvContent;
    }

    private static function applyFilter($csvContent, $filterType, $filterValue) {
        // Vérifiez si les valeurs du formulaire sont définies
        if (!isset($_POST['applyFilters']) || !isset($filterValue)) {
            return $csvContent;
        }

        // Récupérer les valeurs du formulaire
        $filterColumn = $_POST['filterColumn' . $filterType];

        // Convertir les valeurs de colonne en entiers
        $filterColumn = intval($filterColumn);

        switch($filterType) {
        case '>';
            // Filtre pour les valeurs supérieures
            $lines = explode("\n", $csvContent);
            $filteredContent = $lines[0] . "\n"; // Conserver l'en-tête
            foreach ($lines as $line) {
                $values = explode(",", $line);
                if (isset($values[$filterColumn]) && floatval($values[$filterColumn]) > floatval($filterValue)) {
                    $filteredContent .= $line . "\n";
                }
            }
            break;
        
        case '<':
            // Filtre pour les valeurs inférieures
            $lines = explode("\n", $csvContent);
            $filteredContent = $lines[0] . "\n"; // Conserver l'en-tête
            foreach ($lines as $line) {
                $values = explode(",", $line);
                if (isset($values[$filterColumn]) && floatval($values[$filterColumn]) < floatval($filterValue)) {
                    $filteredContent .= $line . "\n";
                }
            }
            break;
        
        case '>=':
            // Filtre pour les valeurs supérieures ou égales
            $lines = explode("\n", $csvContent);
            $filteredContent = $lines[0] . "\n"; // Conserver l'en-tête
            foreach ($lines as $line) {
                $values = explode(",", $line);
                if (isset($values[$filterColumn]) && floatval($values[$filterColumn]) >= floatval($filterValue)) {
                    $filteredContent .= $line . "\n";
                }
            }
            break;
        
        case '<=':
            // Filtre pour les valeurs inférieures ou égales
            $lines = explode("\n", $csvContent);
            $filteredContent = $lines[0] . "\n"; // Conserver l'en-tête
            foreach ($lines as $line) {
                $values = explode(",", $line);
                if (isset($values[$filterColumn]) && floatval($values[$filterColumn]) <= floatval($filterValue)) {
                    $filteredContent .= $line . "\n";
                }
            }
            break;
        
        case '<>':
            // Filtre pour les valeurs différentes
            $lines = explode("\n", $csvContent);
            $filteredContent = $lines[0] . "\n"; // Conserver l'en-tête
            foreach ($lines as $line) {
                $values = explode(",", $line);
                if (isset($values[$filterColumn]) && $values[$filterColumn] !== $filterValue) {
                    $filteredContent .= $line . "\n";
                }
            }
            break;
        
        case 'Commence par':
            // Filtre pour les valeurs qui commencent par
            $lines = explode("\n", $csvContent);
            $filteredContent = $lines[0] . "\n"; // Conserver l'en-tête
            foreach ($lines as $line) {
                $values = explode(",", $line);
                if (isset($values[$filterColumn]) && strpos($values[$filterColumn], $filterValue) === 0) {
                    $filteredContent .= $line . "\n";
                }
            }
            break;    
        }    

        return $filteredContent;
    }
}
?>