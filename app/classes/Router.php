<?php
class Router {
    public function route($url) {
        switch ($url) {
            case '/':
                require_once('./index.php');
                break;
            case '/generator':
                require_once('../csvGenerator/csvGenerator.php');
                break;
            default:
                // Gérer la route par défaut, par exemple, renvoyer une page d'erreur 404.
                header("HTTP/1.0 404 Not Found");
                echo 'Page non trouvée';
                break;
        }
    }
}
?>