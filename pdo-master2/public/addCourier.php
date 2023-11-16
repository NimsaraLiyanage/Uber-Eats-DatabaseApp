<?php
require "../config.php";
require "../common.php";

// Initialize variables
$courierName = $phoneNumber = "";
$selectedRestaurants = [];

// Fetch restaurant data for the multi-select dropdown
try {
    $connection = new PDO($dsn, $username, $password, $options);
    $sql = "SELECT restaurantName FROM userOrder";
    $statement = $connection->prepare($sql);
    $statement->execute();
    $restaurants = $statement->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch data from form
    $courierName = $_POST["courierName"];
    $phoneNumber = $_POST["phoneNumber"];
    $selectedRestaurants = $_POST["restaurants"];

    try {
        $connection = new PDO($dsn, $username, $password, $options);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Insert courier information into Courier table
        $insertCourierSql = "INSERT INTO Courier (courier_name, phone_number) VALUES (:courierName, :phoneNumber)";
        $insertCourierStatement = $connection->prepare($insertCourierSql);
        $insertCourierStatement->bindParam(':courierName', $courierName);
        $insertCourierStatement->bindParam(':phoneNumber', $phoneNumber);

        // Insert the courier first
        $insertCourierStatement->execute();
        $courierId = $connection->lastInsertId();

        // Create an array to hold all the selected restaurants
        $selectedRestaurantIds = [];

        // Iterate through selected restaurants and insert into OrderCourier table
        foreach ($selectedRestaurants as $selectedRestaurant) {
            $restaurantName = $selectedRestaurant;

            // Insert into OrderCourier table
            $insertOrderCourierSql = "INSERT INTO OrderCourier (orderId, courierId)
                                      SELECT userOrder.orderId, :courierId
                                      FROM userOrder
                                      WHERE userOrder.restaurantName = :restaurantName";
            $insertOrderCourierStatement = $connection->prepare($insertOrderCourierSql);
            $insertOrderCourierStatement->bindParam(':restaurantName', $restaurantName);
            $insertOrderCourierStatement->bindParam(':courierId', $courierId);

            // Execute the SQL statement
            $insertOrderCourierStatement->execute();

            // Store the selected restaurant IDs in the array
            $selectedRestaurantIds[] = $restaurantName;
        }

        // Clear form data after successful submission
        $courierName = $phoneNumber = "";
        $selectedRestaurants = [];

        echo "<blockquote>Courier successfully added.</blockquote>";
    } catch (PDOException $error) {
        echo $insertCourierSql . "<br>" . $error->getMessage();
    }
}
?>

<?php require "templates/header.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Courier</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            background-image: url('9.jpg');
            background-size: cover;
            background-position: left;
        }

        .form-container {
            max-width: 400px;
            margin: 50px auto;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-control {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 3px;
            outline: none;
        }

        select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 3px;
            outline: none;
            height: auto;
        }

        .btn {
            background-color: #007bff;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .back-to-previous {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
            background-color: #fff;
            padding: 8px;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .back-to-previous:hover {
            text-decoration: underline;
        }

        blockquote {
            background-color: #fff;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="form-container">
        <h1 style="color: Purple; text-align: center; font-family: Garamond;">Add Courier</h1>

        <!-- Create Courier Form -->
        <form method="post" class="login-form">
            <div class="form-group">
                <label for="courierName">Courier Name</label>
                <input type="text" name="courierName" id="courierName" class="form-control" required
                    value="<?php echo htmlspecialchars($courierName); ?>">
            </div>

            <div class="form-group">
                <label for="phoneNumber">Phone Number</label>
                <input type="text" name="phoneNumber" id="phoneNumber" class="form-control" required
                    value="<?php echo htmlspecialchars($phoneNumber); ?>">
           
                    </div>

<div class="form-group">
    <label for="restaurants">Select Restaurants</label>
    <select multiple name="restaurants[]" id="restaurants" class="form-control">
        <?php
        foreach ($restaurants as $restaurant) {
            echo "<option value='$restaurant' " . (in_array($restaurant, $selectedRestaurants) ? "selected" : "") . ">$restaurant</option>";
        }
        ?>
    </select>
</div>

<div class="form-group">
    <button type="submit" name="submit" class="btn btn-primary">Create Courier</button>
</div>
</form>

<!-- Back to Previous Page Link -->
<a href="addReadCouriers.php" class="back-to-previous">Back to previous page</a>

<?php if (isset($_POST['submit']) && $insertCourierStatement) : ?>
<blockquote>Courier successfully added.</blockquote>
<?php endif; ?>
</div>

</body>

</html>
