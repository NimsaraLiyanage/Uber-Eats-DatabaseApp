<?php
require "../config.php";
require "../common.php";

$result = []; // Initialize $result as an empty array

if (isset($_GET["search"])) {
    $search = '%' . $_GET["search"] . '%'; // Define and initialize $search
    $sql = "SELECT c.courier_name, c.phone_number, GROUP_CONCAT(o.restaurantName SEPARATOR ', ') AS selectedRestaurants
            FROM Courier AS c
            INNER JOIN OrderCourier AS oc ON c.courier_id = oc.courierId
            INNER JOIN userOrder AS o ON oc.orderId = o.orderId
            WHERE c.courier_name LIKE :search
            GROUP BY c.courier_id"; // Group by courier_id to concatenate selected restaurants
} else {
    // Define the default SQL query here when no search is performed.
    $sql = "SELECT courier_name, phone_number FROM Courier WHERE 1=0"; // Select nothing initially
}

try {
    $connection = new PDO($dsn, $username, $password, $options);

    $statement = $connection->prepare($sql);

    if (isset($_GET["search"])) {
        $statement->bindParam(":search", $search, PDO::PARAM_STR);
    }

    $statement->execute();

    $result = $statement->fetchAll();
} catch (PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}
?>

<?php require "templates/header.php"; ?>

<h2>View Couriers</h2>

<!-- Search form -->
<form method="GET">
    <label for="search">Search by Courier Name:</label>
    <input type="text" name="search" id="search" placeholder="Enter a courier name">
    <input type="submit" value="Search">
</form>

<table>
    <thead>
        <tr>
            <th>Courier Name</th>
            <th>Phone Number</th>
            <th>Restaurants</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($result as $row) : ?>
        <tr>
            <td><?php echo escape($row["courier_name"]); ?></td>
            <td><?php echo escape($row["phone_number"]); ?></td>
            <td>
                <?php
                if (isset($row["selectedRestaurants"])) {
                    echo escape($row["selectedRestaurants"]);
                } else {
                    echo "No restaurants selected";
                }
                ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<!-- Link to Create Courier Page -->
<a href="addCourier.php"><h4>Create a Courier</h4></a>

<!-- Back to Previous Page Link -->
<a href="addReadCouriers.php">Back to the previous page</a>

<!-- Include jQuery library -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- JavaScript to handle new couriers and search -->
<script>
$(document).ready(function() {
    // Function to refresh the courier table based on the search results
    function refreshCourierTable(searchTerm) {
        $.ajax({
            url: 'addCourier.php', // Modify the URL to point to your addCourier.php
            type: 'POST', // Use POST or GET based on your implementation
            dataType: 'json',
            success: function(newCourierData) {
                // Only show newly added couriers when a search is performed
                if (searchTerm !== "" && newCourierData) {
                    var newRow = '<tr>' +
                        '<td>' + newCourierData.courier_name + '</td>' +
                        '<td>' + newCourierData.phone_number + '</td>' +
                        '<td></td>' + // Add an empty column for Restaurants
                        '</tr>';
                    $('table tbody').append(newRow);
                }
            }
        });
    }

    // Handle search input changes
    $('#search').on('input', function() {
        var searchTerm = $(this).val();
        refreshCourierTable(searchTerm);
    });
});
</script>

<?php require "templates/footer.php"; ?>
