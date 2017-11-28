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
    $table_id = $_GET['table_id'];
?>
<?php
    $query_tableStatus = "SELECT current_bill FROM tables WHERE id = {$table_id}";
    $result_tableStatus = mysqli_query($conn, $query_tableStatus);
    confirm_query($result_tableStatus);
    $tableStatus = mysqli_fetch_assoc($result_tableStatus);
    $tempBill = "";
    if ($tableStatus['current_bill']==0) {
        date_default_timezone_set('Asia/Calcutta');
        $tempBill = date("YmdHis");
        $query_billUpdate = "UPDATE tables SET current_bill = '{$tempBill}' WHERE id = {$table_id}";
        $result_billUpdate = mysqli_query($conn, $query_billUpdate);
        confirm_query($result_billUpdate);
    } else {
        $query_billUpdate = "";
        $result_billUpdate = "";
    }
?>
<?php
    $query_table = "SELECT * FROM tables WHERE id = {$table_id}";
    $result_table = mysqli_query($conn, $query_table);
    confirm_query($result_table);
    $tableDetails = mysqli_fetch_assoc($result_table);
    $tableCurrentBill = $tableDetails['current_bill'];
?>
<?php
    $query_product = "SELECT * FROM products";
    $result_product = mysqli_query($conn, $query_product);
    confirm_query($result_product);
?>
<?php
    if (isset($_POST['addProduct'])) {
        $order_id = $tableDetails['current_bill'];
        date_default_timezone_set('Asia/Calcutta');
        $dateTemp = date("d-m-Y");
        $order_date = mysqli_real_escape_string($conn, htmlspecialchars($dateTemp));
        $product_name = $_POST['product_name'];
        $query_rate = "SELECT cost FROM products WHERE name = '{$product_name}'";
        $result_rate = mysqli_query($conn, $query_rate);
        confirm_query($result_rate);
        $product_rate = mysqli_fetch_assoc($result_rate);
        $rate = $product_rate['cost'];
        $quantity = $_POST['quantity'];
        $cost = $rate * $quantity;

        $query_order = "INSERT INTO orders (order_id, table_id, order_date, product_name, rate, quantity, cost, order_by) VALUES ('{$order_id}', {$table_id}, '{$order_date}', '{$product_name}', '{$rate}', {$quantity}, '{$cost}', '{$current_user}')";
        $result_order = mysqli_query($conn, $query_order);
        confirm_query($result_order);

        if ($result_order) {
            redirect_to("newOrder.php?table_id=".$table_id);
        }
    }
?>
<?php
    $query_orderList = "SELECT * FROM orders WHERE table_id = {$table_id} AND order_id = '{$tableCurrentBill}'";
    $result_orderList = mysqli_query($conn, $query_orderList);
    $result_orderListFinal = mysqli_query($conn, $query_orderList);
    confirm_query($result_orderList);
    confirm_query($result_orderListFinal);
?>
<?php
    if (isset($_POST['submitDiscount'])) {
        $discount = - $_POST['discount'];
        date_default_timezone_set('Asia/Calcutta');
        $dateTemp = date("d-m-Y");
        $order_date = mysqli_real_escape_string($conn, htmlspecialchars($dateTemp));
        $order_id = $tableDetails['current_bill'];
        $query_discount = "INSERT INTO orders (order_id, table_id, order_date, product_name, cost, order_by) VALUES ('{$order_id}', {$table_id}, '{$order_date}', 'Discount', {$discount}, '{$current_user}')";
        $result_discount = mysqli_query($conn, $query_discount);
        confirm_query($result_discount);

        if ($result_discount) {
            redirect_to("newOrder.php?table_id=".$table_id);
        }
    }
?>
<?php
    $order_id = $tableDetails['current_bill'];
    $query_bill = "SELECT SUM(cost) FROM orders WHERE order_id = '{$order_id}' AND table_id = {$table_id}";
    $result_bill = mysqli_query($conn, $query_bill);
    confirm_query($result_bill);
    $bill_amount = mysqli_fetch_array($result_bill);
