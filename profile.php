<?php
include "header.php";
require_once "config.php";
include "dbconn.php";

var_dump($_POST);

if($_SESSION["userid"] != null && $_SERVER['REQUEST_METHOD'] == "POST") {
    $statement0 = $conn->prepare("
    UPDATE `user` 
    SET username=:username, 
        email=:email, 
        fname=:fname, 
        lname=:lname, 
        gender=:gender, 
        phonecode=:phonecode, 
        phonenum=:phonenum, 
        dob=:dob, 
        address=:address, 
        city=:city, 
        postal_code=:postalcode, 
        countryname=:countryname
    WHERE id = :userid");
    if (!$statement0) die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    $statement0->bindParam(':username', $_POST["username"]);
    $statement0->bindParam(':email', $_POST["email"]);
    $statement0->bindParam(':fname', $_POST["firstname"]);
    $statement0->bindParam(':lname', $_POST["lastname"]);
    $statement0->bindParam(':gender', $_POST["gender"]);
    $statement0->bindParam(':phonecode', $_POST["phonecode"]);
    $statement0->bindParam(':phonenum', $_POST["phonenum"]);
    $statement0->bindParam(':dob', $_POST["dob"]);
    $statement0->bindParam(':address', $_POST["address"]);
    $statement0->bindParam(':city', $_POST["city"]);
    $statement0->bindParam(':postalcode', $_POST["postalcode"]);
    $statement0->bindParam(':countryname', $_POST["country"]);
    $statement0->bindParam(':userid', $_SESSION["userid"]);
    if (!$statement0->execute()) die("Execute failed: (" . $statement0->errno . ") " . $statement0->error);
} else {
    header("index.php");
}



$statement1 = $conn->prepare("
SELECT username, email, fname, lname, gender, phonecode, phonenum, dob, address, city, postal_code, countryname
FROM `user`
WHERE id = :userid");
if (!$statement1) die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
$statement1->bindParam(':userid', $_SESSION["userid"]);
if (!$statement1->execute()) die("Execute failed: (" . $statement1->errno . ") " . $statement1->error);
$row1 = $statement1->fetch(PDO::FETCH_ASSOC);
?>

<h1>My profile</h1>

<form method="post">
    <div>
        <label for="username">Username</label>
        <input type="text" name="username" id="username" value="<?php echo $row1["username"];?>" placeholder="your username" required/>
    </div> 

    <div>
        <label for="email">E-mail</label>
        <input type="email" name="email" id="email" value="<?php echo $row1["email"];?>" placeholder="your email" required/>
    </div>    

    <div>
        <label for="firstname">First name</label>
        <input type="text" name="firstname" id="firstname" value="<?php echo $row1["fname"];?>" placeholder="your first name" required/>
    </div> 

    <div>
        <label for="lastname">Last name</label>
        <input type="text" name="lastname" id="lastname" value="<?php echo $row1["lname"];?>" placeholder="your last name" required/>
    </div>
    
    <a href="changepass.php">Change password</a>

    <div>
        <label for="gender">Gender</label><br>
        <?php
        if ($row1["gender"] == null) { ?>
            <input type="radio" name="gender" value="male"> Male
            <input type="radio" name="gender" value="female"> Female
        <?php
        }
        elseif ($row1["gender"] == "male") { ?>
            <input type="radio" name="gender" value="male" checked> Male
            <input type="radio" name="gender" value="female"> Female
        <?php
        } 
        else { ?>
            <input type="radio" name="gender" value="male" > Male
            <input type="radio" name="gender" value="female" checked> Female
        <?php
        } ?>
    </div>

    <div>
        <label for="phonenumber">Phone number</label>
        <select name="phonecode" id="phonecode" onchange="" size="1">
            <?php
            $statement2 = $conn->query('SELECT phonecode FROM country GROUP BY phonecode ORDER BY phonecode');
            if ($row1["phonecode"] == 0) {
                echo "<option value=\"null\">-</option>";
            }
            else {
                echo "<option value=".$row1["phonecode"].">+".$row1["phonecode"]."</option>";
            }
            while($row2 = $statement2->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value=".$row2["phonecode"].">+".$row2["phonecode"]."</option>";
            }
            
            if ($row1["phonenum"] == 0) { ?>
                <input type="number" name="phonenum" id="phonenum" value="-" min ="0" placeholder="phone number"/>                
            <?php
            }
            else { ?>
                <input type="number" name="phonenum" id="phonenum" value="<?php echo $row1["phonenum"];?>" min ="0" placeholder="phone number"/>
            <?php
            }
            ?>
        </select>
    </div>
    
    <div>
        <label for="dob">Date of birth</label>
            <?php
            if($row1["dob"] == "0000-00-00") { ?>
                <input type="date" name="dob" min="(Date('Y')-90)-01-01" placeholder="yyyy-mm-dd">
            <?php
            }
            else { ?>
                <input type="date" name="dob" value="<?php echo $row1["dob"]; ?>" min="(Date('Y')-90)-01-01" placeholder="yyyy-mm-dd">
            <?php
            }
            ?>
    </div>
    
    <fieldset>
        <legend>Address of residence:</legend>
        <div>
            <label for="address">Street</label>
                <?php
                if($row1["address"] == null) { ?>
                    <input type="text" name="address" id="address" placeholder="your address"/>
                <?php
                }
                else { ?>    
                    <input type="text" name="address" id="address" value="<?php echo $row1["address"];?>" placeholder="your address"/>
                <?php
                }
                ?>
        </div>
        
        <div>
            <label for="city">City</label>
                <?php
                if($row1["city"] == null) { ?>
                    <input type="text" name="city" id="city" placeholder="your city"/>
                <?php
                }
                else { ?>    
                    <input type="text" name="city" id="city" value="<?php echo $row1["city"];?>" placeholder="your city"/>
                <?php
                }
                ?>
        </div>
        
        <div>
            <label for="postalcode">Postal code</label>
                <?php
                if($row1["postal_code"] == 0) { ?>
                    <input type="text" name="postalcode" id="postalcode" placeholder="your postal code"/>
                <?php
                }
                else { ?>
                    <input type="text" name="postalcode" id="postalcode" value="<?php echo $row1["postal_code"];?>" placeholder="your postal code"/>
                <?php
                }
                ?>
        </div>
        
        <div>
            <label for="country">Country</label>
            <select name="country" id="country" onchange="" size="1">
                <?php
                $statement3 = $conn->query('SELECT nicename FROM country');
                if ($row1["countryname"] == "0") {
                    echo "<option value=\"null\">-</option>";
                }
                else {
                    echo "<option value=".$row1["countryname"].">".$row1["countryname"]."</option>";
                }
                while($row3 = $statement3->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value=".$row3["nicename"].">".$row3["nicename"]."</option>";
                }
                ?>
            </select>
        </div>
    </fieldset>
    
    <div>
        <input type="submit" value="Save"/>
    </div>
  
</form>

<br>
<a href="index.php">Go back to main page</a>
<?php
include 'footer.php';
?>
