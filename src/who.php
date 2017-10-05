<?php $xmlDoc=new DOMDocument(); ?>
<!DOCTYPE html>

<html lang="en">
<head>
	<meta charset="utf-8">
	<meta content="IE=edge" http-equiv="X-UA-Compatible">
	<meta content="width=device-width, initial-scale=1" name="viewport">
	<meta name="google" value="notranslate">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js">
	</script>
	<script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js">
	</script>
	<link href="template/chosen/chosen.css" rel="stylesheet">

	<title>Who's that?</title><?php
	        //get the css and js nonesense
	        include 'template/headinc.php'; 
	    ?>
	<script>
		function loadsex() {
			var house = document.getElementById("house-input").value;
			$("#sex-input").load('template/whosex.php?&house=' + house);
		}

		function hidemenu() {
			var menu = document.getElementById("list");
			menu.style.display = menu.style.display === 'none' ? '' : 'none';
			var results = document.getElementById("whoresults");
			results.classList.add("col-sm-12");
			results.classList.remove("col-sm-9");
		}

		function loadparties() {
			var house = document.getElementById("house-input").value;
			var sex = document.getElementById("sex-input").value;
			$("#party-input").load('template/whoparty.php?house=' + house + '&sex=' + sex);
		}

		function loadpartiesjusthouse() {
			var house = document.getElementById("house-input").value;
			$("#party-input").load('template/whoparty.php?house=' + house);
		}

		function loadresults() {
			document.getElementById('loader').style.display = 'inline';
			var house = document.getElementById("house-input").value;
			if (house) {
				var houseurl = '&house=' + house;
			} else {
				var houseurl = "";
			}
			var sex = document.getElementById("sex-input").value;
			if (sex) {
				var sexurl = '&sex=' + sex;
			} else {
				var sexurl = "";
			}
			var party = document.getElementById("party-input").value;
			if (party) {
				var partyurl = '&party=' + encodeURI(party);
			} else {
				var partyurl = "";
			}
			var position = document.getElementById("position-input").value;
			if (position) {
				var positionurl = '&position=' + encodeURI(position);
			} else {
				var positionurl = "";
			}
			var committee = document.getElementById("committee-input").value;
			if (committee) {
				var committeeurl = '&committee=' + encodeURI(committee);
			} else {
				var committeeurl = "";
			}
			var department = document.getElementById("department-input").value;
			if (department) {
				var departmenturl = '&department=' + encodeURI(department);
			} else {
				var departmenturl = "";
			}
			var topic = document.getElementById("topic-input").value;
			if (topic) {
				var topicurl = '&topic=' + encodeURI(topic);
			} else {
				var topicurl = "";
			}
			var photos = document.getElementById("photos-input").value;
			if (photos) {
				var photosurl = '&photos=' + encodeURI(photos);
			} else {
				var photosurl = "";
			}
			var joined = document.getElementById("joined-input").value;
			if (joined) {
				var joinedurl = '&joined=' + String(joined);
			} else {
				var joinedurl = "";
			}
			var sortby = document.getElementById("sortby-input").value;
			if (sortby) {
				var sortbyurl = '&sortby=' + String(sortby);
			} else {
				var sortbyurl = "";
			}
			console.log('Loading List: ' + houseurl + sexurl + partyurl + positionurl + committeeurl + topicurl + departmenturl + photosurl + joinedurl);
			$("#whoresults").load('template/wholist.php?' + houseurl + sexurl + partyurl + positionurl + committeeurl + topicurl + departmenturl + photosurl + joinedurl + sortbyurl, function() {
				document.getElementById('loader').style.display = 'none';
			});
		}

		function hidejobs() {
			elements = document.getElementsByClassName("joblist");
			for (var i = 0; i < elements.length; i++) {
				elements[i].style.display = elements[i].style.display == 'none' ? 'block' : 'none';
			}
		}

		function hideconst() {
			elements = document.getElementsByClassName("constituency");
			for (var i = 0; i < elements.length; i++) {
				elements[i].style.display = elements[i].style.display == 'none' ? 'block' : 'none';
			}
		}

		function hideparty() {
			elements = document.getElementsByClassName("party");
			for (var i = 0; i < elements.length; i++) {
				elements[i].style.display = elements[i].style.display == 'none' ? 'block' : 'none';
			}
		}
	</script>
</head>

