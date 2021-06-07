<?php
session_start();
if(isset($_SESSION['online']) == "1"){
	if($_SESSION['role'] == "admin"){
		header('location: admin_dashboard.php');
	}
}

include("config.php");

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$res = mysqli_query($conn, "SELECT * FROM customer where username = '$_SESSION[username]'");
$row = mysqli_fetch_assoc($res);

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
                <div class="sidebar-brand-text mx-3 logonocolor">Pruts</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - My orders -->
            <li class="nav-item">
                <a class="nav-link" href="user_orders.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>My Orders</span></a>
            </li>
            <!-- Nav Item - place order -->
            <li class="nav-item">
                <a class="nav-link" href="user_place_order.php">
                    <i class="fas fa-fw fa-clipboard"></i>
                    <span>Place Order</span></a>
            </li>
            <!-- Nav Item - Inbox -->
            <li class="nav-item">
                <a class="nav-link" href="user_inbox.php">
                    <i class="fas fa-fw fa-inbox"></i>
                    <span>Inbox</span></a>
            </li>
            <!-- Nav Item - Users -->
            <li class="nav-item active">
                <a class="nav-link" href="user_profile.php">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Profile</span></a>
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
                                    <?php echo $row['fname']; ?>
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
                        <a href="admin_users.php" style="text-decoration: none;">
                            <h1 class="h3 mb-0 text-gray-800">Profile</h1>
                        </a>
                    </div>

                    <!-- Form here for edit -->
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <p id="txt">Only edit misspelled name or update contact number and address!</p>
                            <form class="form-row" method="post">
                                <div class="col-md-12 mb-3">
                                    <p id="txt">First name</p>
                                    <input class="form-control" type="text" name="fname" required value="<?php echo $row['fname']; ?>">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <p id="txt">Middle name</p>
                                    <input class="form-control" type="text" name="mname" required value="<?php echo $row['mname']; ?>">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <p id="txt">Last name</p>
                                    <input class="form-control" type="text" name="lname" required value="<?php echo $row['lname']; ?>">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <p id="txt">Contact number</p>
                                    <input class="form-control" type="text" name="contact" required value="<?php echo $row['contactno']; ?>">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <p id="txt">Address</p>
                                    <input class="form-control" type="text" name="address" required value="<?php echo $row['address']; ?>">
                                </div>
                                <input class="btn btn-success" type="submit" name="btn" value="Save Changes" style="margin-left:5px">
                            </form>
                            <?php
                            if (isset($_POST['btn'])) {
                                $fname = $_POST['fname'];
                                $mname = $_POST['mname'];
                                $lname = $_POST['lname'];
                                $contact = $_POST['contact'];
                                $address = $_POST['address'];
                                $user_id = $row['customerId'];
                                $sql = "UPDATE customer SET fname = '$fname', mname = '$mname', lname = '$lname', contactno = '$contact', address = '$address' WHERE customerId = '$user_id'";
                                mysqli_query($conn, $sql);
                                if (mysqli_query($conn, $sql) == TRUE) { ?>
                                    <script>
                                        window.location.href = "user_profile.php";
                                    </script>
                            <?php }
                            }
                            ?>
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