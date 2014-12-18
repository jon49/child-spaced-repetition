<?php

class Deck extends CustomModel {

  public function delete() {

    $removeDeckDependents =<<<sql
      DELETE FROM student_deck WHERE deck_id={$this->deck_id};
sql;

    db::execute($removeDeckDependents);

    $removalOfDeck =<<<sql
      DELETE FROM deck WHERE deck_id={$this->deck_id};
sql;

    return db::execute($removalOfDeck);

  }

  public function update($input) {
    $cleanedInput = $this->cleanInput(['deck_name'], $input);
    if (is_string($cleanedInput)) return null;

    $newDeckNameStatement =<<<sql
      UPDATE deck
      SET deck_name={$cleanedInput['deck_name']}
      WHERE deck_id={$this->deck_id};
sql;

    return db::execute($newDeckNameStatement);

  }

  public function cards() {

    $cardListFromDB =<<<sql
      SELECT card_id, content
      FROM card
      WHERE deck_id={$this->deck_id};
sql;

    return db::execute($cardListFromDB);

  }

  public function createCard($input) {
    $cleanedInput = $this->cleanInput(['content'], $input);
    if (is_string($cleanedInput)) return null;

    $newCardSqlStatement =<<<sql
      INSERT INTO card (deck_id, content)
      VALUES ({$this->deck_id}, {$cleanedInput['content']});
sql;

    return db::execute($newCardSqlStatement);
  }

  public function updateCard($input) {
    $cleanedInput = $this->cleanInput(['content', 'card_id'], $input);
    if (is_string($cleanedInput)) return null;

    $updateCard =<<<sql
      UPDATE card
      SET content={$cleanedInput['content']}
      WHERE card_id={$cleanedInput['card_id']}
      AND deck_id={$this->deck_id};
sql;

    return db::execute($updateCard);
  }

  public function deleteCard($input) {
    $cleanedInput = $this->cleanInput(['card_id'], $input);
    if (is_string($cleanedInput)) return null;

    $deletionOfCard =<<<sql
      DELETE FROM card WHERE deck_id={$this->deck_id}
      AND card_id={$cleanedInput['card_id']};
sql;

    return db::execute($deletionOfCard);
  }

}
