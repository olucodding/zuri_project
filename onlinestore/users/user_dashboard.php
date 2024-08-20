<?php
session_start();
include '../includes/db.php'; // Include the database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page
    header('Location: customer_login.php');
    exit();
}

// Fetch user details using prepared statements
$user_id = $_SESSION['user_id'];
$user_query = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$user_query->bind_param('i', $user_id);
$user_query->execute();
$user_result = $user_query->get_result();
$user = $user_result->fetch_assoc();
$user_query->close(); // Close the prepared statement

// Fetch low stock alerts using prepared statements
$low_stock_query = $conn->prepare("SELECT * FROM products WHERE quantity < ?");
$low_stock_limit = 10;
$low_stock_query->bind_param('i', $low_stock_limit);
$low_stock_query->execute();
$low_stock_result = $low_stock_query->get_result();
$low_stock_query->close(); // Close the prepared statement

// Fetch user's orders using prepared statements
$order_query = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC");
$order_query->bind_param('i', $user_id);
$order_query->execute();
$order_result = $order_query->get_result();
$order_query->close(); // Close the prepared statement

// Fetch order history using prepared statements
$order_history_query = $conn->prepare("SELECT * FROM order_history WHERE user_id = ?");
$order_history_query->bind_param('i', $user_id);
$order_history_query->execute();
$order_history_result = $order_history_query->get_result();
$order_history_query->close(); // Close the prepared statement

// Close the database connection
mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <script>

window.addEventListener('popstate', function(event) {
    if (sessionStorage.getItem('navigationHandled') !== 'true') {
        if (confirm("Are you sure you want to go back?")) {
            sessionStorage.setItem('navigationHandled', 'true');
            history.back();
        } else {
            sessionStorage.setItem('navigationHandled', 'true');
            history.pushState(null, null, window.location.href);
        }
    } else {
        sessionStorage.setItem('navigationHandled', 'false');
    }
});

history.pushState(null, null, window.location.href);
sessionStorage.setItem('navigationHandled', 'false');


    </script>

<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <!-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> -->


    <!-- Mobile Specific Meta -->
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Favicon-->
	<link rel="shortcut icon" href="../img/fav.png">
	<!-- Author Meta -->
	<meta name="author" content="CodePixar">
	<!-- Meta Description -->
	<meta name="description" content="">
	<!-- Meta Keyword -->
	<meta name="keywords" content="">
	<!-- meta character set -->
	<meta charset="UTF-8">
	<!-- Site Title -->
	

	<!--CSS ============================================= -->
	<link rel="stylesheet" href="../css/linearicons.css">
	<link rel="stylesheet" href="../css/owl.carousel.css">
	<link rel="stylesheet" href="../css/font-awesome.min.css">
	<link rel="stylesheet" href="../css/themify-icons.css">
	<link rel="stylesheet" href="../css/nice-select.css">
	<link rel="stylesheet" href="../css/nouislider.min.css">
	<link rel="stylesheet" href="../css/bootstrap.css">
	<link rel="stylesheet" href="../css/main.css">
	<link rel="stylesheet" href="css2/styles2.css">
	<link rel="stylesheet" href="js2/script2.js">



</head>


