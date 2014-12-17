<?php

// Init
include($_SERVER['DOCUMENT_ROOT'] . '/app/core/initialize.php');
require_once ROOT.'/app/libraries/zaphpa-zaphpa-395db08/zaphpa.lib.php';

$router = new Zaphpa_Router();

// Main Sections
$router->addRoute([
  'path' => '/',
  'get' => ['HomeController', 'render']
]);

// Users
$router->addRoute([
  'path' => '/app/{page}',
  'get'  => ['UserAppController', 'render']
]);

$router->addRoute([
  'path' => '/app/{page}/{id}',
  'get'  => ['UserAppController', 'render']
]);

$router->addRoute([
  'path' => '/app/{page}/{id}/{subpage}',
  'get'  => ['UserAppController', 'render']
]);

// Log In/Out:
$router->addRoute([
  'path' => '/api/login',
  'post' => ['LoginController', 'login']
]);

$router->addRoute([
  'path' => '/api/logout',
  'get'  => ['UserLogin', 'logOut']
]);

// API
$router->addRoute([
  'path' => '/api/students',
  'get'  => ['UserAppController', 'getStudentList'],
  'post' => ['UserAppController', 'addStudent']
]);

$router->addRoute([
  'path'   => '/api/students/{id}',
  'put'    => ['UserAppController', 'editStudent'],
  'delete' => ['UserAppController', 'deleteStudent']
]);

$router->addRoute([
  'path' => '/api/students/{id}/cards',
  'get'  => ['UserAppController', 'getStudentCards'],
  'put' => ['UserAppController', 'updateStudentCards'],
  'handlers' => [
    'id' => Zaphpa_Constants::PATTERN_DIGIT
  ]
]);

$router->addRoute([
  'path' => '/api/students/{id}/decks',
  'get'  => ['UserAppController', 'getStudentDecks'],
  'put'  => ['UserAppController', 'toggleStudentDeck'],
  'handlers' => [
    'id' => Zaphpa_Constants::PATTERN_DIGIT
  ]
]);

$router->addRoute([
  'path' => '/api/decks',
  'get'  => ['UserAppController', 'getDeckList'],
  'post' => ['UserAppController', 'addDeck']
]);

$router->addRoute([
  'path'   => '/api/decks/{id}',
  'put'    => ['UserAppController', 'editDeck'],
  'delete' => ['UserAppController', 'deleteDeck']
]);

$router->addRoute([
  'path'   => '/api/decks/{id}/cards',
  'get'    => ['DeckAppController', 'getCardList'],
  'post'   => ['DeckAppController', 'addCard'],
  'put'    => ['DeckAppController', 'editCard'],
  'delete' => ['DeckAppController', 'deleteCard']
]);

try {
  $router->route();
} catch (Zaphpa_InvalidPathException $ex) {
  header('Content-Type: application/json;', TRUE, 404);
  $out = ['error' => 'not found'];
  die(json_encode($out));
}
