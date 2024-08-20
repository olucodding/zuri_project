<?php
session_start();

// Include the database connection file
include 'includes/db.php';

// Fetch all available products from the database
$query = "SELECT * FROM products WHERE product_status = 'active'";
$result = mysqli_query($conn, $query);

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="zxx" class="no-js">
<head>
  
    <!-- <title></title>
    <link rel="stylesheet" href="styles.css"> -->

<!-- Mobile Specific Meta -->
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Favicon-->
	<link rel="shortcut icon" href="img/fav.png">
	<!-- Author Meta -->
	<meta name="author" content="CodePixar">
	<!-- Meta Description -->
	<meta name="description" content="">
	<!-- Meta Keyword -->
	<meta name="keywords" content="">
	<!-- meta character set -->
	<meta charset="UTF-8">
	<!-- Site Title -->
	<title>Continue Shopping</title>

    <!--
		CSS
		============================================= -->
	<link rel="stylesheet" href="css/linearicons.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/themify-icons.css">
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/owl.carousel.css">
	<link rel="stylesheet" href="css/nice-select.css">
	<link rel="stylesheet" href="css/nouislider.min.css">
	<link rel="stylesheet" href="css/ion.rangeSlider.css" />
	<link rel="stylesheet" href="css/ion.rangeSlider.skinFlat.css" />
	<link rel="stylesheet" href="css/magnific-popup.css">
	<link rel="stylesheet" href="css/main.css">

</head>
<body>

