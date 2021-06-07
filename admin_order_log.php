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

            <!-- Nav Item - Tables -->
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
                        <a href="admin_orders.php" style="text-decoration: none;">
                            <h1 class="h3 mb-0 text-gray-800"><i class="fa fa-chevron-left"></i> Back</h1>
                        </a>
                    </div>
                    <div>
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <?php $res = mysqli_query($conn, "SELECT * FROM `order_log` WHERE 1"); ?>
                                <div class="table-responsive">
                                    <table id="dataTable" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Log ID</th>
                                                <th>Order ID</th>
                                                <th>Customer ID</th>
                                                <th>Address</th>
                                                <th>Description</th>
                                                <th>Total</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Action Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = mysqli_fetch_assoc($res)) { ?>
                                                <tr>
                                                    <td>
                                                        <?= $row['id']; ?>
                                                    </td>
                                                    <td>
                                                        <?= $row['orderId']; ?>
                                                    </td>
                                                    <td>
                                                        <?= $row['customerId']; ?>
                                                    </td>
                                                    <td>
                                                        <?= $row['address']; ?>
                                                    </td>
                                                    <td>
                                                        <?= $row['description']; ?>
                                                    </td>
                                                    <td>
                                                        <?= $row['total']; ?>
                                                    </td>
                                                    <td>
                                                        <?= $row['date']; ?>
                                                    </td>
                                                    <td>
                                                        <?= $row['status']; ?>
                                                    </td>
                                                    <td>
                                                        <?= $row['actionDate']; ?>
                                                    </td>
                                                    <td>
                                                        <?= $row['action']; ?>
                                                    </td>
                                                <?php } ?>
                                        </tbody>

                                    </table>
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