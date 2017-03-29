<!-- 
    Student Name: Seeram Likitha
    Project Name: Project 4
    Due Date: 26 Nov 2016
-->

<!DOCTYPE HTML>
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
  <h1>Online Book Store</h1>

  <div class="container welcome">
      <!-- Login Form -->
      <form class="form-group" method="post">
        <h2>Please sign in</h2>
        <input type="text" class="form-control below-gap" name="name" placeholder="User Name"> 
        <input type="password" class="form-control below-gap" name="password" placeholder="Password">
        
        <button class="btn btn-lg btn-primary btn-block" id="login" type="submit">Sign in</button>
      </form>

        <hr>
        <p>If you are a new User, Please Sign Up</p>
        <a href="register.php"><button class="btn btn-lg btn-primary btn-block" type="submit"><span>Sign Up</span></button></a>
    </div>
<!-- Validating the form after clicking on 'Login' button -->
    <?php
      if($_SERVER["REQUEST_METHOD"] == "POST") {
        if($_POST['name'] != "" && $_POST['password'] != "") {
        session_start();
        $sql = "SELECT * FROM CUSTOMER WHERE USERNAME='".$_POST['name']."'";
        print_r($sql);
        try {
          $result = $db->query($sql);
          $customer = $result->fetch_assoc();
          
          $_SESSION['user'] = $customer['UserName'];
          $_SESSION['selected'] = array();
          $_SESSION['bookCount'] = 0;
          
          }
        catch(Exception $e) {
          echo "Message:" .$e->getMessage();
          }
        if($customer['UserName'] == $_POST['name'] && $customer['Password'] == md5($_POST['password'])) {
          header('Location: shopping.php');
          exit;
          }
        else {
           header('Location: customer.php');
           exit;
          }
        }
      }
      
    ?>

<?php
$db->close();
?>
</body>
</html>