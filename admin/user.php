<?php
include('../server.php');
include('./conn.php');
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>

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

        #updateUserView {
            display: none;
        }
    </style>
</head>

<body>
    <div class="layout">
        <button id="addUserBtn">Add User</button>
        <div class="loadUsers">

        </div>
    </div>
    <div class="modal">
        <div class="modal-header">
            <span id="modal-close">X</span>
        </div>
        <div class="modal-body">

            <div id="addUserView">
                <form>
                    <input id="firstnameAdd" placeholder="First Name" type="text" />
                    <input id="secondnameAdd" placeholder="Second Name" type="text" />
                    <input id="phonenumberAdd" placeholder="Phone Number" type="text" />
                    <input id="emailAdd" placeholder="E-mail" type="email" />
                    <input id="passwordAdd" placeholder="Password" type="password" />
                    <button type="button" id="addUser">Add</button>
                </form>
            </div>


            <div id="updateUserView">
                <form>
                    <input id="firstnameUpdate" placeholder="First Name" type="text" />
                    <input id="secondnameUpdate" placeholder="Second Name" type="text" />
                    <input id="phonenumberUpdate" placeholder="Phone Number" type="text" />
                    <input id="emailUpdate" placeholder="E-mail" type="email" />
                    <input id="passwordUpdate" placeholder="Password" type="password" />
                    <button type="button" id="updateUser">Update</button>
                    <button type="button" id="deleteUser">Delete</button>
                </form>
            </div>

        </div>
        <div class="modal-footer">

        </div>
    </div>


    <script>
        var userID = null;




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
                errorMessage.innerHTML = 'No User Found';

                body.appendChild(errorMessage);

            }


        }
        document.querySelector('#addUser').addEventListener('click', function() {
            var tenantfirstName = document.querySelector('#firstnameAdd').value;
            var tenantsecondName = document.querySelector('#secondnameAdd').value;
            var tenantphoneNumber = document.querySelector('#phonenumberAdd').value;
            var tenantEmail = document.querySelector('#emailAdd').value;
            var tenantPassword = document.querySelector('#passwordAdd').value;

            //console.log(selectedHouseID, tenantfirstName, tenantsecondName, tenantphoneNumber, tenantEmail, tenantPassword);
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {

                    document.querySelector('#firstnameAdd').value = "";
                    document.querySelector('#secondnameAdd').value = "";
                    document.querySelector('#phonenumberAdd').value = "";
                    document.querySelector('#emailAdd').value = "";
                    document.querySelector('#passwordAdd').value = "";
                    loadUsers();

                    document.querySelector('.modal').style.display = "none";
                    document.querySelector('.layout').style.filter = "none";

                }
            };

            xhttp.open("POST", "admin.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send(`addUser&firstname=${tenantfirstName}&secondname=${tenantsecondName}&phonenumber=${tenantphoneNumber}&email=${tenantEmail}&password=${tenantPassword}`);


        });



        document.querySelector('#updateUser').addEventListener('click', function() {
            var tenantfirstName = document.querySelector('#firstnameUpdate').value;
            var tenantsecondName = document.querySelector('#secondnameUpdate').value;
            var tenantphoneNumber = document.querySelector('#phonenumberUpdate').value;
            var tenantEmail = document.querySelector('#emailUpdate').value;
            var tenantPassword = document.querySelector('#passwordUpdate').value;

            // console.log(selectedHouseID, tenantfirstName, tenantsecondName, tenantphoneNumber, tenantEmail, tenantPassword);
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {

                    document.querySelector('#firstnameUpdate').value = "";
                    document.querySelector('#secondnameUpdate').value = "";
                    document.querySelector('#phonenumberUpdate').value = "";
                    document.querySelector('#emailUpdate').value = "";
                    document.querySelector('#passwordUpdate').value = "";
                    loadUsers();
                    document.querySelector('.modal').style.display = "none";
                    document.querySelector('.layout').style.filter = "none";

                }
            };

            xhttp.open("POST", "admin.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send(`updateUser&userID=${userID}&firstname=${tenantfirstName}&secondname=${tenantsecondName}&phonenumber=${tenantphoneNumber}&email=${tenantEmail}&password=${tenantPassword}`);


        });


        document.querySelector('#deleteUser').addEventListener('click', function() {

            //console.log(tenantfirstName, tenantsecondName, tenantphoneNumber, tenantEmail, tenantPassword);
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {

                    document.querySelector('#firstnameUpdate').value = "";
                    document.querySelector('#secondnameUpdate').value = "";
                    document.querySelector('#phonenumberUpdate').value = "";
                    document.querySelector('#emailUpdate').value = "";
                    document.querySelector('#passwordUpdate').value = "";

                    document.querySelector('.modal').style.display = "none";
                    document.querySelector('.layout').style.filter = "none";
                    loadUsers();

                }
            };

            xhttp.open("POST", "admin.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send(`deleteUser&userID=${userID}`);


        });


        function loadUsers() {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // console.log(this.responseText);

                    var data = JSON.parse(this.responseText);
                    generateTable(".loadUsers", data);

                }
            };

            xhttp.open("GET", `admin.php?loadUsers`, true);
            xhttp.send();

        }

        loadUsers();


        setTimeout(function() {

            document.querySelector('#modal-close').addEventListener('click', function() {

                document.querySelector('.modal').style.display = "none";
                document.querySelector('.layout').style.filter = "none";
            });

            document.querySelectorAll('.controller').forEach((value, index) => {
                // console.log(value);
                value.addEventListener('click', function() {
                    //   console.log(value.innerText);
                    document.querySelector('.modal').style.display = "block";
                    document.querySelector('.layout').style.filter = "blur(3px)";
                    document.querySelector('#addUserView').style.display = "none";
                    document.querySelector('#updateUserView').style.display = "block";


                    userID = value.innerText;

                });
            });


            document.querySelectorAll('#addUserBtn').forEach((value, index) => {
                // console.log(value);
                value.addEventListener('click', function() {
                    //   console.log(value.innerText);
                    document.querySelector('.modal').style.display = "block";
                    document.querySelector('.layout').style.filter = "blur(3px)";
                    //selectedHouseID = value.innerText;

                });
            });

        }, 3000);
    </script>

</body>

</html>