<?php
session_start();
if (isset($_SESSION['online']) == "1") {
    if ($_SESSION['role'] == "admin") {
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

$user_id = $row['customerId'];
$cname = $row['fname'];

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
            <li class="nav-item active">
                <a class="nav-link" href="user_inbox.php">
                    <i class="fas fa-fw fa-inbox"></i>
                    <span>Inbox</span></a>
            </li>
            <!-- Nav Item - Users -->
            <li class="nav-item">
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
                        <h1 class="h3 mb-0 text-gray-800">Inbox</h1>
                    </div>

                    <!--start container-->
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <button id="add" data-toggle="modal" data-target="#addModal" class="btn btn-success btn-sm m-0 waves-effect"><i class="fa fa-envelope"></i> Compose</button>
                            <div id="work-collections" style="margin-top:15px;">
                                <ul id=" projects-collection" class="collection">
                                    <?php
                                    $res = mysqli_query($conn, "SELECT * FROM `ticket` WHERE 1 AND ticket.customerId = '$user_id' ORDER BY DATE DESC;");
                                    while ($row = mysqli_fetch_array($res)) {
                                        echo '<a href="user_view_messages.php?id=' . $row['ticketId'] . '"class="collection-item">
                                        <div class="row">
                                        <div class="col s6">
                                        <p class="collections-title">' . $row['description'] . '</p>                                              
                                        </div>
                                        <div class="col s3">
                                        <span class="badge">' . $row['date'] . '</span></div>
                                        <div class="col s3">
                                        <span class="badge">' . $row['status'] . '</span></div>	
                                        </div>
                                        </a>';
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
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

        <!-- Add Modal -->
        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Message</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body mx-3">

                        <form class="form-row" method="post">
                            <div class="col-md-12 mb-3">
                                <p id="txt">To: Fruit Supplier Owner</p>
                                <textarea name="message" id="message"style="width:100%; height:200px;"></textarea>
                            </div>
                            <button class="btn btn-success" type="submit" name="btn" style="margin-left:5px"><i class="fa fa-paper-plane"></i> Send</button>
                        </form>
                    </div>
                </div>
                <?php
                if (isset($_POST['btn'])) {
                    $body = $_POST['message'];
                    $sql = "INSERT INTO ticket (customerId, description, status)
							VALUES ('$user_id', '$body', 'Open')";

                    if ($conn->query($sql) === TRUE) {
                        $last_id = $conn->insert_id;
                    } else {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }
                    
                    $sql1 = "INSERT INTO ticket_message (ticketId, senderName, body)
							VALUES ($last_id, '$cname', '$body')";
                    mysqli_query($conn, $sql1);
                    echo "<meta http-equiv='refresh' content='0'>";
                }
                ?>
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