<body id="category">
<!-- Start Header Area -->
<header class="header_area sticky-header">
		<div class="main_menu">
			<nav class="navbar navbar-expand-lg navbar-light main_box">
				<div class="container">
					<!-- Brand and toggle get grouped for better mobile display -->
					<a class="navbar-brand logo_h" href="../index.html"><img src="../img/logo2.png" alt=""></a>
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
					 aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse offset" id="navbarSupportedContent">
						<ul class="nav navbar-nav menu_nav ml-auto">
							<li class="nav-item active"><a class="nav-link" href="../index.html">Home</a>
							
							</li>

							<li class="nav-item submenu dropdown">
								<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
								 aria-expanded="false">SHOES</a>
								<ul class="dropdown-menu">
									<li class="nav-item"><a class="nav-link" href="../custom_shoes.html">Brogue Shoes</a></li>
									<li class="nav-item"><a class="nav-link" href="../custom_shoes.html">Derby Shoes</a></li>
									<li class="nav-item"><a class="nav-link" href="../custom_shoes.html">Double Monk Shoes</a></li>
									<li class="nav-item"><a class="nav-link" href="../custom_shoes.html">Loafers</a></li>
									<li class="nav-item"><a class="nav-link" href="../custom_shoes.html">Longwing Blucher Shoes</a></li>
									<li class="nav-item"><a class="nav-link" href="../custom_shoes.html">Oxford Shoes</a></li>
									<li class="nav-item"><a class="nav-link" href="../custom_shoes.html">Saddle Shoes</a></li>
									<li class="nav-item"><a class="nav-link" href="../custom_shoes.html">Single Monk Shoes</a></li>
									<li class="nav-item"><a class="nav-link" href="../custom_shoes.html">Wholecut Shoes</a></li>
								</ul>
							</li>

							<li class="nav-item submenu dropdown">
								<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
								 aria-expanded="false">BOOTS</a>
								<ul class="dropdown-menu">
									<li class="nav-item"><a class="nav-link" href="../custom_boots.html">Balmoral Boots</a></li>
									<li class="nav-item"><a class="nav-link" href="../custom_boots.html">Brogue Boots</a></li>
									<li class="nav-item"><a class="nav-link" href="../custom_boots.html">Chelsea Boots</a></li>
									<li class="nav-item"><a class="nav-link" href="../custom_boots.html">Chukka Boots</a></li>
									<li class="nav-item"><a class="nav-link" href="../custom_boots.html">Derby Boots</a></li>
									<li class="nav-item"><a class="nav-link" href="../custom_boots.html">Double Monk Boots</a></li>
									<li class="nav-item"><a class="nav-link" href="../custom_boots.html">Jodhpur Boots</a></li>
									<li class="nav-item"><a class="nav-link" href="../custom_boots.html">Hiking Boots</a></li>
									<li class="nav-item"><a class="nav-link" href="../custom_boots.html">Moc Toe Boots</a></li>
								</ul>
							</li>


							<li class="nav-item submenu dropdown">
								<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
								 aria-expanded="false">CASUAL</a>
								<ul class="dropdown-menu">
									<li class="nav-item"><a class="nav-link" href="../custom_casuals.html">Driving Shoes</a></li>
									<li class="nav-item"><a class="nav-link" href="../custom_casuals.html">Classic Moccasins</a></li>
									<li class="nav-item"><a class="nav-link" href="../custom_casuals.html">Casual Loafers</a></li>
									<li class="nav-item"><a class="nav-link" href="../custom_casuals.html">Casual Booties</a></li>
								</ul>
							</li>


							<li class="nav-item submenu dropdown">
								<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
								 aria-expanded="false">SNEAKERS</a>
								<ul class="dropdown-menu">
									<li class="nav-item"><a class="nav-link" href="../custom_sneakers.html">Classic Lace Up Sneakers</a></li>
									<li class="nav-item"><a class="nav-link" href="../custom_sneakers.html">Low-Top Sneakers</a></li>
									<li class="nav-item"><a class="nav-link" href="../custom_sneakers.html">Slip-on Sneakers</a></li>
									<li class="nav-item"><a class="nav-link" href="../custom_sneakers.html">Belgian Sneakers</a></li>
									<li class="nav-item"><a class="nav-link" href="../custom_sneakers.html">Monk Sneakers</a></li>
									<li class="nav-item"><a class="nav-link" href="../custom_sneakers.html">Chelsea Sneaker Boots</a></li>
									<li class="nav-item"><a class="nav-link" href="../custom_sneakers.html">High-top Sneakers</a></li>
								</ul>
							</li>

							<li class="nav-item submenu dropdown">
								<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
								 aria-expanded="false">SLIPPERS</a>
								<ul class="dropdown-menu">
									<li class="nav-item"><a class="nav-link" href="../custom_slippers.html">Albert Slippers</a></li>
									<li class="nav-item"><a class="nav-link" href="../custom_slippers.html">Belgian Slippers</a></li>
									<li class="nav-item"><a class="nav-link" href="../custom_slippers.html">Monk Slippers</a></li>
								</ul>
							</li>


							<li class="nav-item submenu dropdown">
								<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
								 aria-expanded="false">GOLF SHOES</a>
								<ul class="dropdown-menu">
									<li class="nav-item"><a class="nav-link" href="../custom_golf_shoes.html">Lace Up Golf Shoes</a></li>
									<li class="nav-item"><a class="nav-link" href="../custom_golf_shoes.html">Monk-Strap Golf Shoes</a></li>
									<li class="nav-item"><a class="nav-link" href="../custom_golf_shoes.html">Slip On Golf Shoes</a></li>
								</ul>
							</li>

							<li class="nav-item submenu dropdown">
								<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
								 aria-expanded="false">MY ACCOUNT</a>
								<ul class="dropdown-menu">
									<li class="nav-item"><a class="nav-link" href="../manager_login.php">Manager</a></li>
									<li class="nav-item"><a class="nav-link" href="../customer_login.php">Customer</a></li>
									<li class="nav-item"><a class="nav-link" href="../tracking.php">Tracking</a></li>
									
								</ul>
							</li>
							<li class="nav-item"><a class="nav-link" href="../contact.html">Contact</a></li>
						</ul>
						<ul class="nav navbar-nav navbar-right">
							<li class="nav-item"><a href="../new_cart.php" class="cart"><span class="ti-bag"></span></a></li>
							<li class="nav-item">
								<button class="search"><span class="lnr lnr-magnifier" id="search"></span></button>
							</li>
						</ul>
					</div>
				</div>
			</nav>
		</div>
		<div class="search_input" id="search_input_box">
			<div class="container">
				<form class="d-flex justify-content-between" action="../new_search.php" method="GET">
					<input type="text" class="form-control" id="search_input" name="query" placeholder="Search Here">
					<button type="submit" class="btn">NOW</button>
					</form>
			</div>
		</div>
	</header>
	<!-- End Header Area -->

