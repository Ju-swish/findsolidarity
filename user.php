<?php session_start();

  require("config.php"); // RealPath: includes/config.php

  $action = isset($_GET['action']) ? $_GET['action'] : "";
  $isLoggedinUser = isset($_SESSION['userId']) ? $_SESSION['userId'] : "";

  if ($action != "login" && $action != "logout" && $action !="signup" && !$isLoggedinUser) {
    login();
    exit;
  }

  switch($action) {
    case 'login':
      login();
      break;
    case 'logout':
      logout();
      break;
    case 'signup':
      signup();
      break;
    case 'edit':
      edit();
      break;
    case 'eliminate':
      delete();
      break;
    default:
      profile();
  }

  /**
  * Responsible for the whole login page
  */
  function login() {

    // Code goes here See -> admin.php - login()
    $results = array();
    $results['pageTitle'] = "Login | Find Solidarity";
    $results['formAction'] = "login";
    $results['errorMessage'] = "";

    if (isset($_POST['login'])) {

      if (empty($_POST['user_email']) && empty($_POST['user_pwd'])) {

        $results['errorMessage'] = "Bitte alle Felder ausfüllen!";
        require(TEMPLATE_PATH . "/front/includes/login.php");

      } else if (empty($_POST['user_email']) || empty($_POST['user_pwd'])) {

        $results['errorMessage'] = "Bitte alle Felder ausfüllen!";
        require(TEMPLATE_PATH . "/front/includes/login.php");

      } else {

        // Gather the posted data to local variables
        $email = $_POST['user_email'];
        $password = $_POST['user_pwd'];

        // User has posted the login form: attempt to log the user in
        $user = new App;
        $isLoggedin = $user->login($email, $password);

        if ($isLoggedin) {

          // Login successful: Create a session and redirect to the profile
          // Also check the url of the previous page to redirect the user back to the same page
          /*if (isset($_SESSION['url'])) {
            $url = $_SESSION['url'];
          } else {
            $url = "user.php";
          }*/

          // Redirect the user to the previous page or to user.php
          header("Location: user.php");

        } else {

          // Login failed: display on error message to the user
          $results['errorMessage'] = "Wooopps! Email oder Passwort falsch eigegeben. Versuch's nochmal!";
          require(TEMPLATE_PATH . "/front/includes/login.php");

        }

      }

    } else {

      // User has not posted the login from yet: display the form
      require(TEMPLATE_PATH . "/front/includes/login.php");

    }


  }// login()

  function logout() {

    unset($_SESSION['userId']);
    header("Location: index.php");

  }// logout()

  /**
  * This function just includes the signup page
  */
  function signup() {

    $results = array();
    $results['pageTitle'] = "Signup | Find Solidarity";
    $results['formAction'] = "signup";

    require(TEMPLATE_PATH . "/front/includes/signup.php");

  }// signup()

  function edit() {

    $isLoggedinBrand = isset($_SESSION['userId']) ? $_SESSION['userId'] : "";

    if (!$isLoggedinBrand) {
      login();
    } else {

      $results = array();
      $results['pageTitle'] = "Profile Edit | Treepons";
      $results['formAction'] = "edit";
      $results['alert'] = "";

      $app = new App;
      $results['user'] = App::getById($_SESSION['userId']);
      // convert App/user Object data into an array
      $postArray = array();
      foreach($results['user'] as $key => $result) {
        $postArray[$key] = $result;
      }

      if (isset($_POST['uploadImage'])) {

        // User has uploaded a profile image -> save the image
        $app->storeFormValues($postArray);
        if (isset($_FILES['image']))  {
          if (!$app->storeUploadedImage($_FILES['image'])) {
            echo "Das Bild muss quadratisch sein z.B. 500x500px";
          }
        }
        header("Location: user.php");

      } elseif (isset($_POST['saveChanges'])) {

        // User has posted the profile edit form: save the profile changes
        //$app = new App;

        $app->storeFormValues($_POST);
        if (isset($_POST['deleteImage']) && $_POST['deleteImage'] == "yes") $app->deleteImages();
        $app->update();
        header("Location: user.php");

      } elseif (isset($_POST['cancel'])) {

        // User has cancelled their edits: return to the user profile
        header("Location: user.php");

      } else {

        require(TEMPLATE_PATH . "/front/includes/profile.php");

      }
    }

  }// edit()

  /**
  * Deletes the User and Messages associated to the user
  */
  function delete() {

    if (!$user = App::getById((int) $_SESSION['userId'])) {
      header("Location: user.php");
    }

    $results = array();
    $results['user'] = $user;
    $results['pageTitle'] = "Delete Me | Find Solidarity";

    $message = new Message;
    $messages = Message::getListByRecipient($results['user']->user_id);

    if (isset($_POST['deleteUser'])) {

      if ($messages['totalRows'] > 0) {
        $message->deleteMessagesByRecipient($results['user']->user_id);
      }
      $user->deleteImages();
      $user->delete();
      logout();
      header("Location: user.php");

    } elseif (isset($_POST['cancel'])) {

      // User has cancelled the elimination process: return to the user profile
      header("Location: user.php");

    } else {

      require(TEMPLATE_PATH . "/front/includes/eliminate.php");

    }

  }// delete()

  /**
  * This function shows the profile of the user
  */
  function profile() {

    $results = array();
    $results['pageTitle'] = "Profile | Find Solidarity";
    $results['alert'] = "";
    if (isset($_SESSION['userId'])) {
      $results['user'] = App::getById($_SESSION['userId']);
      $data = Message::getListByRecipient($_SESSION['userId']);
      $results['messages'] = $data['results'];
    }
    $imagePath = App::profileImagePath(
                  IMG_TYPE_THUMB,
                  $results['user']->user_unique_id,
                  $results['user']->user_id,
                  $results['user']->user_img_ext
                );
    $results['userImage'] = $imagePath;
    require(TEMPLATE_PATH . "/front/includes/profile.php");

  }// profile()

?>
