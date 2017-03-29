<!-- 
    Student Name: Seeram Likitha
    Project Name: Project 4
    Due Date: 26 Nov 2016
-->

<?php
session_start();
?>

<?php
function getButtons() {
  //Buttons on the Navigation Bar
  echo "<form method='post'>
        <button type='submit' class='btn btn-success' name='cart'>
            <span class='glyphicon glyphicon-shopping-cart' aria-hidden='true'>Cart&nbsp;<span class='badge'>";
            if (isset($_SESSION['count'])) {
              echo $_SESSION['count'];
            }
            else {
              echo "0";
            }
           echo "</span></span>
        </button>
        <button type='submit' class='btn btn-primary left-gap' name='logout'>
            <span class='glyphicon glyphicon-log-out' aria-hidden='true'>Logout</span>
        </button>
    </form>";
  }

$db = new mysqli('localhost','root','','CheapBook');
  if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }
?>

<!DOCTYPE html>
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

      <div class="nav navbar-form navbar-right">
        <?php getButtons(); ?>
      </div>
    </div>
   </div> 
</nav>

<!-- Code executed after clicking on 'Logout' button -->
<?php
  if(isset($_POST['logout'])) {
    
    $basketItems = "SELECT * FROM SHOPPINGBASKET WHERE UserName= '".$_SESSION['user']."'";
    try {
    $item = $db->query($basketItems);
    }
    catch(Exception $e) {
      echo "Message:" .$e->getMessage();
    }

    //Removing 'shopping basket' data
    $basketDelete = "DELETE FROM SHOPPINGBASKET WHERE UserName= '".$_SESSION['user']."'";
    
    try {
    $delete1 = $db->query($basketDelete);
    
    }
    catch(Exception $e) {
      echo "Message:" .$e->getMessage();
    }

    //Removing 'contains' data
    while($set =  mysqli_fetch_array($item)) {
    $containsDelete = "DELETE FROM CONTAINS WHERE BasketID= '".$set['BasketID']."'";
    try {
    $delete2 = $db->query($containsDelete);
    }
    catch(Exception $e) {
      echo "Message:" .$e->getMessage();
    }
  }
    
    //unsetting session variables
    unset($_SESSION['user']);
    unset($_SESSION['count']);
    session_destroy();
    header('Location: customer.php');
    exit;
  }

//Code redirecting to Page 3 after clicking on 'cart' button
  if(isset($_POST['cart'])) {
    header('Location: cart.php');
    exit;
  }
?>

<!-- This section has a text box and 2 buttons - Search by Author, Search by Title -->
<div class="container welcome above-gap">
  <form class="form-group" method="post">
    <input type="text" class="form-control below-gap" name="search" placeholder="Search for Books"> 
    <div>
      <button class="btn btn-default place" name="author"><span class="glyphicon glyphicon-search input-md text-primary" aria-hidden="true"></span><span class="text-primary">Search by Author</span>
      </button>
      <button class="btn btn-default place left-gap" name="title"><span class="glyphicon glyphicon-search input-md text-primary" aria-hidden="true"></span><span class="text-primary">Search by Title</span>
      </button>
    </div>
  </form>
</div>

