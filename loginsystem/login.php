<?php

require 'db.php';

  //gets login information from html fields
if(isset($_POST['login'])) {

    //gets information from input boxes
  $username = $_POST['username'];
  $password = $_POST['password'];


    //querys the database
  try {
    $result = $connect->prepare('SELECT * FROM users WHERE username = :username');
    $result->execute( array(':username' => $username));


      //if there is one instance of the username login to the arccount
    $count=$result->rowCount();
    if($count>0){
      $data = $result->fetch(PDO::FETCH_ASSOC);


        //confirm a hashed version of the password is the same as the hashed password stored in the database
      if (password_verify($_POST['password'], $data['password'])){



          //sets the session to be the users details
        $_SESSION['username'] = $_POST['username'];
        $_SESSION['id'] = $data['id'];
          //redirects user to the homepage
        header('Location: ../searchcurrent.php');
      }
      else {
          //echos out a popup saying incorrect password and refereshes the page
        echo "<script type='text/javascript'>alert('incorrect password!')</script>";
        header("Refresh:0");
      }
    }
    else{
        //echos out a popup saying incorrect password and refereshes the page
      echo "<script type='text/javascript'>alert('Incorrect username or password!')</script>";
      header("Refresh:0");
    }



    
  }


  catch(PDOException $e) {
    $errMsg = $e->getMessage();
  }

}
?>



<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" type="text/css" href="../css/login.css" media="screen" />
  <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
</head>
<body>


  <!-- Top nav-->
  <div class="top_bar_2">
    <div id="backgroundNav"></div>

    <button onclick="location.href='register.php'".style.display='block'"" id="btnSignUp">Sign Up</button>
    <button onclick="location.href='../searchcurrent.php'".style.display='block'"" id="btnHome">home</button>
  </div>

  <div id="body-content">
    <div id="module">
      <h1 id="headerLogin">login</h1>
      <form class="form" method="post" enctype="multipart/form-data" autocomplete="off">
        <!-- input username password and submtit -->
        <input class="registerClass" type="text" placeholder="Username" name="username" required />
        <input class="registerClass" type="password" placeholder="Password" name="password" autocomplete="new-password" required />
        <input type="submit" value="login" name="login" class="btn btn-block btn-primary" id="btnSubmit"/>
      </form>
    </div>
  </div>


  <div id="footer">
    <p>CAB230 Maps Tim and Jesse </p>
  </div>

</body>
</html>