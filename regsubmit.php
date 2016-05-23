<?php
include "header.php";
require_once "config.php";
include "dbconn.php";

if($_SERVER['REQUEST_METHOD'] != "POST") {
    header("index.php");
}

//Statement to create user in DB
$statement = $conn->prepare(
"INSERT INTO `user` (
    `username`,
    `email`,
    `password`,
    `fname`,
    `lname`)
VALUES (:username, :email, :hashed_password, :firstname, :lastname)"); //the :arguments will be replaced below

if (!$statement) die("Prepare failed: (" . $conn->errno . ") " . $conn->error); //check if an error happens

//We bind all the parameters
$statement->bindParam(':username', $_POST["username"]);
$statement->bindParam(':email', $_POST["email"]);
$statement->bindParam(':hashed_password', password_hash($_POST["password"], PASSWORD_DEFAULT));
$statement->bindParam(':firstname', $_POST["firstname"]);
$statement->bindParam(':lastname', $_POST["lastname"]);

//We execute the statement to create user with POST values
if ($statement->execute()) {
  echo "Registration successful. Thank you! <br> <a href=\"index.php\">Go back to main page</a>";
} else {
    if (!$statement->errno == 1062) {
      //This is result in 200 OK
      echo "This e-mail is already registered";
    } else {
      //This will result in 500 internal server error
        die("Execute failed: (" . $statement->errno . ") " . $statement->error); //check if an error happens
      }
  }
?>

<?php
include "footer.php";
?>
