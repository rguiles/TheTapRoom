<?php
include "config.php";
function console_log( $data ){
  echo '<script>';
  echo 'console.log('. json_encode( $data ) .')';
  echo '</script>';
}
// Check user login or not
if (!isset($_SESSION['uname'])) {
	header('Location: swipe.php');
}
// logout
if (isset($_POST['but_logout'])) {
	session_destroy();
	header('Location: swipe.php');
}
if(isset($_GET['id'], $_GET['beer_id'], $_GET['price'])) {
	if ($_GET['price'] >= 0.01) {
		echo "Customer id:". $_GET['id']. "<br />";
		echo "Price:". $_GET['price']. "<br />";
		echo "Beer id:" . $_GET['beer_id'];
		$customer_id = mysqli_real_escape_string($_GET['id']);
		$price = mysqli_real_escape_string($_GET['price']);
		$beer_id = mysqli_real_escape_string($_GET['beer_id']);
		$sql_query = "CALL AddTransaction(" . $_GET['id']. "," . $_GET['beer_id']. ",". $_GET['price']. ")";
		$result = mysqli_query($con, $sql_query);
		session_start();
		session_destroy();
		header("Location: swipe.php");
	}
	else {
		session_start();
		session_destroy();
		header("Location: swipe.php");
	}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>The Tap Room</title>
	<link href="css/style.css" rel="stylesheet">
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<script src="js/bootstrap.min.js"></script>
	<script src="js/index-script.js"></script>
	<link href="css/animate.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
	<link href="css/featherlight.min.css" type="text/css" rel="stylesheet" />
	<script src="//code.jquery.com/jquery-latest.js"></script>
	<script src="js/featherlight.min.js" type="text/javascript" charset="utf-8"></script>
	<link href="https://fonts.googleapis.com/css?family=Bebas+Neue|Open+Sans&display=swap" rel="stylesheet">
</head>

<body>
	<div class="fadeInDown second">
		<div class="jumbotron jumbotron-fluid">
			<div class="container">
				<div class="fadeInDown first">
					<h1 class="display-4">The Tap Room</h1>
				</div>
				<p class="lead">Order beer by clicking on the beer of your choosing and then pulling the handle.</p>
			</div>
		</div>
	</div>
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12 col-sm-12 col-12">
				<div class="popup-btn" onclick="document.getElementById('id01').style.display='block'">
					<a href="logout.php">EXIT</a>
				</div>
			</div>
		</div>
	</div>
	<div class="fadeIn fourth">
		<div class="gallery">

			<?php
			include('./connect.php');
			$sql = "SELECT * FROM Beer WHERE status ='1'";
			$res = $db->query($sql);
			while ($row = mysqli_fetch_assoc($res)) {
			?>
				<a href="/beer/<?php echo $row['beer_name']; ?>.png" data-featherlight="    
					<center><h1><?php echo $row['beer_name']; ?></h1></center>
					<hr>
					<center><img class='ignore-css' src='/beer/<?php echo $row['beer_name']; ?>.png' style='width:200px;height:200px;'></center>
					<br>
					<h5>Price Per Ounce: <?php echo $row['price_per_ounce']; ?></h5>
					<h5>IBU: <?php echo $row['ibu']; ?></h5>
					<h5>ABV: <?php echo $row['abv']; ?></h5>
					<h5>Style: <?php echo $row['style']; ?></h5>
					<br>
					<center><button class='button' id='pour'>HOLD TO POUR</button>
					<hr>
					<br>
					<h5><div id='timer'>0 Ounces</div></h5>
					</center>
					<script type='text/javascript'>
					var beer_name = '<?php echo $row['beer_name']; ?>';
					var beer_id = '<?php echo $row['beer_id']; ?>';
					customer_id = '<?php echo $_SESSION['uname'] ?>';
					var price_per_ounce = '<?php echo $row['price_per_ounce']; ?>'
					var timer = 0,
						timerInterval,
						button = document.getElementById('pour');

					button.addEventListener('mousedown', function() {
					timerInterval = setInterval(function(){
						timer += .02;
						document.getElementById('timer').innerText = timer.toFixed(2) + ' Ounces';
					}, 10);
					});

					button.addEventListener('mouseup', function() {
					clearInterval(timerInterval);
					var current = $.featherlight.current();
					});

					</script>
					"><img src="/beer/<?php echo $row['beer_name']; ?>.png" class="thumbnail"></a>
				<?php
			}
			?>
		</div>
	</div>
	<script>
		$(function() {
			$('#btn').featherlight('#lightbox');
			$.featherlight.defaults.afterClose = Closed;
		});

		function Closed() {
			var price = (timer * price_per_ounce).toFixed(2);
			if (price >= 0.01){
				var url = (window.location.href + '&price=' + price + '&beer_id=' + beer_id);
				window.location.replace(url);
			}
		}
	</script>
</body>

</html>