<?php
require_once('CsvFilter.php');

class SendMail {
    public function processMail($toEmail, $csvFileName, $applyFilters, $filterTypes, $filterValues) {
        if (isset($csvFileName)) {
            $csvContent = file_get_contents('uploads/' . $csvFileName);
    
            // Appliquez les filtres en fonction des cases cochées et des filtres sélectionnés
            $filteredContent = CsvFilter::applyFilters($csvContent, $applyFilters, $filterTypes, $filterValues);
    
            // Configuration de l'envoi d'e-mail (à personnaliser)
            $subject = 'Fichier CSV Filtré'; // Sujet de l'e-mail
            $message = 'Veuillez trouver ci-joint le fichier CSV filtré.'; // Corps de l'e-mail
            $fromEmail = 'noreply@csvfilter.com'; // Adresse e-mail de l'expéditeur
            $headers = "From: $fromEmail\r\n";
            $headers .= "Content-Type: multipart/mixed; boundary=\"mixed_boundary\"\r\n";
    
            // Validation de l'adresse e-mail du destinataire
            if (filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
                // Délimitateur pour les parties de l'e-mail
                $boundary = "mixed_boundary";
    
                // Création de la partie texte
                $messageBody = "--$boundary\r\n";
                $messageBody .= "Content-Type: text/plain; charset=UTF-8\r\n";
                $messageBody .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
                $messageBody .= $message;
                $messageBody .= "\r\n\r\n";
    
                // Création de la pièce jointe du fichier filtré
                $messageBody .= "--$boundary\r\n";
                $messageBody .= "Content-Type: text/csv; name=\"$csvFileName\"\r\n";
                $messageBody .= "Content-Transfer-Encoding: base64\r\n";
                $messageBody .= "Content-Disposition: attachment; filename=\"$csvFileName\"\r\n\r\n";
                $messageBody .= chunk_split(base64_encode($filteredContent));
    
                // Terminateur de la partie mixte
                $messageBody .= "\r\n--$boundary--";
    
                // Envoi de l'e-mail avec le contenu filtré en pièce jointe
                $result = mail($toEmail, $subject, $messageBody, $headers);

                if ($result) {
                    echo $filteredContent;
                    exit;
                } else {;
                    error_log('Erreur lors de l\'envoi de l\'e-mail.');
                    return ['success' => false, 'message' => 'Erreur lors de l\'envoi de l\'e-mail.'];
                }
            } else {
                error_log('Adresse e-mail du destinataire invalide: ' . $toEmail);
                return ['success' => false, 'message' => 'Adresse e-mail du destinataire invalide.'];
            }
        }
    
        error_log('Les filtres n\'ont pas été appliqués.');
        return ['success' => false, 'message' => 'Les filtres n\'ont pas été appliqués.'];
    }         
}
?>