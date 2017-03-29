<!-- 
    Student Name: Seeram Likitha
    Project Name: Project 4
    Due Date: 26 Nov 2016
-->

<!DOCTYPE HTML>
<?php
session_start();
$cost = 0;

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
  <nav class="navbar navbar-default coloring-nav">
  <div class="container-fluid">
     <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">CheapBook.com</a>
    </div>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="#"><?php echo "Welcome ".$_SESSION['user']; ?> </a></li>
       </ul>  

    </div>
   </div> 
</nav>

<!-- Displaying the details of the BOOKS stored in the SESSION -->
<?php
if(!isset($_POST['order'])) {
	echo "<div class='container'>
	<table class='table'>
	<caption> Items in your Shopping Basket </caption>
	<thead>
		<tr>
			<th>Book Name</th>
			<th>ISBN</th>
			<th>Author</th>
			<th>Seller</th>
			<th>Price</th>
			<th>Quantity</th>
		</tr>
	</thead>
	<tbody>";

    $selectedCount = $_SESSION['bookCount'];
    for($a=1; $a<=$selectedCount; $a++) {
    	echo "<tr>
    		<td>".$_SESSION['selected'][$a]['Title']."</td>
    		<td>".$_SESSION['selected'][$a]['ISBN']."</td>
    		<td>".$_SESSION['selected'][$a]['AuthorName']."</td>
    		<td>".$_SESSION['selected'][$a]['WarehouseName']."</td>
    		<td>".$_SESSION['selected'][$a]['Price']."</td>
    		<td>".$_SESSION['selected'][$a]['Quantity']."</td>  
    	</tr>";
    	$cost = $cost + $_SESSION['selected'][$a]['Price']*$_SESSION['selected'][$a]['Quantity'];
    }

	echo "</tbody>
	<tfoot>
		<tr>
			<th colspan='4'>Total cost</th>
			<th>".$cost."</th>
			<th></th>
		</tr>
	</tfoot>
	</table>
	</div>";
	echo "<div class='container welcome'>
	<form method='post'>
		<button class='btn btn-large btn-primary buy' id='order' name='order'>
            Buy
        </button>
	</form>	
</div>";
	}
?>

<!-- Code after clicking on 'BUY' button -->
<?php
	if (isset($_POST['order']) && isset($_SESSION['selected'])) {
	$cartDetails = "SELECT * from SHOPPINGBASKET S INNER JOIN CONTAINS C ON S.BasketID=C.BasketID INNER JOIN BOOK B on C.ISBN=B.ISBN WHERE S.UserName = '".$_SESSION['user']."'";
	try {
	$details = $db->query($cartDetails);
	}
	catch(Exception $e) {
    echo "Message:" .$e->getMessage();
  }

  //Adding the details to the 'Shipping Order' table
  $selectedCount = $_SESSION['bookCount'];
  for($a=1; $a<=$selectedCount; $a++) {
    $warehouse = "SELECT * FROM WAREHOUSE WHERE WarehouseName='".$_SESSION['selected'][$a]['WarehouseName']."'";
  try {
	$warehouseInfo = $db->query($warehouse);
	$data = $warehouseInfo->fetch_assoc();
	}
	catch(Exception $e) {
   echo "Message:" .$e->getMessage();
  }
  $shippingOrder = "INSERT INTO SHIPPINGORDER (ISBN, WarehouseCode, UserName, Number) VALUES ('".$_SESSION['selected'][$a]['ISBN']."', ".$data['WarehouseCode'].", '".$_SESSION['user']."', ".$_SESSION['selected'][$a]['Quantity'].")";

  try {
	 $confirmOrder = $db->query($shippingOrder);
	}
	catch(Exception $e) {
    echo "Message:" .$e->getMessage();
  }

  //stock update
  $stockUpdate = "UPDATE STOCKS SET NUMBER = NUMBER - ".$_SESSION['selected'][$a]['Quantity']." WHERE ISBN = '".$_SESSION['selected'][$a]['ISBN']."' AND WarehouseCode = ".$data['WarehouseCode'];
  try {
	$stock = $db->query($stockUpdate);
	}
	catch(Exception $e) {
    echo "Message:" .$e->getMessage();
  }
  }

  $basketItems = "SELECT * FROM SHOPPINGBASKET WHERE UserName= '".$_SESSION['user']."'";
  try {
  $item = $db->query($basketItems);
  }
  catch(Exception $e) {
    echo "Message:" .$e->getMessage();
  }

  $basketDelete = "DELETE FROM SHOPPINGBASKET WHERE UserName= '".$_SESSION['user']."'";
  try {
    $delete1 = $db->query($basketDelete);  
  }
  catch(Exception $e) {
    echo "Message:" .$e->getMessage();
  }

  while($set =  mysqli_fetch_array($item)) {
  $containsDelete = "DELETE FROM CONTAINS WHERE BasketID= '".$set['BasketID']."'";
  try {
    $delete2 = $db->query($containsDelete);
  }
  catch(Exception $e) {
    echo "Message:" .$e->getMessage();
  }
  }
  
  echo "<div class='container welcome'>
    <h3>Order has been Confirmed</h3>
    <br>
    <h4>Confirmed Shipping Order Details of the User</h4>
    (Includes history too)";

  $orderDetails = "SELECT * FROM SHIPPINGORDER WHERE UserName = '".$_SESSION['user']."'";
  try {
    $orderResult =  $db->query($orderDetails);
  }
  catch(Exception $e) {
    echo "Message:" .$e->getMessage();
  }
  while($order = mysqli_fetch_array($orderResult)) {
    echo "<table class='myTable'>
    	<tr>
    		<td>ISBN:</td>
    		<td>".$order['ISBN']."</td>
    	</tr>
    	<tr>
    		<td>Warehouse Code:</td>
    		<td>".$order['WarehouseCode']."</td>
    	</tr>
    	<tr>
    		<td>User Name:</td>
    		<td>".$order['UserName']."</td>
    	</tr>
    	<tr>
    		<td>Quantity:</td>
    		<td>".$order['Number']."</td>
    	</tr>
    	</table>
    	<hr>";
    }
    echo "<form method='post'>
    <button type='submit' class='btn btn-primary left-gap' name='logout'>
            <span class='glyphicon glyphicon-log-out' aria-hidden='true'>Logout</span>
        </button>
    </form>";
	}
?>

<!-- For Logout -->
<?php
  if(isset($_POST['logout'])) {
  	unset($_SESSION['user']);
    unset($_SESSION['count']);
    unset($_SESSION['selected']);
    unset($_SESSION['bookCount']);
    session_destroy();
    header('Location: customer.php');
    exit;
  	}
?>

<!-- Closing Connection -->
<?php
$db->close();
?>

</body>
</html>