<!-- 
    Student Name: Seeram Likitha
    Project Name: Project 4
    Due Date: 26 Nov 2016
-->

<!DOCTYPE html>
<?php
  $db = new mysqli('localhost','root','','CheapBook');
  if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }
?>
<html>
<head>
	<title>CSE5335 - Assignment 4</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  	<script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>
  	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
  	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap-theme.min.css">
  	<link rel="stylesheet" type="text/css" href="book.css">
</head>
<body class="bg">
	<h1>CheapBook.com</h1>

<?php
  //Validating the form after submission
	if(isset($_POST['submit']) && !empty($_POST['name']) && !empty($_POST['password'])) {
		$insertCustomer = "INSERT INTO CUSTOMER (UserName, Password, Address, Phone, Email) VALUES ('".$_POST['name']."', '".md5($_POST['password'])."', '".$_POST['address']."', ".$_POST['phone'].", '".$_POST['email']."')";
		try {
		$result = $db->query($insertCustomer);
		}
		catch(Exception $e) {
          echo "Message:" .$e->getMessage();
        }
 
        echo "<div class='container welcome above-gap'>
        <h4>User registered in to the system!<br> Please login to shop in CheapBook.com </h4>
        <a href='customer.php'><button class='btn btn-primary buy above-gap'><span>Login Page</span></button></a>
        </div>";
	}
	else {
  //Registration form
	echo "<h3>Register here!</h3>
	
	<div class='container welcome'>
      	<form method='post'>
        <fieldset>
      		<legend>Please fill in your details</legend>
      		<input type='text' name='name' class='form-control' placeholder='Enter User Name'><br>
      		<input type='password' name='password' class='form-control' placeholder='Enter Password'><br>
      		<input type='text' name='address' class='form-control' placeholder='Enter Address'>
      		<p class='text-muted'>E.g., 701 South Nedderman Drive, Arlington, Texas</p>
      		<input type='tel' name='phone' class='form-control' placeholder='Enter Phone Number'>
      		<p class='text-muted'>E.g., 8172722011</p>
      		<input type='email' name='email' class='form-control' placeholder='Enter Email Address'>
      		<p class='text-muted'>E.g., john.doe@gmail.com</p>
      		<input type='submit' name='submit' value='Register' class='btn btn-primary buy'>
        </fieldset>
      	</form>		
	</div>";
	}
?>

<?php
$db->close();
?>
</body>
</html>