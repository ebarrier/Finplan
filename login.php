<?php // We check on this page if user provided proper credentials

include "header.php";
require_once "config.php";
include "dbconn.php";

//function to check if the email or username provided match the password
function checkCredentials($dbFieldToCheck, $conn, $username, $password) {
    $statement = $conn->prepare("SELECT id, password FROM user WHERE ".$dbFieldToCheck." = :credential");
    if (!$statement) die("Prepare failed: (" . $conn->errno . ") " . $conn->error); 
    $statement->bindParam(':credential', $username);
    $statement->execute();
    $row = $statement->fetch(PDO::FETCH_ASSOC);

    if(password_verify($password, $row["password"])) {
        return $row["id"];
    }
    return false;
}

//we call the function above twice (for email and for username)
$row1 = checkCredentials('email', $conn, $_POST["username/email"], $_POST["password"]);
$row2 = checkCredentials('username', $conn, $_POST["username/email"], $_POST["password"]);

//if one of the function calls above is succesful, user is logged in, otherwise we ask him to try again or to sign up
if($row1) { //if the key-value pair user_id-password exists
    $_SESSION["userid"] = $row1; // This just stores user row number
    header('Location: index.php'); //This will redirect back to index.php
} elseif($row2) {
    $_SESSION["userid"] = $row2;
    header('Location: index.php');
} else { ?>
  <p>It looks like you are not known sorry. Please <a href="registration.php">sign up</a>  to enjoy our services or go back to <a href="index.php">main page</a>.</p>

<?php 
} 
include 'footer.php';
?>
