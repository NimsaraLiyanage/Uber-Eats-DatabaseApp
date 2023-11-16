<?php
require "../config.php";
require "../common.php";

// Fetch all user orders
try {
    $connection = new PDO($dsn, $username, $password, $options);

    $sql = "SELECT * FROM userOrder";

    $statement = $connection->prepare($sql);
    $statement->execute();

    $orders = $statement->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}

?>

<?php require "templates/header.php"; ?>
<style>
    body {
        background-image: url('11.jpg'); 
        background-size: cover;
        background-position: top;
        background-repeat: no-repeat;
        font-family: 'Arial', sans-serif;
        margin: 0;
        padding: 0;
    }
    </style>
<h2>Edit userOrders</h2>

<table>
    <thead>
        <tr>
            <th>Order Id</th>
            <th>Restaurant Name</th>
            <th>Customer Id</th>
            <th>Order Item</th>
            <th>Price</th>
            <th>Location</th>
            <th>Date</th>
            <th>Edit</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orders as $order) : ?>
            <tr>
                <td><?php echo escape($order["orderId"]); ?></td>
                <td><?php echo escape($order["restaurantName"]); ?></td>
                <td><?php echo escape($order["customerId"]); ?></td>
                <td><?php echo escape($order["orderItem"]); ?></td>
                <td><?php echo escape($order["price"]); ?></td>
                <td><?php echo escape($order["location"]); ?></td>
                <td><?php echo escape($order["date"]); ?></td>
                <td><a href="editOrder.php?orderId=<?php echo escape($order["orderId"]); ?>">Edit</a></td>

            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<a href="index.php">Back to Previous page</a>

<?php require "templates/footer.php"; ?>
