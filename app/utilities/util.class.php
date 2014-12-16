<?php

class Util {

  function standardDeviation ($aValues, $bSample = false) {
    $fMean = array_sum($aValues) / count($aValues);
    $fVariance = 0.0;
    foreach ($aValues as $i) {
        $fVariance += pow($i - $fMean, 2);
    }
    $fVariance /= ( $bSample ? count($aValues) - 1 : count($aValues) );
    return (float) sqrt($fVariance);
  }

  public static function isAssoc ($array) {
    return (bool)count(array_filter(array_keys($array), 'is_string'));
  }

  public static function toCamelCase ($string) {
    $string_ = str_replace(' ', '', ucwords(str_replace('_',' ', $string)));
    return lcfirst($string_);
  }

  public static function toUnderscore ($string) {
    return strtolower(preg_replace('/([^A-Z])([A-Z])/', "$1_$2", $string));
  }

  // http://stackoverflow.com/a/1444929/632495
  function transformKeys($transform, &$array) {
    if (!$array) {
      return null;
    }
    foreach (array_keys($array) as $key):
      # Working with references here to avoid copying the value,
      # since you said your data is quite large.
      $value = &$array[$key];
      unset($array[$key]);
      # This is what you actually want to do with your keys:
      #  - remove exclamation marks at the front
      #  - camelCase to snake_case
      $transformedKey = call_user_func($transform, $key);
      # Work recursively
      if (is_object($value)) {
        $value = get_object_vars($value);
      }
      if (is_array($value)) self::transformKeys($transform, $value);
      # Store with new key
      $array[$transformedKey] = $value;
      # Do not forget to unset references!
      unset($value);
    endforeach;
  }

  public static function keysToCamelCase ($array) {
    self::transformKeys(['self', 'toCamelCase'], $array);
    return $array;
  }

  public static function keysToUnderscore ($array) {
    self::transformKeys(['self', 'toUnderscore'], $array);
    return $array;
  }

  public static function someString ($lengthGreaterThan, $value) {
    return (strlen($value) > $lengthGreaterThan) ? $value : false;
  }

  public static function mustBeAStringWithLengthGreaterThan ($integer) {
    return [FILTER_CALLBACK,
      ['options' => Util::partial(['Util', 'someString'], $integer)]];
  }

  public static function zip($keys, $input) {
      $zipped = [];
      // only accept the required keys into array
      // when not all required keys given throw error
      foreach ($keys as $key) {
          if (isset($input[$key])) $zipped[$key] = $input[$key];
          else throw new Exception('Not all keys assigned! '.$key);
      }
      // quote values
      return $zipped;
  }

  public static function partial () {
    $appliedArgs = func_get_args();
    return function() use($appliedArgs) {
      $function = array_shift($appliedArgs);
      return call_user_func_array(
        $function,
        array_merge($appliedArgs, func_get_args())
      );
    };
  }

  public static function putDecodeZaphpa ($key, $req) {
    $value = '['.key($req->get_var('{"'.$key.'":')).']';
    return json_decode($value);
  }

}

