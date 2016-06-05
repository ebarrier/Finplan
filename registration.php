<?php
include "header.php";
?>

<h1>Registration page</h1>

<form method="post" action="regsubmit.php">
  <div>
    <label for="username">Username</label>
    <input type="text" 
        name="username" 
        id="username" 
        pattern="[\w\.]{1,64}"
        placeholder="your username"
        title="Numbers, letters (case sensitive), underscore and dot are allowed. 64 characters max" required/>
  </div> 

  <div>
    <label for="email">E-mail</label>
    <input type="email" 
        name="email"
        id="email"
        placeholder="your email" required/>
  </div>    

  <div>
    <label for="password1">Password</label>
    <input type="password" 
        name="password1" 
        id="password1" 
        pattern=".{8,256}"
        placeholder="type a password"
        title="8 characters minimum" required/>
  </div>

  <div>
    <label for="password2">Repeat password</label>
    <input type="password" 
        name="password2" 
        id="password2" 
        onkeyup="checkPass(); return false;"
        placeholder="retype your password"
        pattern=".{8,256}" required/>
        <span id="confirmMessage" class="confirmMessage"></span>
  </div>

  <div>
    <label for="firstname">First-name</label>
    <input type="text" 
        name="firstname" 
        id="firstname" 
        pattern="[-a-zA-z]{1,30}"
        placeholder="your firstname"
        title="Only letters" required/>
  </div> 

  <div>
    <label for="lastname">Last-name</label>
    <input type="text" 
        name="lastname" 
        id="lastname" 
        pattern="[-a-zA-z]{1,30}"
        placeholder="your lastname"
        title="Only letters" required/>
  </div> 

  <div>
    <input type="submit" value="Sign-up"/>
  </div>
  
</form>

<br>
<a href="index.php">Go back to main page</a>

<script src="js/myJS.js"></script>

<?php
include "footer.php";
?>
