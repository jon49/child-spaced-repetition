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

  public static function sendProtectedJson ($object, $excludeFromClean = []) {

    self::isCleanOutput();

    $protected = xss::protections($object, $excludeFromClean);
    $camel = Util::keysToCamelCase($protected);
    echo json_encode($camel);
    die();

  }

  public static function sendJson ($array) {
    self::isCleanOutput();
    echo json_encode(Util::keysToCamelCase($array));
    die();
  }

}
