<?php
include "header.php";
require_once "config.php";
include "dbconn.php";

if($_SESSION["userid"] != null && $_SERVER['REQUEST_METHOD'] == "POST") {
    $statement = $conn->prepare("SELECT id, password FROM user WHERE id = :userid");
    if (!$statement) die("Prepare failed: (" . $conn->errno . ") " . $conn->error); 
    $statement->bindParam(':userid', $_SESSION["userid"]);
    $statement->execute();
    if (!$statement->execute()) die("Execute failed: (" . $statement0->errno . ") " . $statement0->error);
    $row = $statement->fetch(PDO::FETCH_ASSOC);
    
    if($_POST["newpass"] === $_POST["confirmnewpass"]) {
        if(password_verify($_POST["currentpass"], $row["password"])) {
            $statement1 = $conn->prepare("
                UPDATE `user`
                SET `password` = :pass
                WHERE id = :userid");
            $statement1->bindParam(':pass', password_hash($_POST["newpass"], PASSWORD_DEFAULT));
            $statement1->bindParam(':userid', $_SESSION["userid"]);
            $statement1->execute();
            header("changepassconfirm.php");
        } 
        else {
            echo "<p>Sorry but your current password is not correct please try again</p>";
        }    
    }
    else {
        echo "<p>Sorry but there is a mistake in your new password, please try again</p>";
    }
    
} 
else {
    header("index.php");
}

?>

<h1>Change your password</h1>

<form method="post">
    <div>
        <label for="currentpass">Enter your current password</label>
        <input type="password" name="currentpass" id="currentpass" placeholder="your current password" required/>
    </div>

    <div>
        <label for="newpass">Enter your new password</label>
        <input type="password" 
            name="newpass" 
            id="newpass" pattern=".{8,256}"
            placeholder="type a password"
            title="8 characters minimum" required/>
    </div>
    
    <div>
        <label for="confirmnewpass">Confirm your new password</label>
        <input type="password" 
            name="confirmnewpass" 
            id="confirmnewpass" 
            onkeyup="checkPass(); return false;"
            placeholder="retype your password"
            pattern=".{8,256}" required/>
        <span id="confirmMessage" class="confirmMessage"></span>
    </div>

    <input type="submit" value="Change password"/>
</form>

<br>
<a href="profile.php">Go back to profile</a>

<script src="js/myJS.js"></script>
<?php
include 'footer.php';
?>
