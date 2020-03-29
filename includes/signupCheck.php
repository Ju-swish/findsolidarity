<?php include("../config.php");

  /**
  * The use keyword allows you to introduce local variables into the local
  * scope of an anonymous function
  */
  // PHPMailer global variables
  /*use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;
  use PHPMailer\PHPMailer\Exception;
  require("../vendor/autoload.php");*/

  // Ajax calls this Registration code
  if (isset($_POST["user_name"])) {

    // Gather the posted data into local variables
    $user_name = preg_replace('#[^a-z0-9äöü]#i', '', $_POST['user_name']);
    $user_email = $_POST['user_email'];
    $user_pwd = $_POST['user_pwd'];
    $user_description = $_POST['user_description'];
    $user_city = $_POST['user_city'];
    $user_plz = $_POST['user_plz'];

    // Create necessary objects
    $emailCheck = App::getByEmail($user_email);
    $user_unique_id = Helper::uniqueId();

    // Duplicate data checks for username and email
    if ( $user_name == ""
      || $user_email == ""
      || $user_pwd == ""
      || $user_description == ""
      || $user_city == ""
      || $user_plz == "") {
      echo "Alle Felder müssen Ausgefüllt sein";
      exit;
    } else if (!is_null($emailCheck)) {
      echo "Diese Email Adresse ist schon benutzt für ein Konto";
      exit;
    } else if (is_numeric($user_name[0])) {
      echo "Name darf nicht mit einer Zahl beginnen";
      exit;
    } else {

      // Save the new user
      $_POST['user_unique_id'] = $user_unique_id;
      $user = new App;
      $user->storeFormValues($_POST);
      $user->insert();
      echo True;
      exit;

    }// End-(validation)-If

    exit;

  }// End-($_POST["user_username"])-If
?>
