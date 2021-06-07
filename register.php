<?php
session_start();
if (isset($_SESSION['online']) == "1") {
	if ($_SESSION['role'] == "admin") {
		header('location: admin_dashboard.php');
	} else {
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

function register(mysqli $conn)
{
	if (!empty($_POST['username'])) {
		$query = mysqli_query($conn, "SELECT * FROM customer where username = '$_POST[username]'");
		$row = mysqli_fetch_array($query);
		$euser = $row['username'];
		if (!empty($row['username'])) {
			$_SESSION['username'] = $_POST['username'];
			$_SESSION['euser'] = $euser;
			if ($_SESSION['euser'] == $_SESSION['username']) {
				echo '<script>alert("Username already taken!");</script>';
			}
		} else {
			if ($_POST['password'] == $_POST['cpassword']) {
				$fname = $_POST['fname'];
				$mname = $_POST['mname'];
				$lname = $_POST['lname'];
				$contact = $_POST['contact'];
				$address = $_POST['address'];
				$username = $_POST['username'];
				$password = $_POST['password'];
				$sql = "INSERT INTO customer (fname,mname,lname,contactno,address, username, password)
							VALUES ('$fname','$mname','$lname','$contact','$address', '$username', '$password')";
				mysqli_query($conn, $sql);
				echo '<script>alert("Registered! You may now login!");</script>';
				header('location: login.php');
			} else {
				echo '<script>alert("Please confirm your password if correct!");</script>';
			}
		}
	}
}
?>
<!DOCTYPE html>
<html>

<head>
	<title>Pruts</title>
	<link rel="icon" href="img/logo.png" sizes="16x16" type="image/png">
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

						<!-- Nav Item - Cancel -->
						<li class="nav-item">
							<a class="nav-link" href="index.php">
								<span class="mr-2 d-none d-lg-inline text-gray-600">Cancel</span>
							</a>
						</li>
					</ul>
				</div>
				<!-- End of Topbar -->

				<!-- Begin Page Content -->
				<div>

					<!-- Register -->
					<div class="register shadow">
						<p class="loginsign" align="center">REGISTER</p>
						<form class="loginform" method="post">
							<input class="inputbox" type="text" name="fname" align="center" placeholder="First Name" required>
							<input class="inputbox" type="text" name="mname" align="center" placeholder="Middle Name" required>
							<input class="inputbox" type="text" name="lname" align="center" placeholder="Last Name" required>
							<input class="inputbox" type="text" name="contact" align="center" placeholder="Contact Number" required>
							<input class="inputbox" type="text" name="address" align="center" placeholder="Address" required>
							<input class="inputbox" type="text" name="username" align="center" placeholder="Username" required>
							<input class="inputbox" type="password" name="password" align="center" placeholder="Password" required>
							<input class="inputbox" type="password" name="cpassword" align="center" placeholder="Confirm Password" required>
							<input class="lsubmit" type="submit" align="center" name="btn" value="Register">
							<?php
							if (isset($_POST['btn'])) {
								register($conn);
							}
							?>
							<br><br>
							<span class="rformextra"><a>Already have an account?</a><a href="login.php"> Login</a></span>
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

		<!-- Prevent resubmission forms -->
		<script>
			if (window.history.replaceState) {
				window.history.replaceState(null, null, window.location.href);
			}
		</script>

</body>

</html>