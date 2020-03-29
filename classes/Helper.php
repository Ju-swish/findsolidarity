<?php

class Helper {

  /**
  * Generates a random string of integers for the unique ID
  */
  public static function random_str() {

    // Generate a 4 digit long random number
    $random_int = rand(1000, 9999);
    // Convert the random generated number into a string
    $random_str = (string) $random_int;
    // Default string
    $string = "000000";
    $str_array = array();

    // Add the random_str to array
    for($i = 0; $i < strlen($random_str); $i++) {
      $str_array[] = $random_str[$i];
    }

    /* Create the final string
     For example 4 digit long random number was 1942
     The final string would look like this: "2400000019"
    */
    $string = $str_array[3] . $str_array[2] . $string . $str_array[0] . $str_array[1];

    return $string;

  }// random_str()

  /**
  * Converts the random string into base 34 mostly the random string is base 10
  * The random string comes from random_str() Function
  *
  * Script used from the php manuel
  * @see https://www.php.net/manual/en/function.base-convert.php#109660
  */
  public static function str__base_convert($str, $frombase=10, $tobase = 34) {

    $str = trim($str);

    if (intval($frombase) != 10) {
      $len = strlen($str);
      $str_q = 0;

      for($i = 0; $i < $len; $i++) {
        $str_r = base_convert($str[$i], $frombase, 10);
        $str_q = bcadd(bcmul($str_q, $frombase), $str_r);
      }
    } else {
      $str_q = $str;
    }

    // If integer value of $tobase is not equal 10
    if (intval($tobase) != 10) {
      $str_s = '';
      while (bccomp($str_q, '0', 0) > 0) {
        $str_r = intval(bcmod($str_q, $tobase));
        $str_s = base_convert($str_r, 10, $tobase) . $str_s;
        $str_q = bcdiv($str_q, $tobase, 0);
      }
    } else {
      $str_s = $str_q;
    }

    return strtoupper($str_s);

  }// str__base_convert

  public static function uniqueId() {

    // Get the random string
    $rand_str = self::random_str();
    $unique_ID = self::str__base_convert($rand_str, 10, 34);

    return $unique_ID;

  }// unique_ID()

  /**
  * Generates token code for email activation
  */
  public static function getToken() {

    // Generate token
    $chars = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $charStr = "";
    for($i = 0; $i < 25; $i++) {
      $charStr .= $chars[mt_rand(0, strlen($chars)-1)];
    }
    $token = substr($charStr, 0, 25);
    $token = $charStr;

    return $token;

  }// getToken()

}// End Class

?>
