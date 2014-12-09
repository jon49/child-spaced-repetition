<?php

class UserLogin{

  private static $home = 'http://laere.com';

  public static function logIn ($user_id) {
    $_SESSION['user_id'] = $user_id;
  }

  public static function isLoggedIn() {
    return is_numeric($_SESSION['user_id']);
  }

  public static function logOut() {
    unset($_SESSION['user_id']);
    header('Location: '.self::$home);
    die();
  }

  public static function getUserID() {
    return $_SESSION['user_id'];
  }

  public static function acceptOrReject($path) {
    if (self::isLoggedIn()) {
      return self::getUserID();
    }
    header('Location: '.self::$home.$path);
    die();
  }

}
