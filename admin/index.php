<?php
include('../server.php');
include('./conn.php');
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>

    <nav>
        <ul>
            <li><a href="addHouse.php">Add House</a></li>
            <li><a href="addTenant.php">Add Tenant</a></li>
            <li><a href="houses.php">Houses</a></li>
            <li><a href="tenants.php">Tenants</a></li>
            <li><a href="rent.php">Rent</a></li>
            <li><a href="../logout.php">Log out</a></li>
        </ul>
    </nav>

    <div>
        Show summary of the system

        no of tenants
        no of houses
        revenue
    </div>
</body>
</html>