  <?php session_start();

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;
  use PHPMailer\PHPMailer\Exception;
  /**
  * We use require() rather than include(); because require() generates an error
  * if the file can’t be found
  * @TODO: remove ini_set("display_errors", true); from config.php after
  * development is finished.
  */
  require("config.php");  // Path should be includes/config.php but for now its fine
  $action = isset($_GET['action']) ? $_GET['action'] : "";

  switch($action) {
    case 'viewUser':
      viewUser();
      break;
    default:
      homepage(); //homepage()
  }

  function viewUser() {

    if (!isset($_GET["userId"]) || !$_GET["userId"]) {
      homepage();
      return;
    }

    $results = array();
    $results['user'] = App::getById((int) $_GET["userId"]);
    $results['pageTitle'] = $results['user']->user_name . " | Find Solidarity";
    $results['alert'] = "";
    $imagePath = App::profileImagePath(
                  IMG_TYPE_THUMB,
                  $results['user']->user_unique_id,
                  $results['user']->user_id,
                  $results['user']->user_img_ext
                );
    $results['userImage'] = $imagePath;

    if (isset($_POST['sendMessage'])) {

      $msngrName = $_POST['msg_sender_name'];
      $msngrAge = $_POST['msg_sender_age'];
      $msngrEmail = $_POST['msg_sender_email'];
      $msngrTel = $_POST['msg_sender_tel'];
      $msngrMsg = $_POST['msg_sender_msg'];

      if ( $msngrName == ""
        || $msngrAge == ""
        || $msngrEmail == ""
        || $msngrTel == ""
        || $msngrMsg == "" ) {

        $results['alert'] = "emptyFields";
        //header("Location: .?action=viewUser&userId=" . $results['user']->user_id . "&alert=emptyFields")

      } else {

        // Send the Email
        // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_OFF;                     //SMTP::DEBUG_CONNECTION  to test connection                // Enable verbose debug output
            $mail->isSMTP();                                        // Send using SMTP
            $mail->Host       = 'relay-hosting.secureserver.net';   // Set the SMTP server to send through
            $mail->SMTPAuth   = false;                              // Enable SMTP authentication
            $mail->Username   = 'findsolidarity@gmail.com';         // SMTP username
            $mail->Password   = 'ec0s0cialprojekt';                 // SMTP password
            $mail->SMTPSecure = false;                              // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
            $mail->Port       = 25;                                 // TCP port to connect to

            //Recipients
            $mail->setFrom('noreply@findsolidarity.com', 'Find Solidarity');
            $mail->addAddress($results['user']->user_email, $results['user']->user_name);     // Add a recipient

            // Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'Find Solidarity - Neue Nachricht';
            $mail->Body  = '<h2>Jemand in deiner Umgebung braucht Hilfe.</h2>';
            $mail->Body .= '<p>Name: ' . $msngrName .'</p>';
            $mail->Body .= '<p>Alter: ' . $msngrAge .'</p>';
            $mail->Body .= '<p>Email: ' . $msngrEmail .'</p>';
            $mail->Body .= '<p>Tel.: ' . $msngrTel .'</p>';
            $mail->Body .= '<p>Nachricht: ' . $msngrMsg .'</p>';

            // Send the email to the recipient
            $mail->send();

            // Store the message in the database
            $message = new Message;
            $message->storeFormValues($_POST);
            $message->insert();

            $results['alert'] = "msgSentSuccessfully";
            //header("Location: .?action=viewUser&userId=" . $results['user']->user_id . "&alert=msgSentSuccessfuly")

        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
        // End Send Email

      }

    } //else {

      //require(TEMPLATE_PATH . "/front/includes/viewUser.php");

    //}// End-($_POST['sendMessage'])-if

    // The user has not submitted the message yet
    require(TEMPLATE_PATH . "/front/includes/viewUser.php");

  }// viewUser()

  function homepage() {

    $results = array();
    $results['pageTitle'] = "Find Solidarity";
    $usersData = App::getList();
    $messagesData = Message::getList();
    $results['totalUsers'] = $usersData['totalRows'];
    $results['totalMessages'] = $messagesData['totalRows'];

    if (isset($_POST['search'])) {

      $searchTerm = $_POST['search_term'];
      header("Location: search.php?searchquery=" . $searchTerm);

    } else {
      require(TEMPLATE_PATH . "/front/includes/homepage.php");
    }

  }// homepage()

  ?>
