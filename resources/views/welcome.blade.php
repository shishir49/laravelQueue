<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{csrf_token()}}">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->

        
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  

        <style>
            table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
            }

            td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
            }

            tr:nth-child(even) {
            background-color: #dddddd;
            }
        </style>
        
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h3>Customer Record Management</h3>
                </div>
                <div class="col-12 my-2">
                    <div class="card">
                        <div class="card-header">
                            <h6>Upload CSV File</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{url('upload-csv')}}" method="POST" enctype="multipart/form-data">
                               @csrf
                                <input type="file" name="uploadCsv" id="uploadCsv" class="form-controller">
                                <button class="btn btn-success" type="submit">Upload Records</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-12 my-2">
                    <div class="card">
                        <div class="card-header">
                        <h6>Customer Records</h6>
                        </div>
                        <div class="card-body">
                            <form id="search">
                                <div class="row">
                                    <div class="col-4">
                                        <select name="" class="form-control" id="branch_name">
                                            <option value="1">Branch Number 1</option>
                                            <option value="2">Branch Number 2</option>
                                        </select>
                                    </div>

                                    <div class="col-4">
                                        <select name="" class="form-control" id="gender">
                                            <option value="M">Male</option>
                                            <option value="F">Female</option>
                                        </select>
                                    </div>

                                    <div class="col-4">
                                        <button type="submit" class="btn btn-success">Search</button>
                                    </div>
                                </div>
                            </form>

                            <div class="col-12 my-2">
                                <table>
                                    <tr>
                                        <td>ID</td>
                                        <td>Branch ID</td>
                                        <td>First Name</td>
                                        <td>Last Name</td>
                                        <td>Email</td>
                                        <td>Phone</td>
                                        <td>Gender</td>
                                    </tr>
                                    <tr id="fetch-data">
                                        
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function(){

                // Store CSV Data

                $('#upload-csv').on('submit', function(e){
                    e.preventDefault();
                    var formData = new FormData(this)
                    console.log(formData)

                    $.ajax({
                        type : 'POST',
                        url : '/store-csv-data',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data : formData,
                        contentType: false,
                        processData: false,
                        success : function(data){
                            if(data.status == 200) {
                                alert('File Successfully Uploaded !')
                                getCustomer();
                            }else{
                                alert(data.status)
                            }
                        }
                    })
                })

                // Fetch Customer Data in table 
                $('#search').on('submit', function(e){
                    e.preventDefault();
                    
                    getCustomer();
                });

                function getCustomer(branch_name = '' , gender = '') {
                    $.ajax({
                        type : 'POST',
                        url : '/get-customer',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data : {branch_name : branch_name, gender : gender},
                        dataType : 'json',
                        success : function(data){

                        var getHtml = '';

                        if(data > 0 ) {
                            $.each(data, function(i, data){
                                getHtml +=`
                                    <td>${Number(i) + 1}</td>
                                    <td>${data.branch_id}</td>
                                    <td>${data.first_name}</td>
                                    <td>${data.last_name}</td>
                                    <td>${data.email}</td>
                                    <td>${data.phone}</td>
                                    <td>${data.gender}</td>
                                `;
                            })
                        }else{
                            getHtml +=`
                                <td colspan='7' class='text-center'>No Customer Found</td>
                            `;
                        }

                        $('#fetch-data').html(getHtml);
                        }
                    })
                }

                getCustomer();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })
            })
        </script>
    </body>
</html>