<!-- Start Header Area -->
<header class="header_area sticky-header">
		<div class="main_menu">
			<nav class="navbar navbar-expand-lg navbar-light main_box">
				<div class="container">
					<!-- Brand and toggle get grouped for better mobile display -->
					<a class="navbar-brand logo_h" href="index.html"><img src="img/logo2.png" alt=""></a>
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
					 aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse offset" id="navbarSupportedContent">
						<ul class="nav navbar-nav menu_nav ml-auto">
							<li class="nav-item active"><a class="nav-link" href="index.html">Home</a>
							
							</li>

							<li class="nav-item submenu dropdown">
								<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
								 aria-expanded="false">SHOES</a>
								<ul class="dropdown-menu">
									<li class="nav-item"><a class="nav-link" href="custom_shoes.html">Brogue Shoes</a></li>
									<li class="nav-item"><a class="nav-link" href="custom_shoes.html">Derby Shoes</a></li>
									<li class="nav-item"><a class="nav-link" href="custom_shoes.html">Double Monk Shoes</a></li>
									<li class="nav-item"><a class="nav-link" href="custom_shoes.html">Loafers</a></li>
									<li class="nav-item"><a class="nav-link" href="custom_shoes.html">Longwing Blucher Shoes</a></li>
									<li class="nav-item"><a class="nav-link" href="custom_shoes.html">Oxford Shoes</a></li>
									<li class="nav-item"><a class="nav-link" href="custom_shoes.html">Saddle Shoes</a></li>
									<li class="nav-item"><a class="nav-link" href="custom_shoes.html">Single Monk Shoes</a></li>
									<li class="nav-item"><a class="nav-link" href="custom_shoes.html">Wholecut Shoes</a></li>
								</ul>
							</li>

							<li class="nav-item submenu dropdown">
								<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
								 aria-expanded="false">BOOTS</a>
								<ul class="dropdown-menu">
									<li class="nav-item"><a class="nav-link" href="custom_boots.html">Balmoral Boots</a></li>
									<li class="nav-item"><a class="nav-link" href="custom_boots.html">Brogue Boots</a></li>
									<li class="nav-item"><a class="nav-link" href="custom_boots.html">Chelsea Boots</a></li>
									<li class="nav-item"><a class="nav-link" href="custom_boots.html">Chukka Boots</a></li>
									<li class="nav-item"><a class="nav-link" href="custom_boots.html">Derby Boots</a></li>
									<li class="nav-item"><a class="nav-link" href="custom_boots.html">Double Monk Boots</a></li>
									<li class="nav-item"><a class="nav-link" href="custom_boots.html">Jodhpur Boots</a></li>
									<li class="nav-item"><a class="nav-link" href="custom_boots.html">Hiking Boots</a></li>
									<li class="nav-item"><a class="nav-link" href="custom_boots.html">Moc Toe Boots</a></li>
								</ul>
							</li>


							<li class="nav-item submenu dropdown">
								<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
								 aria-expanded="false">CASUAL</a>
								<ul class="dropdown-menu">
									<li class="nav-item"><a class="nav-link" href="custom_casuals.html">Driving Shoes</a></li>
									<li class="nav-item"><a class="nav-link" href="custom_casuals.html">Classic Moccasins</a></li>
									<li class="nav-item"><a class="nav-link" href="custom_casuals.html">Casual Loafers</a></li>
									<li class="nav-item"><a class="nav-link" href="custom_casuals.html">Casual Booties</a></li>
								</ul>
							</li>


							<li class="nav-item submenu dropdown">
								<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
								 aria-expanded="false">SNEAKERS</a>
								<ul class="dropdown-menu">
									<li class="nav-item"><a class="nav-link" href="custom_sneakers.html">Classic Lace Up Sneakers</a></li>
									<li class="nav-item"><a class="nav-link" href="custom_sneakers.html">Low-Top Sneakers</a></li>
									<li class="nav-item"><a class="nav-link" href="custom_sneakers.html">Slip-on Sneakers</a></li>
									<li class="nav-item"><a class="nav-link" href="custom_sneakers.html">Belgian Sneakers</a></li>
									<li class="nav-item"><a class="nav-link" href="custom_sneakers.html">Monk Sneakers</a></li>
									<li class="nav-item"><a class="nav-link" href="custom_sneakers.html">Chelsea Sneaker Boots</a></li>
									<li class="nav-item"><a class="nav-link" href="custom_sneakers.html">High-top Sneakers</a></li>
								</ul>
							</li>

							<li class="nav-item submenu dropdown">
								<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
								 aria-expanded="false">SLIPPERS</a>
								<ul class="dropdown-menu">
									<li class="nav-item"><a class="nav-link" href="custom_slippers.html">Albert Slippers</a></li>
									<li class="nav-item"><a class="nav-link" href="custom_slippers.html">Belgian Slippers</a></li>
									<li class="nav-item"><a class="nav-link" href="custom_slippers.html">Monk Slippers</a></li>
								</ul>
							</li>


							<li class="nav-item submenu dropdown">
								<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
								 aria-expanded="false">GOLF SHOES</a>
								<ul class="dropdown-menu">
									<li class="nav-item"><a class="nav-link" href="custom_golf_shoes.html">Lace Up Golf Shoes</a></li>
									<li class="nav-item"><a class="nav-link" href="custom_golf_shoes.html">Monk-Strap Golf Shoes</a></li>
									<li class="nav-item"><a class="nav-link" href="custom_golf_shoes.html">Slip On Golf Shoes</a></li>
								</ul>
							</li>

							<li class="nav-item submenu dropdown">
								<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
								 aria-expanded="false">MY ACCOUNT</a>
								<ul class="dropdown-menu">
									<li class="nav-item"><a class="nav-link" href="manager_login.php">Manager</a></li>
									<li class="nav-item"><a class="nav-link" href="customer_login.php">Customer</a></li>
									<li class="nav-item"><a class="nav-link" href="tracking.php">Tracking</a></li>
									
								</ul>
							</li>
							<li class="nav-item"><a class="nav-link" href="contact.html">Contact</a></li>
						</ul>
						<ul class="nav navbar-nav navbar-right">
							<li class="nav-item"><a href="cart.php" class="cart"><span class="ti-bag"></span></a></li>
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
				<form class="d-flex justify-content-between" action="search.php" method="GET">
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
							<img src="img/features/f-icon1.png" alt="">
						</div>
						<h6>Free Delivery</h6>
						<p>Free Shipping on all order</p>
					</div>
				</div>
				<!-- single features -->
				<div class="col-lg-3 col-md-6 col-sm-6">
					<div class="single-features">
						<div class="f-icon">
							<img src="img/features/f-icon2.png" alt="">
						</div>
						<h6>Return Policy</h6>
						<p>Free Shipping on all order</p>
					</div>
				</div>
				<!-- single features -->
				<div class="col-lg-3 col-md-6 col-sm-6">
					<div class="single-features">
						<div class="f-icon">
							<img src="img/features/f-icon3.png" alt="">
						</div>
						<h6>24/7 Support</h6>
						<p>Free Shipping on all order</p>
					</div>
				</div>
				<!-- single features -->
				<div class="col-lg-3 col-md-6 col-sm-6">
					<div class="single-features">
						<div class="f-icon">
							<img src="img/features/f-icon4.png" alt="">
						</div>
						<h6>Secure Payment</h6>
						<p>Free Shipping on all order</p>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- end features Area -->









<div class="container">
    <h2>Available Products</h2>
    <form action="add_to_cart.php" method="POST">
        <table>
            <thead>
                <tr>
                    <th>Select</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><input type="checkbox" name="selected_products[]" value="<?php echo $row['product_id']; ?>"></td>
                    <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['category']); ?></td>
                    <td><?php echo htmlspecialchars($row['price']); ?></td>
                    <td><input type="number" name="quantity_<?php echo $row['product_id']; ?>" min="1" value="1"></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <button type="submit" name="add_to_cart">Add to Cart</button>
    </form>
</div>









