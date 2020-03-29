<?php

  /**
  * Set display_errors to false or remove ini_set() after development
  * for security purposes
  */
  ini_set("display_errors", true);
  date_default_timezone_set("Europe/Berlin");
  define("DB_DSN", "mysql:host=localhost;dbname=solidarity_db;charset=utf8");
  define("DB_USERNAME", "YOUR_DATABASE_USERNAME"); // change this -> database username
  define("DB_PASSWORD", "YOUR_DATABASE_PASSWORD"); // change this -> database password
  define("HOMEPAGE_NUM_RESULTS", 5);

  // -----------------------------------------------------------------------
  // DEFINE NECESSARY CONSTANTS
  // -----------------------------------------------------------------------
  define("IMG_TYPE_FULLSIZE", "fullsize");  // Images Fullsize Path
  define("IMG_TYPE_THUMB", "thumb");        // Images Thumb Path
  define("IMG_PREFIX", "IMG_");             // Image name prefix -> used to name uploaded image
  define("THUMB_WIDTH", 200);               // Images Thumb Width
  define("JPEG_QUALITY", 85);               // Images JPEG Quality

  // -----------------------------------------------------------------------
  // DEFINE SEPERATOR ALIASES
  // -----------------------------------------------------------------------
  define("URL_SEPARATOR", '/');
  define("DS", DIRECTORY_SEPARATOR);
  define("PS", PATH_SEPARATOR);
  define("US", URL_SEPARATOR);

  //----------------------------------------------------------------------------
  // DEFINE RELATIVE PATHS
  //----------------------------------------------------------------------------
  define("ROOT_PATH", __DIR__);
  define("HTTP_ROOT_PATH", $_SERVER["HTTP_HOST"]);
  define("CLASS_PATH", "classes");
  define("INCLUDE_PATH", "includes");
  define("TEMPLATE_PATH", "templates");
  define("USER_IMAGE_PATH", "media" . DS . "images");

  //----------------------------------------------------------------------------
  // DEFINE URLs
  //----------------------------------------------------------------------------
  define("HOME_URL", base_url(TRUE));
  define("USER_LOGIN_URL", base_url(TRUE) . US . "user.php");

  //----------------------------------------------------------------------------
  // CLASSES
  //----------------------------------------------------------------------------
  require(CLASS_PATH . DS . "App.php");
  require(CLASS_PATH . DS . "Helper.php");
  require(CLASS_PATH . DS . "Message.php");
  require("vendor/autoload.php");

  //----------------------------------------------------------------------------
  // DYNAMIC ROOT PATHS
  //----------------------------------------------------------------------------
  /**
  * Gets the base url of the homepage are
  *
  * @param bool $atCore is set to False by default
  * @return string The requested url
  *
  * $atCore = FALSE by default:
  *   Returns the core url + the requested url like following:
  *   http/https://example.com/foo?action=bar
  *
  * $atCore = TRUE:
  *   Returns only the core url like following:
  *   http/https://example.com
  */
  function base_url($atCore=FALSE) {

    if (isset($_SERVER['HTTPS'])) {
      $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
    } else {
      $protocol = "http";
    }

    if ($atCore) {
      return $protocol . "://" . $_SERVER['HTTP_HOST'];
    } else {
      return $protocol . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

  }// base_url()

  // Uncomment this after you're finished with development
  /*function handleException($exception) {
    echo "Sorry, a problem occured. Please try again later.";
    error_log($exception->getMessage());
  }

  set_exception_handler('handleException');*/

?>