<!-- start banner Area -->
<section class="banner-area">
	<div class="container">
	<div class="row features-inner">
		<div>

		<div>	
		</div>
	</div>
</section>
<!-- End banner Area -->

	<!-- start features Area -->
<section class="features-area section_gap">
	<div class="container">
		<div class="row features-inner">
			<!-- single features -->
			<div class="col-lg-3 col-md-6 col-sm-6">
				<div class="single-features">
					<div class="f-icon">
						<img src="../img/features/f-icon1.png" alt="">
					</div>
					<h6>Free Delivery</h6>
					<p>Free Shipping on all order</p>
				</div>
			</div>
			<!-- single features -->
			<div class="col-lg-3 col-md-6 col-sm-6">
				<div class="single-features">
					<div class="f-icon">
						<img src="../img/features/f-icon2.png" alt="">
					</div>
					<h6>Return Policy</h6>
					<p>Free Shipping on all order</p>
				</div>
			</div>
			<!-- single features -->
			<div class="col-lg-3 col-md-6 col-sm-6">
				<div class="single-features">
					<div class="f-icon">
						<img src="../img/features/f-icon3.png" alt="">
					</div>
					<h6>24/7 Support</h6>
					<p>Free Shipping on all order</p>
				</div>
			</div>
			<!-- single features -->
			<div class="col-lg-3 col-md-6 col-sm-6">
				<div class="single-features">
					<div class="f-icon">
						<img src="../img/features/f-icon4.png" alt="">
					</div>
					<h6>Secure Payment</h6>
					<p>Free Shipping on all order</p>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- end features Area -->


