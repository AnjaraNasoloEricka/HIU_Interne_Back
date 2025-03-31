<?php

namespace app\controllers;

use Flight;

class PromptClassification
{
    public function askMistral()
    {
        $categories = [
            ["category" => "Slow Music", "link" => "https://www.youtube.com/watch?v=GuhUpKb6DMg"],
            ["category" => "Funny Videos", "link" => "https://www.youtube.com/watch?v=7uxcIz-sMyI"],
            ["category" => "Cartoons", "link" => "https://www.youtube.com/channel/UCMsgXPD3wzzt8RxHJmXH7hQ"],
            ["category" => "Educational", "link" => "https://www.youtube.com/education"],
            ["category" => "Cooking", "link" => "https://www.youtube.com/watch?v=6LDswBUN21o"],
            ["category" => "Sports", "link" => "https://www.youtube.com/channel/UCEgdi0XIXXZ-qJOFPf4JSKw"],
            ["category" => "Travel", "link" => "https://www.youtube.com/watch?v=QGmZ1rsKJ9c"],
            ["category" => "Technology", "link" => "https://www.youtube.com/watch?v=NLkE6H3ceWg"],
            ["category" => "Fitness", "link" => "https://www.youtube.com/watch?v=P6W8kwmwcno"],
            ["category" => "Movies", "link" => "https://www.youtube.com/watch?v=HfHEnb4NoOM"],
            ["category" => "Music Videos", "link" => "https://www.youtube.com/watch?v=yWL9dLK2meM"],
            ["category" => "DIY", "link" => "https://www.youtube.com/watch?v=Om0Eh8BkGHY"],
            ["category" => "Gaming", "link" => "https://www.youtube.com/watch?v=jldF9HXIp20"],
            ["category" => "Nature", "link" => "https://www.youtube.com/watch?v=s3G2kLruJJo"],
            ["category" => "News", "link" => "https://www.youtube.com/watch?v=AqvyzO3IPXc"],
            ["category" => "Health", "link" => "https://www.youtube.com/watch?v=T7ly5H1k9gM"],
            ["category" => "Science", "link" => "https://www.youtube.com/watch?v=39LMefHPNSw"],
            ["category" => "Automotive", "link" => "https://www.youtube.com/watch?v=e4nrthWzflw"],
            ["category" => "Comedy", "link" => "https://www.youtube.com/watch?v=iWaXjgDEfj0&pp=ygUGI2s0a2lk"],
            ["category" => "Documentaries", "link" => "https://www.youtube.com/watch?v=RZYsPuqsFNg"],
            ["category" => "Art", "link" => "https://www.youtube.com/watch?v=rRvsHXGlO9U&pp=ygUMI3BsYW5ldGhvZzEw"],
            ["category" => "Fashion", "link" => "https://www.youtube.com/watch?v=lcR4W1Jt2Vk"],
            ["category" => "Beauty", "link" => "https://www.youtube.com/watch?v=hpyAUT0Nk5U"],
            ["category" => "Animals", "link" => "https://www.youtube.com/watch?v=slXxPlDaiVs"],
            ["category" => "History", "link" => "https://www.youtube.com/watch?v=AneAgyUxBsg&pp=0gcJCfcAhR29_xXO"],
            ["category" => "Space", "link" => "https://www.youtube.com/watch?v=s3G2kLruJJo"],
            ["category" => "Food Reviews", "link" => "https://www.youtube.com/watch?v=CHICKEN_PASTA"],
            ["category" => "Tech Reviews", "link" => "https://www.youtube.com/watch?v=e4nrthWzflw"],
            ["category" => "Motivational", "link" => "https://www.youtube.com/watch?v=jPOtbq-YU9U"],
            ["category" => "Finance", "link" => "https://www.youtube.com/watch?v=n-kaSG-8_iU"],
            ["category" => "Real Estate", "link" => "https://www.youtube.com/watch?v=Rick_Steves_Europe"],
            ["category" => "Parenting", "link" => "https://www.youtube.com/watch?v=jPOtbq-YU9U"],
        ];


        // Récupération des données envoyées dans le body de la requête
        $data = json_decode(Flight::request()->getBody(), true);

        // Vérification si le champ "prompt" est bien envoyé
        if (!isset($data['prompt']) || empty($data['prompt'])) {
            Flight::json(["succes" => "no", "erreur" => "Le champ 'prompt' est requis et ne peut pas être vide"], 400);
            return;
        }

        $prompt = $data['prompt']; // Récupération de la valeur du prompt
        $promptFormatter = $this->formatterPrompt(($prompt));

        $typeDeRetour = $this->retourMistral($prompt);
        $typeDeRetour = $this->retourMistral($promptFormatter);
        if((int)$typeDeRetour==1){ // just return a text
            $retour = $this->retourMistral($prompt);
            Flight::json(["succes" => "ok", "reponse" => $retour]);
        }else{ // return a link
            $json_string = json_encode($categories, JSON_PRETTY_PRINT);
            $promptRetourneLien = "Donne moi le lien concernant cet prompt : \"".$prompt."\" dans cet json , juste le valeur de lien , ne mentionne plus des phrases autre que le lien . Voici le json_string".$json_string;
            $retour = $this->retourMistral($promptRetourneLien);
            Flight::json(["succes" => "ok", "reponse" => $retour]);
        }
    }

    public function formatterPrompt($prompt) {
        return "est ce que cet prompt veux t-il générer un texte ou il demande une vidéo ou de la musique , si le prompt veux une retour de type texte dit juste 1, sinon , 0 . Voici le promt : \"".$prompt."\"";
    }

    public function retourMistral($prompt) {
        // Utilisation de la fonction map définie dans services.php pour récupérer la clé API
        // Vous devrez probablement créer un service spécifique pour la clé API Mistral
        $apiKey = Flight::mistral_api_key();

        if (empty($apiKey)) {
            Flight::json(["succes" => "no", "erreur" => "Clé API Mistral non configurée"], 500);
            return;
        }

        // Préparation des données pour l'API Mistral
        // Utilisation du modèle "mistral-medium" par défaut (vous pouvez ajuster selon vos besoins)
        $payload = [
            "model" => "mistral-large-latest",
            "messages" => [["role" => "user", "content" => $prompt]],
            "temperature" => 0.7
        ];

        // Initialisation de la requête cURL vers l'API Mistral
        $ch = curl_init("https://api.mistral.ai/v1/chat/completions");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . $apiKey,
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Ajoute un timeout de 30 secondes
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // Vérifie le certificat SSL

        // Exécution de la requête et récupération de la réponse
        $response = curl_exec($ch);

        // Vérification des erreurs cURL
        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            Flight::json(["succes" => "no", "erreur" => "Erreur cURL: " . $error], 500);
            return;
        }

        // Récupération du code HTTP
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Décodage de la réponse JSON de l'API Mistral
        $decodedResponse = json_decode($response, true);

        // Si le code n'est pas 200, il y a une erreur
        if ($httpCode !== 200) {
            $errorMessage = isset($decodedResponse['error']['message']) ? $decodedResponse['error']['message'] : "Erreur HTTP: " . $httpCode;
            Flight::json(["succes" => "no", "erreur" => $errorMessage], $httpCode);
            return;
        }

        // Vérification si la réponse de Mistral est bien reçue
        if (isset($decodedResponse['choices'][0]['message']['content'])) {
            return $decodedResponse['choices'][0]['message']['content'];
        } else {
            return Flight::json(["succes" => "no", "erreur" => "Réponse invalide de l'API: " . json_encode($decodedResponse)], 500);
        }
    }
}
