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
?>
<?php
    if (isset($_POST['gstButton'])) {
        $value = $_POST['GST'];
        $query_gstUpdate = "UPDATE gst SET value = '{$value}'";
        $result_gstUpdate = mysqli_query($conn, $query_gstUpdate);
        confirm_query($result_gstUpdate);
    }

    $query_gstShow = "SELECT * FROM gst";
    $result_gstShow = mysqli_query($conn, $query_gstShow);
    confirm_query($result_gstShow);
    $gstValue = mysqli_fetch_assoc($result_gstShow);
?>
<?php
    if (isset($_POST['addProduct'])) {
        $product = $_POST['product'];
        $cost = $_POST['cost'];

        $query_addProduct = "INSERT INTO products (name, cost) VALUES ('{$product}', '{$cost}')";
        $result_addProduct = mysqli_query($conn, $query_addProduct);
        confirm_query($result_addProduct);
    }

    $query_showProduct = "SELECT * FROM products";
    $result_showProduct = mysqli_query($conn, $query_showProduct);
    confirm_query($result_showProduct);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Trippin Cafe | Inventory</title>
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
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
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
      <span class="logo-mini"><b>T</b>CF</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Trippin</b>Cafe</span>
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
          <a href="dashboard.php">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>
        <li class="active">
          <a href="inventory.php">
            <i class="fa fa-bank"></i>
            <span>Inventory</span>
          </a>
        </li>
        <li>
          <a href="history.php">
            <i class="fa fa-calendar"></i>
            <span>History</span>
          </a>
        </li>
        </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
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
                <div class="box box-primary">
                    <div class="box-header with-border">
                      <h3 class="box-title">Add Products</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" method="post" action="inventory.php">
                      <div class="box-body">
                        <div class="form-group col-lg-6">
                          <label for="GST">GST %</label>
                            <input type="number" name="GST" class="form-control" id="GST" placeholder="GST percentage" min="0" value="<?php echo $gstValue['value']; ?>">
                        </div>
                        
                      <div class="form-group col-lg-6">
                        <label for="submit">Press update to update new GST percentage</label>
                        <button type="submit" name="gstButton" id="submit" class="btn btn-block btn-success">Update</button>
                      </div>
                    </form>
                    <hr>
                    <form role="form" method="post" action="inventory.php" >
                      <div class="box-body">
                        <div class="form-group col-lg-4">
                          <label for="product">Product</label>
                            <input type="text" name="product" class="form-control" id="product" required placeholder="Enter the name of the product">
                        </div>
                        <div class="form-group col-lg-4">
                          <label for="cost">Cost</label>
                          <input type="text" class="form-control" id="cost" name="cost" required placeholder="Enter the cost of the product" min="1">
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
                  <th class="text-center">Product</th>
                  <th class="text-center">Cost</th>
                  <th class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>
                    <?php
                        while ($productList = mysqli_fetch_assoc($result_showProduct)) { ?>
                            <tr>
                              <td><?php echo $productList['name']; ?></td>
                              <td><?php echo $productList['cost']; ?></td>
                              <td class="text-center" >
                                <a href="#" data-toggle="modal" data-target="#modal-add-table"><i class="fa fa-pencil"></i> </a>&nbsp;/ &nbsp;
                                <a href="deleteProduct.php?product_id=<?php echo $productList['id']; ?>" onclick="return confirm('Are you sure you want to delete this product?');"><i class="fa fa-close"></i> </a>
                              </td>
                            </tr>
                            <?php
                        }
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
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; 2017-2018 <a href="http://prashantkbhardwaj.github.io/">Prashant Bhardwaj</a></strong> All rights
    reserved.
  </footer>
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
<div class="modal fade" id="modal-add-table">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Update product details</h4>
              </div>
              <div class="modal-body">
                <p>
                    <div class="box box-primary">
                        <!-- form start -->
                        <form role="form">
                          <div class="box-body">
                            <div class="form-group">
                              <label for="tableName">Product</label>
                              <input type="text" class="form-control" id="tableName" value="Chicken grill">
                            </div>
                            <div class="form-group">
                              <label for="capacity">Cost</label>
                              <input type="text" class="form-control" id="capacity" value="400">
                            </div>
                          </div>
                          <!-- /.box-body -->

                          <div class="box-footer">
                            <button type="submit" class="btn btn-success col-xs-12">Update</button>
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
        <!-- /.modal -->

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<script>
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
</script>
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