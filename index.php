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
  'path' => '/app/{app}',
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
  'get'  => ['UserAppController', 'getStudentList']
]);

$router->addRoute([
  'path' => '/api/students/{id}',
  'get'  => ['UserAppController', 'getStudent']
]);

// Issue Route
$router->route();
