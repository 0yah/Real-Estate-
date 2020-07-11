var userID = null;
console.log('Loaded Actions');



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
        //console.log(location.pathname);
        //var url = location.pathname;
        
        body.appendChild(table);

    } else {
        var errorMessage = document.createElement('span');

        errorMessage.innerHTML = 'No Data Found';

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

    if (tenantEmail != '' && tenantsecondName != '' && tenantfirstName != '' && tenantphoneNumber != '' && tenantPassword != '') {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {

                console.log(this.responseText);
                var response = this.responseText;
                if (response == 'Registered') {

                    document.querySelector('#addUserError').innerHTML = "Email used";
                } else if (response == 'Success') {

                    document.querySelector('#firstnameAdd').value = "";
                    document.querySelector('#secondnameAdd').value = "";
                    document.querySelector('#phonenumberAdd').value = "";
                    document.querySelector('#emailAdd').value = "";
                    document.querySelector('#passwordAdd').value = "";
                    loadUsers();

                    document.querySelector('.modal').style.display = "none";
                    document.querySelector('.layout').style.filter = "none";
                } else if (response == 'Error') {
                    document.querySelector('#addUserError').innerHTML = "Something wrong happened check the fields and try again";
                }


                setListeners();



            }
        };

        xhttp.open("POST", "admin.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send(`addUser&firstname=${tenantfirstName}&secondname=${tenantsecondName}&phonenumber=${tenantphoneNumber}&email=${tenantEmail}&password=${tenantPassword}`);


    }else{
        document.querySelector('#addUserError').innerHTML = "Fill in all the fields";
    }



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

            console.log(this.responseText);
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
    setListeners();

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

    setListeners();


}

loadUsers();




setTimeout(function() {

    document.querySelector('#modal-close').addEventListener('click', function() {

        document.querySelector('.modal').style.display = "none";
        document.querySelector('.layout').style.filter = "none";
        loadUsers();

    });

    document.querySelectorAll('.controller').forEach((value, index) => {
        // console.log(value);
        value.addEventListener('click', function() {
            //   console.log(value.innerText);
            document.querySelector('.modal').style.display = "block";
            document.querySelector('.layout').style.filter = "blur(3px)";
            document.querySelector('#addUserView').style.display = "none";
            document.querySelector('#updateUserView').style.display = "block";
            document.querySelector('#addUserError').innerHTML = "";



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
            document.querySelector('#addUserView').style.display = "block";
            document.querySelector('#updateUserView').style.display = "none";

        });
    });

}, 3000);


function setListeners(){
    console.log('Should add listener');
}