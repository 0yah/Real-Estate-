<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rent</title>
</head>
<body>
<div class="layout">
        <div id="revenue"></div>
        <div class="loadRent">

        </div>
    </div>


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
                if(selectedTenantID){
                    errorMessage.innerHTML = 'No Rent Payments Found';
                }else{
                    errorMessage.innerHTML = 'No Rent Found';
                }


                body.appendChild(errorMessage);

            }







        }

function loadRent(tenantID) {

var xhttp = new XMLHttpRequest();
xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        // console.log(this.responseText);

        var data = JSON.parse(this.responseText);
        console.log(data);
        var rent = data.records;
        var revenue = data.revenue[0].Revenue;
        document.querySelector('#revenue').innerHTML = `KES ${revenue}`;
        generateTable(".loadRent", rent);

    }
};

xhttp.open('GET', `admin.php?loadRent`, true);
xhttp.send();




}

loadRent();
</script>


</body>
</html>