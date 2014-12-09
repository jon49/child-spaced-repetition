<?php

class UserAppController {

  public static function render() {
    echo <<<html
      <script src="/js/app.js"></script>
html;
  }

  public static function getStudentList() {
    $user = new User(UserLogin::acceptOrReject('/'));
    SpaController::sendJson($user->students());
  }

}
