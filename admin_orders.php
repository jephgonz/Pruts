<?php
session_start();
if (isset($_SESSION['online']) == "1") {
    if ($_SESSION['role'] == "customer") {
        header('location: user_orders.php');
    }
}

include("config.php");

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
<!DOCTYPE html>
<html>

<head>

    <title>Pruts</title>
    <link rel="icon" href="img/logo.png" type="image/png">
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <!-- fix bug excess white spaces under footer -->
    <style> 
	        iframe {
	            display: none; 
	        }
	</style>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center">
                <div class="sidebar-brand-icon">
                    <img src="img/logo.png">
                </div>
                <div class="sidebar-brand-text mx-3 logonocolor">Pruts Admin</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">


            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="admin_dashboard.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Nav Item - Orders -->
            <li class="nav-item active">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse1"
                    aria-expanded="true" aria-controls="collapseOne">
                    <i class="fas fa-fw fa-clipboard"></i>
                    <span>Orders</span>
                </a>
                <div id="collapse1" class="collapse" aria-labelledby="headingOne" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="admin_orders.php">All</a>
                        <a class="collapse-item" href="admin_orders_status_n.php">Not Paid</a>
                        <a class="collapse-item" href="admin_orders_status_p.php">Paid</a>
                        <a class="collapse-item" href="admin_orders_status_c.php">Cancelled</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Inbox -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse2"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-inbox"></i>
                    <span>Inbox</span>
                </a>
                <div id="collapse2" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="admin_inbox.php">All</a>
                        <a class="collapse-item" href="admin_inbox_status_o.php">Open</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Tables -->
            <li class="nav-item">
                <a class="nav-link" href="admin_products.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Products</span></a>
            </li>

            <!-- Nav Item - Users -->
            <li class="nav-item">
                <a class="nav-link" href="admin_users.php">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Customers</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler Sidebar -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600">
                                    <?php
                                    $res = mysqli_query($conn, "SELECT * FROM owner where username = '$_SESSION[username]'");
                                    $row = mysqli_fetch_assoc($res);
                                    echo $row['fname']; ?>
                                    <?php echo $row['mname']; ?>
                                    <?php echo $row['lname']; ?>
                                </span>
                                <img class="img-profile rounded-circle" src="img/user.jpg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Orders</h1>
                    </div>

                    <div class="card shadow mb-4">
                    <div class="card-body">
                    <a href='admin_order_log.php' class="btn btn-success btn-sm m-0 waves-effect"><i class="fa fa-clipboard-list"></i> Action Logs</a>
                            <br></br>
                        <div id="work-collections" class="collection">
                            <?php
                            $sql = mysqli_query($conn, "SELECT * FROM `order` WHERE 1 ORDER BY date DESC;");
                            echo '<div>
                                <div>
                                    <ul class="collection">';
                                        while ($row = mysqli_fetch_array($sql)) {
                                        $status = $row['status'];
                                        if($status == 'Canceled'){
                                            echo '<li class="collection-item avatar cyan" style="color:white">
                                            <i class="fa fa-clipboard" style=""></i>
                                            <span class="">Order No. ' . $row['orderId'] . '</span>
                                            <p><strong>Date:</strong> ' . $row['date'] . '</p>
                                            <p><strong>Status:</strong> ' . $row['status'] . '</p>
                                            <form method="post">
                                                <button class="btn btn-danger btn-sm m-0 waves-effect" type="submit" name="dbtn">Delete</button>
                                            </form>
                                            </li>';
                                        }else{
                                            echo '<li class="collection-item avatar cyan" style="color:white">
                                            <i class="fa fa-clipboard" style=""></i>
                                            <span class="">Order No. ' . $row['orderId'] . '</span>
                                            <p><strong>Date:</strong> ' . $row['date'] . '</p>
                                            <p><strong>Status:</strong> ' . $row['status'] . '</p>
                                            <form method="post">
                                                <input type="hidden" name="oid" value="' . $row['orderId'] . '">										
                                                <input type="hidden" name="ostatus" value="' . ($status == 'Not Paid' ? 'Paid' : 'Not Paid') . '">
                                                <button class="btn btn-info btn-sm m-0 waves-effect" type="submit" name="cstatus" id="submit_button">'
                                                . ($status == 'Not Paid' ? 'Paid' : 'Not Paid') . '
                                                </button>
                                                <button class="btn btn-danger btn-sm m-0 waves-effect" type="submit" name="dbtn">Delete</button>
                                            </form>
                                            </li>';
                                        }
                                        $order_id = $row['orderId'];
                                        $customer_id = $row['customerId'];
                                        $sql1 = mysqli_query($conn, "SELECT * FROM order_fruit WHERE orderId = $order_id;");
                                        $sql3 = mysqli_query($conn, "SELECT * FROM customer WHERE customerId = $customer_id;");
                                        while ($row3 = mysqli_fetch_array($sql3)) {
                                        echo '<li class="collection-item">
                                            <p><strong>Name: </strong>' . $row3['fname'] . '</p>
                                            <p><strong>Address: </strong>' . $row['address'] . '</p>
                                            ' . ($row3['contactno'] == '' ? '' : '<p><strong>Contact number: </strong>' . $row3['contactno'] . '</p>') . '			
                                            ' . (!empty($row['description']) ? '<p><strong>Description: </strong>' . $row['description'] . '</p>' : '') . '								
                                            </li>';
                                        }
                                        while ($row1 = mysqli_fetch_array($sql1)) {
                                        $fruit_id = $row1['fruitId'];
                                        $sql2 = mysqli_query($conn, "SELECT * FROM fruit WHERE fruitId = $fruit_id;");
                                        while ($row2 = mysqli_fetch_array($sql2)) {
                                            $fruit_name = $row2['name'];
                                            $fruit_price = $row2['pricePerKilo'];
                                            echo '<li class="collection-item">
                                            <div class="row">
                                            <div class="col s7">
                                            <p class="collections-title"><strong>#' . $row1['fruitId'] . '</strong> ' . $fruit_name . '</p>
                                            </div>
                                            <div class="col s2">
                                            <span>' . $row1['quantity'] . ' Pieces</span>
                                            </div>
                                            <div class="col s3">
                                            <span>' . $fruit_price . ' Per kilo</span>
                                            </div>
                                            </div>
                                            </li>';
                                            }
                                        }
                                        echo '<li class="collection-item">
                                                <div class="row">
                                                    <div class="col s7">
                                                        <p class="collections-title"> Total</p>
                                                    </div>
                                                    <div class="col s2">
                                                    <span> </span>
                                                    </div>
                                                    <div class="col s3">
                                                        <span><strong>PHP ' . $row['total'] . '</strong></span>
                                                    </div>';
                                            echo    '</div>
                                                    </li>';
                                        }
                                        ?>
                                        <?php
                                        if (isset($_POST['cstatus'])) {
                                            $oid = $_POST['oid'];
                                            $ostatus = $_POST['ostatus'];
                                            $sql = "UPDATE orders SET status = '$ostatus' WHERE id = '$oid'";
                                            mysqli_query($conn, $sql);
                                            echo "<meta http-equiv='refresh' content='0'>";
                                        }
                                        if (isset($_POST['dbtn'])) {
                                            $oid = $_POST['oid'];
                                            $sql = "DELETE FROM `order` WHERE `order`.`orderId` = $oid";
                                            mysqli_query($conn, $sql);
                                            echo "<meta http-equiv='refresh' content='0'>";
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

                <!-- Footer -->
                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright &copy; Pruts 2021</span>
                        </div>
                    </div>
                </footer>
                <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Logout</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-success" href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/script.js"></script>

    <!-- Datatables -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="js/datatables.js"></script>
    
    <!-- Prevent resubmission forms -->
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>

</body>

</html>