<?php

class xss {

  public static function protection($value) {
    if (is_string($value)) {
      return htmlentities($value);
    }
    else {
      return $value;
    }
  }

}
