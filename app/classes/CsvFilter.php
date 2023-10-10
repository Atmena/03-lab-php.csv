<?php
class CsvFilter {
    public static function applyFilters($csvContent, $applyFilters, $filterTypes, $filterValues) {
        // Vérifiez si les filtres ont été appliqués
        if (!empty($applyFilters)) {
            $filteredContent = $csvContent;

            foreach ($applyFilters as $filterIndex) {
                $filterType = $filterTypes[$filterIndex];
                $filterValue = $filterValues[$filterIndex];

                $filteredContent = self::applyFilter($filteredContent, $filterType, $filterValue, $filterIndex);
            }

            return $filteredContent;
        }

        return $csvContent;
    }

    private static function applyFilter($csvContent, $filterType, $filterValue, $filterIndex) {
        // Vérifiez si les valeurs du formulaire sont définies
        if (!isset($_POST['applyFilters']) || !isset($filterValue)) {
            return $csvContent;
        }

        $lines = explode("\n", $csvContent);
        $filteredContent = $lines[0] . "\n"; // Conserver l'en-tête

        switch ($filterType) {
            case '>':
                // Filtre pour les valeurs supérieures
                foreach ($lines as $line) {
                    $values = explode(",", $line);
                    if (isset($values[$filterIndex])) {
                        if (is_numeric($values[$filterIndex]) && is_numeric($filterValue)) {
                            if (floatval($values[$filterIndex]) > floatval($filterValue)) {
                                $filteredContent .= $line . "\n";
                            }
                        } else {
                            if (strcasecmp($values[$filterIndex], $filterValue) > 0) {
                                $filteredContent .= $line . "\n";
                            }
                        }
                    }
                }
                break;

            case '<':
                // Filtre pour les valeurs inférieures
                foreach ($lines as $line) {
                    $values = explode(",", $line);
                    if (isset($values[$filterIndex])) {
                        if (is_numeric($values[$filterIndex]) && is_numeric($filterValue)) {
                            if (floatval($values[$filterIndex]) < floatval($filterValue)) {
                                $filteredContent .= $line . "\n";
                            }
                        } else {
                            if (strcasecmp($values[$filterIndex], $filterValue) < 0) {
                                $filteredContent .= $line . "\n";
                            }
                        }
                    }
                }
                break;

            case '>=':
                // Filtre pour les valeurs supérieures ou égales
                foreach ($lines as $line) {
                    $values = explode(",", $line);
                    if (isset($values[$filterIndex])) {
                        if (is_numeric($values[$filterIndex]) && is_numeric($filterValue)) {
                            if (floatval($values[$filterIndex]) >= floatval($filterValue)) {
                                $filteredContent .= $line . "\n";
                            }
                        } else {
                            if (strcasecmp($values[$filterIndex], $filterValue) >= 0) {
                                $filteredContent .= $line . "\n";
                            }
                        }
                    }
                }
                break;

            case '<=':
                // Filtre pour les valeurs inférieures ou égales
                foreach ($lines as $line) {
                    $values = explode(",", $line);
                    if (isset($values[$filterIndex])) {
                        if (is_numeric($values[$filterIndex]) && is_numeric($filterValue)) {
                            if (floatval($values[$filterIndex]) <= floatval($filterValue)) {
                                $filteredContent .= $line . "\n";
                            }
                        } else {
                            if (strcasecmp($values[$filterIndex], $filterValue) <= 0) {
                                $filteredContent .= $line . "\n";
                            }
                        }
                    }
                }
                break;

            case '<>':
                // Filtre pour les valeurs différentes
                foreach ($lines as $line) {
                    $values = explode(",", $line);
                    if (isset($values[$filterIndex])) {
                        if (strcasecmp($values[$filterIndex], $filterValue) !== 0) {
                            $filteredContent .= $line . "\n";
                        }
                    }
                }
                break;

            case 'Commence par':
                // Filtre pour les valeurs qui commencent par
                foreach ($lines as $line) {
                    $values = explode(",", $line);
                    if (isset($values[$filterIndex])) {
                        if (stripos($values[$filterIndex], $filterValue) === 0) {
                            $filteredContent .= $line . "\n";
                        }
                    }
                }
                break;
        }

        return $filteredContent;
    }
}
?>