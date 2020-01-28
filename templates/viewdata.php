<!DOCTYPE html>
<html> 
    <head> 
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Datatable</title>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.css"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <style> 
            * { 
                box-sizing: border-box; 
            } 
              
            /* CSS property for header section */ 
            .header { 
                background-color: teal; 
                padding: 15px; 
                text-align: center; 
            } 
              
            /* CSS property for nevigation menu */ 
            .nav_menu { 
                overflow: hidden; 
                background-color: #333; 
            } 
            .nav_menu a { 
                float: right; 
                display: block; 
                color: white; 
                text-align: center; 
                padding: 14px 16px; 
                text-decoration: none; 
            } 
            .nav_menu a:hover { 
                background-color: white; 
                color: teal; 
            } 
              
            /* CSS property for content section */ 
            .columnA { 
                float: left; 
                width: 20%;
                padding: 15px; 
                text-align:justify; 
				border: 1px solid black;
            }
			.columnB {
				float: left; 
                width: 60%; 
                padding: 15px; 
                text-align:justify;
				border: 1px solid black;				
			}
			.columnC {
				float: left; 
                width: 20%; 
                padding: 15px; 
                text-align:justify;
				border: 1px solid black;
			}
            h2 { 
                color:black; 
                text-align:center; 
            } 

            @import url('//cdn.datatables.net/1.10.2/css/jquery.dataTables.css');
            td.details-control {
            background: url('http://www.datatables.net/examples/resources/details_open.png') no-repeat center center;
            cursor: pointer;
            }
            tr.shown td.details-control {
            background: url('http://www.datatables.net/examples/resources/details_close.png') no-repeat center center;
            }
              
            /* Media query to set website layout  
            according to screen size */ 
            @media screen and (max-width:600px) { 
                .columnA, .columnB, .columnC { 
                    width: 50%; 
                } 
            } 
            @media screen and (max-width:400px) { 
                .columnA, .columnB, .columnC { 
                    width: 100%; 
                } 
            } 
        </style> 
    </head> 
      
    <body> 
          
        <!-- header of website layout -->
        <div class = "header"> 
            <h2 style = "color:white;font-size:200%"> 
                SMUDGES
            </h2> 
        </div> 
          
        <!-- nevigation menu of website layout -->
        <div class = "nav_menu"> 
            <a href = "#">Menu 1</a> 
            <a href = "#">Menu 2</a> 
            <a href = "#">Menu 3</a> 
        </div> 
        <div class="container mt-5">
        <?php
        require_once('config.php');
 
        $sql = "SELECT id, first_name, last_name, age FROM users";
        $result = $conn->query($sql);
        $arr_users = [];
        if ($result->num_rows > 0) {
            $arr_users = $result->fetch_all(MYSQLI_ASSOC);
        }
        ?>
            <table id="users" class="display nowrap" cellspaceing = "0" width = "100%">
                <thead>
                    <th></th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Age</th>
                </thead>
                <tbody>
                <?php if(!empty($arr_users)) { ?>
                    <?php foreach($arr_users as $user) { ?>
                        <tr data-child-value="hidden information">
                            <td class="details-control"></td>
                            <td><?php echo $user['first_name']; ?></td>
                            <td><?php echo $user['last_name']; ?></td>
                            <td><?php echo $user['age']; ?></td>
                        </tr>
                    <?php } ?>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.js"></script>
        <script>
            function format(value) {
                return '<div>Hidden Value: ' + value + '</div>';
            }
            // Add event listener for opening and closing details
            $(document).ready(function() {
                var table = $('#users').DataTable({});

                //listener for opening child row
                $('#users').on('click', 'td.details-control', function () {
                    var tr = $(this).closest('tr');
                    var row = table.row(tr);

                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    // Open this row
                    row.child(format(tr.data('child-value'))).show();
                    console.log("working here");
                    tr.addClass('shown');
                }
                });
  });
        </script>
        </div> 
    </body> 
</html>                     
