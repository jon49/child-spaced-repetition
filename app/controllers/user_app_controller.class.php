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

  // Determine if student matches current user
  // if matched return student, otherwise return error.
  private function getStudent($req) {
    $user = new User(UserLogin::acceptOrReject('/'));
    $student = new Student($req->params['id']);
    if ($user->user_id !== $student->user_id) {
      SpaController::sendJson(['error' => 'Incorrect student id.']);
    }
    return $student;
  }

  public function getStudentCards($req) {
    $student = $this->getStudent($req);
    $cardData = $student->todaysQuiz();
    $protectedCards = [];
    foreach ($cardData as $key => $value) {
      $protectedCards[$key] = xss::protections($value);
    }
    $protectedCards['studentName'] = $student->student_name;
    shuffle($protectedCards['cards']);
    SpaController::sendJson($protectedCards);
  }

  public function updateStudentCards($req) {
    $student = $this->getStudent($req);
    $putResult = Util::putDecodeZaphpa('cards', $req);
    $calculatedCards =
      new CardEvaluations($student, Util::keysToUnderscore($putResult));
    $newValues = $calculatedCards->newValues();
    $result = $student->updateCards($newValues);
    if ($result) {
      SpaController::sendJson(['message' => 'success']);
    }
    else {
      SpaController::sendJson(['error' => $result]);
    }
  }

}
