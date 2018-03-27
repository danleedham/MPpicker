<?php $xmlDoc=new DOMDocument(); ?>
    <?php include 'template/core/header.php'; ?>
	<title>Who's that?</title>
	
	<script>
	window.onload = function() {
		wholoadcommitteelists();
		wholoaddepartmentlists();
		whotopics();
	};
  	</script>
</head>

<body>
	<div class="container-fluid bootcards-container push-right">
		<div class="row">
			<!-- Search panel -->
			<!--options column-->
			<div class="col-sm-3 bootcards-list dontPrint" data-title="Contacts" id="list">
				<div class="panel panel-default">
					<div class="panel-heading clearfix">
						<h3 class="panel-title">
							<span onclick="whohidemenu();return false;" style="float:right;">
								<i aria-hidden="true" class="fa fa-bars"></i>
							</span> 
								Use the search tools below 
							<span id="loader" style="display:none;">
								<i class="pull-right" style="font-size:20px"></i>
							</span>
						</h3>
					</div>
					<div class="list-group">
						<!-- start search form -->
						<!-- house -->
						<div class="list-group-item">
							<select class="form-control custom-select mb-2 mr-sm-2 mb-sm-0" form="mpsearch" id="house-input" name="house" 
								onchange="wholoadsex(); wholoadpartiesjusthouse(); updatelists();">
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
								</option><?php $house = "Commons" ?><?php include("template/who-listsex.php"); ?>
							</select>
						</div>
						<!-- party -->

						<div class="list-group-item">
							<select class="form-control custom-select mb-2 mr-sm-2 mb-sm-0" id="party-input">
								<?php include("template/who-listparty.php"); ?>
							</select>
						</div>
						<!-- positions -->


						<div class="list-group-item">
							<select class="form-control custom-select mb-2 mr-sm-2 mb-sm-0" form="mpsearch" id="position-input" name="positions">
								<option value="">
									Select Position Type
								</option><?php  include("template/who-listpositions.php"); ?>
							</select>
						</div>
						<!-- committees -->
						<div class="list-group-item">
							<div id="whocommittee">	
     							<select class="form-control custom-select mb-2 mr-sm-2 mb-sm-0">
     								<option value="">Loading Committees...</option>
     							</select>
							</div>
						</div>
						
						<!-- departments -->
						<div class="list-group-item">
							<div id="whodepartment">
								<select class="form-control custom-select mb-2 mr-sm-2 mb-sm-0">
     								<option value="">Loading Departments...</option>
     							</select>	
							</div>
						</div>
						
						<!-- interests -->
					<div class="list-group-item">
							<div id="whotopics">
								<select class="form-control custom-select mb-2 mr-sm-2 mb-sm-0">
     								<option value="">Loading Interest Topics...</option>
     							</select>	
							</div>
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
							<a class="btn btn-success" href="#" onclick="wholoadresults();return false;" role="button">Hit me up</a> <a class="btn btn-danger" href="#" onclick="window.location.reload()" role="button">Reset</a>
						</div>


						<div class="list-group-item">
							Toggle:<br>
							<a class="btn btn-warning" href="#" onclick="whohideparty();return false;" role="button">Party</a> <a class="btn btn-warning" href="#" onclick="whohideconst();return false;" role="button">Const</a> <a class="btn btn-warning" href="#" onclick="whohidejobs();return false;" role="button">Jobs</a>
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
	<script src="template/chosen/chosen.jquery.js" type="text/javascript">
	</script> 
	<script charset="utf-8" src="template/chosen/docsupport/init.js" type="text/javascript">
	</script> 
	
	<?php include 'template/core/footer.php'; ?> 
	<?php include 'template/core/includejs.php'; ?>
</body>
</html>
