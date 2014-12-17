<?php

class CardEvaluations {

  private $student;
  private $cards;
  private $testedCards;

  private function normalizeCards($testedCards) {
    $times = array_column($testedCards, 'lapsed_time');
    $average = array_sum($times)/count($times);
    $stdev = Util::standardDeviation($times);
    $grades =
      array_reduce($times, function ($acc, $time) use ($average, $stdev) {
        $normalized = ($average - $time) / $stdev;
        $acc[] = ($normalized >  1.0) ? 5
                      : ($normalized >  0.5) ? 4
                      : ($normalized >  0.0) ? 3
                      : ($normalized > -0.5) ? 2
                      : ($normalized > -1.0) ? 1
                      : 0;
        return $acc;
      }, []);
    $result = [];
    foreach ($testedCards as $key => $card) {
      $card['grade'] = $grades[$key];
      $result[] = $card;
    }
    return $result;
  }

  public function __construct ($student, $testedCards) {
    $this->cards =
      $student->getCardsById(array_column($testedCards, 'card_id'));
    $this->student = $student;
    $this->testedCards = $this->normalizeCards($testedCards);
  }

  // set new card setting values.
  public function newValues () {

    $getCard = function ($cardId) {
      $result =
        array_filter($this->testedCards, function ($card) use ($cardId){
          return $card['card_id'] == $cardId;
        });
        $result = array_values($result)[0];
        return $result;
    };

    $new = [];
    while ($row = $this->cards->fetch_assoc()) {

      // match current row with tested card
      $testedCard = $getCard($row['card_id']);
      if (!$testedCard) {
        continue;
      }

      // set new interval/repetition values
      if ($testedCard['grade'] >= 3) {
        if ($row->repetition = 0) {
          $testedCard['interval'] = 1;
          $testedCard['repetition'] = 1;
        }
        else if ($row->repetition = 1) {
          $testedCard['interval'] = 4;
          $testedCard['repetition'] = 2;
        }
        else {
          $testedCard['interval'] =
            round($row->interval * $row->easiness_factor);
          $testedCard['repetition'] = $row->repetition + 1;
        }
      }
      else {
        $testedCard['interval'] = 1;
        $testedCard['repetition'] = 0;
      }

      // set new easiness factor
      $ef =
        $row->easiness_factor + (0.1 - (5 - $testedCard['grade'])
        * (0.08 + (5 - $testedCard['grade']) * 0.02));
      if ($ef < 1.3) {
        $ef = 1.3;
      }
      $testedCard['easiness_factor'] = $ef;

      $new[] = $testedCard;

    }

    return $new;

  }

}
