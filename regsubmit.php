<?php
include "header.php";
require_once "config.php";
include "dbconn.php";

if($_SERVER['REQUEST_METHOD'] != "POST") {
    header("index.php");
}

$validFields = true;

if($_POST["username"] == null || preg_match("[\w.]{1,64}", $_POST["username"]) != 1) {
    $validFields = false;
    echo "<p>Your username is missing or invalid</p>";
}

if($_POST["email"] == null || preg_match("^[a-z0-9._%+-]+@(?:[a-z0-9-]+\.)+[a-z]{2,}$", $_POST["email"]) != 1) {
    $validFields = false;
    echo "<p>Your email is missing or invalid</p>";
}

if($_POST["password1"] == null || preg_match(".{8,256}", $_POST["password1"]) != 1 || $_POST["password1"] !== $_POST["password2"]) {
    $validFields = false;
    echo "<p>Your password is missing or invalid</p>";
}

if($_POST["firstname"] == null || preg_match("[-a-zA-z]{1,30}", $_POST["firstname"]) != 1) {
    $validFields = false;
    echo "<p>Your firstname is missing or invalid</p>";
}

if($_POST["lastname"] == null || preg_match("[-a-zA-z]{1,30}", $_POST["lastname"]) != 1) {
    $validFields = false;
    echo "<p>Your lastname is missing or invalid</p>";
}

if($validFields) {
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
        if ($statement->errorCode() == 1062) {
          //This is result in 200 OK
          echo "This e-mail or username is already registered";
        } else {
          //This will result in 500 internal server error
            die("Execute failed: (" . $statement->errorCode() . ") " . $statement->errorInfo()); //check if an error happens
          }
    }
}
?>

<br>
<a href="registration.php">Go back to registration page</a>

<?php
include "footer.php";
?>
