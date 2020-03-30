<?php
session_start();
define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'id12553816_smudgesdb');
    define('DB_PASSWORD', 'SexyDiffusedGalaxies');
    define('DB_NAME', 'id12553816_mydb');
    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);// Check connection
        if($conn === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
if (isset($_POST["import"])) {
    
    $fileName = $_FILES["file"]["tmp_name"];
    
    if ($_FILES["file"]["size"] > 0) {
        
        $file = fopen($fileName, "r");
        while (($column = fgetcsv($file, 0, ",")) !== FALSE) {
           $sqlInsert = "INSERT into udg (Name , Right_Ascension , Declination , m_g, m_r, m_z, e_m_g, e_m_r, e_m_z, mu_0_g, mu_0_r, mu_0_z, e_mu_0_g, e_mu_0_r, e_mu_0_z, r_e, e_r_e, ba, e_ba, owner, FileName) VALUES ('" . $column[0] . "','" . $column[1] . "','" . $column[2] . "','" . $column[3] . "','" . $column[4] . "','" . $column[5] . "','" . $column[6] . "','" . $column[7] . "','" . $column[8] . "','" . $column[9] . "','" . $column[10] . "','" . $column[11] . "','" . $column[12] . "','" . $column[13] . "','" . $column[14] . "','" . $column[15] . "','" . $column[16] . "','" . $column[17] . "','" . $column[18] . "','" . $_SESSION['login_user'] . "','" . $fileName . "')";
            $result = mysqli_query($conn, $sqlInsert);
            if (! empty($result)) {
                $type = "success";
                $message = "CSV Data Imported into the Database";
            } else {
                $type = "error";
                $message = "Error: " . $sqlInsert . "<br>" . mysqli_error($conn);
            }
        }
        $d= date("Y-M-D");
        $sqlInsert= 'INSERT into Files(FileName, DateCreated, Owner, public) VALUES ($fileName, $d, $_SESSION["login_user"], "0")';
        mysqli_query($conn,$sqlInsert);
    }
}
?>

<html>
    <head>
        <title> 
            SMUDGES: Systematically Mapping Ultra-Diffuse Galaxies
        </title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <script src="jquery-3.2.1.min.js"></script>
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

	  .button {
	  background-color: #20B2A9;
	  border: none;
	  color: white;
	  padding: 15px 25px;
	  text-align: center;
	  font-size: 16px;
	  cursor: pointer;
	  }

	  .button:hover {
	      background-color: #1B938C;
	    }


	    .button1 {
	    position: absolute;
	    left: 300px;
	    }
	    
	  .button2 {
	    position: absolute;
	    right: 300px;
	    }

	    .moveRight {
	    position: absolute;
	    right: 600px;
	    }
	    
        </style>
    <script type="text/javascript">
		$(document).ready(function() {
		$("#frmCSVImport").on("submit", function () {

	    $("#response").attr("class", "");
        $("#response").html("");
        var fileType = ".csv";
        var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + fileType + ")$");
        if (!regex.test($("#file").val().toLowerCase())) {
        	    $("#response").addClass("error");
        	    $("#response").addClass("display-block");
            $("#response").html("Invalid File. Upload : <b>" + fileType + "</b> Files.");
            return false;
		}
        return true;
		});
	});
	</script>
	</head>
    <body>
        <div class = "header"> 
            <h2 style = "color:white;font-size:200%">SMUDGES: Upload Data</h2> 
        </div> 
            <div class="nav_menu"> 
                <?php
					if(isset($_SESSION['login_user'])){
						echo '<a href="/logout">Logout</a>';
					} else {
						echo '<a href="/login">Login</a>';
					}
				?>	
                <a href="/viewData">View Data</a> 
                <?php
                    if(isset($_SESSION['id'])){
                        echo '<a href="/uploadData">Upload Data</a>';
                        if($_SESSION['id'] == '1'){
                            echo '<a href="/adminPortal">Admin Portal</a>';
                        }
                    }
                ?>
                <a href="/">Homepage</a> 
            </div> 

	<div id="response" class="<?php if(!empty($type)) { echo $type . " display-block"; } ?>"><?php if(!empty($message)) { echo $message; } ?></div>
		<div class="outer-scontainer">
			<div class="row">

            <form class="form-horizontal" action="" method="post"
                name="frmCSVImport" id="frmCSVImport" enctype="multipart/form-data">
                <div class="input-row">
                    <label class="col-md-4 control-label">Choose CSV
                        File</label> <input type="file" name="file"
                        id="file" accept=".csv">
                    <button type="submit" id="submit" name="import"
                        class="btn-submit">Import</button>
                    <br />

                </div>

            </form>

			</div>
        <?php
            $sqlSelect = 'SELECT * FROM udg WHERE owner=$_SESSION["login_user"] and FileName=$fileName ';
            $result = mysqli_query($conn, $sqlSelect);
            if (mysqli_num_rows(0) > 0) {
                ?>
            <table id='userTable'>
            <thead> `r_e`, `e_r_e`, `b/a`, `e_b/a`, `owner`, `FileName`
                <tr>
                    <th>UDG Name</th>
                    <th>Right_Ascension</th>
                    <th>Declination</th>
                    <th>m_g</th>
					<th>m_r</th>
					<th>m_z</th>
					<th>e_m_g</th>
					<th>e_m_r</th>
					<th>e_m_z</th>
					<th>mu_0_g</th>
					<th>mu_0_r</th>
					<th>mu_0_z</th>
					<th>e_mu_0_g</th>
					<th>e_mu_0_r</th>
					<th>e_mu_0_z</th>
					<th>r_e</th>
					<th>e_r_e</th>
					<th>ba</th>
					<th>e_ba</th>
					<th>Owner</th>
					<th>File Name</th>

                </tr>
            </thead>
<?php
                
                while ($row = mysqli_fetch_array($result)) {
                    ?>
                    
                <tbody>
                <tr>
                    <td><?php  echo $row['name']; ?></td>
                    <td><?php  echo $row['Right_Ascension']; ?></td>
                    <td><?php  echo $row['Declination']; ?></td>
                    <td><?php  echo $row['m_g']; ?></td>
					<td><?php  echo $row['m_r']; ?></td>
					<td><?php  echo $row['m_z']; ?></td>
					<td><?php  echo $row['e_m_g']; ?></td>
					<td><?php  echo $row['e_m_r']; ?></td>
					<td><?php  echo $row['e_m_z']; ?></td>
					<td><?php  echo $row['mu_0_g']; ?></td>
					<td><?php  echo $row['mu_0_r']; ?></td>
					<td><?php  echo $row['mu_0_z']; ?></td>
					<td><?php  echo $row['e_mu_0_g']; ?></td>
					<td><?php  echo $row['e_mu_0_r']; ?></td>
					<td><?php  echo $row['e_mu_0_z']; ?></td>
					<td><?php  echo $row['r_e']; ?></td>
					<td><?php  echo $row['e_r_e']; ?></td>
					<td><?php  echo $row['ba']; ?></td>
					<td><?php  echo $row['e_ba']; ?></td>
                </tr>
                    <?php
                }
                ?>
                </tbody>
        </table>
        <?php } ?>
    </div>

</body>

</html>