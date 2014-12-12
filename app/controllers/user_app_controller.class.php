<?php

class UserAppController {

  public function render() {
    echo <<<html
      <script src="/js/app.js"></script>
html;
  }

  public function getStudentList() {
    $user = new User(UserLogin::acceptOrReject('/'));
    SpaController::sendProtectedJson($user->students());
  }

  public function getStudentCards($req) {
    $user = new User(UserLogin::acceptOrReject('/'));
    $student = new Student($req->params['id']);
    if ($user->user_id !== $student->user_id) {
      SpaController::sendJson(['error' => 'Incorrect student id.']);
    }
    $cardData = $student->cards();
    $protectedCards = [];
    foreach ($cardData as $key => $value) {
      $protectedCards[$key] = xss::protections($value);
    }
    $protectedCards['studentName'] = $student->student_name;
    shuffle($protectedCards['cards']);
    SpaController::sendJson($protectedCards);
  }

}
