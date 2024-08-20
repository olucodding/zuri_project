<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Product</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Basic styles for form layout and responsiveness */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
            margin: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        input[type="text"],
        input[type="file"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 8px;
            margin-top: 3px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .btn {
            display: inline-block;
            padding: 10px 15px;
            margin-top: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
        }
        .btn:hover {
            background-color: #218838;
        }
        .btn-cancel {
            background-color: #dc3545;
        }
        .btn-cancel:hover {
            background-color: #c82333;
        }
        .currency-symbol {
            display: inline-block;
            padding: 8px;
            background-color: #ddd;
            border: 1px solid #ccc;
            border-radius: 4px 0 0 4px;
            margin-right: -1px;
            box-sizing: border-box;
        }
        .input-group {
            display: flex;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Upload Product</h2>
        <form action="product_upload_handler.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="product_name">Product Name:</label>
                <input type="text" id="product_name" name="product_name" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <div class="input-group">
                    <span class="currency-symbol">$</span>
                    <input type="number" id="price" name="price" step="0.01" required>
                </div>
            </div>
            <div class="form-group">
                <label for="image">Product Image:</label>
                <input type="file" id="image" name="image" accept="image/*" required>
            </div>
            <button type="submit" class="btn">Upload Product</button>
            <a href="manage_products.php" class="btn btn-cancel">Cancel</a>
        </form>
    </div>
</body>
</html>
