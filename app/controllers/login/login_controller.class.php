<?php

class LoginController {

  public static function login($req) {

    $user = (new User())->isValid($_POST);

    if ($user === null) {
      $user = new User($_POST);
    }

    if($user){
      $success['redirect'] = '/app/students';
      UserLogin::logIn($user->user_id);
      SpaController::sendJson($success);
    } else {
      $error['redirect'] = '/';
      $error['errormsg'] =
        'Please enter a valid User Name and Password.';
      SpaController::sendJson($error);
    }
    exit();
  }

}
