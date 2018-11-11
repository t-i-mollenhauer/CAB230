<?php

// requests the database login information
require 'db.php';



// genetates errros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(isset($_POST['register'])) {
	//gets the variables from html and adds them to php variables
	$username = $_POST['username'];
	$email = $_POST['email'];
	$firstname = $_POST['firstname'];
	$lastname = $_POST['lastname'];
	$DOB = $_POST['DOB'];
	$postcode = $_POST['postcode'];
	$password = $_POST['password'];
	$confirmpassword = $_POST['confirmpassword'];


	$_SESSION['username'] = $_POST['username'];
	//tests to see if the password is the same as the confirm password
	if ($password == $confirmpassword){
		
		$hash = password_hash($password, PASSWORD_DEFAULT);
		//hashes the password before it is added to the database
		$hash_password= hash('sha256', $password);		
		//tests to see if the user already exists
		$testusername = $connect->prepare('SELECT * FROM users WHERE username = :username 
			OR email=:email');
		$testusername->execute( array(':username' => $username, ':email'=>$email));
		$testnumber=$testusername->rowCount();
		if($testnumber>0){
			echo "<script type='text/javascript'>alert('Username or email address Exists)</script>";
			header("Refresh:0");	
		}
		else{
			//adds the new user into the database
			try {
				$stmt = "INSERT INTO users (username, firstname, lastname, DOB, postcode, email, password) VALUES (:username, :firstname, :lastname, :DOB, :postcode, :email, :password)";

				$query = $connect->prepare( $stmt );
				$query->execute( array( ':username'=>$username, ':password'=>$hash, ':firstname'=>$firstname, ':lastname'=>$lastname, ':postcode'=>$postcode, ':email'=>$email, ':DOB'=>$DOB) );
				header('Location: login.php');
			}
			catch(PDOException $e) {
				echo $e->getMessage();
			}
		}
	}
	// error if the passwords do not match
	else{
		echo "<script type='text/javascript'>alert('Confirm password is differet to password')</script>";
		header("Refresh:0");
	}
}
?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="../css/register.css" media="screen" />
	<link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</head>
<body>

	<!--Top nav  -->
	<div class="top_bar_2">
		<button id="btnLogin" onclick="location.href='login.php'".style.display='block' id="btnLogin">login</button>
		<button onclick="location.href='../searchcurrent.php'".style.display='block' id="btnHome">home</button>

	</div>

	<div id="body-content">
		<div id="module">
			<h1>Create an account</h1>
			<form class="form" method="post" enctype="multipart/form-data" autocomplete="off">


				<!--input fields for the accounts  -->
				<input class="registerClass" type="text"  placeholder="Username" name="username" maxlength="15" required />
				<input class="registerClass" type="email" placeholder="Email" name="email" maxlength="30" required />
				<input class="registerClass" type="text" placeholder="First name" name="firstname" maxlength="30" required>
				<input class="registerClass" type="text" placeholder="Last name" name="lastname" maxlength="30" required>        
				<input class="registerClass" type="date" placeholder="Date of birth" name="DOB" min="1900-01-01" max="2017-05-04" required>      
				<input class="registerClass" type="PostCode" placeholder="Post Code" name="postcode" max="5" min"3" required>
				<input class="registerClass" type="password" placeholder="Password" name="password" minlength="4"
				maxlength="20" autocomplete="new-password" required />
				<input class="registerClass" type="password" placeholder="Confirm Password" name="confirmpassword" autocomplete="new-password" required />

				<input type="submit" value="Register" name="register" class="btn btn-block btn-primary" id="btnRegister"/>
			</form>
		</div>
	</div>
</body>
</html>