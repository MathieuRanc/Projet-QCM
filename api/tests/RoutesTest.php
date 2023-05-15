<?php

namespace Tests;

class RoutesTest extends TestCase
{
  public function testGetRoot()
  {
    $app = $this->getAppInstance();

    $request = $this->createRequest('GET', '/');
    $response = $app->handle($request);

    $this->assertEquals(200, $response->getStatusCode());
    $this->assertJsonStringEqualsJsonString(
      json_encode(['message' => 'Hello worlds lol mathieu en roue libre']),
      (string) $response->getBody()
    );
  }

  public function testPostQuizCreate()
  {
    $app = $this->getAppInstance();

    $request = $this->createRequest('POST', '/quiz/create');
    $request = $request->withParsedBody(['name' => 'test_quiz']);
    $response = $app->handle($request);

    $this->assertEquals(200, $response->getStatusCode());
    $this->assertJsonStringEqualsJsonString(
      json_encode(['message' => 'Le quiz test_quiz a été créé avec succès']),
      (string) $response->getBody()
    );
  }

  public function testPostQuizOmr()
  {
    $app = $this->getAppInstance();

    $request = $this->createRequest('POST', '/quiz/omr');
    $request = $request->withParsedBody(['name' => 'test_quiz']);
    $response = $app->handle($request);

    $this->assertEquals(200, $response->getStatusCode());
    $this->assertJsonStringEqualsJsonString(
      json_encode(['message' => 'Execution du script OMR réussie sur le quizz test_quiz']),
      (string) $response->getBody()
    );
  }

  public function testPostQuizPrepareCorrection()
  {
    $app = $this->getAppInstance();

    $request = $this->createRequest('POST', '/quiz/prepare_correction');
    $request = $request->withParsedBody(['name' => 'test_quiz']);
    $response = $app->handle($request);

    $this->assertEquals(200, $response->getStatusCode());
    $this->assertJsonStringEqualsJsonString(
      json_encode(['message' => 'Le quiz test_quiz a été preparé pour la correction ']),
      (string) $response->getBody()
    );
  }

  public function testPostQuizCorrect()
  {
    $app = $this->getAppInstance();

    $request = $this->createRequest('POST', '/quiz/correct');
    $request = $request->withParsedBody(['name' => 'test_quiz']);
    $response = $app->handle($request);

    $this->assertEquals(200, $response->getStatusCode());
    $this->assertJsonStringEqualsJsonString(
      json_encode(['message' => 'Le quiz test_quiz a été corrigé ']),
      (string) $response->getBody()
    );
  }

  public function testPostQuizOmrErrorsResolved()
  {
    $app = $this->getAppInstance();

    $request = $this->createRequest('POST', '/quiz/omr_errors_resolved');
    $request = $request->withParsedBody(['name' => 'test_quiz']);
    $response = $app->handle($request);

    $this->assertEquals(200, $response->getStatusCode());
    $this->assertJsonStringEqualsJsonString(
      json_encode(['message' => 'Execution du script OMR errors réussie sur le quizz test_quiz']),
      (string) $response->getBody()
    );
  }
}
