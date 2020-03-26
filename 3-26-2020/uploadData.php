<html>
    <head>
        <title> 
            SMUDGES: Systematically Mapping Ultra-Diffuse Galaxies
        </title>
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
    </head>
    <body>
        <div class = "header"> 
            <h2 style = "color:white;font-size:200%">SMUDGES: Upload Data</h2> 
        </div> 
        <div class = "nav_menu"> 
            <a href = "login">Login/Logout</a>
            <a href = "uploadData">Upload Data</a>   
            <a href = "viewData">View Data</a>             
            <a href = "adminPortal">Admin Portal</a> 
            <a href = "/">Homepage</a> 
        </div> 

	<br>
	<br><br><br><br>
	<button class="button button1">Choose File</button>
	<br>
	<div class="moveRight">(Preview)</div>
	<br><br><br><br><br><br><br><br><br><br><br><br>
	<button class="button button2">Upload</button>
	
    </body>
</html>
