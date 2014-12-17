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
      SELECT deck_id, deck_name, active
      FROM student_deck
      NATURAL JOIN deck
      WHERE student_id = {$this->student_id};
sql;

    return db::execute($getDecksSqlStatement);

  }

  public function toggleDeck($deckId) {

    $cleanedInput = $this->cleanInput(['deck_id'], $deckId);

    $deckUpdate =<<<sql
      UPDATE student_deck
      SET active=IF(active, 0, 1)
      WHERE student_id={$this->student_id}
      AND deck_id={$cleanedInput['deck_id']};
sql;

    $result = db::execute($deckUpdate);
    return $result;

  }

}
