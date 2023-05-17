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
        // save from request body
        $data = $request->getParsedBody();
        $quiz_name = $data['name'];
        try {
            $result = exec("bash ".__DIR__ . "/../bin/create_quiz.sh " . $quiz_name);

            if (str_starts_with($result, "Quiz")) {
                // success
                $response = $response->withStatus(200);
                $data = array('message' => 'Le quiz ' . $quiz_name . ' a été créé avec succès');
                // // create quiz in mysql db

                 try {
                 $db = new PDO('mysql:host=localhost;dbname=qcm;charset=utf8', 'root', 'root');
                     $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                     $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                     $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                     $stmt = $db->prepare("INSERT INTO list (qcmName) VALUES (:quiz_name)");
                     $stmt->bindParam(':quiz_name', $quiz_name);
                     $stmt->execute();
                 } catch (PDOException $e) {
                     $response = $response->withStatus(500);
                     $data = array('message' => 'Erreur lors de la création du quiz ' . $quiz_name . ' : ' . $e->getMessage());
                 }
            } else {
                // erreur
                $response = $response->withStatus(304);
                $data = array('message' => 'Erreur lors de la création du quiz ' . $quiz_name);
            }
        } catch (Exception $e) {
            $response = $response->withStatus(500);
            $data = array('message' => 'Erreur lors de la création du quiz ' . $quiz_name . ' : ' . $e->getMessage());
        }
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($data));
        return $response;
    });
$app->post('/quiz/delete', function (Request $request, Response $response, $args) {
    // RÃ©cupÃ©rer les donnÃ©es depuis le corps de la requÃªte
    $data = $request->getParsedBody();
    $quiz_name = $data['name'];

    try {
        $result = exec("rm -r " . __DIR__ . "/../quiz_data/" . $quiz_name);

        if (empty($result)) {
            // SuccÃ¨s de la suppression du dossier

            $db = new PDO('mysql:host=localhost;dbname=qcm;charset=utf8', 'root', 'root');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            $stmt = $db->prepare("DELETE FROM list WHERE qcmName = 'QCM10'");
            //$stmt->bindParam(':quiz_name', $quiz_name);
            $stmt->execute();

            $response = $response->withStatus(200);
            $data = array('message' => 'Le quiz ' . $quiz_name . ' a Ã©tÃ© supprimÃ© avec succÃ¨s');
        } else {
            // Erreur lors de la suppression du dossier
            $response = $response->withStatus(500);
            $data = array('message' => 'Erreur lors de la suppression du quiz ' . $quiz_name);
        }
    } catch (PDOException $e) {
        // Erreur de la base de donnÃ©es
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
            // Retrieve the students_answers file
            $students_answers_file = __DIR__ . "/../quiz_data/" . $quiz_name . "/" . $quiz_name . ".students_answers";
            
            if (file_exists($students_answers_file)) {
                // Read the file content and convert it to JSON
                $students_answers = file_get_contents($students_answers_file);
                $students_answers_json = json_encode($students_answers);
                
                // Add the JSON data to the database
                try {
                    $conn = new PDO("mysql:host=localhost;dbname=qcm", "root", "root");
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    
                    // Update the 'list' table with the students_answers JSON
                    $stmt = $conn->prepare("UPDATE list SET students_answers = :students_answers WHERE qcmName = :quiz_name");
                    $stmt->bindParam(':students_answers', $students_answers_json);
                    $stmt->bindParam(':quiz_name', $quiz_name);
                    $stmt->execute();
                    
                    // Close the database connection
                    $conn = null;
                    
                    // Return success response
                    $response = $response->withStatus(200);
                    $data = array('message' => 'Le quiz ' . $quiz_name . ' a été préparé pour la correction et les réponses des étudiants ont été ajoutées à la base de données.');
                } catch(PDOException $e) {
                    // Error occurred while accessing the database
                    $response = $response->withStatus(304);
                    $data = array('message' => 'Erreur lors de la préparation de la correction ' . $quiz_name . '. Erreur de la base de données: ' . $e->getMessage());
                }
            } else {
                // Error: students_answers file not found
                $response = $response->withStatus(304);
                $data = array('message' => 'Erreur lors de la préparation de la correction ' . $quiz_name . '. Fichier students_answers non trouvé.');
            }
        } else {
            // Error occurred while preparing the correction
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
//$quiz_name = $data['name'];
        $quiz_name = "test2";
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
        $resumableChunkNumber = $request->getParsedBody()['resumableChunkNumber']; // numéro de chunk
        $resumableTotalChunks = $request->getParsedBody()['resumableTotalChunks']; // nombre total de chunks

        $tmp_name = $directory . 'temp/' . $resumableIdentifier . '/' . $resumableChunkNumber;
        $filePath = $directory . "list_id.csv";

        // Vérifie si le fichier existe déjà et si oui le supprimme
        if (file_exists($filePath)) {
            $result = exec("rm -r " . $filePath);
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


		// Convertit le fichier en JSON
        	$jsonData = json_encode(file_get_contents($filePath));
        	// Enregistre le fichier JSON dans la base de données
       	$pdo = new PDO('mysql:host=localhost;dbname=qcm', 'root', 'root');
       	$stmt = $pdo->prepare('UPDATE list SET list_student = :jsonData WHERE qcmName = :quiz_name');
        	$stmt->execute(array('jsonData' => $jsonData, 'quiz_name' => $quiz_name));

		

            // return json with success message and file path
            $data = array('message' => 'Le fichier list_id a été uploadé avec succès', 'path' => $filePath);
        } else {
            // return json with success message
            $data = array('message' => 'Le chunk ' . $resumableChunkNumber . ' a été uploadé avec succès');
        }
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($data));
        return $response->withStatus(200);
    });
};
