<?php
require "../config.php";
require "../common.php";

$connection = new PDO($dsn, $username, $password, $options);

// Check if the user clicked the "Read Courier" link or if a new courier has been added
$courierAdded = isset($_GET['courier_added']) ? $_GET['courier_added'] : false;

try {
    if ($courierAdded) {
        // Fetch and display the details of the newly added courier
        $newCourierId = $connection->lastInsertId();
        $sql = "SELECT courier_id, courier_name, phone_number, CreatedDate FROM Courier WHERE courier_id = :courier_id";
        $statement = $connection->prepare($sql);
        $statement->bindParam(":courier_id", $newCourierId, PDO::PARAM_INT);
        $statement->execute();
        $newCourier = $statement->fetch(PDO::FETCH_ASSOC);
    } else {
        // Fetch the list of all couriers
        $sql = "SELECT courier_id, courier_name, phone_number, CreatedDate FROM Courier";
        $statement = $connection->prepare($sql);
        $statement->execute();
        $couriers = $statement->fetchAll();
    }
} catch (PDOException $error) {
    echo 'Error: ' . $error->getMessage();
}
?>
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
<?php require "templates/header.php"; ?>

<h1 style="color: Purple; font-family: Garamond;">Add/Read Couriers</h1>

<!-- Create Courier Link -->
<h2 style="color: Navy; font-family: Gotham;">Create a Courier</h2>
<a href="addCourier.php">Create a Courier</a>

<!-- Read Courier Link -->
<h2 style="color: Navy; font-family: Gotham;">Read Couriers</h2>
<a href="readCourier.php">Read Couriers</a>

<!-- Display Couriers -->
<?php if ($courierAdded && isset($newCourier)) : ?>
    <h2 style="color: Navy; font-family: Gotham;">Newly Added Courier</h2>
    <table border="0">
        <tr>
            <th style="color: SeaGreen; font-family: Minion;">Courier Id</th>
            <th style="color: SeaGreen; font-family: Minion;">Courier Name</th>
            <th style="color: SeaGreen; font-family: Minion;">Phone Number</th>
            <th style="color: SeaGreen; font-family: Minion;">Created Date</th>
            <th style="color: SeaGreen; font-family: Minion;">Action</th>
        </tr>
        <tr>
            <td><?php echo $newCourier['courier_id']; ?></td>
            <td><?php echo $newCourier['courier_name']; ?></td>
            <td><?php echo $newCourier['phone_number']; ?></td>
            <td><?php echo $newCourier['CreatedDate']; ?></td>
            <td><a href="viewCourier.php?courier_id=<?php echo $newCourier['courier_id']; ?>">View Courier</a></td>
        </tr>
    </table>
<?php elseif (!$courierAdded && isset($couriers)) : ?>
    <h2 style="color: Navy; font-family: Gotham;">Couriers</h2>
    <table border="0">
        <tr>
            <th style="color: SeaGreen; font-family: Minion;">Courier Id</th>
            <th style="color: SeaGreen; font-family: Minion;">Courier Name</th>
            <th style="color: SeaGreen; font-family: Minion;">Phone Number</th>
            <th style="color: SeaGreen; font-family: Minion;">Created Date</th>
            <th style="color: SeaGreen; font-family: Minion;">Action</th>
        </tr>

        <?php foreach ($couriers as $courier) : ?>
            <tr>
                <td><?php echo $courier['courier_id']; ?></td>
                <td><?php echo $courier['courier_name']; ?></td>
                <td><?php echo $courier['phone_number']; ?></td>
                <td><?php echo $courier['CreatedDate']; ?></td>
                <td><a href="viewCourier.php?courier_id=<?php echo $courier['courier_id']; ?>">View Courier</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<a href="index.php"><br><br>Back to home</a>

<?php require "templates/footer.php"; ?>