<!-- Validation after clicking on any search button -->
<?php
  if(isset($_POST['author'])) {
    if(isset($_POST['search'])) {
      $authorSearch = $_POST['search'];
      $sql1 = "SELECT * FROM BOOK B INNER JOIN WRITTENBY W ON B.ISBN=W.ISBN INNER JOIN STOCKS S ON B.ISBN=S.ISBN INNER JOIN AUTHOR A ON W.SSN=A.SSN INNER JOIN WAREHOUSE WH ON S.WAREHOUSECODE=WH.WAREHOUSECODE AND A.AUTHORNAME LIKE '%".$authorSearch."%' AND S.NUMBER>0";
      try {
        $result1 = $db->query($sql1);
        }
      catch(Exception $e) {
        echo "Message:" .$e->getMessage();
        }
        echo "<div class='container'>
            <table class='table'>
              <caption>Search Results</caption>
              <thead>
                <tr>
                <th>Book Name</th>
                <th>ISBN</th>
                <th>Author</th>
                <th>Seller</th>
                <th>Quantity Available</th>
                <th colspan='2'></th>
                </tr>
              </thead>
              <tbody>";

        $i =0; //Variable to keep tack of count while adding a book to SESSION variable 
        $_SESSION['list'] = array();     
        while($list1 = mysqli_fetch_array($result1)) {
          $i++;
          $_SESSION['list'][$i] = $list1;
        }
        $n = $i; //Number of books added to the SESSION variable

        //Displaying the search results
        for($x=1; $x <= $n; $x++) {
          echo "
                <tr>
                <td>".$_SESSION['list'][$x]['Title']."</td>
                <td>".$_SESSION['list'][$x]['ISBN']."</td>
                <td>".$_SESSION['list'][$x]['AuthorName']."</td>
                <td>".$_SESSION['list'][$x]['WarehouseName']."</td>
                <td>".$_SESSION['list'][$x]['Number']."</td>
                <td>
                  <form class='form-group' method='post'>
                    <input type='number' name='quantity' min='1' max='";
                    if($_SESSION['list'][$x]['Number'] > 5) echo "5";
                    else echo $_SESSION['list'][$x]['Number'];
                    echo "' placeholder='Qty'>
                    <button class='btn btn-small btn-primary left-gap' id='myButton' name='add' value=".$x.">
                    Add to cart
                    </button>
                  </form>
                </td>
                </tr>";
        }
        echo "</tbody>
            <table>
          </div>";
    }  

  }
  if(isset($_POST['title'])) {
      if(isset($_POST['search'])) {
        $titleSearch = $_POST['search'];
        $sql2 = "SELECT * FROM BOOK B INNER JOIN WRITTENBY W ON B.ISBN=W.ISBN INNER JOIN STOCKS S ON B.ISBN=S.ISBN INNER JOIN AUTHOR A ON W.SSN=A.SSN INNER JOIN WAREHOUSE WH ON S.WAREHOUSECODE=WH.WAREHOUSECODE AND B.TITLE LIKE '%".$titleSearch."%' AND S.NUMBER>0";
        try {
          $result2 = $db->query($sql2);
          }
        catch(Exception $e) {
          echo "Message:" .$e->getMessage();
          }
        echo "<div class='container'>
            <table class='table'>
              <caption>Search Results</caption>
              <thead>
                <tr>
                <th>Book Name</th>
                <th>ISBN</th>
                <th>Author</th>
                <th>Seller</th>
                <th>Quantity Available</th>
                </tr>
              </thead>
              <tbody>";

        $i =0; //Variable to keep tack of count while adding a book to SESSION variable 
        $_SESSION['list'] = array();     
        while($list2 = mysqli_fetch_array($result2)) {
          $i++;
          $_SESSION['list'][$i] = $list2;
        }
        $n = $i;  //Number of books added to the SESSION variable

        //Displaying the search results
        for($x=1; $x <= $n; $x++) {
          echo "
                <tr>
                <td>".$_SESSION['list'][$x]['Title']."</td>
                <td>".$_SESSION['list'][$x]['ISBN']."</td>
                <td>".$_SESSION['list'][$x]['AuthorName']."</td>
                <td>".$_SESSION['list'][$x]['WarehouseName']."</td>
                <td>".$_SESSION['list'][$x]['Number']."</td>
                <td>
                  <form class='form-group' method='post'>
                    <input type='number' name='quantity' min='1' max='";
                    if($_SESSION['list'][$x]['Number'] > 5) echo "5";
                    else echo $_SESSION['list'][$x]['Number'];
                    echo "' placeholder='Qty'>
                    <button class='btn btn-small btn-primary left-gap' id='myButton' name='add' value=".$x.">
                    Add to cart
                    </button>
                  </form>
                </td>
                </tr>";
        }
        echo "</tbody>
            <table>
          </div>";
      }
    }
?>

<!-- Validation after clicking on 'Add to Cart' -->
<?php
  if(isset($_POST['add']) && isset($_POST['quantity'])) {
    $val = $_POST['add'];    
   
   //Session variable to keep track of selected book in the results and its quantity
    $_SESSION['value'] = $val;
    $_SESSION['qty'] = $_POST['quantity'];

    $basketInsert = "INSERT INTO SHOPPINGBASKET (BasketID, UserName) VALUES ('".uniqid()."', '".$_SESSION['user']."')";
    try {
      $insertResult1 = $db->query($basketInsert);
    }
    catch(Exception $e) {
      echo "Message:" .$e->getMessage();
    }

    $basketRetrieve = "SELECT * FROM SHOPPINGBASKET WHERE UserName = '".$_SESSION['user']."'";
    try {
      $selectResult1 = $db->query($basketRetrieve);

      $j =0;
      $_SESSION['item'] = array();
      while($basket =  mysqli_fetch_array($selectResult1)) {
        $j++;
        $basketID = $basket['BasketID'];
        $_SESSION['item'][$j] = $basket;
      }
    }
    catch(Exception $e) {
      echo "Message:" .$e->getMessage();
    }

    $containsInsert = "INSERT INTO CONTAINS (ISBN, BasketID, Number) VALUES ('".$_SESSION['list'][$val]['ISBN']."', '".$basketID."', ".$_POST['quantity'].")";
    try {
      $insertResult2 = $db->query($containsInsert);
    }
    catch(Exception $e) {
      echo "Message:" .$e->getMessage();
    }
    
    $count = 0; //This variable keeps track of number of books added to the cart
    for($z=1; $z <= $j; $z++) {
      
    $containsRetrieve = "SELECT * FROM CONTAINS WHERE BASKETID ='".$_SESSION['item'][$z]['BasketID']."'";

    try {
      $selectResult2 = $db->query($containsRetrieve);
      $basket = $selectResult2->fetch_assoc();
      $count = $count + $basket['Number'];
    }

    catch(Exception $e) {
      echo "Message:" .$e->getMessage();
    }
    }

    $_SESSION['count'] = $count; //Assigning the count of books to a SESSION variable
    header("Refresh:0"); //Refreshing the page to get the count on the cart button
    exit;

    }

?>

<!-- Displaying results after adding an Item to cart and assigning the selected details to a SESSION variable -->
<?php
  if (isset($_SESSION['count']) > 0 && !isset($_POST['author']) && !isset($_POST['title'])) {
  echo "<div class='container welcome above-gap '>
    <h4>Items are added to your shopping basket</h4>
    <p>To add more Items, Search for the books by 'Title' or 'Author Name'</p>
    </div>";

  $_SESSION['bookCount']++;
  $_SESSION['selected'][$_SESSION['bookCount']] = $_SESSION['list'][$_SESSION['value']];
  $_SESSION['selected'][$_SESSION['bookCount']]['Quantity'] = $_SESSION['qty'];
  }
?>

<!-- Closing DB connection -->
<?php
$db->close();
?>
</body>
</html>