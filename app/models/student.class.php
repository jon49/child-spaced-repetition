<?php

/**
 * User
 */
class Student extends CustomModel {

  public function cards() {

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
      SELECT deck_id, card_id, content, `interval`, easiness_factor
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

}
