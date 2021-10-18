<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>NILDS</title>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12"><br>
                    <div class="row">
                        <div class="col-md-6"><br>
                            <h2 style="font-family:serif;">NILDS ATTENDANCE RECORDS</h2>
                        </div>
                        <div class="col-md-1 offset-md-4">
                            <img src={{ asset('logo.png') }} width="170"/>
                        </div>
                    </div>
                    <h3 id="dateholder">

                    </h3>
                    <div id="searchbox" class="row" style="display: none">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Date</label>
                            <input type="date" class="form-control" id="date">
                          </div>
                          <button type="submit" id="clickCheck" onclick="loadDoc()" class="btn btn-primary btn-sm">Submit</button>
                          <a   target="_blank" href="http://127.0.0.1:8090/attendance" type="submit" class="btn btn-primary btn-sm m-5">Sync Attendance</a>
                    </div>
                    <br><br>
                    <table class="table table-striped">
                        <thead>
                          <tr>
                            <th scope="col">#</th>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Date</th>
                            <th scope="col">Clock In</th>
                            <th scope="col">Clock Out</th>
                            <th scope="col">Status</th>
                          </tr>
                        </thead>
                        <tbody id="demo">
                        </tbody>
                      </table>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script>
            const loadDoc = async() => {
                const date = document.querySelector("#date").value
                const request = await fetch('http://127.0.0.1:8090/api/search', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        date
                    })
                });
                const response = await request.json()
                var i = 1;
                response.records.forEach(element => {
                    const tr = document.createElement("tr")

                    const html = `
                    <td>${i++}</td>
                    <td>${element.employee_id}</td>
                    <td>${element.employee.name}</td>
                    <td>${element.auth_date}</td>
                    <td>${element.clock_in}</td>
                    <td>${element.clock_out}</td>
                    <td><span class="text-success">PRESENT</span></td>`

                    tr.innerHTML += html

                    document.querySelector("#demo").appendChild(tr)
                });
            }
        </script>
        <script>
            $(function() {

                var now = new Date();
                var day = ("0" + now.getDate()).slice(-2);
                var month = ("0" + (now.getMonth() + 1)).slice(-2);

                const wkdys = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

                const mnths = ["January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"
                ];

                var wkdy = wkdys[now.getDay()];
                var mnth = mnths[now.getMonth()];

                var tdy = wkdy+", "+(day)+" "+(mnth)+" "+now.getFullYear() ;
                var tdydte = now.getFullYear()+"-"+(month)+"-"+(day) ;

                $("#date").val(tdy);

                $("#dateholder").text(function(){
                    return ($("#date").val() == null ? $("#date").val() : tdy);
                });


                document.querySelector("#date").addEventListener("change", function() {
                    const wday = wkdys[new Date($("#date").val()).getDay()]
                    const date_num = new Date($("#date").val()).getDate()
                    const mnth_day = mnths[new Date($("#date").val()).getMonth()];
                    const mnth_year = new Date($("#date").val()).getFullYear();
                    var tdy_n = wday+", "+(date_num)+" "+(mnth_day)+" "+mnth_year;
                    $("#dateholder").text(tdy_n)
                })
                $("#clickCheck").click(function() {
                    $("#searchbox").hide()
                });
                $("#dateholder").click(function(){
                    $("#searchbox").toggle();
                });
            });

        </script>
        <style>
            #dateholder{
                cursor: pointer;
                font-family: serif;
                margin-top: 0px;
            }
        </style>
    </body>
</html>
