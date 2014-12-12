<?php

abstract class CustomModel extends Model {

  use ModelUtils;

  public function __construct($id = null) {

    //when value given work on customer otherwise do nothing
    if ($id)  {
      if (is_array($id)) {

        //when id for table is being worked with 
        //call the update method
        if (@$id[$this->get_table_id()] &&
              method_exists($this, 'update')) {
          $id = $this->update($id);
          parent::__construct($id);

        //when no table id is given call the insert method
        } else if (method_exists($this, 'insert')) {
          $id = $this->insert($id);
          parent::__construct($id);
        }
      }

      //otherwise get the record with value of $id
      if ($this->validate([$this->get_table_id() => $id])) {
        parent::__construct($id);
      };
    }
  }

  /* validation */

  protected function validators() {
    return [
      'user_id' => [FILTER_VALIDATE_INT],
      'password' => Util::mustBeAStringWithLengthGreaterThan(5),
      'email' => [FILTER_VALIDATE_EMAIL],
      'student_id' => [FILTER_VALIDATE_INT],
      'student_name' => Util::mustBeAStringWithLengthGreaterThan(0),
      'deck_id' => [FILTER_VALIDATE_INT],
      'deck_name' => Util::mustBeAStringWithLengthGreaterThan(0),
      'hint' => [FILTER_DEFAULT],
      'active' => [FILTER_VALIDATE_INT],
      'setting_id' => [FILTER_VALIDATE_INT],
      'queue_limit' => [FILTER_VALIDATE_INT],
      'card_id' => [FILTER_VALIDATE_INT],
      'content' => Util::mustBeAStringWithLengthGreaterThan(0),
      'due' => [FILTER_VALIDATE_INT],
      'interval' => [FILTER_VALIDATE_INT,
        ['options' => ['default' => 0, 'min_range' => 0]]],
      'easiness_factor' => [FILTER_VALIDATE_FLOAT,
        ['options' => ['default' => 1.3, 'min_range' => 1.3]]],
    ];
  }

}
