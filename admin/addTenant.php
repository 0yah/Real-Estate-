<?php include('../server.php') ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        body {
            margin: 0px;
            position: relative;
        }

        table {
            background-color: white;
        }

        .layout {
            position: relative;
            filter: blur(0px);
            height: 100vh;
        }

        .modal {
            position: absolute;
            top: 0;
            background-color: white;
            border-radius: 5px;
            padding: 5px;
            overflow-x: scroll;
            display: none;
            top: 25%;
            left: 10%
        }

        .modal::-webkit-scrollbar {
            display: none;
        }

        .modal-header {
            position: relative;

        }

        .modal-header span {
            position: absolute;
            right: 0;
            top: 0;
            font-size: 0.8em;
        }

        .modal-body {
            margin: 20px;

        }

        .modal-body table {}

        .modal-body table th {
            background-color: blue;
            color: white;
            padding: 5px;
        }

        .modal-body table th,
        .modal-body table td {
            border: 1px solid #ddd;
        }


        .modal-body table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .modal-body table tr:hover {
            background-color: #e2d8d8;
        }
    </style>
</head>

<body>


    <div class="layout">
        <div class="loadedHouses">

        </div>
        <form>
            <input id="numberRooms" type="number" placeholder="Number of bedrooms" />
            <input id="numberArea" type="number" placeholder="Area Sqft" />
            <button id="findHouseBtn" type="button">Find</button>
        </form>

        <div class="selectHouse">

        </div>
        <div class="houseDetails">
            <span id="houseCourt"></span>
            <span id="houseNumber"></span>
            <span id="houseBedrooms"></span>
            <span id="houseStatus"></span>
        </div>
        <form>
            <input id="firstname" type="text" />
            <input id="secondname" type="text" />
            <input id="phonenumber" type="text" />
            <input id="email" type="email" />
            <input id="password" type="password" />
            <button type="button" id="addNewTenant">Add Tenant</button>
        </form>

    </div>
    <div class="modal">
        <div class="modal-header">
            <span id="modal-close">X</span>
        </div>
        <div class="modal-body">
            
        
        </div>
        <div class="modal-footer">

        </div>
    </div>

</body>

<script>
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
                generateTable(".loadedHouses", data);

            }
        };

        xhttp.open("GET", `admin.php?loadHouses&Status=${status}`, true);
        xhttp.send();
    }

    document.querySelector('#findHouseBtn').addEventListener('click', function() {
        var getNoRooms = document.querySelector('#numberRooms').value;
        var getNoArea = document.querySelector('#numberArea').value;
        console.log(getNoArea, getNoRooms);

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                console.log(this.responseText);


                var data = JSON.parse(this.responseText);
                generateTable(".loadedHouses", data);

            }
        };

        if (getNoArea == 0 || getNoArea == null) {
            getNoArea = 0;

        }

        xhttp.open("GET", `admin.php?findHouse&rooms=${getNoRooms}&area=${getNoArea}`, true);
        xhttp.send();

    });

    loadHouses();



    document.querySelector('#modal-close').addEventListener('click', function() {

        document.querySelector('.modal').style.display = "none";
        document.querySelector('.layout').style.filter = "none";
    });

    setTimeout(function(){

        document.querySelectorAll('.controller').forEach((value, index) => {
        console.log(value);
        value.addEventListener('click', function() {
            console.log(value.innerHTML);
            document.querySelector('.modal').style.display = "block";
            document.querySelector('.layout').style.filter = "blur(3px)";


        });
    });

    },3000);


</script>

</html>