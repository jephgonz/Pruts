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

// initialize total
$total = 0;

$res = mysqli_query($conn, "SELECT * FROM customer where username = '$_SESSION[username]'");
$row = mysqli_fetch_assoc($res);

$user_id = $row['customerId'];
$address = $row['address'];
$description =  htmlspecialchars($_POST['description']);

foreach ($_POST as $key => $value) {
	if ($value == '') {
		continue;
	}
	if (is_numeric($key)) {
		$result = mysqli_query($conn, "SELECT * FROM fruit WHERE fruitId = '$key'");
		while ($row = mysqli_fetch_array($result)) {
			$name = $row['name'];
			$price = $row['pricePerKilo'];
		}
		$price = $value * $price;
		$total = $total + $price;
	}
	if($description == ''){
		$description = "Ordered $value Kilo(s) of $name";
	}
}

if($total != '0'){
	$sql = "INSERT INTO `order`( `customerId`, `address`, `description`, `total`) VALUES ('$user_id', '$address', '$description', '$total')";
	if ($conn->query($sql) === TRUE) {
		$order_id =  $conn->insert_id;
		foreach ($_POST as $key => $value) {
			if (is_numeric($key)) {
				$result = mysqli_query($conn, "SELECT * FROM fruit WHERE fruitId = '$key'");
				$row = mysqli_fetch_assoc($result);
				$fname = $row['name'];
				$fprice = $row['pricePerKilo'];
				$sql = "INSERT INTO `order_fruit`(`orderId`, `fruitId`, `fruitName`, `pricePerKilo`, `quantity`) VALUES ('$order_id', '$key', '$fname', '$fprice', '$value')";
				$conn->query($sql) === TRUE;
			}
		}
		header("location: user_place_order.php");
	}
}else if($total == '0'){
	echo '<script>
			alert("Invalid order!");
			window.location.href = "user_place_order.php";
		</script>';	
}
?>