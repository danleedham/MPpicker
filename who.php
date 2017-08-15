<?php $xmlDoc=new DOMDocument(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="google" value="notranslate">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
  <style> 
  	.grid-item { width: 200px; }
  </style>
  <title>Guess Who?</title>

	<?php
		//get the css and js nonesense
		include 'template/headinc.php';	
		?>
<script>
function loadsex(){
   var house = document.getElementById("house-input").value;
   $("#sex-input").load('template/whosex.php?&house='+house);
}
function loadparties(){
   var house = document.getElementById("house-input").value;
   var sex = document.getElementById("sex-input").value;
   $("#party-input").load('template/whoparty.php?house='+house+'&sex='+sex);
}
function loadcommittees(){
   var house = document.getElementById("house-input").value;
   $("#committee-input").load('template/whocommittee.php?house='+house);
}

function loadresults(){
   var house = document.getElementById("house-input").value;
   		if(house) { var houseurl = '&house='+house; }
   		else { var houseurl = ""; }
   var sex = document.getElementById("sex-input").value;
		if(sex) { var sexurl = '&sex='+sex; }
   		else { var sexurl = ""; }
   var party = document.getElementById("party-input").value;
   		if(party) { var partyurl = '&party='+party; }
   		else { var partyurl = ""; }
   var position = document.getElementById("position-input").value;
   		if(position) { var positionurl = '&position='+position; }
   		else { var positionurl = ""; }
   var committee = document.getElementById("committee-input").value;
   		if(committee) { var committeeurl = '&committee='+encodeURI(committee); }
   		else { var committeeurl = ""; }
   var department = document.getElementById("department-input").value;
   		if(department) { var departmenturl = '&department='+encodeURI(department); }
   		else { var departmenturl = ""; }
   console.log('Loading List: '+houseurl+sexurl+partyurl+positionurl+committeeurl+departmenturl);
   $("#whoresults").load('template/wholist.php?'+houseurl+sexurl+partyurl+positionurl+committeeurl+departmenturl);
}
</script>

</head>

<body>

   <div class="container-fluid bootcards-container push-right">

    <div class="row">
	 <!-- Search panel -->
      <!--options column-->
      <div class="col-sm-3 bootcards-list" id="list" data-title="Contacts">
        <div class="panel panel-default">  
         <div class="panel-heading clearfix">
                <h3 class="panel-title pull-left"> 
					Use the search tools below
				</h3> 
         </div>     
		 <div class="list-group">
			<!-- start search form -->
				<!-- house -->
				 <div class="list-group-item">
				 <select name="house" onchange="loadsex(); loadcommittees();" form="mpsearch" class="form-control custom-select mb-2 mr-sm-2 mb-sm-0" id="house-input">
				 <option value="Commons"> Commons </option>
				 <option value="Lords"> Lords </option>
				 <option value="both"> Both </option>
				 </select>
				 </div>
				
				<!-- sex -->
				 <div class="list-group-item">
				 <select name="sex" onchange="loadparties()" form="mpsearch" class="form-control custom-select mb-2 mr-sm-2 mb-sm-0" id="sex-input">
				 <option value="">Select Sex</option>
				 <?php echo file_get_contents("http://leedhammedia.com/parliament/template/whosex.php?house=".$house); ?>
				 </select>
				 </div>

				<!-- party -->		
				<div class="list-group-item">
				<select name="party" form="mpsearch" class="form-control custom-select mb-2 mr-sm-2 mb-sm-0" id="party-input">
					<option value="">Select Party</option>
					<?php echo file_get_contents("http://leedhammedia.com/parliament/template/whoparty.php?sex=".$sex); ?>
				</select>
				</div>
			
			
				<!-- positions -->
				 <div class="list-group-item">
				 <select name="positions" form="mpsearch" class="form-control custom-select mb-2 mr-sm-2 mb-sm-0" id="position-input">
				 <option value="">Select Current position</option>
				 <?php echo file_get_contents("http://leedhammedia.com/parliament/template/whopositions.php"); ?>
				 </select>
				 </div>
			
				<!-- committees -->
				<div class="list-group-item">
				<select name="committee" form="mpsearch" class="form-control custom-select mb-2 mr-sm-2 mb-sm-0" id="committee-input">
					<option value="">Select Committee</option>
					<?php echo file_get_contents("http://leedhammedia.com/parliament/template/whocommittee.php?house=Commons"); ?>
				</select>
				</div>
			
					<!-- departments -->
				<div class="list-group-item">
				<select name="department" form="mpsearch" class="form-control custom-select mb-2 mr-sm-2 mb-sm-0" id="department-input">
					<option value="">Select Department</option>
					<?php echo file_get_contents("http://leedhammedia.com/parliament/template/whodepartment.php"); ?>
				</select>
				</div>
			
				<!-- Joined after -->
				<div class="list-group-item">
				<label for="joined-input">Joined After:</label>	
				<input id="joined-input" type="date" class="input-sm form-control" onchange="" name="joined-after" >
				</div>
				
				<!-- sortby -->
				 <div class="list-group-item">
				 <select name="sortby" form="mpsearch" class="form-control custom-select mb-2 mr-sm-2 mb-sm-0" id="sortby">
				 <option value="">Sort by</option>
				 <option value="alphabetical">Alphabetical</option>
				 <option value="joined-recent"> Date Joined (Most Recent) </option>
				 <option value="joined-furthest"> Date Joined (Least Recent) </option>
				 <option value="alpha"> Alphabetical </option>
				 <option value="age"> Age </option>
				 </select>
				 </div>
			 	
				
				<!-- submit -->
				<div class="list-group-item">
					<a href="#" onclick="loadcommittees();return false;" class="btn btn-info" role="button">Test</a>
					<a href="#" onclick="loadresults();return false;" class="btn btn-danger" role="button"><i class="fa fa-refresh"></i>Reset</a>
				</div>
            </div> <!--list group-->

        </div><!--panel-->

        </div><!--options column-->

        <!--list details column-->
        <div id="whoresults" class="col-sm-9 bootcards-cards bootcards-who">
        	

			</div>
		</div><!--list-details-->

    </div><!--row-->


  </div><!--container-->

<?php include 'template/footer.php'; ?>

<?php include 'template/core.php'; ?>  
  
  </body>
</html>
