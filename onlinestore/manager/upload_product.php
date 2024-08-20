<?php
// Include the database connection file
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_names = $_POST['product_name'];
    $descriptions = $_POST['description'];
    $prices = $_POST['price'];
    $categories = $_POST['category'];
	$brand = $_POST['brand'];
    $suppliers = $_POST['supplier'];
    $quantities = $_POST['quantity'];
    $product_statuses = $_POST['product_status'];
    $product_images = $_FILES['product_image'];

    $upload_dir = 'uploads/products/';

    for ($i = 0; $i < count($product_names); $i++) {
        $product_name = mysqli_real_escape_string($conn, $product_names[$i]);
        $description = mysqli_real_escape_string($conn, $descriptions[$i]);
        $price = mysqli_real_escape_string($conn, $prices[$i]);
        $category = mysqli_real_escape_string($conn, $categories[$i]);
		$brand = mysqli_real_escape_string($conn, $brand[$i]);
        $supplier = mysqli_real_escape_string($conn, $suppliers[$i]);
        $quantity = mysqli_real_escape_string($conn, $quantities[$i]);
        $product_status = mysqli_real_escape_string($conn, $product_statuses[$i]);
        
        $image_name = $product_images['name'][$i];
        $image_tmp_name = $product_images['tmp_name'][$i];
        $image_path = $upload_dir . basename($image_name);

        if (move_uploaded_file($image_tmp_name, $image_path)) {
            $sql = "INSERT INTO products (product_name, description, price, category, brand, supplier, quantity, product_status, product_image) VALUES ('$product_name', '$description', '$price', '$category', '$brand', '$supplier', '$quantity', '$product_status', '$image_name')";
            if (!mysqli_query($conn, $sql)) {
                echo "Error: " . mysqli_error($conn);
            }
        } else {
            echo "Error uploading file.";
        }
    }

    // Redirect to manage_products.php after upload
    header('Location: manage_product.php');
    exit;
}
?>

<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Products</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="upload-container">
    <h2>Upload Multiple Products</h2>
    <form action="upload_product.php" method="POST" enctype="multipart/form-data">
        <div id="product-form">
            <div class="product-entry">
                <label for="product_name">Product Name</label>
                <input type="text" name="product_name[]" required>

                <label for="description">Description</label>
                <textarea name="description[]" required></textarea>

                <label for="price">Price (in USD)</label>
                <input type="number" name="price[]" step="0.01" required>

                <label for="category">Category</label>
                <input type="text" name="category[]" required>

                <label for="supplier">Supplier</label>
                <input type="text" name="supplier[]" required>

                <label for="quantity">Quantity</label>
                <input type="number" name="quantity[]" required>

                <label for="product_status">Product Status</label>
                <input type="text" name="product_status[]" required>

                <label for="product_image">Product Image</label>
                <input type="file" name="product_image[]" accept="image/*" required>
            </div>
        </div>
        <button type="button" id="add-product">Add Another Product</button>
        <button type="submit">Upload Products</button>
    </form>
</div>

<script>
    document.getElementById('add-product').addEventListener('click', function() {
        const productForm = document.getElementById('product-form');
        const productEntry = document.querySelector('.product-entry');
        const newProductEntry = productEntry.cloneNode(true);
        productForm.appendChild(newProductEntry);
    });
</script>
</body>
</html> -->


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Multiple Products</title>
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
					<a class="navbar-brand logo_h" href="index.html"><img src="../img/logo2.png" alt=""></a>
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