<!-- start side bar category Area -->
<br>
<div class="container">
		<div class="row">
			<div class="col-xl-3 col-lg-4 col-md-5">

                <div class="sidebar-categories">
					<div class="head">MANAGE ORDERS</div>
					<ul class="main-categories">
						
						<li class="main-nav-list"><a href="../manager/inventory_overview.php" aria-expanded="false" aria-controls="Slippers">
                            <span class="lnr lnr-arrow-right"></span>PRODUCTS OVERVIEW</a>
						</li>

                        <li class="main-nav-list"><a href="../new_order_tracking.php" aria-expanded="false" aria-controls="Shoes">
                            <span class="lnr lnr-arrow-right"></span>ORDER TRACKING</a>
						</li>

						<li class="main-nav-list"><a href="../new_order_history.php" aria-expanded="false" aria-controls="Boots">
                            <span class="lnr lnr-arrow-right"></span>SHOPPING HISTORY</a>
						</li>

					</ul>
				</div>

                <br><br>

				<div class="sidebar-categories">
					<div class="head">MANAGE CART</div>
					<ul class="main-categories">
						
						<li class="main-nav-list"><a href="../new_cart.php" aria-expanded="false" aria-controls="Slippers">
                            <span class="lnr lnr-arrow-right"></span>MY CART</a>
						</li>

                        <li class="main-nav-list"><a href="../sample_continue_shopping.php" aria-expanded="false" aria-controls="Sneakers">
                            <span class="lnr lnr-arrow-right"></span>CONTINUE SHOPPING</a>
						</li>

                        <li class="main-nav-list"><a href="../new_checkout.php" aria-expanded="false" aria-controls="Shoes">
                            <span class="lnr lnr-arrow-right"></span>CHECK OUT</a>
						</li>

					</ul>
				</div>

                <br><br>


                <div class="sidebar-categories">
					<div class="head">MANAGE ACCOUNTS</div>
					<ul class="main-categories">

						<li class="main-nav-list"><a href="update_user_profile.php" aria-expanded="false" aria-controls="Boots">
                            <span class="lnr lnr-arrow-right"></span>MY PROFILE</a>
						</li>

                        <li class="main-nav-list"><a href="update_user_profile.php" aria-expanded="false" aria-controls="Shoes">
                            <span class="lnr lnr-arrow-right"></span>EDIT PROFILE</a>
						</li>

                        <a href="logout.php" onclick="return confirmLogout();">LOGOUT</a>
                            <script>
                                function confirmLogout() {
                                 return confirm("Are you sure you want to logout?");
                                            }
                            </script>

					</ul>
				</div>

			</div>
			<div class="col-xl-9 col-lg-8 col-md-7">	
			
                
<!-- end side bar category Area -->    

<div class="dashboard-container">
    <h2>Welcome, <?php echo htmlspecialchars($user['full_name']); ?></h2>
    <p>Manage your account efficiently from here.</p>

    <div class="dashboard-sections">
        <div class="section">
            <h3>Low Stock Alerts</h3>
            <?php if (mysqli_num_rows($low_stock_result) > 0): ?>
                <ul>
                    <?php while ($product = mysqli_fetch_assoc($low_stock_result)): ?>
                        <li><?php echo htmlspecialchars($product['product_name']); ?> - <?php echo htmlspecialchars($product['quantity']); ?> left</li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>No low stock items.</p>
            <?php endif; ?>
        </div>


        <div class="section">
            <!-- <h3>Order Tracking</h3> -->

			<!-- <?php if (mysqli_num_rows($result) > 0): ?> -->
        <table>
        <tr>
            <th>Order ID</th>
            <th>Product Name</th>
            <th>Order Date</th>
            <th>Status</th>
        </tr>
        <!-- <?php while ($row = mysqli_fetch_assoc($result)): ?> -->
        <tr>
            <td><?php echo $row['order_id']; ?></td>
            <td><?php echo $row['product_name']; ?></td>
            <td><?php echo $row['order_date']; ?></td>
            <td><?php echo $row['order_status']; ?></td>
        </tr>
        <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>You have no orders to track.</p>
    <?php endif; ?>
        </div>
    </div>
</div>


	</div>
	</div>
	</div>


<div>
<!-- start footer Area -->

  <!-- Footer -->
  <footer class="footer2">
        <div class="container2">
            <p>&copy; 2024 My Online Store. All rights reserved.</p>
        </div>

		
    </footer>
	
</div>


<!-- End footer Area -->



	<script src="../js/vendor/jquery-2.2.4.min.js"></script>
	<script src="../https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
	 crossorigin="../anonymous"></script>
	<script src="../js/vendor/bootstrap.min.js"></script>
	<script src="../js/jquery.ajaxchimp.min.js"></script>
	<script src="../js/jquery.nice-select.min.js"></script>
	<script src="../js/jquery.sticky.js"></script>
	<script src="../js/nouislider.min.js"></script>
	<script src="../js/jquery.magnific-popup.min.js"></script>
	<script src="../js/owl.carousel.min.js"></script>
	<!--gmaps Js-->
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCjCGmQ0Uq4exrzdcL6rvxywDDOvfAu6eE"></script>
	<script src="../js/gmaps.min.js"></script>
	<script src="../js/main.js"></script>
	

    </script>

</body>
</html>
