<?php

trait ModelUtils {

  use Validation;

  public function cleanInput($requiredInput, $input, $noQuote = []) {

    $underscore_keys = Util::keysToUnderscore($input);
    $boundedValues = Util::zip($requiredInput, $underscore_keys);
    $validatedValues = $this->validate($boundedValues);
    if (array_key_exists('failed', $validatedValues)) {
      return 'The required key "'
        .$validatedValues['failed']
        .'" was not provided!';
    }
    return db::auto_quote($validatedValues, $noQuote);
  }

}
