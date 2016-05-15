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
?>


<h1>Etienne's financial planner</h1>
<p>Welcome to the financial planner to manage your budget</p>

<?php
if (array_key_exists("userid", $_SESSION) && $_SESSION["userid"] != NULL) {
    //If the $_SESSION["userid"] is set we say hello with his name
    echo "Session user in session: ";
    var_dump($_SESSION["userid"]); //This is just to show the content of $_SESSION variable
    $results = $conn->query("SELECT * FROM user WHERE id = " . $_SESSION["userid"]);
    $row = $results->fetch(PDO::FETCH_ASSOC);
    echo ("<p>Hello " . $row["fname"] . " " . $row["lname"]);?>
    <br>
    <a href="logout.php">Log out<a></p>
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
<?php 
} ?>

<p>
 <a href="http://www.itcollege.ee">itcollege.ee</a>
</p>

<?php
include "footer.php";
?>
