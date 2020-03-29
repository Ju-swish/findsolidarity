<?php
/**
* Class to handle Users
*
* I called it App because this is the core class and there was no intention of
* making other classes but it can be changed to something else e.g. User but it
* depends on the project.
*/

class App {

// Properties
/**
* @var int The User ID from the database
*/
public $user_id = null;
/**
* @var int The User unique ID from the database
*/
public $user_unique_id = null;
/**
* @var string The registration date of the user
*/
public $user_reg_date = null;
/**
* @var string The name of the user
*/
public $user_name = null;
/**
* @var string The email of the user
*/
public $user_email = null;
/**
* @var string The password of the user must be hashed
*/
public $user_pwd = null;
/**
* @var int The age of the user
*/
public $user_age = 0;
/**
* @var string Description about the user
*/
public $user_description = null;
/**
* @var string The city where the user live
*/
public $user_city = null;
/**
* @var string The zip code of the place where the user live
*/
public $user_plz = null;
/**
* @var string The image extension
*/
public $user_img_ext = "";

/**
* Sets the object's properties using the values in the supplied array
*
* @param assoc The property values
*/
public function __construct($data = array()) {

  if (isset($data['user_id'])) $this->user_id = (int) $data['user_id'];
  if (isset($data['user_unique_id'])) $this->user_unique_id = $data['user_unique_id'];
  if (isset($data['user_reg_date'])) $this->user_reg_date = $data['user_reg_date'];
  if (isset($data['user_name'])) $this->user_name = preg_replace("/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['user_name']);
  if (isset($data['user_email'])) $this->user_email = $data['user_email'];
  if (isset($data['user_pwd'])) $this->user_pwd = $data['user_pwd'];
  if (isset($data['user_age'])) $this->user_age = $data['user_age'];
  if (isset($data['user_description'])) $this->user_description = $data['user_description'];
  if (isset($data['user_city'])) $this->user_city = $data['user_city'];
  if (isset($data['user_plz'])) $this->user_plz = $data['user_plz'];
  if (isset($data['user_img_ext'])) $this->user_img_ext = $data['user_img_ext'];

}// __construct()

/**
* Sets the object's properties using the edit form post values in the supplied array
*
* @param assoc The form post values
*/
public function storeFormValues($params) {

  // Store all the parameters
  $this->__construct($params);

  // Hash the user password
  /**
  * We use PASSWORD_DEFAULT becuase we don't know what kind of encryption PHP
  * will use in the future but right now PHP uses Bcrypt
  * If you use PASSWORD_DEFAULT then the password field in database should be
  * VARCHAR (255)
  */
  if (isset($params['user_pwd'])) {
    $user_pwd_hashed = password_hash($params['user_pwd'], PASSWORD_DEFAULT);
    $this->user_pwd = $user_pwd_hashed;
  }

}// storeFormValues()

/**
* Stores any image uploaded from the edit form
*
* @param assoc The 'image' element from the $_FILES array containing the file upload data
*/
public function storeUploadedImage($image) {

  if ($image['error'] == UPLOAD_ERR_OK) {

    // Does any previous image(s) for this user exist
    /**
    * Now it's not needed for this project but for later on methodology
    * like this can be added of course in another way
    * What it does:
    * Checks if the image already exists, if it does then delete it
    */
    //$this->deleteImages();

    // Get and store the image filename extension
    $this->user_img_ext = strtolower(strrchr($image['name'], '.'));

    // Store the image
    $tempFilename = trim($image['tmp_name']);

    if (is_uploaded_file($tempFilename)) {
      if (!(move_uploaded_file($tempFilename, $this->getImagePath()))) trigger_error( "App::storeUploadedImage(): Couldn't move uploaded file.", E_USER_ERROR);
      if (!(chmod( $this->getImagePath(), 0666))) trigger_error( "App::storeUploadedImage(): Couldn't set permissions on uploaded file.", E_USER_ERROR);
    }

    // Get the image size and type
    $attrs = getimagesize($this->getImagePath());
    $imageWidth = $attrs[0];
    $imageHeight = $attrs[1];
    $imageType = $attrs[2];

    if ($imageWidth != $imageHeight) {
      $this->deleteImages();
      echo "Das Bild muss quadratisch sein z.B. 500x500px";
      exit;
    }

    // Load the image into memory
    switch($imageType) {
      case IMAGETYPE_GIF:
        $imageResource = imagecreatefromgif($this->getImagePath());
        break;
      case IMAGETYPE_JPEG:
        $imageResource = imagecreatefromjpeg($this->getImagePath());
        break;
      case IMAGETYPE_PNG:
        $imageResource = imagecreatefrompng($this->getImagePath());
        break;
      default:
        trigger_error ( "App::storeUploadedImage(): Unhandled or unknown image type ($imageType)", E_USER_ERROR);
    }

    // Copy and resize the image to create the thumbnail
    $thumbHeight = intval($imageHeight / $imageWidth * THUMB_WIDTH);
    $thumbResource = imagecreatetruecolor(THUMB_WIDTH, $thumbHeight);
    imagecopyresampled($thumbResource, $imageResource, 0, 0, 0, 0, THUMB_WIDTH, $thumbHeight, $imageWidth, $imageHeight);

    // Save the thumbnail
    switch($imageType) {
      case IMAGETYPE_GIF:
        imagegif($thumbResource, $this->getImagePath(IMG_TYPE_THUMB));
        break;
      case IMAGETYPE_JPEG:
        imagejpeg($thumbResource, $this->getImagePath(IMG_TYPE_THUMB), JPEG_QUALITY);
        break;
      case IMAGETYPE_PNG:
        imagepng($thumbResource, $this->getImagePath(IMG_TYPE_THUMB));
        break;
      default:
        trigger_error ( "App::storeUploadedImage(): Unhandled or unknown image type ($imageType)", E_USER_ERROR);
    }

    $this->updateImage();

  }// end-($image['error'])-if

}// storeUploadedImage()

/**
* Deletes any images and/or thumbnails associated with the user object
*/
public function deleteImages() {

  // Delete all fullsize images for this article
  foreach(glob(USER_IMAGE_PATH . "/" . IMG_TYPE_FULLSIZE . "/" . IMG_PREFIX . $this->user_unique_id . $this->user_id . ".*") as $filename) {
    if ( !unlink( $filename ) ) trigger_error( "App::deleteImages(): Couldn't delete image file.", E_USER_ERROR);
  }

  // Delete all thumbnail images for this article
  foreach(glob(USER_IMAGE_PATH . "/" . IMG_TYPE_THUMB . "/" . IMG_PREFIX . $this->user_unique_id . $this->user_id . ".*") as $filename) {
    if ( !unlink( $filename ) ) trigger_error( "App::deleteImages(): Couldn't delete thumbnail file.", E_USER_ERROR);
  }

  // Remove the image filename extension from the object
  $this->user_img_ext = "";
  $this->updateImage();

}// deleteImages()

/**
* Returns the relative path to the user's full-size or thumbnail image
*
* @param string The type of image path to retrieve (IMG_TYPE_FULLSIZE or IMG_TYPE_THUMB). Defaults to IMG_TYPE_FULLSIZE.
* @return string|false The image's path, or false if an image hasn't been uploaded
* The image name is IMG_PREFIX + user unique id + user id
*/
public function getImagePath($type = IMG_TYPE_FULLSIZE) {

  return ($this->user_id && $this->user_img_ext) ? (USER_IMAGE_PATH . "/$type/" . IMG_PREFIX . $this->user_unique_id . $this->user_id . $this->user_img_ext) : false;

}// getImagePath()

/**
* Returns the name for the image name
* Takes the name of the user and replace space by underscore
*
* @param string The name of the user
*
* This method is not used anymore in this project but might be useful
*/
public static function getImageName($name) {

  $separate_name = explode(' ', $name);

  if (count($separate_name) == 2) {
    list($first_name, $last_name) = $separate_name;
    return $first_name . "_" . $last_name;
  } else {
    return $separate_name[0];
  }

}// getImageName()

/**
* Returns an App object matching the given User ID / User object
*
* @param int The user ID
* @return Ad|false The Users object, or false if the record was not found or there was a problem
*/
public static function getById($id) {

  $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  $sql = "SELECT * FROM users WHERE user_id = :user_id";
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(":user_id", $id, PDO::PARAM_INT);
  $stmt->execute();
  $row = $stmt->fetch();    // ->fetch(PDO::FETCH_ASSOC)
  $conn = null;
  if ($row) return new App($row);

}// getById()

/**
* Returns an App object matching the given User Name / User object
*
* @param string username
* @return object/false App()
*
* This method is not used but might be used for Ajax call to check if username
* already exists
*/
public static function getByUsername($username) {

  $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  $sql = "SELECT user_id FROM users WHERE user_name=:user_name LIMIT 1";
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(":user_name", $username, PDO::PARAM_STR);
  $stmt->execute();
  $row = $stmt->fetch();
  $conn = null;
  if ($row) return new App($row);

}// getByUsername()

/**
* Gets the User by checking the email
*
* @param string email
* @return object/false App or false
*
* This method is not used but again might be useful
*/
public static function getByEmail($email) {

  $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  $sql = "SELECT * FROM users WHERE user_email=:user_email LIMIT 1";
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(":user_email", $email, PDO::PARAM_STR);
  $stmt->execute();
  $row = $stmt->fetch();
  $conn = null;
  if ($row) return new App($row);

}// getByEmail()

/**
* Gets the User by checking/searching the zipcode/postal code
*
* @param string zipcode/postal code
* @return object/false App or false
*/
public static function getByZipcode($zipcode) {

  $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  $sql = "SELECT * FROM users WHERE user_plz=:user_plz";
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(":user_plz", $zipcode, PDO::PARAM_STR);
  $stmt->execute();
  $row = $stmt->fetch();
  $conn = null;
  if ($row) return new App($row);

}// getByZipcode()

/**
* Gets the User by checking/searching the zipcode/postal code
* This method is used to retrieve search results
* Actually the MySQL query for search looks different than what I use but the
* purpose of this website is to retrieve exact results the user is looking for
* Actuall search query: SELECT * FROM ... WHERE ... LIKE %SEARCH_TERM%
*
* @param string zipcode/postal code
* @return object/false App or false
*/
public static function getListByZipcode($zipcode) {

  $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  $sql = "SELECT * FROM users WHERE user_plz=:user_plz ORDER BY user_id DESC";
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(":user_plz", $zipcode, PDO::PARAM_STR);
  //$stmt->bindValue(":numRows", $numRows, PDO::PARAM_INT);
  $stmt->execute();
  $list = array();

  while($row = $stmt->fetch()) {
    $user = new App($row);
    $list[] = $user;
  }

  // Now get the total number of Users that matched the criteria
  /*$sql = "SELECT COUNT(*) FROM users AS totalRows";
  $totalRows = $conn->query($sql)->fetch();
  $conn = null;*/
  return (array("results" => $list));

}// getList()

/**
* Gets the profile image path
*
* @param string The name of the file (image)
* @param string The type of the image fullsize/thumbnail also the folder name
* @param int The id of the user which is included in the name
* @param string The extension of the file (image)
* @return string The complete path of an image file
*/
public static function profileImagePath($type, $unique_id, $id, $extension) {

  $path = USER_IMAGE_PATH . DS . $type . DS . IMG_PREFIX . $unique_id . $id . $extension;
  return $path;

}// profileImagePath()

/**
* This method is responsible for user login
*
* @param string username
* @param string password
* @return bool true/false
*/
public function login($email, $password) {

  $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  $sql = "SELECT * FROM users WHERE user_email=:user_email LIMIT 1";
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(":user_email", $email, PDO::PARAM_STR);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $conn = null;

  if ($stmt->rowCount() > 0) {

    if (password_verify($password, $row['user_pwd'])) {

      $_SESSION['userId'] = $row['user_id'];   //$_SESSION['session'] = $row['user_id'];
      return true;

    } else {

      // Password is not verified -> password is wrong
      return false;

    }

  } else {

    // Account not found -> email is wrong or doesn't exists
    return false;

  }

}// login()

/**
* Returns all (or a range of) User objects in the DB
* @param int Optional The number of rows to return (default = all)
* @param string Optional column by which to order the users (default = "ad_date DESC")
* @return array|false A two-element array: results => array, a list of User objects; totalRows => Total number of Users
* @deprecated SQL_CALC_FOUND_ROWS and FOUND_ROWS() - use instead COUNT(*)
* @deprecated mysql_escape_string - use instead PDO::quote() (= Quotes a string for use in a query)
*/
public static function getList($numRows = 1000000, $order="ad_date DESC") {

  $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  $sql = "SELECT * FROM users ORDER BY $order LIMIT :numRows";
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(":numRows", $numRows, PDO::PARAM_INT);
  $stmt->execute();
  $list = array();

  while($row = $stmt->fetch()) {
    $user = new App($row);
    $list[] = $user;
  }

  // Now get the total number of Users that matched the criteria
  $sql = "SELECT COUNT(*) FROM users AS totalRows";
  $totalRows = $conn->query($sql)->fetch();
  $conn = null;
  return (array("results" => $list, "totalRows" => $totalRows[0]));

}// getList()

/**
* Inserts the current User object into the database, and sets its ID property
* The data is inserted by users from the registration form
*/
public function insert() {

  // Does the App (user) object already have an ID?
  if($this->user_id != null) trigger_error("App::insert(): Attempt to insert an User object that already has its ID property set (to $this->user_id).", E_USER_ERROR);

  // Insert the User
  $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  //$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
  $sql = "INSERT INTO users (
        user_unique_id,
        user_reg_date,
        user_name,
        user_email,
        user_pwd,
        user_age,
        user_description,
        user_city,
        user_plz,
        user_img_ext
  ) VALUES (
        :user_unique_id,
        now(),
        :user_name,
        :user_email,
        :user_pwd,
        :user_age,
        :user_description,
        :user_city,
        :user_plz,
        :user_img_ext
  )";
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(":user_unique_id", $this->user_unique_id, PDO::PARAM_STR);
  $stmt->bindValue(":user_name", $this->user_name, PDO::PARAM_STR);
  $stmt->bindValue(":user_email", $this->user_email, PDO::PARAM_STR);
  $stmt->bindValue(":user_pwd", $this->user_pwd, PDO::PARAM_STR);
  $stmt->bindValue(":user_age", $this->user_age, PDO::PARAM_INT);
  $stmt->bindValue(":user_description", $this->user_description, PDO::PARAM_STR);
  $stmt->bindValue(":user_city", $this->user_city, PDO::PARAM_STR);
  $stmt->bindValue(":user_plz", $this->user_plz, PDO::PARAM_STR);
  $stmt->bindValue(":user_img_ext", $this->user_img_ext, PDO::PARAM_STR);
  $stmt->execute();
  $this->user_id = $conn->lastInsertId();
  $conn = null;

}// insert()

/**
* Updates the current App (user) object in the database when updated by the user himself
*/
public function update() {

  // Does the User object have an ID?
  if (is_null($this->user_id)) trigger_error("App::update(): Attempt to update a User object that does not have its ID property set.", E_USER_ERROR);

  $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
  $sql = "UPDATE users SET
          user_name = :user_name,
          user_email = :user_email,
          user_description = :user_description,
          user_city = :user_city,
          user_plz = :user_plz
          WHERE user_id = :user_id";
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(":user_name", $this->user_name, PDO::PARAM_STR);
  $stmt->bindValue(":user_email", $this->user_email, PDO::PARAM_STR);
  $stmt->bindValue(":user_description", $this->user_description, PDO::PARAM_STR);
  $stmt->bindValue(":user_city", $this->user_city, PDO::PARAM_STR);
  $stmt->bindValue(":user_plz", $this->user_plz, PDO::PARAM_STR);
  $stmt->bindValue(":user_id", $this->user_id, PDO::PARAM_INT);
  $stmt->execute();
  $conn = null;

}// update()

/**
* Updates the user profile image actually the user_img_ext field in the user table
*/
public function updateImage() {

  // Does the user object have an ID?
  if (is_null($this->user_id)) trigger_error ( "App::updateImage(): Attempt to update a user object that does not have its ID property set.", E_USER_ERROR);

  $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  $sql = "UPDATE users SET user_img_ext = :user_img_ext
          WHERE user_id = :user_id";
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(":user_img_ext", $this->user_img_ext, PDO::PARAM_STR);
  $stmt->bindValue(":user_id", $this->user_id, PDO::PARAM_INT);
  $stmt->execute();
  $conn = null;

}// updateImage()

/**
* Deletes the current user object from the database
*/
public function delete() {

  // Does the User object have an ID?
  if (is_null($this->user_id)) trigger_error("User::delete(): Attempt to delete a User object that does not have its ID property set.", E_USER_ERROR);

  // Delete the user
  $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  $sql = "DELETE FROM users WHERE user_id=:user_id LIMIT 1";
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(":user_id", $this->user_id, PDO::PARAM_INT);
  $stmt->execute();
  $conn = null;

}// delete()

}// End Class

?>