<!-- start category Area -->
	<div class="container">
		<div class="row">
			<div class="col-xl-3 col-lg-4 col-md-5">
				<div class="sidebar-categories">
					<div class="head">Product Categories</div>
					<ul class="main-categories">
						<li class="main-nav-list"><a data-toggle="collapse" href="#Shoes" aria-expanded="false" aria-controls="Shoes"><span
								 class="lnr lnr-arrow-right"></span>SHOES<span class="number">(53)</span></a>
							<ul class="collapse" id="Shoes" data-toggle="collapse" aria-expanded="false" aria-controls="Shoes">
								<li class="main-nav-list child"><a href="#">Brogue Shoes<span class="number">(13)</span></a></li>
								<li class="main-nav-list child"><a href="#">Derby Shoes<span class="number">(09)</span></a></li>
								<li class="main-nav-list child"><a href="#">Double Monk Shoes<span class="number">(17)</span></a></li>
								<li class="main-nav-list child"><a href="#">Loafers<span class="number">(01)</span></a></li>
								<li class="main-nav-list child"><a href="#">Longwing Blucher Shoes<span class="number">(13)</span></a></li>
								<li class="main-nav-list child"><a href="#">Oxford Shoes<span class="number">(09)</span></a></li>
								<li class="main-nav-list child"><a href="#">Saddle Shoes<span class="number">(17)</span></a></li>
								<li class="main-nav-list child"><a href="#">Single Monk Shoes<span class="number">(01)</span></a></li>
								<li class="main-nav-list child"><a href="#">Wholecut Shoes<span class="number">(11)</span></a></li>
							</ul>
						</li>

						<li class="main-nav-list"><a data-toggle="collapse" href="#Boots" aria-expanded="false" aria-controls="Boots"><span
								 class="lnr lnr-arrow-right"></span>BOOTS<span class="number">(53)</span></a>
							<ul class="collapse" id="Boots" data-toggle="collapse" aria-expanded="false" aria-controls="Boots">
								<li class="main-nav-list child"><a href="#">Balmoral Boots<span class="number">(13)</span></a></li>
								<li class="main-nav-list child"><a href="#">Brogue Boots<span class="number">(09)</span></a></li>
								<li class="main-nav-list child"><a href="#">Chelsea Boots<span class="number">(17)</span></a></li>
								<li class="main-nav-list child"><a href="#">Chukka Boots<span class="number">(01)</span></a></li>
								<li class="main-nav-list child"><a href="#">Derby Boots<span class="number">(13)</span></a></li>
								<li class="main-nav-list child"><a href="#">Double Monk Boots<span class="number">(09)</span></a></li>
								<li class="main-nav-list child"><a href="#">Jodhpur Boots<span class="number">(17)</span></a></li>
								<li class="main-nav-list child"><a href="#">Hiking Boots<span class="number">(01)</span></a></li>
								<li class="main-nav-list child"><a href="#">Moc Toe Boots<span class="number">(11)</span></a></li>
							</ul>
						</li>
						<li class="main-nav-list"><a data-toggle="collapse" href="#CasualShoes" aria-expanded="false" aria-controls="CasualShoes"><span
								 class="lnr lnr-arrow-right"></span>CASUAL SHOES<span class="number">(53)</span></a>
							<ul class="collapse" id="CasualShoes" data-toggle="collapse" aria-expanded="false" aria-controls="CasualShoes">
								<li class="main-nav-list child"><a href="#">Driving Shoes<span class="number">(13)</span></a></li>
								<li class="main-nav-list child"><a href="#">Classic Moccasins<span class="number">(09)</span></a></li>
								<li class="main-nav-list child"><a href="#">Casual Loafers<span class="number">(17)</span></a></li>
								<li class="main-nav-list child"><a href="#">Casual Booties<span class="number">(01)</span></a></li>
								
							</ul>
						</li>
						<li class="main-nav-list"><a data-toggle="collapse" href="#Sneakers" aria-expanded="false" aria-controls="Sneakers"><span
								 class="lnr lnr-arrow-right"></span>SNEAKERS<span class="number">(24)</span></a>
							<ul class="collapse" id="Sneakers" data-toggle="collapse" aria-expanded="false" aria-controls="Sneakers">
								<li class="main-nav-list child"><a href="#">Classic Lace Up Sneakers<span class="number">(13)</span></a></li>
								<li class="main-nav-list child"><a href="#">Low-Top Sneakers<span class="number">(09)</span></a></li>
								<li class="main-nav-list child"><a href="#">Slip-on Sneakers<span class="number">(17)</span></a></li>
								<li class="main-nav-list child"><a href="#">Belgian Sneakers<span class="number">(01)</span></a></li>
								<li class="main-nav-list child"><a href="#">Monk Sneakers<span class="number">(11)</span></a></li>
								<li class="main-nav-list child"><a href="#">Chelsea Sneaker Boots<span class="number">(09)</span></a></li>
								<li class="main-nav-list child"><a href="#">High-top Sneakers<span class="number">(17)</span></a></li>
								
							</ul>
						</li>
						<li class="main-nav-list"><a data-toggle="collapse" href="#Slippers" aria-expanded="false" aria-controls="Slippers"><span
								 class="lnr lnr-arrow-right"></span>SLIPPERS<span class="number">(53)</span></a>
							<ul class="collapse" id="Slippers" data-toggle="collapse" aria-expanded="false" aria-controls="Slippers">
								<li class="main-nav-list child"><a href="#">Albert Slippers<span class="number">(13)</span></a></li>
								<li class="main-nav-list child"><a href="#">Belgian Slippers<span class="number">(09)</span></a></li>
								<li class="main-nav-list child"><a href="#">Monk Slippers<span class="number">(17)</span></a></li>
								
							</ul>
						</li>

						<li class="main-nav-list"><a data-toggle="collapse" href="#GolfShoes" aria-expanded="false" aria-controls="GolfShoes"><span
								 class="lnr lnr-arrow-right"></span>GOLF SHOES<span class="number">(77)</span></a>
							<ul class="collapse" id="GolfShoes" data-toggle="collapse" aria-expanded="false" aria-controls="GolfShoes">
								<li class="main-nav-list child"><a href="#">Lace Up Golf Shoes<span class="number">(13)</span></a></li>
								<li class="main-nav-list child"><a href="#">Monk-Strap Golf Shoes<span class="number">(09)</span></a></li>
								<li class="main-nav-list child"><a href="#">Slip On Golf Shoes<span class="number">(17)</span></a></li>

							</ul>
						</li>
						<li class="main-nav-list"><a data-toggle="collapse" href="#Belts" aria-expanded="false" aria-controls="Belts"><span
								 class="lnr lnr-arrow-right"></span>BELTS<span class="number">(65)</span></a>
							<ul class="collapse" id="Belts" data-toggle="collapse" aria-expanded="false" aria-controls="Belts">
								<li class="main-nav-list child"><a href="#">Mainline Belts Collection<span class="number">(13)</span></a></li>
								<li class="main-nav-list child"><a href="#">Patina Leather Belts Collection<span class="number">(09)</span></a></li>

							</ul>
						</li>
						<li class="main-nav-list"><a data-toggle="collapse" href="#SpecialEdition" aria-expanded="false" aria-controls="SpecialEdition"><span
								 class="lnr lnr-arrow-right"></span>SPECIAL EDITIONS<span class="number">(29)</span></a>
							<ul class="collapse" id="SpecialEdition" data-toggle="collapse" aria-expanded="false" aria-controls="SpecialEdition">
								<li class="main-nav-list child"><a href="#">Special Edition Shoes<span class="number">(13)</span></a></li>
								<li class="main-nav-list child"><a href="#">Special Edition Boots<span class="number">(09)</span></a></li>
								<li class="main-nav-list child"><a href="#">Special Edition Sneakers<span class="number">(17)</span></a></li>
								<li class="main-nav-list child"><a href="#">Special Edition Belts<span class="number">(01)</span></a></li>
								
							</ul>
						</li>
						

					</ul>
				</div>
				
			</div>
			<div class="col-xl-9 col-lg-8 col-md-7">
				

        <!-- end category Area -->    



