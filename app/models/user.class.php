<?php

/**
 * User
 */
class User extends CustomModel {

  // determine if the user's password and user name are correct.
  public function isValid($input) {

    // validate user name
    $cleanedInput = $this->cleanInput(['email', 'password'], $input);
    if (is_string($cleanedInput)) return null;

    $sqlPasswordValidation =<<<sql
      SELECT user_id
      FROM user
      WHERE email = {$cleanedInput['email']}
      AND `password` =
      PASSWORD({$cleanedInput['password']});
sql;

    $result = db::execute($sqlPasswordValidation);
    $user = null;
    if ($row = $result->fetch_assoc()) {
      $user = new User($row['user_id']);
    }
    return $user;
  }

  /**
   * Insert User
   */
  protected function insert($input) {

    // Prepare SQL Values
    $cleanedInput = $this->cleanInput(
      ['email', 'password'],
      $input
    );

    if (is_string($cleanedInput)) {
      return null;
    }

    $passwordInsert =<<<sql
      INSERT INTO
        user (email, `password`)
        VALUES ({$cleanedInput['email']},
        PASSWORD(CONCAT(timestamp, {$cleanedInput['password']})));
sql;

    // Insert
    $results = db::execute($passwordInsert);

    // Return the Insert ID
    return $results->insert_id;

  }

  /**
   * Update User
   */
  public function updateEmail($input) {

    // Prepare SQL Values
    $cleanedInput = $this->cleanInput(
      ['email'], $input
    );

    if (is_string($cleanedInput)) {
      return null;
    }

    // Update
    db::update(
      'user',
      $cleanedInput,
      "WHERE user_id = {$this->user_id}"
    );

    // Return a new instance of this user as an object
    return new User($this->user_id);
  }

  public function students() {

    $studentsSqlStatement =<<<sql
      SELECT student_id, student_name
      FROM student
      WHERE user_id = {$this->user_id};
sql;

    return db::execute($studentsSqlStatement);
  }

  public function createStudent($input) {

    $cleanedInput = $this->cleanInput(
      ['student_name'], $input
    );

    if (is_string($cleanedInput)) {
      return null;
    }

    $newStudentOnDatabase =<<<sql
      INSERT INTO
        student (user_id, student_name)
        VALUES ({$this->user_id}, {$cleanedInput['student_name']});
sql;

    return db::execute($newStudentOnDatabase);

  }

  public function decks() {

    $decksSqlStatement =<<<sql
      SELECT deck_id, deck_name
      FROM deck
      WHERE user_id = {$this->user_id};
sql;

    return db::execute($decksSqlStatement);
  }

  public function createDeck($input) {

    $cleanedInput = $this->cleanInput(
      ['deck_name'], $input
    );

    if (is_string($cleanedInput)) {
      return null;
    }

    $newDeckOnDatabase =<<<sql
      INSERT INTO
        deck (user_id, deck_name)
        VALUES ({$this->user_id}, {$cleanedInput['deck_name']});
sql;

    return db::execute($newDeckOnDatabase);

  }

}
