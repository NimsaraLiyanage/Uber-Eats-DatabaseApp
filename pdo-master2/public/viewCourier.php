<?php
require "../config.php";
require "../common.php";

if (isset($_GET["courier_id"])) {
    try {
        $connection = new PDO($dsn, $username, $password, $options);

        $courierId = $_GET["courier_id"];
        $sql = "SELECT c.*, GROUP_CONCAT(o.restaurantName) AS selectedRestaurants
                FROM Courier AS c
                INNER JOIN OrderCourier AS oc ON c.courier_id = oc.courierId
                INNER JOIN userOrder AS o ON oc.orderId = o.orderId
                WHERE c.courier_id = :courierId
                GROUP BY c.courier_id";

        $statement = $connection->prepare($sql);
        $statement->bindValue(":courierId", $courierId);
        $statement->execute();

        $courier = $statement->fetch();
    } catch (PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
} else {
    echo "No courier ID specified.";
}

// Handle delete request
if (isset($_POST["delete"])) {
    try {
        $deleteCourierId = $_POST["delete"];

        // Delete associated records in the ordercourier table first
        $deleteOrderCourierSql = "DELETE FROM OrderCourier WHERE courierId = :deleteCourierId";
        $deleteOrderCourierStatement = $connection->prepare($deleteOrderCourierSql);
        $deleteOrderCourierStatement->bindValue(":deleteCourierId", $deleteCourierId);
        $deleteOrderCourierStatement->execute();

        // Now, delete the courier
        $deleteCourierSql = "DELETE FROM Courier WHERE courier_id = :deleteCourierId";
        $deleteCourierStatement = $connection->prepare($deleteCourierSql);
        $deleteCourierStatement->bindValue(":deleteCourierId", $deleteCourierId);
        $deleteCourierStatement->execute();

        // Redirect back to the previous page (addReadCourier.php) after deletion
     header("Location: addReadCouriers.php");
     exit();
    } catch (PDOException $error) {
        echo $deleteCourierSql . "<br>" . $error->getMessage();
    }
}
?>

<?php require "templates/header.php"; ?>

<h2>View Courier</h2>

<?php if ($courier) : ?>
    <p><strong>Courier ID:</strong> <?php echo escape($courier["courier_id"]); ?></p>
    <p><strong>Courier Name:</strong> <?php echo escape($courier["courier_name"]); ?></p>
    <p><strong>Phone Number:</strong> <?php echo escape($courier["phone_number"]); ?></p>
    <p><strong>Selected Restaurants:</strong> <?php echo escape($courier["selectedRestaurants"]); ?></p>
    <!-- Add any other details you want to display -->

    <!-- Edit Link -->
    <a href="editCourier.php?courier_id=<?php echo escape($courier["courier_id"]); ?>">Edit</a>
   <p></p>
    <!-- Delete Form -->
    <form method="post" onsubmit="return confirm('Are you sure you want to delete this courier?');">
        <input type="hidden" name="delete" value="<?php echo escape($courier["courier_id"]); ?>">
        <input type="submit" value="Delete">
    </form>
<?php else : ?>
    <p>No courier found with that ID.</p>
<?php endif; ?>
<p></p>
<a href="addReadCourier.php">Back to previous page</a>

<?php require "templates/footer.php"; ?>
