<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Page Title</title>

    <style>
        body {
            font-family: 'Uber Move', 'Helvetica Neue', sans-serif;
            background-image: url('5.jpg');
            background-size: cover;
            background-position: right top;
            margin: 0;
            padding: 0;
            color: Black;
            font-size: 20px; 
        }

        header {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 1em;
        }

        nav li {
            display: inline;
            margin-right: 10px;
        }

        nav a {
            text-decoration: none;
            color: white;
            font-weight: bold;
            padding: 10px 20px; 
            border-radius: 5px;
            background-color: #555;
            font-size: 18px; 
        }

        nav a:hover {
            background-color: blue;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            margin-bottom: 20px; 
        }
    </style>
</head>

<body>
    <?php include "templates/header.php"; ?>

    <nav>
        <ul>
            <li><a href="Home.php"><strong>Home</strong></a> - Home page</li><br><br><br>
            <li><a href="createOrder.php"><strong>Create Order</strong></a> - Add user order</li><br><br><br>
            <li><a href="addReadCouriers.php"><strong>Courier</strong></a> - Create/Read/last created Courier</li><br><br><br>
            <li><a href="read.php"><strong>Read</strong></a> - Find user order</li><br><br><br>
            <li><a href="update.php"><strong>Update</strong></a> - Edit user order</li><br><br><br>
            <li><a href="delete.php"><strong>Delete</strong></a> - Delete user order </li><br><br><br>
        </ul>
    </nav>

    <?php include "templates/footer.php"; ?>
</body>

</html>
