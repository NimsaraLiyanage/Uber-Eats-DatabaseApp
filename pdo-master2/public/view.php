<?php require "../config.php"; ?>
<?php require "../common.php"; ?>

<?php
if (isset($_GET["orderId"])) {
    try {
        $connection = new PDO($dsn, $username, $password, $options);

        $orderId = $_GET["orderId"];
        $sql = "SELECT * FROM userOrder WHERE orderId = :orderId";

        $statement = $connection->prepare($sql);
        $statement->bindValue(":orderId", $orderId);
        $statement->execute();

        $userOrder = $statement->fetch();
    } catch (PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
} else {
    echo "No order ID specified.";
}

// Check if the variable $userOrder is defined
if (!isset($userOrder)) {
    // Initialize $userOrder to an empty array
    $userOrder = [];
}

// Check if the variable $userOrder is empty
if (empty($userOrder)) {
    // Display a message to the user indicating that no order was found with that ID
    echo "No order found with that ID.";
} else {
    // Display the order details
    // ...
}
?>

<?php require "templates/header.php"; ?>

<h2>View User Order</h2>

<?php if ($userOrder) : ?>
    <p><strong>Order ID:</strong> <?php echo escape($userOrder["orderId"]); ?></p>
    <p><strong>Restaurant Name:</strong> <?php echo escape($userOrder["restaurantName"]); ?></p>
    <p><strong>Customer ID:</strong> <?php echo escape($userOrder["customerId"]); ?></p>
    <p><strong>Order Item:</strong> <?php echo escape($userOrder["orderItem"]); ?></p>
    <p><strong>Price:</strong> <?php echo escape($userOrder["price"]); ?></p>
    <p><strong>Location:</strong> <?php echo escape($userOrder["location"]); ?></p>
    <!-- Add any other details you want to display -->

    <!-- Edit Link -->
    <a href="editOrder.php?orderId=<?php echo escape($userOrder["orderId"]); ?>">Edit</a>
<?php else : ?>
    <p>No order found with that ID.</p>
<?php endif; ?>
<hr>
<a href="index.php">Back to home</a>

<?php require "templates/footer.php"; ?>
