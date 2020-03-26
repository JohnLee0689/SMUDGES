<?php
session_start();
?>
<html>
    <head>
        <title> 
            SMUDGES: Systematically Mapping Ultra-Diffuse Galaxies
        </title>
        
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"/>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        
        <style>
            .header { 
                background-image: url('UDG.jpg');
                background-color: teal; 
                padding: 10px; 
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
            
            @import url('//cdn.datatables.net/1.10.2/css/jquery.dataTables.css');
            td.details-control {
            background: url('http://www.datatables.net/examples/resources/details_open.png') no-repeat center center;
            cursor: pointer;
            }
            tr.shown td.details-control {
            background: url('http://www.datatables.net/examples/resources/details_close.png') no-repeat center center;
            }  
        </style>
    </head>
    <body>
        <div class = "header"> 
            <h2 style = "color:white;font-size:200%">SMUDGES: View Data</h2> 
        </div> 
        <div class = "nav_menu"> 
            <a href = "login">Login/Logout</a>
            <a href = "uploadData">Upload Data</a>
            <a href = "adminPortal">Admin Portal</a>    
            <a href = "/">Home Page</a>
        </div> 
        <?php
            if(isset($_SESSION['id']) > 0){
                echo '        <div align=right>
                <br>
                <h5>Admin Options:   <t>  
                <button type="button" onclick="changed(\'onlyMine\')"> View Only My Data</button>
                <button type="button" onclick="changed(\'everyone\')">View All Data</button>
                </h5>
                <br>
        </div>';
            }
        ?>
        <div style = "width:100%">
            <table id="users" class="display nowrap" cellspaceing = "0" style="width:100%">
            <thead>
            <tr>
                <th></th>
                <th>File Name</th>
                <th>Date Created</th>
                <th>Owner</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th></th>
                <th>File Name</th>
                <th>Date Created</th>
                <th>Owner</th>
            </tr>
        </tfoot>
    </table>
        </div>
           
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
        <script>
            function format(index) {
                return '<table id = "childTable'+ index +'" class="display" cellpadding="5" cellspacing="0" border="0" style="padding-left:5px;">'+
                '<thead>'+
                    '<tr>'+
                        '<th>Name</th>'+
                        '<th>Right Ascension</th>'+
                        '<th>Declination</th>'+
                        '<th>m_g</th>'+
                        '<th>m_r</th>'+
                        '<th>m_z</th>'+
                        '<th>e_m_g</th>'+
                        '<th>e_m_r</th>'+
                        '<th>e_m_z</th>'+
                        '<th>mu_0_g</th>'+
                        '<th>mu_0_r</th>'+
                        '<th>mu_0_z</th>'+
                        '<th>e_mu_0_g</th>'+
                        '<th>e_mu_0_r</th>'+
                        '<th>e_mu_0_z</th>'+
                        '<th>r_e</th>'+
                        '<th>e_r_e</th>'+
                        '<th>b/a</th>'+
                        '<th>e_b/a</th>'+
                    '</tr>'+
                '</thead>'
                '</table>';
            }
            var table = null;
            var d = {value:"everyone"}
            
            //function to change what data is in the table
            function changed(data){
                d.value = data;
                $.ajax({
                    url: "/resources/php/viewDataPOST.php",
                    dataSrc: 'aaData',
                    type: "POST",
                    dataType: 'json',
                    data: d,
                    failure: function(data) {
                        console.log("oh no");
                    },
                    success: function(data) {
                        if (typeof table == 'object') {
                            table.rows().remove().draw();
                            table.rows.add(data.aaData);
                            table.draw();
                        }
                    },
                });
            }
            // function for table init (first time table opening)
            function Tableinit(){
                table = $('#users').DataTable( {
                    ajax: {
                        url: "/resources/php/viewDataPOST.php",
                        dataSrc: 'aaData',
                        type: "POST",
                        data: d,  
                        dataType: "json",
                        failure: function(data) {
                            console.log(data); 
                        }
                    },
                    columns: [
                        {
                            className:      'details-control',
                            orderable:      false,
                            data:           null,
                            defaultContent: ''
                        },
                        { data: "first_name" },
                        { data: "last_name" },
                        { data: "age" }
                    ],
                    order: [[1, 'asc']],
                    destroy: true,
                });
            }
            
            Tableinit(); 
            
                //listener for opening child row
                $('#users').on('click', 'td.details-control', function () {
                    var tr = $(this).closest('tr');
                    var row = table.row(tr);
                    var index = row.index();

                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    //Open this row
                    row.child(format(index)).show();
                    tr.addClass('shown');

                    fname = row.data().first_name
                    lname = row.data().age
                    pass = {"value": fname, "owner":lname}
                
                    
                    var childtable = $('#childTable'+index).DataTable( {
                    ajax: {
                        url: "/resources/php/viewDataPOSTchild.php",
                        dataSrc: 'aaData',
                        type: "POST",
                        dataType: "json",
                        data: pass,  
                        failure: function(data) {
                            console.log(data); 
                        }
                    },
                    dom: 'lBfrtip',
                    destroy: true,
                    columns: [
                        { data: "Name" },
                        { data: "Right Ascension" },
                        { data: "Declination" },
                        { data: "m_g" },
                        { data: "m_r" },
                        { data: "m_z" },
                        { data: "e_m_g" },
                        { data: "e_m_r" },
                        { data: "e_m_z" },
                        { data: "mu_0_g" },
                        { data: "mu_0_r" },
                        { data: "mu_0_z" },
                        { data: "e_mu_0_g" },
                        { data: "e_mu_0_r" },
                        { data: "e_mu_0_z" },
                        { data: "r_e" },
                        { data: "e_r_e" },
                        { data: "b/a" },
                        { data: "e_b/a" }
                    ],
                    order: [[1, 'asc']],
                    buttons: ['csv', {text: "delete",
                        action: function ( e, dt, node, config ) {
                            $.ajax({
                                url: "/resources/php/deleteFile.php",
                                type: "POST",
                                data: pass,
                                failure: function(data) {
                                    console.log("oh no");
                                },
                                success: function(data) {
                                    alert(data);
                                },
                            })
                            
                    row.child.hide();
                    tr.removeClass('shown');
                    changed(d.value);
                    },
                }
                    ],
                    initComplete: function () {
                        childtable.buttons().container()
                  .appendTo( $('#childTable'+index, childtable.table().container() ) );
              }//end of buttons
            });//end of datatable
            };//end of else statement
            });//end child table event
        </script>
    </body>
</html>
