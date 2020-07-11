<?php
include('../server.php');
include('./conn.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenants</title>
    <link rel="stylesheet" href="styles.css">

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

        #updateTenantView,
        #rentRecordView {
            display: none;

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
            <div class="loadTenants">

            </div>
        </div>
    </div>


    <div class="modal">
        <div class="modal-header">
            <span id="modal-close">X</span>
        </div>
        <div class="modal-body">
            <div class=".houseInfo">
                <span id="HouseID"></span>
                <span id="HouseRent"></span>
                <span id="HouseStatus"></span>
            </div>

            <button id="editTenant">Edit</button>
            <button id="rentTenant">Rent Record</button>
            <div id="updateTenantView">
                <form>
                    <input id="updateFirstname" placeholder="First Name" type="text" />
                    <input id="updateSecondname" placeholder="Second Name" type="text" />
                    <input id="updatePhonenumber" placeholder="Phone Number" type="text" />
                    <input id="updateEmail" placeholder="E-mail" type="email" />
                    <div id="updateTenantError"></div>
                    <button type="button" id="updateTenant">Update Tenant</button>
                    <button type="button" id="deleteTenant">Delete</button>

                </form>
            </div>

            <div id="rentRecordView">
                <form action="">
                    <input type="date" name="" id="rentMonth">
                    <input type="number" name="" id="rentAmount">
                    <div id="rentRecordError"></div>
                    <button type="button" id="addRentRecord">Add</button>
                </form>
            </div>



            <div class="rentHistory">

            </div>

        </div>
        <div class="modal-footer">

        </div>
    </div>

    <script>
        var selectedTenantID = null;
        var selectedHouseID = null;


        document.querySelector('#addRentRecord').addEventListener('click', function() {

            var rentMonth = document.querySelector('#rentMonth').value;
            //document.querySelector('#rentMonth').value;

            var rentAmount = document.querySelector('#rentAmount').value;

            if(rentAmount != '' && rentMonth !=''){
                var nd = new Date(rentMonth);
            console.log(rentMonth, rentAmount, selectedTenantID, selectedHouseID);

            //document.querySelector('#rentAmount').value;

            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {

                    var data = this.responseText;
                    if(data == "Success"){
                        document.querySelector('#rentMonth').value = "";
                    document.querySelector('#rentAmount').value = "";
                    document.querySelector('#rentRecordError').innerHTML ="Successfully  recorded";
                    }else if(data=="Error"){
                        document.querySelector('#rentRecordError').innerHTML ="Error while  recording";
                    }


                }
            };

            xhttp.open("POST", "admin.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send(`addRent&tenantID=${selectedTenantID}&houseID=${selectedHouseID}&rentMonth=${rentMonth}&rentAmount=${rentAmount}`);

            }else{
                document.querySelector('#rentRecordError').innerHTML ="Fill in all the fields";
            }





        });

        document.querySelector('#editTenant').addEventListener('click', function() {
            document.querySelector('#updateTenantError').innerHTML = "";
            document.querySelector('#updateTenantView').style.display = 'block';
            document.querySelector('#rentRecordView').style.display = 'none';

        });

        document.querySelector('#rentTenant').addEventListener('click', function() {

            document.querySelector('#updateTenantView').style.display = 'none';
            document.querySelector('#rentRecordView').style.display = 'block';

        });


        document.querySelector('#deleteTenant').addEventListener('click', function() {
            document.querySelector('#updateTenantError').innerHTML = "";
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {


                    var data = this.responseText;
                    if (data == "Success") {
                        document.querySelector('#updateTenantError').innerHTML = "Successfully deleted!";
                        document.querySelector('#updateFirstname').value = "";
                        document.querySelector('#updateSecondname').value = "";
                        document.querySelector('#updatePhonenumber').value = "";
                        document.querySelector('#updateEmail').value = "";
                        document.querySelector('.modal').style.display = "none";
                        document.querySelector('.layout').style.filter = "none";
                    } else if (data == "Error") {
                        document.querySelector('#updateTenantError').innerHTML = "Could not delete!";
                    }
                }
            };

            xhttp.open("POST", "admin.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send(`deleteTenant&tenantID=${selectedTenantID}`);


        });
        document.querySelector('#updateTenant').addEventListener('click', function() {


            var tenantfirstName = document.querySelector('#updateFirstname').value;
            var tenantsecondName = document.querySelector('#updateSecondname').value;
            var tenantphoneNumber = document.querySelector('#updatePhonenumber').value;
            var tenantEmail = document.querySelector('#updateEmail').value;

            //console.log(selectedHouseID, tenantfirstName, tenantsecondName, tenantphoneNumber, tenantEmail, tenantPassword);

            if (tenantEmail != '' && tenantfirstName != '' && tenantsecondName != '' && tenantphoneNumber != '') {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {


                        var data = this.responseText;
                        if (data == "Success") {
                            document.querySelector('#updateTenantError').innerHTML = "Successfully Updated!";
                            document.querySelector('#updateFirstname').value = "";
                            document.querySelector('#updateSecondname').value = "";
                            document.querySelector('#updatePhonenumber').value = "";
                            document.querySelector('#updateEmail').value = "";
                        } else if (data == "Error") {
                            document.querySelector('#updateTenantError').innerHTML = "Could not update!";
                        }
                    }
                };

                xhttp.open("POST", "admin.php", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send(`updateTenant&tenantID=${selectedTenantID}&firstname=${tenantfirstName}&secondname=${tenantsecondName}&phonenumber=${tenantphoneNumber}&email=${tenantEmail}`);

            } else {

                document.querySelector('#updateTenantError').innerHTML = "Fill in all the fields!";
            }


        });



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
                if (selectedTenantID) {
                    errorMessage.innerHTML = 'No Rent Payments Found';
                } else {
                    errorMessage.innerHTML = 'No Tenants Found';
                }


                body.appendChild(errorMessage);

            }







        }

        function loadTenants(status = 0) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // console.log(this.responseText);

                    var data = JSON.parse(this.responseText);
                    generateTable(".loadTenants", data);

                }
            };

            xhttp.open("GET", `admin.php?loadTenants`, true);
            xhttp.send();
        }

        loadTenants();

        function loadTenantInfo(tenantID) {

            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // console.log(this.responseText);

                    var data = JSON.parse(this.responseText);
                    selectedHouseID = data.House[0].HouseID;
                    document.querySelector('#HouseID').innerHTML = data.House[0].HouseID;
                    document.querySelector('#HouseRent').innerHTML = data.House[0].Rent;
                    document.querySelector('#HouseStatus').innerHTML = data.House[0].HouseStatus;
                    console.log(data.Rent);
                    generateTable(".rentHistory", data.Rent);

                }
            };

            xhttp.open('GET', `admin.php?loadTenantInfo&TenantID=${tenantID}`, true);
            xhttp.send();




        }



        document.querySelector('#modal-close').addEventListener('click', function() {

            document.querySelector('.modal').style.display = "none";
            document.querySelector('.layout').style.filter = "none";
            document.querySelector('#updateTenantError').innerHTML = "";
        });

        setTimeout(function() {

            document.querySelectorAll('.controller').forEach((value, index) => {
                // console.log(value);
                value.addEventListener('click', function() {
                    console.log(value.innerText);
                    document.querySelector('.modal').style.display = "block";
                    selectedTenantID = value.innerText;
                    loadTenantInfo(selectedTenantID);

                });
            });

        }, 3000);
    </script>

</body>

</html>