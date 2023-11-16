<?php
require "../config.php";
require "../common.php";

$success = null;

if (isset($_POST["submit"])) {
    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'])) die();

    try {
        $connection = new PDO($dsn, $username, $password, $options);
        $id = $_POST["submit"];

        // Check for related records in ordercourier
        $checkSql = "SELECT * FROM ordercourier WHERE orderId = :orderId";
        $checkStatement = $connection->prepare($checkSql);
        $checkStatement->bindValue(':orderId', $id);
        $checkStatement->execute();
        $relatedRecords = $checkStatement->fetchAll();

        if (!empty($relatedRecords)) {
            // Delete related records in ordercourier first
            $deleteOrderCourierSql = "DELETE FROM ordercourier WHERE orderId = :orderId";
            $deleteOrderCourierStatement = $connection->prepare($deleteOrderCourierSql);
            $deleteOrderCourierStatement->bindValue(':orderId', $id);
            $deleteOrderCourierStatement->execute();
        }

        // Now you can safely delete the record from userOrder
        $deleteUserOrderSql = "DELETE FROM userOrder WHERE orderId = :orderId";
        $deleteUserOrderStatement = $connection->prepare($deleteUserOrderSql);
        $deleteUserOrderStatement->bindValue(':orderId', $id);
        $deleteUserOrderStatement->execute();

        $success = "Order successfully deleted";
    } catch (PDOException $error) {
        echo $error->getMessage();
    }
}

try {
    $connection = new PDO($dsn, $username, $password, $options);

    $sql = "SELECT * FROM userOrder";

    $statement = $connection->prepare($sql);
    $statement->execute();

    $result = $statement->fetchAll();
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
<h2>Delete Orders</h2>

<?php if ($success) echo $success; ?>

<form method="post">
    <input name="csrf" type="hidden" value="<?php echo escape($_SESSION['csrf']); ?>">
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
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($result as $row) : ?>
                <tr>
                    <td><?php echo escape($row["orderId"]); ?></td>
                    <td><?php echo escape($row["restaurantName"]); ?></td>
                    <td><?php echo escape($row["customerId"]); ?></td>
                    <td><?php echo escape($row["orderItem"]); ?></td>
                    <td><?php echo escape($row["price"]); ?></td>
                    <td><?php echo escape($row["location"]); ?></td>
                    <td><?php echo escape($row["date"]); ?> </td>
                    <td>
                        <form method="post" style="margin: 0;">
                            <input name="csrf" type="hidden" value="<?php echo escape($_SESSION['csrf']); ?>">
                            <button type="submit" name="submit" value="<?php echo escape($row["orderId"]); ?>">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</form>

<a href="index.php">Back to home</a>

<?php require "templates/footer.php"; ?>
