<?php
include "header.php";
require_once "config.php";
include "dbconn.php";
?>


<h1>Etienne's webshop</h1>

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

<h2>Take a look at our products:</h2>
<ul>
	
<?php 
$statement = $conn->prepare("SELECT id, name, price FROM product");
//$result = $conn->query("SELECT id, name, price FROM product");
$statement->execute();

while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
    echo "<li><a href=\"description.php?id=" . $row["id"] . "\">" .  $row["name"] . "</a> " . $row["price"] . "eur</li>";
}
?>
</ul>
<p>
 <a href="http://www.itcollege.ee">itcollege.ee</a>
</p>

<?php
include "footer.php";
?>
