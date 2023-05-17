<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        // return JSON
        $data = array('message' => 'Hello worlds lol mathieu en roue libre');
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($data));
        return $response;
    });

    $app->post('/quiz/create', function (Request $request, Response $response, $args) {
        // Save from request body
        $data = $request->getParsedBody();
        $quiz_name = $data['name'];
        
        try {
            // Check database connection
            $db = new PDO('mysql:host=172.17.0.2;dbname=qcm;charset=utf8', 'root', 'root');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            
            // Check for duplicate quiz_name
            $stmt = $db->prepare("SELECT COUNT(*) FROM list WHERE qcmName = :quiz_name");
            $stmt->bindParam(':quiz_name', $quiz_name);
            $stmt->execute();
            $count = $stmt->fetchColumn();
            
            if ($count > 0) {
                $response = $response->withStatus(400);
                $data = array('message' => 'Le nom du quiz existe déjà');
            } else {
                // Execute create_quiz.sh
                $result = exec("bash ".__DIR__ . "/../bin/create_quiz.sh " . $quiz_name);
    
                if (str_starts_with($result, "Quiz")) {
                    // Success
                    $stmt = $db->prepare("INSERT INTO list (qcmName) VALUES (:quiz_name)");
                    $stmt->bindParam(':quiz_name', $quiz_name);
                    $stmt->execute();
                    
                    // Perform local actions
                    
                    $response = $response->withStatus(200);
                    $data = array('message' => 'Le quiz ' . $quiz_name . ' a été créé avec succès');
                } else {
                    // Error
                    $response = $response->withStatus(304);
                    $data = array('message' => 'Erreur lors de la création du quiz ' . $quiz_name);
                }
            }
        } catch (PDOException $e) {
            $response = $response->withStatus(500);
            $data = array('message' => 'Erreur lors de la création du quiz ' . $quiz_name . ' : ' . $e->getMessage());
        }
        
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($data));
        
        return $response;
    });
    $app->post('/quiz/delete', function (Request $request, Response $response, $args) {
        // Récupérer les données depuis le corps de la requête
        $data = $request->getParsedBody();
        $quiz_name = $data['name'];
    
        try {
            // Vérifier la connexion à la base de données
            $db = new PDO('mysql:host=172.17.0.2;dbname=qcm;charset=utf8', 'root', 'root');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    
            // Supprimer le dossier
            $result = exec("rm -r " . __DIR__ . "/../quiz_data/" . $quiz_name);
    
            if (empty($result)) {
                // Succès de la suppression du dossier
    
                // Supprimer la valeur dans la base de données
                $stmt = $db->prepare("DELETE FROM list WHERE qcmName = :quiz_name");
                $stmt->bindParam(':quiz_name', $quiz_name);
                $stmt->execute();
    
                // Effectuer les actions locales
    
                $response = $response->withStatus(200);
                $data = array('message' => 'Le quiz ' . $quiz_name . ' a été supprimé avec succès');
            } else {
                // Erreur lors de la suppression du dossier
                $response = $response->withStatus(500);
                $data = array('message' => 'Erreur lors de la suppression du quiz ' . $quiz_name);
            }
        } catch (PDOException $e) {
            // Erreur de la base de données
            $response = $response->withStatus(500);
            $data = array('message' => 'Erreur lors de la suppression du quiz ' . $quiz_name . ' : ' . $e->getMessage());
        } catch (Exception $e) {
            // Autre erreur
            $response = $response->withStatus(500);
            $data = array('message' => 'Erreur lors de la suppression du quiz ' . $quiz_name . ' : ' . $e->getMessage());
        }
    
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($data));
        return $response;
    });
    

    $app->post('/quiz/omr', function (Request $request, Response $response, $args) {
        // save from request body
        $data = $request->getParsedBody();
        $quiz_name = $data['name'];
        $result = exec("bash ".__DIR__ . "/../bin/omr.sh " . $quiz_name);
        if (str_starts_with($result, "All done")) {
            // success
            $response = $response->withStatus(200);
            $data = array('message' => 'Execution du script OMR réussie sur le quizz ' . $quiz_name);
        } else {
            // erreur
            $response = $response->withStatus(304);
            $data = array('message' => 'Erreur lors du script OMR ' . $quiz_name);
        }
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($data));
        return $response;
    });
    
    $app->post('/quiz/prepare_correction', function (Request $request, Response $response, $args) {
        // save from request body
        $data = $request->getParsedBody();
        $quiz_name = $data['name'];
        $result = exec("bash ".__DIR__ . "/../bin/prepare_correction.sh " . $quiz_name);
        if (str_starts_with($result, "All done")) {
            // success
            $response = $response->withStatus(200);
            $data = array('message' => 'Le quiz ' . $quiz_name . ' a été preparé pour la correction ');
        } else {
            // erreur
            $response = $response->withStatus(304);
            $data = array('message' => 'Erreur lors de la préparation de la correction ' . $quiz_name);
        }
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($data));
        return $response;
    });

    $app->post('/quiz/correct', function (Request $request, Response $response, $args) {
        // save from request body
        $data = $request->getParsedBody();
        $quiz_name = $data['name'];
        $result = exec("bash ".__DIR__ . "/../bin/correct_quiz.sh " . $quiz_name);
        if (str_starts_with($result, "All done")) {
            // success
            $response = $response->withStatus(200);
            $data = array('message' => 'Le quiz ' . $quiz_name . ' a été corrigé ');
        } else {
            // erreur
            $response = $response->withStatus(304);
            $data = array('message' => 'Erreur lors de la correction ' . $quiz_name);
        }
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($data));
        return $response;
    });

    $app->post('/quiz/omr_errors_resolved', function (Request $request, Response $response, $args) {
        // save from request body
        $data = $request->getParsedBody();
        $quiz_name = $data['name'];
        $result = exec("bash ".__DIR__ . "/../bin/omr_errors_resolved.sh " . $quiz_name);
        if (str_starts_with($result, "All done")) {
            // success
            $response = $response->withStatus(200);
            $data = array('message' => 'Execution du script OMR errors réussie sur le quizz ' . $quiz_name);
        } else {
            // erreur
            $response = $response->withStatus(304);
            $data = array('message' => 'Erreur lors du script OMR errors' . $quiz_name);
        }
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($data));
        return $response;
    });
    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });

    // exemple d'upload avec des chunks
    $app->post('/upload', function (Request $request, Response $response, $args) {
        $directory = __DIR__ . '/../src/Uploads/'; // répertoire de destination de l'upload
        $resumableIdentifier = $request->getParsedBody()['resumableIdentifier']; // identifiant de l'upload
        $resumableFilename = $request->getParsedBody()['resumableFilename']; // nom du fichier
        $resumableChunkNumber = $request->getParsedBody()['resumableChunkNumber']; // numéro de chunk
        $resumableTotalChunks = $request->getParsedBody()['resumableTotalChunks']; // nombre total de chunks

        $tmp_name = $directory . 'temp/' . $resumableIdentifier . '/' . $resumableChunkNumber;
        $filePath = $directory . $resumableFilename;

        // Vérifie si le fichier existe déjà
        if (file_exists($filePath)) {
            // Si oui, vérifie si le chunk a déjà été uploadé
            if (file_exists($tmp_name)) {
                $response->getBody()->write("Chunk already exists.");
                return $response->withStatus(200);
            }
        }

        // Crée le répertoire temporaire s'il n'existe pas
        if (!file_exists($directory . 'temp/' . $resumableIdentifier)) {
            mkdir($directory . 'temp/' . $resumableIdentifier, 0777, true);
        }

        // Enregistre le chunk sur le serveur
        move_uploaded_file($_FILES['file']['tmp_name'], $tmp_name);

        // Vérifie si tous les chunks ont été uploadés
        if ($resumableChunkNumber == $resumableTotalChunks) {
            // Réassemble les chunks en un seul fichier
            for ($i = 1; $i <= $resumableTotalChunks; $i++) {
                $chunk = $directory . 'temp/' . $resumableIdentifier . '/' . $i;
                $buffer = file_get_contents($chunk);
                file_put_contents($filePath, $buffer, FILE_APPEND);
                unlink($chunk);
            }

            // Supprime le répertoire temporaire
            rmdir($directory . 'temp/' . $resumableIdentifier);

            // return json with success message and file path
            $data = array('message' => 'Le fichier ' . $resumableFilename . ' a été uploadé avec succès', 'path' => $filePath);
        } else {
            // return json with success message
            $data = array('message' => 'Le chunk ' . $resumableChunkNumber . ' a été uploadé avec succès');
        }
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($data));
        return $response->withStatus(200);
    });
    $app->post('/quiz/uploadlist', function (Request $request, Response $response, $args) {
        $quiz_name = $request->getParsedBody()['name']; // nombre total de chunks
        $directory = __DIR__ . "/../quiz_data/" . $quiz_name; // répertoire de destination de l'upload
        $resumableIdentifier = $request->getParsedBody()['resumableIdentifier']; // identifiant de l'upload
        $resumableFilename = $request->getParsedBody()['resumableFilename']; // nom du fichier
        $resumableChunkNumber = $request->getParsedBody()['resumableChunkNumber']; // numéro de chunk
        $resumableTotalChunks = $request->getParsedBody()['resumableTotalChunks']; // nombre total de chunks

        $tmp_name = $directory . 'temp/' . $resumableIdentifier . '/' . $resumableChunkNumber;
        $filePath = $directory . $resumableFilename;

        // Vérifie si le fichier existe déjà
        if (file_exists($filePath)) {
            // Si oui, vérifie si le chunk a déjà été uploadé
            if (file_exists($tmp_name)) {
                $response->getBody()->write("Chunk already exists.");
                return $response->withStatus(200);
            }
        }

        // Crée le répertoire temporaire s'il n'existe pas
        if (!file_exists($directory . 'temp/' . $resumableIdentifier)) {
            mkdir($directory . 'temp/' . $resumableIdentifier, 0777, true);
        }

        // Enregistre le chunk sur le serveur
        move_uploaded_file($_FILES['file']['tmp_name'], $tmp_name);

        // Vérifie si tous les chunks ont été uploadés
        if ($resumableChunkNumber == $resumableTotalChunks) {
            // Réassemble les chunks en un seul fichier
            for ($i = 1; $i <= $resumableTotalChunks; $i++) {
                $chunk = $directory . 'temp/' . $resumableIdentifier . '/' . $i;
                $buffer = file_get_contents($chunk);
                file_put_contents($filePath, $buffer, FILE_APPEND);
                unlink($chunk);
            }

            // Supprime le répertoire temporaire
            rmdir($directory . 'temp/' . $resumableIdentifier);

            // return json with success message and file path
            $data = array('message' => 'Le fichier ' . $resumableFilename . ' a été uploadé avec succès', 'path' => $filePath);
        } else {
            // return json with success message
            $data = array('message' => 'Le chunk ' . $resumableChunkNumber . ' a été uploadé avec succès');
        }
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($data));
        return $response->withStatus(200);
    });
};
