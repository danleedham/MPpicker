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
  <link rel="stylesheet" href="template/chosen/chosen.css">
  <title>Who's that?</title>

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

function loadresults(){
   document.getElementById('loader').style.display = 'inline';
   var house = document.getElementById("house-input").value;
   		if(house) { var houseurl = '&house='+house; }
   		else { var houseurl = ""; }
   var sex = document.getElementById("sex-input").value;
		if(sex) { var sexurl = '&sex='+sex; }
   		else { var sexurl = ""; }
   var party = document.getElementById("party-input").value;
   		if(party) { var partyurl = '&party='+encodeURI(party); }
   		else { var partyurl = ""; }
   var position = document.getElementById("position-input").value;
   		if(position) { var positionurl = '&position='+encodeURI(position); }
   		else { var positionurl = ""; }
   var committee = document.getElementById("committee-input").value;
   		if(committee) { var committeeurl = '&committee='+encodeURI(committee); }
   		else { var committeeurl = ""; }
   var department = document.getElementById("department-input").value;
   		if(department) { var departmenturl = '&department='+encodeURI(department); }
   		else { var departmenturl = ""; }
   var photos = document.getElementById("photos-input").value;
   		if(photos) { var photosurl = '&photos='+encodeURI(photos); }
   		else { var photosurl = ""; }
   var joined = document.getElementById("joined-input").value;
   		if(joined) { var joinedurl = '&joined='+String(joined); }
   		else { var joinedurl = ""; }
   var sortby = document.getElementById("sortby-input").value;
   		if(sortby) { var sortbyurl = '&sortby='+String(sortby); }
   		else { var sortbyurl = ""; }		
   console.log('Loading List: '+houseurl+sexurl+partyurl+positionurl+committeeurl+departmenturl+photosurl+joinedurl);
   $("#whoresults").load('template/wholist.php?'+houseurl+sexurl+partyurl+positionurl+committeeurl+departmenturl+photosurl+joinedurl+sortbyurl,function() {
   		document.getElementById('loader').style.display = 'none';
   });
}

function hidejobs(){
	elements = document.getElementsByClassName("joblist");
    for (var i = 0; i < elements.length; i++) {
        elements[i].style.display = elements[i].style.display == 'block' ? 'none' : 'block';
    }
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
			<h3 class="panel-title"> 
				Use the search tools below 
				<span id="loader" style="display:none;">
					<i class="fa fa-refresh fa-spin" class="pull-right" style="font-size:20px"></i>
				</span>
			</h3> 
         </div>     
		 <div class="list-group">
			<!-- start search form -->
				<!-- house -->
				 <div class="list-group-item">
				 <select name="house" onchange="loadsex();" form="mpsearch" class="form-control custom-select mb-2 mr-sm-2 mb-sm-0" id="house-input">
				 <option value="Commons"> Commons </option>
				 <option value="Lords"> Lords </option>
				 <option value="both"> Both </option>
				 </select>
				 </div>
				
				<!-- sex -->
				 <div class="list-group-item">
				 <select name="sex" form="mpsearch" class="form-control custom-select mb-2 mr-sm-2 mb-sm-0" id="sex-input">
				 <option value="">Choose Gender</option>
				 <?php $house = "Commons" ?>
				 <?php include("template/whosex.php"); ?>
				 </select>
				 </div>

				<!-- party -->		
				<div class="list-group-item">
				<select data-placeholder="Choose a Party..." class="chosen-select form-control custom-select mb-2 mr-sm-2 mb-sm-0" tabindex="2" id="party-input">
					<option value=""></option>
					<?php include("template/whoparty.php"); ?>
				</select>
				</div>
			
			
				<!-- positions -->
				 <div class="list-group-item">
				 <select name="positions" form="mpsearch" class="form-control custom-select mb-2 mr-sm-2 mb-sm-0" id="position-input">
				 <option value="">Select Current position</option>
				 <?php  include("template/whopositions.php"); ?>
				 </select>
				 </div>
			
				<!-- committees -->
				<div class="list-group-item">
				<select data-placeholder="Choose a Committee..." class="chosen-select form-control custom-select mb-2 mr-sm-2 mb-sm-0" tabindex="2" id="committee-input">
					<option value=""></option>
					<?php $house = "Commons";
						  include("template/whocommittee.php"); ?>
				</select>
				</div>
			
					<!-- departments -->
				<div class="list-group-item">
				<select data-placeholder="Choose a Department..." class="chosen-select form-control custom-select mb-2 mr-sm-2 mb-sm-0" tabindex="2" id="department-input">
					<option value=""></option>
					<?php include("template/whodepartment.php"); ?>
				</select>
				</div>
			
				<!-- Joined after -->
				<div class="list-group-item">
				<label for="joined-input">Joined After:</label>	
				<input id="joined-input" type="date" class="input-sm form-control" onchange="" name="joined-after" >
				</div>
				
				<!-- sortby -->
				 <div class="list-group-item">
				 <select name="sortby" form="mpsearch" class="form-control custom-select mb-2 mr-sm-2 mb-sm-0" id="sortby-input">
				 <option value="">Sort by</option>
				 <option value="first">Name First</option>
				 <option value="last">Name Last</option>
				 <option value="consta">Constituency A-Z</option>
				 <option value="constz">Constituency Z-A</option>
				 <option value="joinedlast"> Date Joined (Most Recent) </option>
				 <option value="joinedfirst"> Date Joined (Least Recent) </option>
				 <option value="oldest"> Age (Oldest) </option>
				 <option value="youngest"> Age (Youngest) </option>
				 </select>
				 </div>
				 
				 <!-- sortby -->
				 <div class="list-group-item">
				 <select name="photos" form="mpsearch" class="form-control custom-select mb-2 mr-sm-2 mb-sm-0" id="photos-input">
				 <option value="">Picture Type</option>
				 <option value="stock"> Stock</option>
				 <option value="screenshot"> Screenshot</option>
				 </select>
				 </div>
			 	
				
				<!-- submit -->
				<div class="list-group-item">
					<a href="#" onclick="loadresults();return false;" class="btn btn-success" role="button">Hit me up</a>
					<a href="#" onclick="window.location.reload()" class="btn btn-danger" role="button">Reset</a>
					<a href="#" onclick="hidejobs();return false;" class="btn btn-warning" role="button">Hide Jobs</a>
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


  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.js" type="text/javascript"></script>
  <script src="template/chosen/chosen.jquery.js" type="text/javascript"></script>
  <script src="template/chosen/docsupport/prism.js" type="text/javascript" charset="utf-8"></script>
  <script src="template/chosen/docsupport/init.js" type="text/javascript" charset="utf-8"></script>

<?php include 'template/footer.php'; ?>

<?php include 'template/core.php'; ?>  
  
  </body>
</html>