<!-- Start exclusive deal Area -->
<section class="exclusive-deal-area">
		<div class="container-fluid">
			
		</div>
	</section>
	<!-- End exclusive deal Area -->

	<!-- Start brand Area -->
	<section class="brand-area section_gap">
		<div class="container">
			<div class="row">
				<a class="col single-img" href="#">
					<img class="img-fluid d-block mx-auto" src="img/brand/1.png" alt="">
				</a>
				<a class="col single-img" href="#">
					<img class="img-fluid d-block mx-auto" src="img/brand/2.png" alt="">
				</a>
				<a class="col single-img" href="#">
					<img class="img-fluid d-block mx-auto" src="img/brand/3.png" alt="">
				</a>
				<a class="col single-img" href="#">
					<img class="img-fluid d-block mx-auto" src="img/brand/4.png" alt="">
				</a>
				<a class="col single-img" href="#">
					<img class="img-fluid d-block mx-auto" src="img/brand/5.png" alt="">
				</a>
			</div>
		</div>
	</section>
	<!-- End brand Area -->

	

	<!-- start footer Area -->
	<footer class="footer-area section_gap">
		<div class="container">
			<div class="row">
				<div class="col-lg-3  col-md-6 col-sm-6">
					<div class="single-footer-widget">
						<h6>About Us</h6>
						<p>
							We’re very serious about our shoes and passionate custom shoe aficionados as well. We also love the freedom of having the perfect matching belt for our custom footwear – our special design tool solves this problem.
						</p>
					</div>
				</div>
				<div class="col-lg-4  col-md-6 col-sm-6">
					<div class="single-footer-widget">
						<h6>Newsletter</h6>
						<p>Stay up to date with our latest</p>
						<div class="" id="mc_embed_signup">

							<form target="_blank" novalidate="true" action="https://spondonit.us12.list-manage.com/subscribe/post?u=1462626880ade1ac87bd9c93a&amp;id=92a4423d01"
							 method="get" class="form-inline">

								<div class="d-flex flex-row">

									<input class="form-control" name="EMAIL" placeholder="Enter Email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter Email '"
									 required="" type="email">


									<button class="click-btn btn btn-default"><i class="fa fa-long-arrow-right" aria-hidden="true"></i></button>
									<div style="position: absolute; left: -5000px;">
										<input name="b_36c4fd991d266f23781ded980_aefe40901a" tabindex="-1" value="" type="text">
									</div>

									<!-- <div class="col-lg-4 col-md-4">
												<button class="bb-btn btn"><span class="lnr lnr-arrow-right"></span></button>
											</div>  -->
								</div>
								<div class="info"></div>
							</form>
						</div>
					</div>
				</div>
				<div class="col-lg-3  col-md-6 col-sm-6">
					<div class="single-footer-widget mail-chimp">
						<h6 class="mb-20">Instragram Feed</h6>
						<ul class="instafeed d-flex flex-wrap">
							<li><a href="#"><img src="img/i1.jpg" alt=""></a></li>
							<li><a href="#"><img src="img/i2.jpg" alt=""></a></li>
							<li><a href="#"><img src="img/i3.jpg" alt=""></a></li>
							<li><a href="#"><img src="img/i4.jpg" alt=""></a></li>
							<li><a href="#"><img src="img/i5.jpg" alt=""></a></li>
							<li><a href="#"><img src="img/i6.jpg" alt=""></a></li>
							<li><a href="#"><img src="img/i7.jpg" alt=""></a></li>
							<li><a href="#"><img src="img/i8.jpg" alt=""></a></li>
						</ul>
					</div>
				</div>
				<div class="col-lg-2 col-md-6 col-sm-6">
					<div class="single-footer-widget">
						<h6>Follow Us</h6>
						<p>Let us be social</p>
						<div class="footer-social d-flex align-items-center">
							<a href="#"><i class="fa fa-facebook"></i></a>
							<a href="#"><i class="fa fa-twitter"></i></a>
							<a href="#"><i class="fa fa-dribbble"></i></a>
							<a href="#"><i class="fa fa-behance"></i></a>
						</div>
					</div>
				</div>
			</div>
			<div class="footer-bottom d-flex justify-content-center align-items-center flex-wrap">
				<p class="footer-text m-0"><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This website is developed by <a href="#" target="_blank">Olustud</a>
<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
</p>
			</div>
		</div>
	</footer>
	<!-- End footer Area -->

	<script src="js/vendor/jquery-2.2.4.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
	 crossorigin="anonymous"></script>
	<script src="js/vendor/bootstrap.min.js"></script>
	<script src="js/jquery.ajaxchimp.min.js"></script>
	<script src="js/jquery.nice-select.min.js"></script>
	<script src="js/jquery.sticky.js"></script>
	<script src="js/nouislider.min.js"></script>
	<script src="js/countdown.js"></script>
	<script src="js/jquery.magnific-popup.min.js"></script>
	<script src="js/owl.carousel.min.js"></script>
	<!--gmaps Js-->
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCjCGmQ0Uq4exrzdcL6rvxywDDOvfAu6eE"></script>
	<script src="js/gmaps.min.js"></script>
	<script src="js/main.js"></script>




</body>





</html>
