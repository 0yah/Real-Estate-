<?php
include('../server.php');
include('./conn.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Houses</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="../style.css">
    <style>
        body {
            margin: 0px;
            position: relative;
        }
    </style>
</head>

<body>



<div class="navbar">
        <?php


        if (isset($_SESSION['username'])) {


            echo "
    
    
    
  <a href='index.php'>Dashboard</a>
  
  <div class='dropdown'>
    <button class='dropbtn'>Houses 
    </button>
    <div class='dropdown-content'>
      <a href='houses.php'>View All</a>
      <a href='addHouse.php'>Add House</a>
    </div>
  </div> 

  <div class='dropdown'>
  <button class='dropbtn'>Tenants 
  </button>
  <div class='dropdown-content'>
    <a href='tenants.php'>View All</a>
    <a href='addTenant.php'>Add Tenant</a>
  </div>
</div> 
  <a href='rent.php'>Rent</a>

  <div class='dropdown'>
    <button class='dropbtn'>Users 
    </button>
    <div class='dropdown-content'>
      <a href='user.php'>View All</a>
      <a href='addUser.php'>Add User</a>
    </div>
  </div> 
  <a href='../logout.php'>Log Out</a>
    
    ";
        }


        ?>
    </div>

    <div class="layout">
        
        <div class="right">
            <form >
                <select id="filterHouseStatus">
                    <option selected disabled>Filter By Status</option>
                    <option value="1">Available</option>
                    <option value="5">Booked</option>
                    <option value="2">Occupied</option>
                    <option value="3">Maintainance</option>
                    <option value="4">Out of Service</option>
                </select>
                <input id="numberRooms" min="1" type="number" placeholder="Number of bedrooms" />
                <input id="numberArea" type="number" placeholder="Area Sqft" />
                <button class='btn'id="findHouseBtn" type="button">Find</button>
            </form>
            <div class="loadHouses">

            </div>
        </div>
    </div>



    <div class="layout">

        <a href="addHouse.php">Add House</a>

    </div>
    <div class="modal">
        <span class="modal-title">Update House</span>
        <div class="modal-header">
            <span id="modal-close">X</span>

        </div>
        <div class="modal-body">
            <div class="tenantInfo">
                <span id="tenantName"></span>
                <span id="tenantEmail"></span>
                <span id="tenantPhone"></span>
            </div>
            <form>
                <input id="updatehouseNumber" type="text" placeholder="House Number" />
                <input id="updatehouseBedrooms" type="number" min='1'placeholder="No of Bedrooms" />
                <input id="updatehouseRent" type="number" min='1' placeholder="Amount of Rent" />
                <input id="updatehouseArea" type="number" min='1' placeholder="Area(m²)" />
                <select id="updateHouseStatus">
                <option selected disabled>Change Status</option>
                    <option value="Available">Available</option>
                    <option value="Maintainance">Maintainance</option>
                    <option value="Out of Service">Out of Service</option>
                </select>
                <div id="updateHouseError"></div>
                <button type="button" id="updateHouseBtn">Update House</button>
                <button type="button" id="deleteHouseBtn">Delete</button>
            </form>


            <div class="rentHistory">

            </div>

        </div>
        <div class="modal-footer">

        </div>
    </div>


    <script>
        var selectHouseID = null;
        var selectedStatus = null;

        function generateTable(target, data) {
            var body = document.querySelector(target);
            body.innerHTML = "";
            var table = document.createElement("table");
            var tableHead = document.createElement("thead");
            var tableBody = document.createElement("tbody");
            tableBody.setAttribute("id", "myTable");



            if (data.length > 0) {
                var headers = Object.keys(data[0]);
                var noHeaders = Object.keys(data[0]).length;
                for (var i = 0; i < noHeaders; i++) {
                    var table_head_row = document.createElement('th');
                    table_head_row.innerText = headers[i]
                    tableHead.appendChild(table_head_row);
                    //console.log(headers[i])
                }

                for (var j = 0; j < data.length; j++) {
                    var table_row = document.createElement('tr');
                    table_row.setAttribute("class", "row");

                    var counter = 0;
                    for (const [key, value] of Object.entries(data[j])) {
                        //console.log(`${key}: ${value} : ${counter}`);



                        var table_data = document.createElement('td');
                        if (counter == 0) {
                            table_data.setAttribute('class', 'controller')
                        }
                        counter++;
                        table_data.innerText = value;
                        table_row.appendChild(table_data);
                    }
                    tableBody.appendChild(table_row);
                }

                table.appendChild(tableHead);
                table.appendChild(tableBody);
                // body.appendChild(table);

                body.appendChild(table);

            } else {
                var errorMessage = document.createElement('span');
                errorMessage.innerHTML = 'No House Found';

                body.appendChild(errorMessage);

            }







        }

        function loadHouses(status = 0) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // console.log(this.responseText);

                    var data = JSON.parse(this.responseText);
                    generateTable(".loadHouses", data);

                }
            };

            xhttp.open("GET", `admin.php?loadHouses&Status=${status}`, true);
            xhttp.send();
        }

        loadHouses();


        document.querySelector('#findHouseBtn').addEventListener('click', function() {
            var getNoRooms = document.querySelector('#numberRooms').value;
            var getNoArea = document.querySelector('#numberArea').value;
            console.log(getNoArea, getNoRooms);

            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    console.log(this.responseText);


                    var data = JSON.parse(this.responseText);
                    generateTable(".loadHouses", data);

                }
            };

            if (getNoArea == 0 || getNoArea == null) {
                getNoArea = 0;

            }

            xhttp.open("GET", `admin.php?findHouse&rooms=${getNoRooms}&area=${getNoArea}`, true);
            xhttp.send();

        });



        function houseInfo(houseID) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // console.log(this.responseText);

                    var data = JSON.parse(this.responseText);
                    console.log(data.Tenant);
                    if (data.Tenant.length > 0) {

                    } else {
                        console.log('No rent data available');
                        document.querySelector('.tenantInfo').innerHTML = "No Tenant Infomation Available";
                    }

                    document.querySelector('#updatecourtName').value = data.House[0].Court;
                    document.querySelector('#updatehouseNumber').value = data.House[0].HouseNo;
                    document.querySelector('#updatehouseBedrooms').value = data.House[0].BedRooms;
                    document.querySelector('#updatehouseRent').value = data.House[0].Rent;
                    document.querySelector('#updatehouseArea').value = data.House[0].Area;
                    //generateTable(".loadHouses", data);

                }
            };

            xhttp.open("GET", `admin.php?loadHouseInfo&HouseID=${houseID}`, true);
            xhttp.send();

        }


        setTimeout(function() {

            document.querySelector('#filterHouseStatus').addEventListener('change', function(event) {
                loadHouses(event.target.value);
                console.log(event.target.value);
            });

            document.querySelector('#modal-close').addEventListener('click', function() {

                document.querySelector('.modal').style.display = "none";
                document.querySelector('.layout').style.filter = "none";

            });


            document.querySelectorAll('.controller').forEach((value, index) => {
                // console.log(value);
                value.addEventListener('click', function() {
                    console.log(value.innerText);
                    var houseId = value.innerText;
                    
                    window.location.assign(`updateHouse.php?houseID=${houseId}`);
                    //houseInfo(selectHouseID);


                });
            });

        }, 3000);
    </script>

</body>

</html>