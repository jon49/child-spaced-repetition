<?php

class Deck extends CustomModel {

//   public function getCardsById($cardIds) {
//
//     $ids = implode(',', $cardIds);
//     $getCardsByIdSqlStatement =<<<sql
//       SELECT card_id, repetition, `interval`, easiness_factor
//       FROM student_card
//       WHERE card_id IN ($ids)
//       AND student_id = {$this->student_id};
// sql;
//     $result = db::execute($getCardsByIdSqlStatement);
//     return $result;
//   }

//   public function updateCards($updatedCards) {
//
//     foreach ($updatedCards as $card) {
//       $updateCardsSqlStatement =<<<sql
//         UPDATE student_card
//         SET
//           due=UNIX_TIMESTAMP(NOW() + INTERVAL {$card['interval']} DAY),
//           `interval`={$card['interval']},
//           repetition={$card['repetition']},
//           easiness_factor={$card['easiness_factor']}
//         WHERE student_id={$this->student_id}
//         AND card_id={$card['card_id']};
// sql;
//       return db::execute($updateCardsSqlStatement);
//     }
//
//   }

  public function delete() {

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

}
