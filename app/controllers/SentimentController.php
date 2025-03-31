<?php

namespace app\controllers;

use Flight;

class SentimentController
{
    public function __construct() {}

    public function sentimentController()
    {
        // Récupération des données envoyées dans le body de la requête
        $data = json_decode(Flight::request()->getBody(), true);
    
        // Vérification si le champ "inputs" est bien envoyé
        if (!isset($data['inputs']) || empty($data['inputs'])) {
            Flight::json(["success" => "no", "error" => "Le champ 'inputs' est requis et ne peut pas être vide"], 400);
            return;
        }
    
        $inputs = $data['inputs']; // Récupération du texte à analyser
    
        // Utilisation de la fonction map pour récupérer la clé API Hugging Face
        $apiKey = Flight::hf_api_key(); // Fonction qui récupère ta clé API Hugging Face
    
        if (empty($apiKey)) {
            Flight::json(["success" => "no", "error" => "Clé API Hugging Face non configurée"], 500);
            return;
        }
    
        // Préparation des données pour l'API Hugging Face
        $payload = [
            "inputs" => $inputs,
            "options" => ["wait_for_model" => true]
        ];
    
        // Initialisation de la requête cURL vers Hugging Face
        $ch = curl_init("https://api-inference.huggingface.co/models/cmarkea/distilcamembert-base-sentiment");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . $apiKey,
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Timeout de 30 secondes
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // Vérification SSL
    
        // Exécution de la requête et récupération de la réponse
        $response = curl_exec($ch);
    
        // Vérification des erreurs cURL
        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            Flight::json(["success" => "no", "error" => "Erreur cURL: " . $error], 500);
            return;
        }
    
        // Récupération du code HTTP
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    
        // Décodage de la réponse JSON
        $decodedResponse = json_decode($response, true);
    
        // Gestion des erreurs API
        if ($httpCode !== 200) {
            $errorMessage = isset($decodedResponse['error']['message']) ? $decodedResponse['error']['message'] : "Erreur HTTP: " . $httpCode;
            Flight::json(["success" => "no", "error" => $errorMessage], $httpCode);
            return;
        }
    
        // Vérification du contenu de la réponse
        if (isset($decodedResponse[0])) {
            $result = $decodedResponse[0]; // Le premier résultat d'analyse

            switch($result[0]["label"])
            {
                case "5 stars":
                    Flight::json(["success" => "ok", "sentiment" => 1]);
                    break;

                case "4 stars":
                    Flight::json(["success" => "ok", "sentiment" => 1]);
                    break;

                case "3 stars":
                    Flight::json(["success" => "ok", "sentiment" => 1]);
                    break;

                case "2 stars":
                    Flight::json(["success" => "ok", "sentiment" => 0]);
                    break;

                case "1 star":
                    Flight::json(["success" => "ok", "sentiment" => 0]);
                    break;
            }
        } else {
            Flight::json(["success" => "no", "error" => "Réponse invalide de l'API: " . json_encode($decodedResponse)], 500);
        }
    }
    
}