?>
<?php
    $query_gst = "SELECT * FROM gst";
    $result_gst = mysqli_query($conn, $query_gst);
    confirm_query($result_gst);
    $show_gst = mysqli_fetch_assoc($result_gst);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Trippin Cafe | New Order</title>
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
  <style type="text/css">
      @media screen {
          #printSection {
              display: none;
          }
        }

        @media print {
          body * {
            visibility:hidden;
          }
          #printSection, #printSection * {
            visibility:visible;
          }
          #printSection {
            position:absolute;
            left:0;
            top:0;
          }
        }
  </style>
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
        <li class="active">
          <a href="dashboard.php">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>
        <li>
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
        Table <?php echo $tableDetails['name']; ?>
        <small>Order <?php echo $tableDetails['current_bill']; ?></small>
      </h1>
      <ol class="breadcrumb">
        <button type="button" class="btn btn-lg btn-info" data-toggle="modal" data-target="#modal-add-table">Checkout</button>
        <a href="deleteOrder.php?order_id=<?php echo $tableDetails['current_bill']; ?>&table_id=<?php echo $table_id ?>" onclick="return confirm('Are you sure you want to delete this order?');"><button type="submit" class="btn btn-lg btn-danger" >Cancel Order</button></a>
      </ol>
    </section><br><br>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
            <div class="col-lg-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                      <h3 class="box-title">Add Orders</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" method="post" action="newOrder.php?table_id=<?php echo $table_id ?>">
                      <div class="box-body">
                        <div class="form-group col-lg-4">
                          <label for="exampleInputEmail1">Select Product</label>
                              <select class="form-control" name="product_name">
                                <?php 
                                    while ($productList = mysqli_fetch_assoc($result_product)) { ?>
                                        <option value="<?php echo $productList['name'] ?>"><?php echo $productList['name']; ?></option>
                                        <?php
                                    }
                                ?>
                              </select>
                        </div>
                        <div class="form-group col-lg-4">
                          <label for="exampleInputPassword1">Enter Quantity</label>
                          <input type="number" class="form-control" id="exampleInputPassword1" name="quantity" placeholder="Quantity" min="1">
                        </div>

                      <div class="form-group col-lg-4">
                        <label for="submit">Press add to add this product</label>
                        <input type="submit" id="submit" name="addProduct" value="Add" class="btn btn-block btn-success">
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
              <h3 class="box-title">Current Order</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example2" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th class="text-center">Product</th>
                  <th class="text-center">Rate</th>
                  <th class="text-center">Quantity</th>
                  <th class="text-center">Cost</th>
                  <th class="text-center">Delete</th>
                </tr>
                </thead>
                <tbody>
                    <?php
                        while ($listOrder = mysqli_fetch_assoc($result_orderList)) { ?>
                            <tr>
                              <td><?php echo $listOrder['product_name']; ?></td>
                              <td><?php echo $listOrder['rate']; ?></td>
                              <td><?php echo $listOrder['quantity']; ?></td>
                              <td><?php echo $listOrder['cost']; ?></td>
                              <td class="text-center">
                                  <a href="deleteProductOrder.php?product_id=<?php echo $listOrder['id']; ?>&table_id=<?php echo $table_id ?>" onclick="return confirm('Are you sure you want to delete this?');"><i class="fa fa-close"></i> </a>
                              </td>
                            </tr>
                            <?php
                        }
                    ?>
                
                </tbody>
              </table>
              <hr>
              <div class="col-lg-6">
                  <form role="form" method="post" action="newOrder.php?table_id=<?php echo $table_id ?>">
                      <div class="form-group col-lg-6">
                          <label for="discount">Discount</label>
                          <input type="number" class="form-control" name="discount" id="discount" value="0" min="0" required>
                      </div>
                      <div class="form-group col-lg-6">
                          <label for="submitDiscount">Click submit to add discount</label>
                          <input type="submit" name="submitDiscount" id="submitDiscount" class="form-control btn btn-block btn-success" value="Submit">
                      </div>
                  </form>
              </div>
              <div class="col-lg-6">
                  <strong>
                      <h2>Total <i class="fa fa-inr"></i> <?php echo $bill_amount[0]; ?></h2>
                  </strong>
              </div>
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
              </div>
              <div class="modal-body">
                <p>
                    <div class="box box-primary">
                        <div id="printThis">
                            <div class="box-header with-border text-center">
                                <div>
                                    <h2 class="box-title">Trippin Cafe</h2>
                                <h5 id="date"></h5>
                                <h6>Invoice No.: <?php echo $tableDetails['current_bill']; ?></h6>
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
                                    while ($listOrderFinal = mysqli_fetch_assoc($result_orderListFinal)) { ?>
                                        <tr>
                                          <td><?php echo $listOrderFinal['product_name']; ?></td>
                                          <td><?php echo $listOrderFinal['rate']; ?></td>
                                          <td><?php echo $listOrderFinal['quantity']; ?></td>
                                          <td><?php echo $listOrderFinal['cost']; ?></td>
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
                            
                      <div class="box-footer">
                        <button type="button" id="btnPrint" class="btn btn-primary col-lg-12">Print</button>
                      </div>
                        
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
<script type="text/javascript">
    document.getElementById("btnPrint").onclick = function () {
        printElement(document.getElementById("printThis"));
        window.print();
    }

    function printElement(elem) {
        var domClone = elem.cloneNode(true);
        
        var $printSection = document.getElementById("printSection");
        
        if (!$printSection) {
            var $printSection = document.createElement("div");
            $printSection.id = "printSection";
            document.body.appendChild($printSection);
        }
        
        $printSection.innerHTML = "";
        
        $printSection.appendChild(domClone);
    }
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
<script type="text/javascript" src="js/printThis.js"></script>
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