<body>
	<div class="container-fluid bootcards-container push-right">
		<div class="row">
			<!-- Search panel -->
			<!--options column-->


			<div class="col-sm-3 bootcards-list" data-title="Contacts" id="list">
				<div class="panel panel-default">
					<div class="panel-heading clearfix">
						<h3 class="panel-title"><span onclick="hidemenu();return false;" style="float:right;"><i aria-hidden="true" class="fa fa-bars"></i></span> Use the search tools below <span id="loader" style="display:none;"><i class="pull-right" style="font-size:20px"></i></span></h3>
					</div>


					<div class="list-group">
						<!-- start search form -->
						<!-- house -->


						<div class="list-group-item">
							<select class="form-control custom-select mb-2 mr-sm-2 mb-sm-0" form="mpsearch" id="house-input" name="house" onchange="loadsex(); loadpartiesjusthouse();">
								<option value="Commons">
									Commons
								</option>

								<option value="Lords">
									Lords
								</option>

								<option value="both">
									Both
								</option>
							</select>
						</div>
						<!-- sex -->


						<div class="list-group-item">
							<select class="form-control custom-select mb-2 mr-sm-2 mb-sm-0" form="mpsearch" id="sex-input" name="sex">
								<option value="">
									Choose Gender
								</option><?php $house = "Commons" ?><?php include("template/whosex.php"); ?>
							</select>
						</div>
						<!-- party -->

						<div class="list-group-item">
							<select class="form-control custom-select mb-2 mr-sm-2 mb-sm-0" id="party-input">
								<?php include("template/whoparty.php"); ?>
							</select>
						</div>
						<!-- positions -->


						<div class="list-group-item">
							<select class="form-control custom-select mb-2 mr-sm-2 mb-sm-0" form="mpsearch" id="position-input" name="positions">
								<option value="">
									Select Position Type
								</option><?php  include("template/whopositions.php"); ?>
							</select>
						</div>
						<!-- committees -->


						<div class="list-group-item">
							<select class="chosen-select form-control custom-select mb-2 mr-sm-2 mb-sm-0" data-placeholder="Choose a Committee..." id="committee-input" tabindex="2">
								<option value="">
								</option><?php $house = "Commons";
								                          include("template/whocommittee.php"); ?>
							</select>
						</div>
						<!-- departments -->


						<div class="list-group-item">
							<select class="chosen-select form-control custom-select mb-2 mr-sm-2 mb-sm-0" data-placeholder="Choose a Department..." id="department-input" tabindex="2">
								<option value="">
								</option><?php include("template/whodepartment.php"); ?>
							</select>
						</div>
						<!-- departments -->


						<div class="list-group-item">
							<select class="chosen-select form-control custom-select mb-2 mr-sm-2 mb-sm-0" data-placeholder="Choose a topic..." id="topic-input" tabindex="2">
								<option value="">
								</option><?php include("template/whointerests.php"); ?>
							</select>
						</div>
						<!-- Joined after -->


						<div class="list-group-item">
							<label for="joined-input">Joined After:</label> <input class="input-sm form-control" id="joined-input" name="joined-after" onchange="" type="date">
						</div>
						<!-- sortby -->


						<div class="list-group-item">
							<select class="form-control custom-select mb-2 mr-sm-2 mb-sm-0" form="mpsearch" id="sortby-input" name="sortby">
								<option value="">
									Sort by
								</option>

								<option value="first">
									Name First
								</option>

								<option value="last">
									Name Last
								</option>

								<option value="consta">
									Constituency A-Z
								</option>

								<option value="constz">
									Constituency Z-A
								</option>

								<option value="joinedlast">
									Date Joined (Most Recent)
								</option>

								<option value="joinedfirst">
									Date Joined (Least Recent)
								</option>

								<option value="oldest">
									Age (Oldest)
								</option>

								<option value="youngest">
									Age (Youngest)
								</option>
							</select>
						</div>
						<!-- sortby -->


						<div class="list-group-item">
							<select class="form-control custom-select mb-2 mr-sm-2 mb-sm-0" form="mpsearch" id="photos-input" name="photos">
								<option value="stock">
									Picture Type
								</option>

								<option value="">
									Old Stock
								</option>

								<option value="screenshot">
									Screenshot
								</option>
							</select>
						</div>
						<!-- submit -->


						<div class="list-group-item">
							<a class="btn btn-success" href="#" onclick="loadresults();return false;" role="button">Hit me up</a> <a class="btn btn-danger" href="#" onclick="window.location.reload()" role="button">Reset</a>
						</div>


						<div class="list-group-item">
							Toggle:<br>
							<a class="btn btn-warning" href="#" onclick="hideparty();return false;" role="button">Party</a> <a class="btn btn-warning" href="#" onclick="hideconst();return false;" role="button">Const</a> <a class="btn btn-warning" href="#" onclick="hidejobs();return false;" role="button">Jobs</a>
						</div>
					</div>
					<!--list group-->
				</div>
				<!--panel-->
			</div>
			<!--options column-->
			<!--list details column-->


			<div class="col-sm-9 bootcards-cards bootcards-who" id="whoresults">
			</div>
		</div>
		<!--list-details-->
	</div>
	<!--row-->
	<!--container-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.js" type="text/javascript">
	</script> 
	<script src="template/chosen/chosen.jquery.js" type="text/javascript">
	</script> 
	<script charset="utf-8" src="template/chosen/docsupport/prism.js" type="text/javascript">
	</script> 
	<script charset="utf-8" src="template/chosen/docsupport/init.js" type="text/javascript">
	</script> <?php include 'template/footer.php'; ?> <?php include 'template/core.php'; ?>
</body>
</html>