<?php
include('../server.php');
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <div class="layout">
        <div class="left">
            <nav class="nav">
                <ul>
                    <li><a href="addHouse.php">Add House</a></li>
                    <li><a href="addTenant.php">Add Tenant</a></li>
                    <li><a href="houses.php">Houses</a></li>
                    <li><a href="tenants.php">Tenants</a></li>
                    <li><a href="rent.php">Rent</a></li>
                    <li><a href="../logout.php">Log out</a></li>
                </ul>
            </nav>
        </div>
        <div class="right">
            <div class="summary">
                <div id="revenue"></div>
                <div id="houses"></div>
                <div id="tenants"></div>
            </div>
        </div>
    </div>




    <script>
        function loadInfo() {

            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // console.log(this.responseText);

                    var data = JSON.parse(this.responseText);

                    var noRevenue = data.revenue[0].Revenue;
                    var noHouses = data.houses[0].Houses;
                    var noTenants = data.tenants[0].Tentants;

                    document.querySelector('#revenue').innerHTML = `KES ${noRevenue}`;
                    document.querySelector('#houses').innerHTML = `Houses ${noHouses}`;
                    document.querySelector('#tenants').innerHTML = `Tenants ${noTenants}`;
                    console.log(data);

                }
            };

            xhttp.open('GET', `admin.php?loadInfo`, true);
            xhttp.send();

        }

        loadInfo();
    </script>
</body>

</html>