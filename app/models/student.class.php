<?php

/**
 * User
 */
class Student extends CustomModel {

  public function todaysQuiz() {

    $studentID = $this->student_id;

    $limitSqlStatement =<<<sql
      SELECT queue_limit
      FROM setting
      WHERE student_id = {$studentID};
sql;

    $limitResult = db::execute($limitSqlStatement);
    $limit = 50;
    if ($row = $limitResult->fetch_assoc()) {
      $limit = $row['queue_limit'];
    }

    $hintSqlStatement =<<<sql
      SELECT deck_id, hint
      FROM student_deck
      NATURAL JOIN deck
      WHERE student_id = {$studentID}
      AND active = 1;
sql;

    $resultHints = db::execute($hintSqlStatement);

    $cardSqlStatement =<<<sql
      SELECT deck_id, card_id, content
      FROM student_deck
      NATURAL JOIN deck
      NATURAL JOIN setting
      NATURAL JOIN card
      NATURAL JOIN student_card
      WHERE student_id = {$studentID}
      AND active = 1
      AND UNIX_TIMESTAMP(NOW() + INTERVAL 8 HOUR) > due
      ORDER BY due DESC
      LIMIT {$limit};
sql;

    $resultCards = db::execute($cardSqlStatement);

    return ['hints' => $resultHints, 'cards' => $resultCards];

  }

  public function getCardsById($cardIds) {

    $ids = implode(',', $cardIds);
    $getCardsByIdSqlStatement =<<<sql
      SELECT card_id, repetition, `interval`, easiness_factor
      FROM student_card
      WHERE card_id IN ($ids)
      AND student_id = {$this->student_id};
sql;
    $result = db::execute($getCardsByIdSqlStatement);
    return $result;
  }

  public function updateCards($updatedCards) {

    foreach ($updatedCards as $card) {
      $updateCardsSqlStatement =<<<sql
        UPDATE student_card
        SET
          due=UNIX_TIMESTAMP(NOW() + INTERVAL {$card['interval']} DAY),
          `interval`={$card['interval']},
          repetition={$card['repetition']},
          easiness_factor={$card['easiness_factor']}
        WHERE student_id={$this->student_id}
        AND card_id={$card['card_id']};
sql;
      return db::execute($updateCardsSqlStatement);
    }

  }

  public function decks() {

    $getDecksSqlStatement =<<<sql
      SELECT deck.deck_id, deck_name, IFNULL(active, 0) AS active
      FROM deck
      LEFT OUTER JOIN student_deck
      ON deck.deck_id=student_deck.deck_id
      AND student_id={$this->student_id};
sql;

    return db::execute($getDecksSqlStatement);

  }

  public function toggleDeck($deckId) {

    $cleanedInput = $this->cleanInput(['deck_id'], $deckId, ['deck_id']);

    $activeStatement =<<<sql
      SELECT active
      FROM student_deck
      WHERE deck_id={$cleanedInput['deck_id']}
      AND student_id={$this->student_id};
sql;

    $active = db::execute($activeStatement);

    if ($row = $active->fetch_assoc()) {
      $update =<<<sql
        UPDATE student_deck
        SET active = !active
        WHERE deck_id={$cleanedInput['deck_id']}
        AND student_id={$this->student_id};
sql;
      return db::execute($update);
    }

    $newActive =<<<sql
      INSERT INTO student_deck (deck_id, student_id, active)
      VALUES ({$cleanedInput['deck_id']}, {$this->student_id}, 0);
sql;

    db::execute($newActive);

    $deck = new Deck($cleanedInput['deck_id']);
    $cards = $deck->cards();

    $cardList = '';
    while ($card = $cards->fetch_assoc()) {
      $cardList .= '('.$this->student_id.','.$card['card_id'].'),';
    }

    $cardList = substr($cardList, 0, -1);

    $addCardsToUser =<<<sql
      INSERT INTO student_card (student_id, card_id)
      VALUES $cardList;
sql;

    return db::execute($addCardsToUser);

  }

  public function delete() {

    $deletionOfStudentDecks =<<<sql
      DELETE FROM student_deck WHERE student_id={$this->student_id};
sql;

    db::execute($deletionOfStudentDecks);

    $deletionOfStudentCards =<<<sql
      DELETE FROM student_card WHERE student_id={$this->student_id};
sql;

    db::execute($deletionOfStudentCards);

    $removalOfStudent =<<<sql
      DELETE FROM student WHERE student_id={$this->student_id};
sql;

    return db::execute($removalOfStudent);

  }

  public function update($input) {
    $cleanedInput = $this->cleanInput(['student_name'], $input);
    if (is_string($cleanedInput)) return null;

    $newStudentNameStatement =<<<sql
      UPDATE student
      SET student_name={$cleanedInput['student_name']}
      WHERE student_id={$this->student_id};
sql;

    return db::execute($newStudentNameStatement);

  }

}
