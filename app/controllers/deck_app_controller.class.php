<?php

class DeckAppController {

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

  public function getCardList($req) {
    $deck = $this->getDeck($req);
    SpaController::sendProtectedJson($deck->cards());
  }

  public function addCard($req) {
    $deck = $this->getDeck($req);
    $data = Util::objectFromZaphpa($req);
    $result = $deck->createCard($data);
    if ($id = $result->insert_id) {
      $card = new Card($id);
      $card_['content'] = $card->content;
      $card_['card_id'] = $card->card_id;
      SpaController::sendProtectedJson($card_);
    }
      SpaController::sendJson(['error' => 'New card not created.']);
  }

  public function editCard($req) {
    $deck = $this->getDeck($req);
    $data = Util::objectFromZaphpa($req);
    if ($result = $deck->updateCard($data)) {
      SpaController::sendJson(['message' => 'success']);
    }
    SpaController::sendJson(
      ['error' => 'Oops! Card wasn\'t renamed!']
    );
  }

  public function deleteCard($req) {
    $deck = $this->getDeck($req);
    if ($result = $deck->deleteCard(Util::objectFromZaphpa($req))) {
      SpaController::sendJson(['message' => 'success']);
    }
    SpaController::sendJson(
      ['error' => 'Oops! Card wasn\'t deleted!']
    );
  }

}
