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
    $cleanedInput = array_map(
        function ($card) use($student) {
          return $student->cleanInput(
            ['card_id', 'lapsed_time'],
            get_object_vars($card),
            ['lapsed_time', 'card_id']);
        }, $putResult);
    $calculatedCards = new CardEvaluations($student, $cleanedInput);
    $newValues = $calculatedCards->newValues();
    $result = $student->updateCards($newValues);
    if ($result) {
      SpaController::sendJson(['message' => 'success']);
    }
    else {
      SpaController::sendJson(['error' => $result]);
    }
  }

  public function getStudentDecks($req) {
    $student = $this->getStudent($req);
    SpaController::sendProtectedJson($student->decks());
  }

  public function toggleStudentDeck ($req) {
    $student = $this->getStudent($req);
    $deckId = Util::objectFromZaphpa($req);
    if ($result = $student->toggleDeck($deckId)) {
      SpaController::sendJson(['message' => 'success']);
    }
    SpaController::sendJson(['error' => $result]);
  }

  public function editStudent($req) {
    $student = $this->getStudent($req);
    $data = Util::objectFromZaphpa($req);
    if ($result = $student->update($data)) {
      SpaController::sendJson(['message' => 'success']);
    }
    SpaController::sendJson(
      ['error' => 'Oops! Student wasn\'t renamed!']
    );
  }

  public function deleteStudent($req) {
    $student = $this->getStudent($req);
    if ($result = $student->delete()) {
      SpaController::sendJson(['message' => 'success']);
    }
    SpaController::sendJson(
      ['error' => 'Oops! Student wasn\'t deleted!']
    );
  }

  public function addStudent($req) {
    $user = new User(UserLogin::acceptOrReject('/'));
    $data = Util::objectFromZaphpa($req);
    $result = $user->createStudent($data);
    if ($id = $result->insert_id) {
      $student = new Student($id);
      $student_['student_name'] = $student->student_name;
      $student_['student_id'] = $student->student_id;
      SpaController::sendProtectedJson($student_);
    }
      SpaController::sendJson(['error' => 'New student not created.']);
  }

  // Determine if student matches current user
  // if matched return student, otherwise return error.
  private function getDeck($req) {
    $user = new User(UserLogin::acceptOrReject('/'));
    $deck = new Deck($req->params['id']);
    if ($user->user_id !== $deck->user_id) {
      SpaController::sendJson(['error' => 'Incorrect deck id.']);
    }
    return $deck;
  }

  public function getDeckList() {
    $user = new User(UserLogin::acceptOrReject('/'));
    SpaController::sendProtectedJson($user->decks());
  }

  public function addDeck($req) {
    $user = new User(UserLogin::acceptOrReject('/'));
    $data = Util::objectFromZaphpa($req);
    $result = $user->createDeck($data);
    if ($id = $result->insert_id) {
      $deck = new Deck($id);
      $deck_['deck_name'] = $deck->deck_name;
      $deck_['deck_id'] = $deck->deck_id;
      SpaController::sendProtectedJson($deck_);
    }
      SpaController::sendJson(['error' => 'New deck not created.']);
  }

  public function editDeck($req) {
    $deck = $this->getDeck($req);
    $data = Util::objectFromZaphpa($req);
    if ($result = $deck->update($data)) {
      SpaController::sendJson(['message' => 'success']);
    }
    SpaController::sendJson(
      ['error' => 'Oops! Deck wasn\'t renamed!']
    );
  }

  public function deleteDeck($req) {
    $deck = $this->getDeck($req);
    if ($result = $deck->delete()) {
      SpaController::sendJson(['message' => 'success']);
    }
    SpaController::sendJson(
      ['error' => 'Oops! Deck wasn\'t deleted!']
    );
  }

}
