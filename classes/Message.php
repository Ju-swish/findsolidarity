<?php

class Message {

// Properties
/**
* @var int The Message ID from the database
*/
public $msg_id = null;
/**
* @var int The recipient ID from the database
*/
public $msg_recipient_id = null;
/**
* @var string The date on which the message was sent
*/
public $msg_date = null;
/**
* @var string The name of the sender
*/
public $msg_sender_name = null;
/**
* @var int The age of the sender
*/
public $msg_sender_age = null;
/**
* @var string The email of the sender
*/
public $msg_sender_email = null;
/**
* @var string The telephone/cell number of the sender
*/
public $msg_sender_tel = null;
/**
* @var string The message of the sender
*/
public $msg_sender_msg = null;

/**
* Sets the object's properties using the values in the supplied array
*
* @param assoc The property values
*/
public function __construct($data = array()) {

  if (isset($data['msg_id'])) $this->msg_id = (int) $data['msg_id'];
  if (isset($data['msg_recipient_id'])) $this->msg_recipient_id = (int) $data['msg_recipient_id'];
  if (isset($data['msg_date'])) $this->msg_date = $data['msg_date'];
  if (isset($data['msg_sender_name'])) $this->msg_sender_name = $data['msg_sender_name'];
  if (isset($data['msg_sender_age'])) $this->msg_sender_age = $data['msg_sender_age'];
  if (isset($data['msg_sender_email'])) $this->msg_sender_email = $data['msg_sender_email'];
  if (isset($data['msg_sender_tel'])) $this->msg_sender_tel = $data['msg_sender_tel'];
  if (isset($data['msg_sender_msg'])) $this->msg_sender_msg = $data['msg_sender_msg'];

}// __construct()

/**
* Sets the object's properties using the edit/insert form post values in the supplied array
*
* @param assoc The form post values
*/
public function storeFormValues($params) {

  // Store all the parameters
  $this->__construct($params);

}// storeFormValues()

/**
* Returns a Message object matching the given message ID
*
* @param int The message ID
* @return object|false The Message object, or false if the record was not found or there was a problem
*/
public static function getById($id) {

  /**
  * $conn object can be initiated in the constructor once
  * define connection property and create connection object in the constructor
  */
  $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  $sql = "SELECT * FROM messages WHERE msg_id = :msg_id";
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(":msg_id", $id, PDO::PARAM_INT);
  $stmt->execute();
  $row = $stmt->fetch();    // ->fetch(PDO::FETCH_ASSOC)
  $conn = null;
  if ($row) return new Message($row);

}// getById()

/**
* Returns a Message object matching the given recipient ID
*
* @param int The recipient/user ID
* @return object|false The Message object, or false if the record was not found or there was a problem
*/
public static function getByRecipientId($id) {

  $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  $sql = "SELECT * FROM messages WHERE msg_recipient_id = :msg_recipient_id";
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(":msg_recipient_id", $id, PDO::PARAM_INT);
  $stmt->execute();
  $row = $stmt->fetch();    // ->fetch(PDO::FETCH_ASSOC)
  $conn = null;
  if ($row) return new Message($row);

}// getById()

/**
* Returns all (or a range of) Message objects in the DB
* @param int Optional The number of rows to return (default = all)
* @param string Optional column by which to order the users (default = "ad_date DESC")
* @return array|false A two-element array: results => array, a list of Message objects; totalRows => Total number of Messages
* @deprecated SQL_CALC_FOUND_ROWS and FOUND_ROWS() - use instead COUNT(*)
* @deprecated mysql_escape_string - use instead PDO::quote() (= Quotes a string for use in a query)
*/
public static function getList($numRows = 1000000, $order="ad_date DESC") {

  $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  $sql = "SELECT * FROM messages ORDER BY $order LIMIT :numRows";
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(":numRows", $numRows, PDO::PARAM_INT);
  $stmt->execute();
  $list = array();

  while($row = $stmt->fetch()) {
    $message = new Message($row);
    $list[] = $message;
  }

  // Now get the total number of Messages that matched the criteria
  $sql = "SELECT COUNT(*) FROM messages AS totalRows";
  $totalRows = $conn->query($sql)->fetch();
  $conn = null;
  return (array("results" => $list, "totalRows" => $totalRows[0]));

}// getList()

/**
* Returns all (or a range of) Message objects in the DB
* @param int Optional The number of rows to return (default = all)
* @param string Optional column by which to order the users (default = "ad_date DESC")
* @param int Compulsory recipient ID
* @return array|false A two-element array: results => array, a list of Message objects; totalRows => Total number of Messages
*/
public static function getListByRecipient($recipientId) {

  $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  $sql = "SELECT * FROM messages WHERE msg_recipient_id = :msg_recipient_id
          ORDER BY msg_date DESC";
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(":msg_recipient_id", $recipientId, PDO::PARAM_INT);
  $stmt->execute();
  $list = array();

  while($row = $stmt->fetch()) {
    $message = new Message($row);
    $list[] = $message;
  }

  // Now get the total number of Messages that matched the criteria
  $sql = "SELECT COUNT(*) FROM messages AS totalRows WHERE msg_recipient_id = $recipientId";
  $totalRows = $conn->query($sql)->fetch();
  $conn = null;
  return (array("results" => $list, "totalRows" => $totalRows[0]));

}// getList()

/**
* Inserts the current Message object into the database, and sets its ID property
* The data is inserted by users from the "send message to helper" form
*/
public function insert() {

  // Does the Message object already have an ID?
  if($this->msg_id != null) trigger_error("Message::insert(): Attempt to insert a Message object that already has its ID property set (to $this->msg_id).", E_USER_ERROR);

  // Insert the User
  $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  //$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
  $sql = "INSERT INTO messages (
        msg_recipient_id,
        msg_date,
        msg_sender_name,
        msg_sender_age,
        msg_sender_email,
        msg_sender_tel,
        msg_sender_msg
  ) VALUES (
        :msg_recipient_id,
        now(),
        :msg_sender_name,
        :msg_sender_age,
        :msg_sender_email,
        :msg_sender_tel,
        :msg_sender_msg
  )";
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(":msg_recipient_id", $this->msg_recipient_id, PDO::PARAM_INT);
  $stmt->bindValue(":msg_sender_name", $this->msg_sender_name, PDO::PARAM_STR);
  $stmt->bindValue(":msg_sender_age", $this->msg_sender_age, PDO::PARAM_INT);
  $stmt->bindValue(":msg_sender_email", $this->msg_sender_email, PDO::PARAM_STR);
  $stmt->bindValue(":msg_sender_tel", $this->msg_sender_tel, PDO::PARAM_STR);
  $stmt->bindValue(":msg_sender_msg", $this->msg_sender_msg, PDO::PARAM_STR);
  $stmt->execute();
  $this->user_id = $conn->lastInsertId();
  $conn = null;

}// insert()

/**
* Deletes all the messages associated to a specific user
*
* @param int The recipient/ user ID
*/
public function deleteMessagesByRecipient($recipientId) {

  $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
  $sql = "DELETE FROM messages WHERE msg_recipient_id = :msg_recipient_id";
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(":msg_recipient_id", $recipientId, PDO::PARAM_INT);
  $stmt->execute();
  $conn = null;

}// deleteMessagesByRecipient()

}// End Class

?>
