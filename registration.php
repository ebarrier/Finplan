<?php
include "header.php";
?>

<h1> Registration page</h1>

<form method="post" action="regsubmit.php">
  <div>
  <label for="username">Username</label>
  <input type="text" name="username" id="username" required/>
  </div> 

  <div>
  <label for="email">E-mail</label>
  <input type="email" name="email" id="email" required/>
  </div>    

  <div>
  <label for="password">Password</label>
  <input type="password" name="password" id="password" required/>
  </div>   

  <div>
  <label for="firstname">First-name</label>
  <input type="text" name="firstname" id="firstname" required/>
  </div> 

  <div>
  <label for="lastname">Last-name</label>
  <input type="text" name="lastname" id="lastname" required/>
  </div> 

  <div>
  <input type="submit" value="Sign-up"/>
  </div>
  
</form>

<?php
include "footer.php";
?>
