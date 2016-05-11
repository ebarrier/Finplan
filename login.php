<?php // We check on this page if user provided proper credentials

include "header.php";
require_once "config.php";
echo "This is the content of the POST request: ";
var_dump($_POST); ?>

<br>

<?php
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error)
  die("Connection to database failed:".$conn->connect_error);
$conn->query("set names utf8");

//function checkCredentials(string $dbFieldToCheck) {
//    $statement = $conn->prepare("SELECT id FROM user WHERE `".$dbFieldToCheck."` = ? AND password = PASSWORD(?)"); // not to use this in production!
//    if (!$statement) die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
//    $statement->bind_param("ss", $_POST["username/email"], $_POST["password"]);
//    $statement->execute();
//    $result = $statement->get_result();
//    $row = $result->fetch_assoc(); //fetch_assoc stores info as key-value pair
//    return $row;
//}
//$row1 = checkCredentials('email');
//$row2 = checkCredentials('username');

$statement = $conn->prepare("SELECT id FROM user WHERE email = ? AND password = PASSWORD(?)"); // not to use this in production!
if (!$statement) die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
$statement->bind_param("ss", $_POST["username/email"], $_POST["password"]);
$statement->execute();
$result = $statement->get_result();
$row = $result->fetch_assoc(); //fetch_assoc stores info as key-value pair

$statement2 = $conn->prepare("SELECT id FROM user WHERE username = ? AND password = PASSWORD(?)"); // not to use this in production!
if (!$statement2) die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
$statement2->bind_param("ss", $_POST["username/email"], $_POST["password"]);
$statement2->execute();
$result2 = $statement2->get_result();
$row2 = $result2->fetch_assoc(); //fetch_assoc stores info as key-value pair


if($row) { //if the key-value pair user_id-password exists
    $_SESSION["userid"] = $row["id"]; // This just stores user row number
    header('Location: index.php'); //This will redirect back to index.php
} elseif($row2) {
    $_SESSION["userid"] = $row2["id"];
    header('Location: index.php');
} else { ?>
  <p>It looks like you are not known sorry. Please <a href="registration.php">sign up</a>  to enjoy our services or go back to <a href="index.php">main page</a>.</p>

<?php 
} 
?>
