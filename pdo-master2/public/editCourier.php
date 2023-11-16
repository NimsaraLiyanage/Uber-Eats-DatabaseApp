<?php
require "../config.php";
require "../common.php";

// Check if a courier_id is provided in the URL
if (isset($_GET['courier_id'])) {
    $courierId = $_GET['courier_id'];

    // Fetch the courier details to edit
    try {
        $connection = new PDO($dsn, $username, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

        $sql = "SELECT * FROM Courier WHERE courier_id = :courierId";

        $statement = $connection->prepare($sql);
        $statement->bindValue(':courierId', $courierId);
        $statement->execute();

        $courier = $statement->fetch(PDO::FETCH_ASSOC);

        // Check if the courier exists
        if (!$courier) {
            die("Courier not found.");
        }
    } catch (PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
} else {
    die("Missing courier_id parameter.");
}

// If the user has submitted the form to update the courier details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $newCourierName = $_POST["courier_name"];
        $newPhoneNumber = $_POST["phone_number"];
        // Add other fields as needed

        // Update the courier details
        $updateSql = "UPDATE Courier SET
            courier_name = :courier_name,
            phone_number = :phone_number
            WHERE courier_id = :courier_id";

        $updateStatement = $connection->prepare($updateSql);
        $updateStatement->bindValue(":courier_name", $newCourierName);
        $updateStatement->bindValue(":phone_number", $newPhoneNumber);
        $updateStatement->bindValue(":courier_id", $courierId);

        if ($updateStatement->execute()) {
            // Redirect to addReadCourier.php after updating
            header("Location: addReadCouriers.php"); // Change the URL to your desired destination
            exit();
        } else {
            echo "Error updating courier details.";
        }
    } catch (PDOException $error) {
        echo $updateSql . "<br>" . $error->getMessage();
    }
}
?>

<?php require "templates/header.php"; ?>

<h2>Edit Courier</h2>

<form method="post">
    <label for="courier_id">Courier ID:</label>
    <input type="text" name="courier_id" id="courier_id" value="<?php echo escape($courier['courier_id']); ?>" readonly>
    <label for="courier_name">Courier Name:</label>
    <input type="text" name="courier_name" id="courier_name" value="<?php echo escape($courier['courier_name']); ?>">
    <label for="phone_number">Phone Number:</label>
    <input type="text" name="phone_number" id="phone_number" value="<?php echo escape($courier['phone_number']); ?>">

    <input type="submit" name="update" value="Update">
</form>

<p></p>
<a href="addReadCouriers.php?courier_id=<?php echo escape($courier['courier_id']); ?>">Back to view</a>

<?php require "templates/footer.php"; ?>
