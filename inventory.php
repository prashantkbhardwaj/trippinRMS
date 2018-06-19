<?php require_once("includes/session.php");?>
<?php require_once("includes/db_connection.php");?>
<?php require_once("includes/functions.php");?>
<?php confirm_logged_in(); ?>
<?php
    $current_user = $_SESSION["username"];
    $name_query = "SELECT * FROM users WHERE username = '{$current_user}' LIMIT 1";
    $name_result = mysqli_query($conn, $name_query);
    confirm_query($name_result);
    $name_title = mysqli_fetch_assoc($name_result);
    $first_name = explode(" ", $name_title['name']);
    $user_type = $name_title['type'];
?>


<?php
    if (isset($_POST['addProduct'])) {
        $Location = $_POST['Location'];
        $Cupboard = $_POST['Cupboard'];
        $Spare_item = $_POST['Spare_item'];
        $Product_number= $_POST['Product_number'];
        $Make = $_POST['Make'];
        $Serial_number = $_POST['Serial_number'];
        $Version_ID = $_POST['Version_ID'];
        $Product_ID = $_POST['Product_ID'];
        $Quantity= $_POST['Quantity'];
        $Remarks = $_POST['Remarks'];


        $query_addProduct = "INSERT INTO products (Location,Cupboard,Spare_item,Product_number,Make,Serial_number,Version_ID,Product_ID,Quantity,Remarks) VALUES ('{$Location}', '{$Cupboard}','{$Spare_item}','{$Product_number}','{$Make}','{$Serial_number}','{$Version_ID}','{$Product_ID}','{$Quantity}','{$Remarks}')";
        $result_addProduct = mysqli_query($conn, $query_addProduct);
        confirm_query($result_addProduct);
    }

    $query_showProduct = "SELECT * FROM products";
    $result_showProduct = mysqli_query($conn, $query_showProduct);
    $row_result = mysqli_query($conn, $query_showProduct);
    confirm_query($result_showProduct);
?>

<?php
  if(isset($_POST['updateProduct'])){//if the submit button is clicked
  
  
  $Location = $_POST['Location'];
  $Cupboard = $_POST['Cupboard'];
  $Spare_item = $_POST['Spare_item'];
  $Product_number = $_POST['Product_number'];
  $Make= $_POST['Make'];
  $Serial_number = $_POST['Serial_number'];
  $Version_ID = $_POST['Version_ID'];
  $Product_ID = $_POST['Product_ID'];
  $Quantity = $_POST['Quantity'];
  $Remarks = $_POST['Remarks'];
  $id = $_POST['id'];
  
  $update = "UPDATE products SET Location='$Location', Cupboard='$Cupboard', Spare_item='$Spare_item',Serial_number = '$Serial_number',Make= '$Make',Version_ID ='$Version_ID',Product_ID = '$Product_ID',Quantity = '$Quantity',Remarks = '$Remarks' WHERE ID = '{$id}'";
    
  
  $conn->query($update) or die("Cannot update");//update or error
  }
//Create a query
$sql = "SELECT * FROM products WHERE Product_number = '".$Product_number."'";
//submit the query and capture the result
$result = $conn->query($sql) or die(mysql_error());
$query=getenv(QUERY_STRING);
parse_str($query);

?>


<?php
    if (isset($_POST['deleteProduct'])) {
        $id = $_POST['id'];
        $query_deleteProduct = "DELETE FROM products WHERE ID =  {$id}";
        $result_deleteProduct = mysqli_query($conn, $query_deleteProduct);
        confirm_query($result_deleteProduct);
    }

    $query_showProduct = "SELECT * FROM products";
    $result_showProduct = mysqli_query($conn, $query_showProduct);
    confirm_query($result_showProduct);
