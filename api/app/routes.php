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
        $data = array('message' => 'Hello worlds lol mathieu');
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($data));
        return $response;
    });

    $app->get('/create_quiz/{id}', function (Request $request, Response $response, $args) {
        // return 
        $quiz_name=$args['id'];
        system($quiz_bin_dir."create_quiz.sh ".$quiz_name." &",$returnval);
        $data = array('message' => 'Correction quizz '.+$quiz_name.+$returnval);
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($data));
        return $response;
    });
    $app->get('/prepare_correction/{id}', function (Request $request, Response $response, $args) {
        // return 
        $quiz_name=$args['id'];
        system($quiz_bin_dir."prepare_correction.sh ".$quiz_name." &",$returnval);
        $data = array('message' => 'Correction quizz '.+$quiz_name.+$returnval);
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($data));
        return $response;
    });
    $app->get('/correct_quiz/{id}', function (Request $request, Response $response, $args) {
        // return 
        $quiz_name=$args['id'];
        system($quiz_bin_dir."create_quiz.sh ".$quiz_name." &",$returnval);
        $data = array('message' => 'Correction quizz '.+$quiz_name.+$returnval);
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($data));
        return $response;
    });
    $app->get('/omr/{id}', function (Request $request, Response $response, $args) {
        // return 
        $quiz_name=$args['id'];
        system($quiz_bin_dir."omr.sh ".$quiz_name." &",$returnval);
        $data = array('message' => 'Correction quizz '.+$quiz_name.+$returnval);
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($data));
        return $response;
    });
    $app->get('/omr_errors_resolved/{id}', function (Request $request, Response $response, $args) {
        // return 
        $quiz_name=$args['id'];
        system($quiz_bin_dir."omr_errors_resolved.sh ".$quiz_name." &",$returnval);
        $data = array('message' => 'Correction quizz '.+$quiz_name.+$returnval);
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($data));
        return $response;
    });

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });
};
