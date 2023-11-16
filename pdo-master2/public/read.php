<?php

/**
 * Function to query information based on 
 * a parameter: in this case, orderId.
 *
 */

require "../config.php";
require "../common.php";

if (isset($_POST['submit'])) {
  if (!hash_equals($_SESSION['csrf'], $_POST['csrf'])) die();

  try  {
    $connection = new PDO($dsn, $username, $password, $options);

    $sql = "SELECT * 
            FROM userOrder
            WHERE orderId = :orderId";

    $orderId = $_POST['orderId'];
    $statement = $connection->prepare($sql);
    $statement->bindParam(':orderId', $orderId, PDO::PARAM_STR);
    $statement->execute();

    $result = $statement->fetchAll();
  } catch(PDOException $error) {
      echo $sql . "<br>" . $error->getMessage();
  }
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
<?php  
if (isset($_POST['submit'])) {
  if ($result && $statement->rowCount() > 0) { ?>
    <h2>Results</h2>
  
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
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
    <?php } else { ?>
      <blockquote>No results found for <?php echo escape($_POST['orderId']); ?>.</blockquote>
    <?php } 
} ?> 

<h2>Find user based on orderId</h2>

<form method="post">
  <input name="csrf" type="hidden" value="<?php echo escape($_SESSION['csrf']); ?>">
  <label for="orderId">orderId</label>
  <input type="text" id="orderId" name="orderId">
  <input type="submit" name="submit" value="View Results">
</form>

<a href="index.php">Back to home</a>

<?php require "templates/footer.php"; ?>