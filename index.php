<?php
session_start();
if(isset($_SESSION['online']) == "1"){
	if($_SESSION['role'] == "admin"){
		header('location: admin_dashboard.php');
	}else{
		header('location: user_orders.php');
	}
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
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <div class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow" style="background-repeat: repeat-x;
                            position: fixed;
                            z-index: 500;
                            width: 100%;">

                    <!-- Topbar Logo -->
                    <div>
                        <h2 class="logo"><img src="img/logo.png" width="30px"> PRUTS</h2>
                    </div>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - About -->
                        <li class="nav-item">
                            <a class="nav-link" href="#about">
                                <span class="mr-2 d-none d-lg-inline text-gray-600">About Us</span>
                            </a>
                        </li>

                        <!-- Nav Item - Login -->
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">
                                <span class="mr-2 d-none d-lg-inline text-gray-600">Login</span>
                                <img class="img-profile rounded-circle" src="img/user.jpg">

                            </a>
                        </li>
                    </ul>
                </div>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div>

                    <!-- Banner -->
                    <section id="banner">
                        <div class="content">
                            <h1>Welcome to Pruts</h1>
                        </div>
                    </section>

                    <!-- Page content here -->
                    <div class="container-fluid">

                        <section id="about">
                            <div class="content">
                                <h1>About Us</h1>
                                <br>
                                <p>Pruts has been a family owned business for over 70 years.</p>

                                <p>We have a modern-day 114,000 sq. ft. facility located in Toril, Davao City. We have an extensive selection of local produce and an impressive intuitive online ordering portal. Our active membership with Produce Alliance as well as other industry partners helps us stay ahead of the trends and ensure a wide range of quality products. We are proud to be Woman-owned and currently hold our WBENC Certificate.</p>

                                <p>Our state-of-the-art 114,000 square-foot facility boasts six separate coolers, optimizing shelf life of the products in each temperature controlled cooler. Our facility is HAACP and SQF certified and independently audited several times a year. Our aggressive food safety program ensures our customers’ confidence in our products.</p>

                                <p>Since 1986, Pruts has an in-house fresh cut department with hundreds of customizable options, the possibilities are endless.</p>

                                <p>Our focused attention on the produce industry and food service industry enables us to provide flexible delivery schedules, convenient pack sizes and smaller quantities. We also provide information, market updates, local product news, new product announcements and more. </p>
                            </div>
                        </section>

                    </div>
                </div>
                <!-- End page content -->

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

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/script.js"></script>

</body>
</html>