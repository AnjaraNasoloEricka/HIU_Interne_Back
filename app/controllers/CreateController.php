<?php

namespace app\controllers;

use flight\Engine;
use app\models\CreateModel;
use Flight;


class CreateController
{
	protected Engine $app;

	public function __construct($app) {
		$this->app = $app;
	}

    public function goToHomePage()
    {
        Flight::render('accueil');
    }

    public function createArticle()
    {
        $CreateModel = new CreateModel(Flight::db());
        $title = Flight::request()->data->titre;
        $description = Flight::request()->data->description;
        $date_create = Flight::request()->data->date_create;
        $date_modification = Flight::request()->data->date_modification;

        // $title = "Nouveau produit en vente";
        // $description = "Un article exclusif disponible dès aujourd'hui.";
        // $date_create = "2025-03-29";
        // $date_modification = "2025-03-29";        

        $creer = $CreateModel->create($title, $description, $date_create, $date_modification);
        $tab = 
        [
            'titre' => $title,
            'description' => $description,
            'date_create' => $date_create,
            'date_modification' => $date_modification
        ];

        if($creer){
            Flight::json($tab);
        } else{
            Flight::json(['erreur' => "tsy mandeha eh"]);
        }
    }

    public function find_ById($id)
    {
        $CreateModel = new CreateModel(Flight::db());

        try{
            if (empty($id)) {
                Flight::json(['erreur' => "ID article manquant "]);
                return;
            }

            $article = $CreateModel->findById($id);

            if(!empty($article)){
                $tab = [
                    'title' => $article['title'],
                    'description' => $article['description'],
                    'date_create' => $article['date_create'],
                    'date_modification' => $article['date_modification']
                ];
                Flight::json($tab);
            } else{
                Flight::json(['erreur' => "tsy mandeha ndray"]);
            }

        } catch (Exception $e) {
            Flight::json([
                'erreur' => "Erreur : " . $e->getMessage()
            ]);
        }  
    }

    public function askGPT()
    {
        // Récupération des données envoyées dans le body de la requête
        $data = json_decode(Flight::request()->getBody(), true);
    
        // Vérification si le champ "prompt" est bien envoyé
        if (!isset($data['prompt']) || empty($data['prompt'])) {
            Flight::json(["succes" => "no", "erreur" => "Le champ 'prompt' est requis et ne peut pas être vide"], 400);
            return;
        }
    
        $prompt = $data['prompt']; // Récupération de la valeur du prompt
    
        // Utilisation de la fonction map définie dans services.php pour récupérer la clé API
        $apiKey = Flight::openai_api_key();
    
        if (empty($apiKey)) {
            Flight::json(["succes" => "no", "erreur" => "Clé API non configurée"], 500);
            return;
        }
    
        // Préparation des données pour l'API OpenAI
        $payload = [
            "model" => "gpt-4o-mini",
            "messages" => [["role" => "user", "content" => $prompt]],
            "temperature" => 0.7
        ];
    
        // Initialisation de la requête cURL vers l'API OpenAI
        $ch = curl_init("https://api.openai.com/v1/chat/completions");
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
        
        // Décodage de la réponse JSON de l'API OpenAI
        $decodedResponse = json_decode($response, true);
        
        // Si le code n'est pas 200, il y a une erreur
        if ($httpCode !== 200) {
            $errorMessage = isset($decodedResponse['error']['message']) ? $decodedResponse['error']['message'] : "Erreur HTTP: " . $httpCode;
            Flight::json(["succes" => "no", "erreur" => $errorMessage], $httpCode);
            return;
        }
    
        // Vérification si la réponse de GPT-4o est bien reçue
        if (isset($decodedResponse['choices'][0]['message']['content'])) {
            $reponseGPT = $decodedResponse['choices'][0]['message']['content'];
            Flight::json(["succes" => "ok", "reponse" => $reponseGPT]);
        } else {
            Flight::json(["succes" => "no", "erreur" => "Réponse invalide de l'API: " . json_encode($decodedResponse)], 500);
        }
    }
    
}

