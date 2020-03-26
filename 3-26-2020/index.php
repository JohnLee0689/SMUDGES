<?php
session_start();
?>
<!DOCTYPE html>
<!-- saved from url=(0042)https://smudgesgraphing.000webhostapp.com/ -->
<html><head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/5.7.0/d3.min.js"></script>
    <script src="https://unpkg.com/topojson-client@3"></script>
    <script src="//d3js.org/d3.v5.min.js"></script>
	<script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
    <title> 
        Navigating SMUDGes: Systematically Measuring Ultra-Diffuse Galaxies
    </title> 
    <style id="plotly.js-style-modebar-4b4e50"></style></head> 
    <body>
    <!-- header of website layout -->
        <div class="header"> 
            <h2 style="color:white;font-size:200%;">
            Navigating SMUDGes:
            Systematically Measuring Ultra-Diffuse Galaxies</h2> 
            <p style="color:white">
            The SMUDGes survey aims to understand the extremes of galaxy formation and the nature of dark matter using the properties of these mysterious galaxies. This applet is intended to provide those within the SMUDGes team and those who are curious about our work with the ability to visualize and share recent results. The basic functionality mimics that of the popular graphical tool for tabular data, TOPCAT.</p>
            <!-- navigation menu of website layout -->
            <div class="nav_menu"> 
                <a href="/login">Login/Logout</a>
                <a href="/uploadData">Upload Data</a>   
                <a href="/viewData">View Data</a>             
                <a href="/adminPortal">Admin Portal</a> 
                <a href="/">Homepage</a> 
            </div> 
        </div> 
        
        <div id="sidebarleft" class="sidebar1">
            <div style="margin:10px">
            <a href="javascript:void(0)" class="closebtn1" onclick="closeNav1()">×</a>
			<h2 style="color:black; float:left">Graphing Tools</h2>
			<select id="tools" name="tools" onchange="changeTool(this.value)">
				<option value="1" selected="selected">Sky Plot</option>
				<option value="2">Scatterplot</option>
				<option value="3">Histogram Plot</option>
			</select>
			
            <!-- menu options for the skyplot -->
			<form class="sidebar" name="skyPlotForm" style="visibility: visible;">
			    <br>
			    <?php
                    if(isset($_SESSION['login_user'])){
                        echo '<h4>Private Data</h4>';
                        require_once("resources/php/connect.php");
			            $searchQuery  = "SELECT Owner, FileName FROM Files WHERE Owner = '".$_SESSION['login_user']."'";
                        $rows = $conn->query($searchQuery);
                        if($rows->rowCount() < 1){
                            echo "You Have Uploaded no files<br>";
                        }
                        foreach($rows as $row){
                            echo '<input type="checkbox" name="public" value="'.$row['Owner'].'@'.$row['FileName'].'">'.$row['Owner'].'-'.$row['FileName'].'<br>';
                        }
                    }
			    ?>
			    <br>
			    <h4>Public Data</h4>
			    <input type="checkbox" name="public" value="default@" checked="">Default Public Data<br>
			    <?php
			    
                        require_once("resources/php/connect.php");
			            $searchQuery  = "SELECT Owner, FileName FROM Files WHERE public = 1";
                        $rows = $conn->query($searchQuery);
                        foreach($rows as $row){
                            echo '<input type="checkbox" name="public" value="'.$row['Owner'].'@'.$row['FileName'].'">'.$row['Owner'].'-'.$row['FileName'].'<br>';
                        }
			        //get the filenames and usernames of all publically avaliable files
			    ?>		    
			    <input type="button" id="submit" name="submit" value="Update skyplot" onclick="SkyPlotMain(this.form)">
			</form>
			<div id="graphTools" class="graphTools">
			<input type="button" value="Update Plot" onclick="UpdatePlot()">
		    <!-- filters for the plots -->
			<p>Sort By:</p>
		    <p>Right Ascension</p>
			<p>min:
			    <span>
			    <input type="number" value=-90 id="ra1" class="filter">
			    -
			    <input type="number" value=90 id="ra2" class="filter">
			    </span>
			:max</p>
			<p>Declination</p>
			<p>min:
			    <span>
			    <input type="number" value=0 id="d1" class="filter">
			    -
			    <input type="number" value=360 id="d2" class="filter">
			    </span>
			:max</p>
            </div>
            </div>
        </div>
        <!-- end of left sidebar -->
        
        <div id="main">
        <button class="openbtn1" onclick="openNav1()">☰ Graphing Tools</button>  
        </div>
        
        <div id="sidebarright" class="sidebar2">
            <div style="margin:10px">
                <a href="javascript:void(0)" class="closebtn2" onclick="closeNav2()">×</a>
			    <h2 style="color:black">Galaxy Data</h2>
			    <p id="galaxyName">Name:</p>
			    <p id="galaxyRa">Right Ascention:</p>
			    <p id="galaxyDec">Declination:</p>
			    <div id="MoreGalaxyData">
			    </div>
			</div>
        </div>
        <!-- end of right sidebar -->
        
        <div id="main">
        <button class="openbtn2" onclick="openNav2()">Galaxy Data ☰ </button>  
        </div>
        
		<div id="tester" style="position:relative;left:30%;right:30%;width:40%;height:70%:z-index:-10" class="js-plotly-plot"></div>
        <svg id="skyplot" style="position: fixed; width: 100%; height: 100%; z-index: -15;"></svg>
        <div class="tooltip" style="opacity:0;width:200px;min-height:45px"></div>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.js" type="text/javascript"></script>
        <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
		<script>
        var coll = document.getElementsByClassName("collapsible");
        var i;
        for (i = 0; i < coll.length; i++) {
            coll[i].addEventListener("click", function() {
                this.classList.toggle("active");
                var content = this.nextElementSibling;
                if (content.style.display === "block") {
                    content.style.display = "none";
                } else {
                    content.style.display = "block";
                }
              });
        }
        </script>
		
		<!-- functions to open and close sidebars -->
        <script>
        function openNav1() {
            document.getElementById("sidebarleft").style.width = "250px";
        }
        function closeNav1() {
            document.getElementById("sidebarleft").style.width = "0";
        }
        function openNav2() {
            document.getElementById("sidebarright").style.width = "250px";
        }
        function closeNav2() {
            document.getElementById("sidebarright").style.width = "0";
        }
        
        function changeNav2(name,ra,dec,users,files){
            document.getElementById("galaxyName").innerHTML = "Name: "+name;
            document.getElementById("galaxyRa").innerHTML = "Right Ascension: "+ra;
            document.getElementById("galaxyDec").innerHTML = "Declination: "+dec;
            var moreGalaxies = document.getElementById("MoreGalaxyData")
            moreGalaxies.innerHTML = "";
            //search db for all galaxies from selected files with this name and ra and dec
            //for each one found add a table to the div
            $.ajax({
                type: "POST",
                url: '/resources/php/skyPlotMoreData.php',
                data: ({ issession : 1, 'users': users, 'fileNames' : files, 'name': name, 'ra': ra, 'dec':dec}),
                dataType: "json",
                success: function(data) {
                    for(i = 0; i < data.length; i++){
                        var keys = Object.keys(data[i])
                        var num = Math.random();
                        $('#MoreGalaxyData').append('<table width=90%, style="text-align:center;border: 2px solid rgb(150,150,150);margin:5%"><tbody id='+num+'></tbody></table>');
                        var table = document.getElementById(num)
                        for(j = 0; j < keys.length; j++){
                            table.innerHTML += "<tr><td  style='background-color:lightgray; border: 1px solid rgb(120,120,120);'>"+keys[j]+"</td><td style='border: 1px solid rgb(120,120,120);'>"+data[i][keys[j]]+"</td></tr>"
                        }
                    }
                },
                error: function() {
                    alert('Error occured');
                }
            });
        }
        </script>
		
		<!-- swaps between the different graphing tools -->
		<script>
		//changes what is visible in the sidebar?
		changeTool(1);
		function changeTool(tool){
			if(tool == '1'){
			    //creates skyplot and makes unrelated sidebar elements hidden
			    //and removes other plot
                SkyPlotMain();
			}
			if(tool == '2'){
			    //add the functionality to make related sidebar elements visible
				hideSkyPlot();
				showScatterPlot();
			}
			if(tool == '3'){
				hideSkyPlot();
				showHistogram();
			}
		}
		
		function UpdatePlot(){
		    changeTool(document.getElementById('tools').value);
		}
		function filterData(data,r1, r2) {
		    newData = data.filter(function(test) {
		        return test >= r1;})
		    newData = newData.filter(function(test) {
		        return test <= r2;})
		    return newData;
		}
		
		//Makes the div containing the plotly grpah visible and shows the Histogram
		function showHistogram() {
		    clearPlot();
		    document.getElementById('graphTools').style.visibility="visible";
		    raMin = document.getElementById('ra1').value;
		    raMax = document.getElementById('ra2').value;
		    TESTER = document.getElementById('tester');
		    TESTER.style.display = "block";
		    testArray= filterData([1, 1, 1, 1, 2, 2, 3, 4, 5],raMin,raMax);
	        Plotly.newPlot( TESTER, [{
	        x: testArray,
            mode: 'markers',
            type: 'histogram' }]);
		}
		

        //Makes the div containing the plotly graph visible and shows the ScatterPlot
		function showScatterPlot() {
		    clearPlot();
		    document.getElementById('graphTools').style.visibility="visible";
        	TESTER = document.getElementById('tester');
        	TESTER.style.display = "block";
	        Plotly.newPlot( TESTER, [{
	        x: [1, 2, 3, 4, 5],
	        y: [1, 2, 4, 8, 16],
            mode: 'markers',
            type: 'scatter' }]);
		}
		
		//Makes the div containing the plotly graph invisble and clears the graph
		function clearPlot() {
			Plotly.purge(tester);
			document.getElementById('submit').style.visibility="hidden";
			var x = document.getElementById("tester");
            x.style.display = "none";
		}
		
        function SkyPlotMain(form){
            //hides and clears other plots
            clearPlot();
            document.getElementById('submit').style.visibility="visible";
            document.getElementById('graphTools').style.visibility="hidden";
            user = [];
            fileNames = [];
            publicBoxes = document.forms['skyPlotForm'].elements['public'];
            for (i=0;i<publicBoxes.length;i++) {
                if (publicBoxes[i].checked == true) {
                    info = publicBoxes[i].value.split('@')
                    user.push(info[0]);
                    fileNames.push(info[1]);
                }
            }
			hideSkyPlot();
			runSkyPlot(user,fileNames);
			
			//make unrelated sidebar elements invisible
			//related sidebar elements are named "skyPlotForm"
			var sidebar = document.getElementsByClassName('sidebar');
			for(i=0;i<sidebar.length;i++){
			    if(sidebar[i].name != "skyPlotForm"){
			        sidebar[i].style.visibility = "hidden";
			    } else{
			        sidebar[i].style.visibility = "visible";
			    }
			}
        }
        function hideSkyPlot() {
	        d3.select("svg").selectAll("*").remove();
	        document.getElementById("skyplot").style.zIndex = "-15";
        }

        function runSkyPlot(userList,fileList) {
            $.ajax({
                type: "POST",
                url: '/resources/php/skyPlotPOST.php',
                data: ({ issession : 1, 'users': userList, 'fileNames' : fileList }),
                dataType: "json",
                success: function(data) {
                    // Run the code here that needs
                    //    to access the data returned
                    showSkyPlot(data, userList, fileList);
                },
                error: function() {
                    alert('Error occured');
                }
            });
        }

        function showSkyPlot(data,userList,fileList){
        // Define the div for the tooltip
        document.getElementById("skyplot").style.zIndex = "-5";
        
        var div = d3.select(".tooltip")
        
        var svg = d3.select("svg")
            .append("g")
            .attr("position","center")
            .style('transform', 'translate(50%, 40%)');


        var projection = d3.geoOrthographic()
            .translate([0,0])
              
        var path = d3.geoPath()
            .projection(projection)
            .pointRadius(2);
    

        var graticule = d3.geoGraticule()


        var dip_angle = 45
        var dip_extent = dip_angle - 1

 
        var world = {type:"Sphere"}
  
        svg.append("path")
            .datum(world)
            .attr("d", path)
            .attr("fill","white");

        //add graticule
        svg.selectAll('path.graticule').data(graticule.lines())
            .enter().append('path')
            .attr('class', 'graticule')
            .attr('d', path)

        svg.selectAll("stars")
            .data(data.Features)
            .enter()
            .append("path")
            .attr("fill","deepskyblue")
            .attr("d",path)
            .on("mouseover", function(d) {
                div.transition()		
                    .duration(200)		
                    .style("opacity", .9);		
                div	.html("name: "+(d.properties.id)+"<br>"+"ra: "+(d.geometry.coordinates[0]) + "<br>"  + "dec: "+ d.geometry.coordinates[1])	
                    .style("left", (d3.event.pageX) + "px")		
                    .style("top", (d3.event.pageY - 28) + "px")
                })			
            .on("mouseout", function(d) {		
                    div.transition()		
                        .duration(500)		
                        .style("opacity", 0);	
                })
            .on("click", function(d){
                changeNav2(d.properties.id,d.geometry.coordinates[0],d.geometry.coordinates[1],userList,fileList);
                openNav2();
            })
            const initialScale = projection.scale();
            //svg.selectAll("stars[geometry.coordinates[0]<=document.getElementById("r1").value")
            //    .visibility="hidden";
          
        var allPoints = {"type":"FeatureCollection","Features":[]};
        for (var lat = -90; lat < 90; lat=lat+10) {
            for (var lon = 0; lon < 360; lon=lon+180) {
                allPoints.Features.push({"type":"Feature","properties":{"name":lat+"_"+lon,"radius":0.0},"geometry":{"type":"Point","coordinates":[lon,lat]}});
            }
        }
        
        for (var lon = 0; lon < 360; lon=lon+10) {
            allPoints.Features.push({"type":"Feature","properties":{"name":0+"_"+lon,"radius":0.0},"geometry":{"type":"Point","coordinates":[lon,0]}});
        }
    
        var intersections = svg.selectAll('dots')
            .data(allPoints.Features)
            .enter().append('path')
            .attr("d",path)
            .attr("id", d => d.properties.name)
            .attr("class", "dots")
            .style("opacity", 0)

            // Draw graticule lines


        var labels = svg.selectAll('label')
            .data(allPoints.Features)
            .enter()
            .append("text")
            .attr("class", "label")
      
        function position_labels(){
            labels
                .attr("transform", function(d){
                    if(isNaN(projection(d.geometry.coordinates)[0]) || d == null || d3.select("[id = '"+d.properties.name+"']").attr('d') == null){
                    return "translate(500,500)"}
                    else{
                        if(d.geometry.coordinates[1] == 0){
                            if(d.geometry.coordinates[0] == 0 || d.geometry.coordinates[0] == 180){
                                return"translate("+projection(d.geometry.coordinates)+") rotate(-45)";
                            }
                        return "translate("+projection(d.geometry.coordinates)+") rotate(-90)";
                        }else{
                        return "translate("+projection(d.geometry.coordinates)+")";
                        }
                        }})
                .text(function(d) {
                    coord = d.geometry.coordinates
                    if(coord[1] == 0){
                        if(coord[0] == 0 || coord[0] == 180){
                            return coord[0]+"°, "+ coord[1] + "°"
                        }
                        return coord[0] + "°"
                    } else if(coord[0] == 0 || coord[0] == 180){
                    return (coord[1] + "°")
                }
                });
                }
        position_labels();

        svg.call(d3.drag()
            .on("drag", function(d) {
                var c = projection.rotate();
                projection.rotate([c[0] + d3.event.dx/6, c[1] - d3.event.dy/6])
                svg.selectAll('path').attr('d', path);
                position_labels();
            }))
            .call(d3.zoom().on('zoom', () => {
                position_labels();
                projection.scale(initialScale * d3.event.transform.k);
                svg.selectAll('path').attr('d', path);
                position_labels();
            }));
}

</script></body></html>