<div class="container mt-5">
    <h2 class="mb-4">Upload Multiple Products</h2>
    
    <form action="upload_product.php" method="post" enctype="multipart/form-data">
        <div id="product-fields">
            <div class="product-item mb-3">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="product_name[]">Product Name</label>
                        <input type="text" class="form-control" name="product_name[]" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="product_price[]">Price (in dollars)</label>
                        <input type="number" class="form-control" name="product_price[]" step="0.01" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="category[]">Category</label>
                        <input type="text" class="form-control" name="category[]" required>
                    </div>

					<!-- <div class="form-row"> -->
                    <div class="form-group col-md-6">
                        <label for="brand[]">brand</label>
                        <input type="text" class="form-control" name="brand[]" required>
                    
					</div>

                    <div class="form-group col-md-6">
                        <label for="product_image[]">Product Image</label>
                        <input type="file" class="form-control-file" name="product_image[]" accept="image/*" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="product_description[]">Product Description</label>
                    <textarea class="form-control" name="product_description[]" rows="3" required></textarea>
                </div>
                <button type="button" class="btn btn-danger remove-product">Remove</button>
                <hr>
            </div>
        </div>
        <button type="button" class="btn btn-primary" id="add-product">Add Another Product</button>
        <button type="submit" class="btn btn-success mt-3">Upload Products</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    $(document).ready(function() {
        // Add another product input set
        $('#add-product').click(function() {
            var productItem = $('.product-item:first').clone();
            productItem.find('input, textarea').val('');
            $('#product-fields').append(productItem);
        });

        // Remove product input set
        $(document).on('click', '.remove-product', function() {
            if ($('.product-item').length > 1) {
                $(this).closest('.product-item').remove();
            } else {
                alert('You must have at least one product.');
            }
        });
    });
</script>
</div>
</DIV>
</div>


<br><br><br>
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
