<?php
  require 'db.php';
 



if ( isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
}

else {
      header( "location: login.php" );
   
 
}
?>
<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Welcome </title>
  
</head>

<body>
  <div class="form">

          <h1>Welcome <?php echo $username;?></h1>
          
          <p>

          
          
          <a href="logout.php"><button class="button button-block" name="logout"/>Log Out</button></a>

    </div>
    
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src="js/index.js"></script>

</body>
</html>
