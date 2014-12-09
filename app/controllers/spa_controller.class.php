<?php

/**
 * Ajax Controller
 */
class SpaController {

  private static function isCleanOutput() {

    // Catch Output Buffer
    $unexpected_output = ob_get_contents();
    ob_end_clean();

    // If Unexected output was found
    if (!empty($unexpected_output)) {
      http_response_code('400');
      exit($unexpected_output);
    }

  }

  private static function addProtection ($array, $excludeFromClean = []) {

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

  public static function sendJson ($array, $excludeFromClean = []) {
    $result = [];
    if (is_array($array)) {
      $result = self::addProtection($array, $excludeFromClean);
    }
    else {
      while ($row = $array->fetch_assoc()) {
        $result[] = self::addProtection($row);
      }
    }

    echo json_encode($result);
    die();

  }

}
