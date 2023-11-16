<?php
try {
    require "../config.php";
    require "../common.php";

    $connection = new PDO($dsn, $username, $password, $options);

    $sql = "SELECT * FROM userOrder ORDER BY date DESC LIMIT 3";

    $result = $connection->query($sql);
} catch (PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}
?>
<?php require "templates/header.php"; ?>

<?php
if ($result->rowCount() > 0) { ?>
    <h2>Latest Orders</h2>
    <style>
    body {
        background-image: url('12.png'); 
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        font-family: 'Arial', sans-serif;
        margin: 0;
        padding: 0;
    }
    </style>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Restaurant Name</th>
                <th>Customer ID</th>
                <th>Order Item</th>
                <th>Price</th>
                <th>Location</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($result as $row) { ?>
                <tr>
                    <td><?php echo escape($row["orderId"]); ?></td>
                    <td><?php echo escape($row["restaurantName"]); ?></td>
                    <td><?php echo escape($row["customerId"]); ?></td>
                    <td><?php echo escape($row["orderItem"]); ?></td>
                    <td><?php echo escape($row["price"]); ?></td>
                    <td><?php echo escape($row["location"]); ?></td>
                    <td><?php echo escape($row["date"]); ?></td>
                    <td><a href='view.php?orderId=<?php echo escape($row["orderId"]); ?>'><strong>View</strong></a></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php } else { ?>
    <blockquote>No orders to show!!!</blockquote>
<?php } ?>

<a href="index.php">Back to home</a>

<?php require "templates/footer.php"; ?>