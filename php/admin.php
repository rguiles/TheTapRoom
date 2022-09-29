<?php
include "config.php";
function console_log( $data ){
  echo '<script>';
  echo 'console.log('. json_encode( $data ) .')';
  echo '</script>';
}
if(isset($_GET['id'], $_GET['close'])) {
	if ($_GET['close'] = 1) {
		$sql_query = "UPDATE Transactions SET status=0 WHERE customer_id=".$_GET['id'];
		$result = mysqli_query($con, $sql_query);
		header("Location: admin.php");
	}
}
?>

<html lang="en">

<head>
  <title>Admin Panel</title>
  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  <link href="css/admin.css" rel="stylesheet">
  <script src="js/admin-script.js"></script>
</head>

<body>
  <section class="container">
    <div class="left-half">
      <article>
        <?php
        if (isset($_GET['id'])) {
          ?>
          <table class="table">
            <thead>
              <tr>
                <th scope="col">Date Time</th>
                <th scope="col">Beer</th>
                <th scope="col">Price</th>
              </tr>
            </thead>
            <tbody>
              <?php
                include('./connect.php');
                $sql = "SELECT *, (SELECT SUM(price) from Transactions where customer_id=Customer.customer_id) as totalprice, (SELECT Count(price) from Transactions where customer_id=Customer.customer_id) as totalcount FROM Customer INNER JOIN Transactions ON Customer.customer_id = Transactions.customer_id INNER JOIN Beer On Transactions.beer_poured = Beer.beer_id WHERE Transactions.status=1 && Transactions.customer_id =" . $_GET['id'];
                $res = $db->query($sql);
                $count = 0;
                while ($row = mysqli_fetch_assoc($res)) {
                  if ($count < 1) {
                    $count++;
                  ?>
                    <h3><?php echo $row['first_name']; ?> <?php echo $row['last_name']; ?>'s Transactions</h3>
                    <br>
                  <?php
                  }
                  ?>
                <tr>
                  <td scope="row"><?php echo $row['datetime']; ?></th>
                  <td><?php echo $row['beer_name']; ?></td>
                  <td><?php echo $row['price']; ?></td>
                </tr>
              <?php
                }
                ?>
            <tbody>
          </table>
          <br>
          <br>
		  <form method="get">
		  <input type="hidden" id="id" name="id" value="<?php echo $_GET['id']; ?>">
          <button type="submit" name="close" value="1" class="btn btn-danger">Close Tab</button>
		  </form>
        <?php
        } else {
          ?>
          <h5>Please select a customer...</h5>
        <?php
        }
        ?>

      </article>
    </div>
    <div class="right-half">
      <article>
        <form method="get">
          <?php
          include('./connect.php');
          $sql = "SELECT a.customer_id, a.first_name, a.last_name, COUNT(b.customer_id) TotalOrders, SUM(b.price) TotalPrice FROM Customer a LEFT JOIN Transactions b ON a.customer_id = b.customer_id && b.status = 1 GROUP BY a.customer_id, a.first_name, a.last_name HAVING `TotalPrice` >= 0.01 ORDER BY `TotalPrice` DESC";
          $res = $db->query($sql);
          while ($row = mysqli_fetch_assoc($res)) {
            ?>
            <button type="submit" name="id" value="<?php echo $row['customer_id']; ?>" class="btn btn-light"><?php echo $row['first_name']; ?> <?php echo $row['last_name']; ?> - <?php echo $row['TotalPrice']; ?></button>
            <br>
          <?php
          }
          ?>
        </form>
      </article>
    </div>
  </section>

  <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <a class="navbar-brand" href="#">
      <img src="/images/TTR.png" height="50" width="100" style="display: inline-block;">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="menu">
      <div class="navbar-nav">
        <a class="nav-item nav-link" data-page="home" href="#"><b>Home</b></a>
      </div>
      <ul class="nav navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-item nav-link" onclick="Redirect();"><b>Logout</b></a>
        </li>
      </ul>
    </div>
  </nav>


</html>