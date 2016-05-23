<?php
include "header.php";
require_once "config.php";
include "dbconn.php";

//if($_SESSION["orderid"] == null) {
    //header("index.php");
//}

var_dump($_SESSION["userid"]);

$statement = $conn->prepare(
"SELECT username, email, fname, lname, gender, phonecode, phonenum, dob, address, city, postal_code, countryname
FROM `user`
WHERE id = :userid");
if (!$statement) die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
$statement->bindParam(':userid', $_SESSION["userid"]);
if (!$statement->execute()) die("Execute failed: (" . $statement->errno . ") " . $statement->error);
$row = $statement->fetch(PDO::FETCH_ASSOC);
?>

<h1>My profile</h1>

<form method="post" action="regsubmit.php">
    <div>
        <label for="username">Username</label>
        <input type="text" name="username" id="username" value="<?php echo $row["username"];?>" placeholder="your username" required/>
    </div> 

    <div>
        <label for="email">E-mail</label>
        <input type="email" name="email" id="email" value="<?php echo $row["email"];?>" placeholder="your email" required/>
    </div>    

    <div>
        <label for="firstname">First name</label>
        <input type="text" name="firstname" id="firstname" value="<?php echo $row["fname"];?>" placeholder="your first name" required/>
    </div> 

    <div>
        <label for="lastname">Last name</label>
        <input type="text" name="lastname" id="lastname" value="<?php echo $row["lname"];?>" placeholder="your last name" required/>
    </div>

    <div>
        <label for="gender">Gender</label><br>
        <?php
        if ($row["gender"] == null) { ?>
            <input type="radio" name="gender" value="male"> Male
            <input type="radio" name="gender" value="female"> Female
        <?php
        }
        elseif ($row["gender"] == "male") { ?>
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
            if ($row["phonecode"] == 0) {
                echo "<option value=\"null\">-</option>";
            }
            else {
                echo "<option value=".$row["phonecode"].">+".$row["phonecode"]."</option>";
            }
            while($row2 = $statement2->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value=".$row2["phonecode"].">+".$row2["phonecode"]."</option>";
            }
            
            if ($row["phonenum"] == 0) { ?>
                <input type="number" name="phonenum" id="phonenum" value="-" min ="0" placeholder="phone number"/>                
            <?php
            }
            else { ?>
                <input type="number" name="phonenum" id="phonenum" value="<?php echo $row["phonenum"];?>" min ="0" placeholder="phone number"/>
            <?php
            }
            ?>
        </select>
    </div>
    
    <div>
        <label for="dob">Date of birth</label>
            <?php
            if($row["dob"] == "0000-00-00") { ?>
                <input type="date" name="dob" min="(Date('Y')-90)-01-01" placeholder="yyyy-mm-dd">
            <?php
            }
            else { ?>
                <input type="date" name="dob" value="<?php echo $row["dob"]; ?>" min="(Date('Y')-90)-01-01" placeholder="yyyy-mm-dd">
            <?php
            }
            ?>
    </div>
    
    <fieldset>
        <legend>Address of residence:</legend>
        <div>
            <label for="address">Street</label>
                <?php
                if($row["address"] == null) { ?>
                    <input type="text" name="address" id="address" placeholder="your address"/>
                <?php
                }
                else { ?>    
                    <input type="text" name="address" id="address" value="<?php echo $row["address"];?>" placeholder="your address"/>
                <?php
                }
                ?>
        </div>
        
        <div>
            <label for="city">City</label>
                <?php
                if($row["city"] == null) { ?>
                    <input type="text" name="city" id="city" placeholder="your city"/>
                <?php
                }
                else { ?>    
                    <input type="text" name="city" id="city" value="<?php echo $row["city"];?>" placeholder="your city"/>
                <?php
                }
                ?>
        </div>
        
        <div>
            <label for="postalcode">Postal code</label>
                <?php
                if($row["city"] == 0) { ?>
                    <input type="text" name="postalcode" id="postalcode" placeholder="your postal code"/>
                <?php
                }
                else { ?>    
                    <input type="text" name="postalcode" id="postalcode" value="<?php echo $row["postal_code"];?>" placeholder="your postal code"/>
                <?php
                }
                ?>
        </div>
        
        <div>
            <label for="country">Country</label>
            <select name="country" id="country" onchange="" size="1">
                <?php
                $statement3 = $conn->query('SELECT nicename FROM country');
                if ($row["country_id"] == 0) {
                    echo "<option value=\"null\">-</option>";
                }
                else {
                    echo "<option value=".$row["countryname"].">+".$row["countryname"]."</option>";
                }
                while($row3 = $statement3->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value=".$row3["nicename"].">+".$row3["nicename"]."</option>";
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
