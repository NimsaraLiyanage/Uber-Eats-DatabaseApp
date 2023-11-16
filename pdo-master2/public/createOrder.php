<?php
require "../config.php";
require "../common.php";

$connection = new PDO($dsn, $username, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

$sql = "SELECT restaurant_name FROM Courier";
$statement = $connection->prepare($sql);
$statement->execute();

$restaurants = $statement->fetchAll();

$options = "";
foreach ($restaurants as $restaurant) {
    $options .= "<option value='" . $restaurant['restaurant_name'] . "'>" . $restaurant['restaurant_name'] . "</option>";
}

if (isset($_POST['submit'])) {
    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'])) die();

    try {
        $connection = new PDO($dsn, $username, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        $new_order = array(
            "orderId" => $_POST['orderId'],
            "restaurantName" => $_POST['restaurantName'],
            "customerId"  => $_POST['customerId'],
            "orderItem"     => $_POST['orderItem'],
            "price"       => $_POST['price'],
            "location"  => $_POST['location']
        );

        $sql = sprintf(
            "INSERT INTO %s (%s) values (%s)",
            "userOrder",
            implode(", ", array_keys($new_order)),
            ":" . implode(", :", array_keys($new_order))
        );

        $statement = $connection->prepare($sql);
        $statement->execute($new_order);
    } catch (PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Order</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            background-image: url('7.jpg');
            background-size: cover;
            background-position: center;
        }

        .login-container {
            max-width: 400px;
            margin: 50px auto;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .login-form {
            display: grid;
            gap: 15px;
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

        /* Style for the back-to-home link */
        .back-to-home {
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

        .back-to-home:hover {
            text-decoration: underline;
        }

        /* Style for the success message blockquote */
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

    <?php require "templates/header.php"; ?>

    <?php if (isset($_POST['submit']) && $statement) : ?>
        <blockquote><?php echo escape($_POST['restaurantName']); ?> User order is successfully added.</blockquote>
    <?php endif; ?>

    <div class="login-container">
        <h1 style="color: crimson; text-align: center; font-family: Gotham;">Add An User Order !!</h1>
        <form method="post" class="login-form">
            <input name="csrf" type="hidden" value="<?php echo escape($_SESSION['csrf']); ?>">
            <div class="form-group">
                <label for="orderId">Order Id</label>
                <input type="text" name="orderId" id="orderId" class="form-control">
            </div>
            <div class="form-group">
                <label for="restaurantName">Restaurant Name</label>
                <input type="text" name="restaurantName" id="restaurantName" class="form-control">
            </div>
            <div class="form-group">
                <label for="customerId">Customer Id</label>
                <input type="text" name="customerId" id="customerId" class="form-control">
            </div>
            <div class="form-group">
                <label for="orderItem">Order Item</label>
                <input type="text" name="orderItem" id="orderItem" class="form-control">
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="text" name="price" id="price" class="form-control">
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" name="location" id="location" class="form-control">
            </div>
            <div class="form-group">
                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>

    <a href="index.php" class="back-to-home">Back to home</a>

    <?php require "templates/footer.php"; ?>

</body>

</html>
