<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Form</title>
    <!-- Include Bootstrap CSS (you can adjust the version as needed) -->
    <link href="bootstrap/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Your existing styles */
        .form-container {
            margin: 20px auto;
            padding: 20px;
            border-radius: 12px;
            background-color: #ffffff;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            max-width: 500px;
        }
        .input-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .input-field {
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            border-radius: 8px;
            padding: 12px;
            width: 100%;
        }
        .quantity-field {
            font-size: 18px;
        }
        .btn-logout {
            background-color: #dc3545;
            border: none;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 12px;
            transition: background-color 0.3s;
            margin-left: 10px;
            cursor: pointer;
            color: #fff;
        }
        .btn-logout:hover {
            background-color: #c82333;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            width: 100%;
            font-size: 16px;
            color: #fff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        label {
            font-weight: bold;
        }
        .input-container > div {
            flex: 1;
            margin-right: 10px;
        }
        .input-container > div:last-child {
            margin-right: 0;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2 class="text-center">Smart Count</h2>

        <form action="bulk_store.php" method="POST">
            <!-- First row: Name, Date, and Logout button -->
            <div class="input-container">
                <div>
                    <label for="name" class="form-label">Name</label>
                    <input type="text" id="name" name="name" class="input-field" value="<?php echo htmlspecialchars($name); ?>" readonly>
                </div>
                <div>
                    <label for="date" class="form-label">Date</label>
                    <input type="date" id="date" name="date" class="input-field" value="<?php echo $date; ?>" readonly>
                </div>
                <button type="button" class="btn btn-logout" onclick="confirmLogout()">Logout</button>
            </div>

            <!-- Second row: ID and Line -->
            <div class="input-container">
                <div>
                    <label for="id" class="form-label">ID</label>
                    <input type="text" id="id" name="id" class="input-field" value="<?php echo htmlspecialchars($id); ?>" readonly>
                </div>
                <div>
                    <label for="line" class="form-label">Line</label>
                    <input type="text" id="line" name="line" class="input-field" value="<?php echo htmlspecialchars($line); ?>" readonly>
                </div>
            </div>

            <!-- Quantity input and Submit button -->
            <div>
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" id="quantity" name="quantity" class="input-field quantity-field" placeholder="Enter quantity" required>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <!-- Logout confirmation script -->
    <script>
        function confirmLogout() {
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = 'logout.php';
            }
        }
    </script>

    <!-- Include Bootstrap JS (optional) -->
    <script src="js/jquery-3.5.1.slim.min.js"></script>
</body>
</html>