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
    $query_gst = "SELECT * FROM gst";
    $result_gst = mysqli_query($conn, $query_gst);
    confirm_query($result_gst);
    $show_gst = mysqli_fetch_assoc($result_gst);
?>
<?php
    $order_date = $_GET['order_date'];
    $query_invoice = "SELECT DISTINCT(order_id) FROM orders WHERE order_date = '{$order_date}'";
    $result_invoice = mysqli_query($conn, $query_invoice);
    confirm_query($result_invoice);
    $invoiceNos = [];
    $i = 0;
    while ($invoiceList = mysqli_fetch_assoc($result_invoice)) {
        $invoiceNos[$i] = $invoiceList['order_id'];
        $i = $i+1;
    }
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Trippin Cafe | Dates</title>
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
            <a href="logout.php" class="dropdown-toggle">
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
          <a href="#">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>
        <li>
          <a href="inventory.php">
            <i class="fa fa-bank"></i>
            <span>Inventory</span>
          </a>
        </li>
        <li class="active">
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
        <?php echo $order_date; ?>
        <small>Bills</small>
      </h1>
    </section><br>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <?php
            for ($i=0; $i < sizeof($invoiceNos); $i++) { 
                $query_invoiceArr = "SELECT * FROM orders WHERE order_date = '{$order_date}' AND order_id = '{$invoiceNos[$i]}'";
                $result_invoiceArr = mysqli_query($conn, $query_invoiceArr);
                confirm_query($result_invoiceArr);
                $query_bill = "SELECT SUM(cost) FROM orders WHERE order_id = '{$invoiceNos[$i]}' AND order_date = '{$order_date}'";
                $result_bill = mysqli_query($conn, $query_bill);
                confirm_query($result_bill);
                $bill_amount = mysqli_fetch_array($result_bill); ?>
                    <div class="col-lg-6 col-xs-6">
                      <div class="box box-primary">
                          <div class="box-header with-border text-center">
                                <h2 class="box-title">Trippin Cafe</h2>
                                <h6>Invoice No.: <?php echo $invoiceNos[$i]; ?></h6>
                            </div>  
                            <div class="box-body">
                                <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                  <th class="text-center">Product</th>
                                  <th class="text-center">Rate</th>
                                  <th class="text-center">Quantity</th>
                                  <th class="text-center">Cost</th>
                                  
                                </tr>
                                </thead>
                                <tbody>
                <?php
                while ($invoiceArr = mysqli_fetch_assoc($result_invoiceArr)) { ?>
                    
                                <tr>
                                  <td><?php echo $invoiceArr['product_name']; ?></td>
                                  <td><?php echo $invoiceArr['rate']; ?></td>
                                  <td><?php echo $invoiceArr['quantity']; ?></td>
                                  <td><?php echo $invoiceArr['cost']; ?></td>
                                </tr>
                                
                    <?php
                    
                }
                ?>
                            </tbody>
                              </table>
                                <hr>
                                Total <i class="fa fa-inr"></i> <?php echo $bill_amount[0]; ?><br>
                              GST <?php echo $show_gst['value']; ?>%<br>
                              Grand Total <span><strong><h3>
                                <i class="fa fa-inr"></i>
                                <?php
                                    $grandTotal = $bill_amount[0] + (($show_gst['value']/100)*$bill_amount[0]);
                                    echo $grandTotal;
                                  ?>
                              </h3></strong></span>
                            </div>
                        </div>
                    </div>
                <?php
            }
        ?>
        
      </div>
      <!-- /.row -->

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

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
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