?>
<?php
    $query = $_GET['query']; 
    // gets value sent over search form
     
    $min_length = 3;
    // you can set minimum length of the query if you want
     
    if(strlen($query) >= $min_length){ // if query length is more or equal minimum length then
         
        $query = htmlspecialchars($query); 
        // changes characters used in html to their equivalents, for example: < to &gt;
         
        $query = mysql_real_escape_string($query);
        // makes sure nobody uses SQL injection
         
        $raw_results = mysql_query("SELECT * FROM products
            WHERE (`Product_number` LIKE '%".$query."%') OR (`Make` LIKE '%".$query."%')") ;
             
        // * means that it selects all fields, you can also write: `id`, `title`, `text`
        // articles is the name of our table
         
        // '%$query%' is what we're looking for, % means anything, for example if $query is Hello
        // it will match "hello", "Hello man", "gogohello", if you want exact match use `title`='$query'
        // or if you want to match just full word so "gogohello" is out use '% $query %' ...OR ... '$query %' ... OR ... '% $query'
         
        if(mysql_num_rows($raw_results) > 0){ // if one or more rows are returned do following
             
            while($results = mysql_fetch_array($raw_results)){
            // $results = mysql_fetch_array($raw_results) puts data from database into array, while it's valid it does the loop
             
                echo "<p><h3>".$results['Product_number']."</h3>".$results['Make']."</p>";
                // posts results gotten from database(title and text) you can also show id ($results['id'])
            }
             
        }
        else{ // if there is no matching rows do following
            echo "No results";
        }
         
    }
?>
<!DOCTYPE html>
<html>
<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>IDEA | Inventory</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="bower_components/morris.js/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="bower_components/jvectormap/jquery-jvectormap.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
  <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js odoesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini">

<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="dashboard.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>I</b>IM</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>IDEA</b>Inventory</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="dropdown tasks-menu">
            <a href="logout.php" class="dropdown-toggle" >
              <i class="fa fa-sign-out"></i>
            </a>
          </li>
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <span class="hidden-xs"><?php echo htmlentities($first_name[0]); ?></span>
            </a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
        <li>
          
        </li>
        <li class="active">
          <a href="inventory.php">
            <i class="fa fa-bank"></i>
            <span>Inventory</span>
          </a>
        </li>
        <li>
          
        </li>
        </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <form action="inventory.php" method="GET">
   
</form>
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Inventory
        <small>Add, delete and update products</small>
      </h1>
    </section><br>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
            <div class="col-lg-12">
                <div <?php if($user_type == "1"){echo "style='display:none;'";} ?> class="box box-primary">
                    <div class="box-header with-border">
                      <h3 class="box-title">Add Products</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    
                  
                    <hr>
                    <form role="form" method="post" action="inventory.php" >
                      <div class="box-body">
                        <div class="form-group col-lg-4">
                          <label for="Location">Location</label>
                            <input type="text" name="Location" class="form-control" id="Location" required placeholder="Enter the Location of the product">
                        </div>
                         <div class="form-group col-lg-4">
                          <label for="Cupboard">Cupboard</label>
                            <input type="text" name="Cupboard" class="form-control" id="Cupboard" required placeholder="Enter the Location of the product">
                        </div>
                        <div class="form-group col-lg-4">
                          <label for="Spare_item">Spare Item</label>
                            <input type="text" name="Spare_item" class="form-control" id="Spare_item" required placeholder="Enter the spare_item of the product">
                        </div>
                        <div class="form-group col-lg-4">
                          <label for="Product_number">Product Number</label>
                            <input type="text" name="Product_number" class="form-control" id="Product_number" required placeholder="Enter the Product_number of the product">
                        </div>
                        <div class="form-group col-lg-4">
                          <label for="Make">Make</label>
                            <input type="text" name="Make" class="form-control" id="Make" required placeholder="Enter the Make of the product">
                        </div>
                        <div class="form-group col-lg-4">
                          <label for="Serial_number">Serial Number</label>
                            <input type="text" name="Serial_number" class="form-control" id="Serial_number" required placeholder="Enter the Serial_number of the product">
                        </div>
                        
                        <div class="form-group col-lg-4">
                          <label for="Version_ID">Version ID</label>
                            <input type="text" name="Version_ID" class="form-control" id="Version_ID" required placeholder="Enter the Version_ID of the product">
                        </div>
                        <div class="form-group col-lg-4">
                          <label for="Product_ID">Product ID</label>
                            <input type="text" name="Product_ID" class="form-control" id="Product_ID" required placeholder="Enter the Product_ID of the product">
                        </div>

                        <div class="form-group col-lg-4">
                          <label for="Quantity">Quantity</label>
                            <input type="text" name="Quantity" class="form-control" id="Quantity" required placeholder="Enter the Quantity of the product">
                        </div>
      
                        <div class="form-group col-lg-4">
                          <label for="Remarks">Remarks</label>
                            <input type="text" name="Remarks" class="form-control" id="Remarks" required placeholder="Enter the Remarks of the product">
                        </div>
                      <div class="form-group col-lg-4">
                        <label for="submit">Press add to add this product</label>
                        <button type="submit" id="submit" name="addProduct" class="btn btn-block btn-success">Add</button>
                      </div>
                    </form>
                  </div>
            </div>
      </div>
      <hr>
      <div class="container">
          <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of Products</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example2" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th class="text-center">Location</th>
                  <th class="text-center">Cupboard</th>
                  <th class="text-center">Spare Item</th>
                  <th class="text-center">Product Number</th>
                  <th class="text-center">Make</th>
                  <th class="text-center">Serial Number</th>
                  <th class="text-center">Version ID</th>
                  <th class="text-center">Product ID</th>
                  <th class="text-center">Quantity</th>
                  <th class="text-center">Remarks</th>
                </tr>
                </thead>
                <tbody>
                    <?php
                        while ($productList = mysqli_fetch_assoc($result_showProduct)) { ?>
                            <tr>
                              <td><?php echo $productList['Location']; ?></td>
                              <td><?php echo $productList['Cupboard']; ?></td>
                              <td><?php echo $productList['Spare_item']; ?></td>
                              <td><?php echo $productList['Product_number']; ?></td> 
                              <td><?php echo $productList['Make']; ?></td>
                              <td><?php echo $productList['Serial_number']; ?></td>
                              <td><?php echo $productList['Version_ID']; ?></td>
                              <td><?php echo $productList['Product_ID']; ?></td>
                              <td><?php echo $productList['Quantity']; ?></td>
                              <td><?php echo $productList['Remarks']; ?></td>
                              <td <?php if($user_type == "1"){echo "style='display:none;'";} ?> class="text-center" >
                                <a href="#" data-toggle="modal" data-target="#modal-add-table<?php echo $productList['ID']; ?>"><i class="fa fa-pencil"></i> </a>&nbsp;/ &nbsp;
                               
                              </td>
                            </tr>
                    
                    <?php }

                    ?>
                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      </div>
    </section>
    <!-- /.content -->
  </div>


  <!-- /.content-wrapper -->

  
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
<?php 
  while ($row = mysqli_fetch_assoc($row_result)) { ?>
    <div class="modal fade" id="modal-add-table<?php echo $row['ID']; ?>">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Update or Delete productd <?php echo $row['Product_number'];?></h4>
              </div>

              <div class="modal-body">
                <p>
                    <div class="box box-primary">
                        <!-- form start -->
                        <form role="form" method="post" action="inventory.php" >
                          <input type="hidden" name="id" value="<?php echo $row['ID']; ?>">
                        
                          <div class="box-body">
                            <div class="form-group">
                              <label for="Location">Location</label>
                              <input type="text" class="form-control" name="Location" value="<?php echo $row['Location']; ?>">
                            </div>
                            <div class="form-group">
                              <label for="Cupboard">Cupboard</label>
                              <input type="text" class="form-control" name="Cupboard" value="<?php echo $row['Cupboard']; ?>">
                            </div>
                            <div class="form-group">
                              <label for="Spare_tem">Spare Item</label>
                              <input type="text" class="form-control" name="Spare_item" value="<?php echo $row['Spare_item']; ?>">
                            </div>
                            <div class="form-group">
                              <label for="Product_number">Product Number</label>
                              <input type="text" class="form-control" name="Product_number" value="<?php echo $row['Product_number']; ?>">
                            </div>
                            <div class="form-group">
                              <label for="Make">Make</label>
                              <input type="text" class="form-control" name="Make" value="<?php echo $row['Make']; ?>">
                            </div>
                            <div class="form-group">
                              <label for="Serial_number">Serial Number</label>
                              <input type="text" class="form-control" name="Serial_number" value="<?php echo $row['Serial_number']; ?>">
                            </div          
                            <div class="form-group">
                              <label for="Version_ID">Version ID</label>
                              <input type="text" class="form-control" name="Version_ID" value="<?php echo $row['Version_ID']; ?>">
                            </div>
                            <div class="form-group">
                              <label for="Quantity">Quantity</label>
                              <input type="text" class="form-control" name="Quantity" value="<?php echo $row['Quantity']; ?>">
                            </div>
                            <div class="form-group">
                              <label for="Product_ID">Product ID</label>
                              <input type="text" class="form-control" name="Product_ID" value="<?php echo $row['Product_ID']; ?>">
                            </div>
                            <div class="form-group">
                              <label for="Remarks">Remarks</label>
                              <input type="text" class="form-control" name="Remarks" value="<?php echo $row['Remarks']; ?>">
                            </div>

                          </div>
                          <!-- /.box-body -->

                          <div class="btn-group" style="width:100%">
                            <input style="width:50%" type="submit" id="submit" name="updateProduct" class="btn btn-success col-xs-12" value="Update">
                            <input style="width:50%" type="submit" id="submit" name="deleteProduct" class="btn btn-success col-xs-12" value="Delete">
                          </div>
                        
                        </form>
                        
                    </div>
                </p>
              </div>
            </div>
            <!-- /.modal-content -->
        </div>
          <!-- /.modal-dialog -->
    </div>
    <?php
  }
?>
        <!-- /.modal -->

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!--<script>
    var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!

var yyyy = today.getFullYear();
if(dd<10){
    dd='0'+dd;
} 
if(mm<10){
    mm='0'+mm;
} 
var today = dd+'/'+mm+'/'+yyyy;
document.getElementById("date").innerHTML = today;
</script>-->
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="bower_components/raphael/raphael.min.js"></script>
<script src="bower_components/morris.js/morris.min.js"></script>
<!-- Sparkline -->
<script src="bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>
<?php
if (isset ($conn)){
  mysqli_close($conn);
}
?>