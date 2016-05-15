<?php
require_once "config.php";
include "header.php";

try {
    $conn = new PDO('mysql:host='.DB_SERVER.';dbname='.DB_NAME, DB_USER, DB_PASS);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connected successfully";
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$statement = $conn->prepare(
"INSERT INTO `user` (
    `username`,
    `email`,
    `password`,
    `fname`,
    `lname`)
VALUES (:username, :email, :hashed_password, :firstname, :lastname)"); //the :arguments will be replaced below

if (!$statement) die("Prepare failed: (" . $conn->errno . ") " . $conn->error); //check if an error happens

$statement->bindParam(':username', $_POST["username"]);
$statement->bindParam(':email', $_POST["email"]);
$statement->bindParam(':hashed_password', password_hash($_POST["password"], PASSWORD_DEFAULT));
$statement->bindParam(':firstname', $_POST["firstname"]);
$statement->bindParam(':lastname', $_POST["lastname"]);

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
