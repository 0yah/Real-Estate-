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
    <style>
        body {
            margin: 0px;
            position: relative;
        }


    </style>
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
        <form>
            <select id="filterHouseStatus">
                    <option value="1">Available</option>
                    <option value="5">Booked</option>
                    <option value="2">Occupied</option>
                    <option value="3">Maintainance</option>
                    <option value="4">Out of Service</option>
            </select>
            <input id="numberRooms" min="1" type="number" placeholder="Number of bedrooms" />
            <input id="numberArea" type="number" placeholder="Area Sqft" />
            <button id="findHouseBtn" type="button">Find</button>
        </form>
        <div class="loadHouses">

        </div>
        </div>
    </div>



    <div class="layout">

    <a href="addHouse.php">Add House</a>
        
    </div>
    <div class="modal">
    <span class="modal-title">Add House</span>
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
                <input id="updatehouseBedrooms" type="number" placeholder="No of Bedrooms" />
                <input id="updatehouseRent" type="number" placeholder="Amount of Rent" />
                <input id="updatehouseArea" type="number" placeholder="Area(m²)" />
                <select id="updateHouseStatus">
                    <option value="Available">Available</option>
                    <option value="Maintainance">Maintainance</option>
                    <option value="Out of Service">Out of Service</option>
                </select>
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


        document.querySelector('#updateHouseBtn').addEventListener('click', function() {

            var courtName = document.querySelector('#updatecourtName').value;
            var houseNumber = document.querySelector('#updatehouseNumber').value;
            var houseBedroom = document.querySelector('#updatehouseBedrooms').value;
            var houseRent = document.querySelector('#updatehouseRent').value;
            var houseArea = document.querySelector('#updatehouseArea').value;

            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    loadHouses();
                    document.querySelector('#updatecourtName').value = "";
                    document.querySelector('#updatehouseNumber').value = "";
                    document.querySelector('#updatehouseBedrooms').value = "";
                    document.querySelector('#updatehouseRent').value = "";
                    document.querySelector('#updatehouseArea').value = "";


                    document.querySelector('.modal').style.display = "none";
                    document.querySelector('.layout').style.filter = "none";
                }
            };

            xhttp.open("POST", "admin.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send(`updateHouse&houseID=${selectHouseID}&courtName=${courtName}&houseNumber=${houseNumber}&houseBedroom=${houseBedroom}&houseArea=${houseArea}&houseRent=${houseRent}&houseStatus=${selectedStatus}`);
            console.log(`updateHouse&houseID=${selectHouseID}&courtName=${courtName}&houseNumber=${houseNumber}&houseBedroom=${houseBedroom}&houseArea=${houseArea}&houseRent=${houseRent}&houseStatus=${selectedStatus}`);

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

document.querySelector('#filterHouseStatus').addEventListener('change',function(event){
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
                    document.querySelector('.modal').style.display = "block";
                    selectHouseID = value.innerText;
                    houseInfo(selectHouseID);


                });
            });

            document.querySelector('#updateHouseStatus').addEventListener('change', function(event) {

                selectedStatus = event.target.value;
                console.log(selectedStatus);
            });

            document.querySelector('#updateHouseBtn').addEventListener('click', function() {

                var houseBedroom = document.querySelector('#updatehouseBedrooms').value;
                var houseRent = document.querySelector('#updatehouseRent').value;
                var houseArea = document.querySelector('#updatehouseArea').value;

                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        loadHouses();
                        document.querySelector('#updatehouseBedrooms').value = "";
                        document.querySelector('#updatehouseRent').value = "";
                        document.querySelector('#updatehouseArea').value = "";


                        document.querySelector('.modal').style.display = "none";
                        document.querySelector('.layout').style.filter = "none";
                    }
                };

                xhttp.open("POST", "admin.php", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send(`updateHouse&houseID=${selectHouseID}&houseBedroom=${houseBedroom}&houseArea=${houseArea}&houseRent=${houseRent}&houseStatus=${selectedStatus}`);
                console.log(`updateHouse&houseID=${selectHouseID}&houseBedroom=${houseBedroom}&houseArea=${houseArea}&houseRent=${houseRent}&houseStatus=${selectedStatus}`);

            });



            document.querySelector('#deleteHouseBtn').addEventListener('click', function() {


                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        loadHouses();
                        document.querySelector('#updatecourtName').value = "";
                        document.querySelector('#updatehouseNumber').value = "";
                        document.querySelector('#updatehouseBedrooms').value = "";
                        document.querySelector('#updatehouseRent').value = "";
                        document.querySelector('#updatehouseArea').value = "";


                        document.querySelector('.modal').style.display = "none";
                        document.querySelector('.layout').style.filter = "none";
                    }
                };

                xhttp.open("POST", "admin.php", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send(`deleteHouse&houseID=${selectHouseID}`);
                console.log(`deleteHouse&houseID=${selectHouseID}`);

            });

        }, 3000);
    </script>

</body>

</html>