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

  private static function protectArray ($array, $excludeFromClean = []) {

    $result = [];

    if (is_array($array)) {
      foreach ($array as $key => $value) {
        if (!in_array($key, $excludeFromClean)) {
          $result[$key] = xss::protection($value);
        }
        else {
          $result[$key] = $value;
        }
      }
    }
    else {
      $result = xss::protection($array);
    }

    return $result;

  }

  public static function protections ($object, $excludeFromClean = []) {

    $result = [];
    if (is_array($object)) {
      $result = self::protectArray($object, $excludeFromClean);
    }
    elseif (is_string($object)) {
      $result = self::protection($object);
    }
    else {
      while ($row = $object->fetch_assoc()) {
        $result[] = self::protectArray($row);
      }
    }

    return $result;

  }

}
