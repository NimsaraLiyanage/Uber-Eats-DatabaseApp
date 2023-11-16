<?php
require "../config.php";
require "../common.php";

// Check if an orderId is provided in the URL
if (isset($_GET['orderId'])) {
    $orderId = $_GET['orderId'];

    // Fetch the user order to edit
    try {
        $connection = new PDO($dsn, $username, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

        $sql = "SELECT * FROM userOrder WHERE orderId = :orderId";

        $statement = $connection->prepare($sql);
        $statement->bindValue(':orderId', $orderId);
        $statement->execute();

        $order = $statement->fetch(PDO::FETCH_ASSOC);

        // Check if the order exists
        if (!$order) {
            die("Order not found.");
        }
    } catch (PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
} else {
    die("Missing orderId parameter.");
}

// If the user has submitted the form to update the order
if (isset($_POST['submit'])) {
    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'])) die();

    try {
        $connection = new PDO($dsn, $username, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

        $updated_order = array(
            "orderId" => $_POST['orderId'],
            "restaurantName" => $_POST['restaurantName'],
            "customerId"  => $_POST['customerId'],
            "orderItem"     => $_POST['orderItem'],
            "price"       => $_POST['price'],
            "location"  => $_POST['location']
        );

        $sql = "UPDATE userOrder SET
            restaurantName = :restaurantName,
            customerId = :customerId,
            orderItem = :orderItem,
            price = :price,
            location = :location
            WHERE orderId = :orderId";

        $statement = $connection->prepare($sql);
        $statement->execute($updated_order);

           header("Location: Home.php");
           exit();
    } catch (PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
}
?>

<?php require "templates/header.php"; ?>

<h1>Edit User Order</h1>

<form method="post">
    <input name="csrf" type="hidden" value="<?php echo escape($_SESSION['csrf']); ?>">
    <label for="orderId">Order Id</label>
    <input type="text" name="orderId" id="orderId" value="<?php echo escape($order['orderId']); ?>" readonly>
    <label for="restaurantName">Restaurant Name</label>
    <input type="text" name="restaurantName" id="restaurantName" value="<?php echo escape($order['restaurantName']); ?>">
    <label for="customerId">Customer Id</label>
    <input type="text" name="customerId" id="customerId" value="<?php echo escape($order['customerId']); ?>">
    <label for="orderItem">Order Item</label>
    <input type="text" name="orderItem" id="orderItem" value="<?php echo escape($order['orderItem']); ?>">
    <label for="price">Price</label>
    <input type="text" name="price" id="price" value="<?php echo escape($order['price']); ?>">
    <label for="location">Location</label>
    <input type="text" name="location" id="location" value="<?php echo escape($order['location']); ?>">
    <input type="submit" name="submit" value="Update">
</form>

<a href="index.php">Back to home</a>

<?php require "templates/footer.php"; ?>
