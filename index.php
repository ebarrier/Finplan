<?php
require_once "config.php";
include "header.php";
$conn = new mysqli(DB_SERVER, 
DB_USER, 
DB_PASS, 
DB_NAME);
if ($conn->connect_error)
  die("Connection to database failed:".$conn->connect_error);
$conn->query("set names utf8");

?>

<h1>Etienne's financial planner</h1>
<p>Welcome to the financial planner to manage your budget</p>

<?php
if (array_key_exists("userid", $_SESSION)) {
    //If the $_SESSION["user"] is set we say hello with his name
    echo "Session user in session: ";
    var_dump($_SESSION["userid"]); //This is just to show the content of $_SESSION variable
    $results = $conn->query("SELECT * FROM user
    WHERE id = " . $_SESSION["userid"]);

    $row = $results->fetch_assoc();
    echo ("<p>Hello " . $row["fname"] . " " . $row["lname"]);?>
    <br>
    <a href="logout.php">Log out<a>
<?php
} else { //else we display the login page
    ?> <p>Enter your financial planner<p>
  <form action="login.php" method="post">
    <input type="text" name="username/email" placeholder="username or email" required/>
    <input type="password" name="password" placeholder="password" required/>
    <input type="submit" value="Log in!"/>
  </form>

    <br>

  <form action="registration.php" method="post">
    Not registered yet? Sign up!
    <input type="submit" value="Sign up!"/>
  </form> 
<?php } ?>

<?php
$conn->close();
?>

<p>
 <a href="http://www.itcollege.ee">itcollege.ee</a>
</p>

<?php
include "footer.php";
?>
