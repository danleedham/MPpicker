	<?php include 'template/core/header.php'; ?>
	
	<title>Parliamentary Stacker</title>
	
	<?php     
	        $xml=simplexml_load_file("http://data.parliament.uk/membersdataplatform/services/mnis/members/query/id=8/FullBiog");
	        $feed = file_get_contents("template/betaimages.xml");
	        $betaimages = simplexml_load_string($feed);
	        $imagescount =  count($betaimages);
	        
	?>
		<script>
	window.onload = function() {
		pickconst();
		pickpos();
	};

  	</script>
</head>

<body>
	<div class="container-fluid bootcards-container push-right">
		<div class="row">
			<!--panel body-->


			<div id="mobilemenu">
				<div class="panel-body">
					<a class="btn btn-warning" href="#" onclick="searchtogglemobilelist();return false;" role="button">Toggle Search</a>
				</div>
				<!--panel body-->
			</div>
			<!--panel-->
			<!-- left list column -->
			<div class="col-sm-4 bootcards-list" data-title="Contacts" id="list">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="search-form">	
							<div class="col-sm-12 input-toggle">
								<div class="form-group">
									<select class="form-control custom-select" form="mpsearch" id="searchby" name="searchby" onchange="searchchangesearchby();">
										<option value="name">
											Name
										</option>

										<option value="constituency">
											Constituency
										</option>

										<option value="position">
											Position
										</option>
									</select>
								</div>
							</div>
							<div class="col-sm-12 input-toggle" id="typeinput">
								<div class="form-group">
									<input class="form-control" form="mpsearch" name="q" onkeyup="searchshowResult(this.value)" placeholder="Start Typing..." size="20" type="text">
								</div>
							</div>
							<div class="col-sm-12 input-toggle" id="constpickdiv" style="display: block;">
								<div id="pick-const" class="form-group">
									<select class="form-control custom-select mb-2 mr-sm-2 mb-sm-0">
										<option value="">Loading Constituencies...</option>
									</select>	
								</div>
							</div>	
							<div class="col-sm-12 input-toggle" id="positiontypediv" style="display: none";>
								<div class="form-group">
									<select class="form-control custom-select" form="mpsearch" id="positiontype" name="positiontype" onchange="searchselectpostyle(this.value)">
										<option selected="selected" value="government">
											Government
										</option>

										<option value="opposition">
											Opposition
										</option>

										<!-- <option value="both">
											All Parties
										</option> -->
									</select>
								</div>
							</div>			
						</div>
						<div class="col-sm-12 input-toggle" id="pospickdiv" style="display: block;">
								<div id="pick-pos" class="form-group">
									<select class="form-control custom-select mb-2 mr-sm-2 mb-sm-0">
										<option value="">Loading Positions...</option>
									</select>	
								</div>
							</div>		
						<div class="search-form" style="padding-top: 6px">	
							<div class="col-sm-6 membersearch-options input-toggle">
								<input data-off="Commons" data-offstyle="success" data-on="Lords" data-onstyle="danger" data-toggle="toggle" data-width="100%" id="choosehouse" name="house" type="checkbox" value="Lords">
							</div>

							<div class="col-sm-6 membersearch-options input-toggle">
								<input data-off="Stock" data-offstyle="primary" data-on="ScreenShot" data-onstyle="warning" data-toggle="toggle" data-width="100%" id="photos" name="photos" type="checkbox" value="screenshot">
							</div>
						</div>
					</div>

					<div class="list-group" id="livesearchmember">
						
					</div>
					<!--list-group-->
					<!-- <div class="panel-footer">
            <small class="pull-left">This section auto-populates by magic (and JavaScript).</small>
            <a class="btn btn-link btn-xs pull-right" href="data.parliament.uk/membersdataplatform/">
              PDS Live Data</a>
          </div> -->
				</div>
				<!--panel-->
			</div>
			<!--list-->
			<!--list details column-->


			<div class="col-sm-8 bootcards-cards">
				<!--contact details -->


				<div class="stacker-contactCard" id="contactCard">
					<?php require("template/search-member.php"); ?>
				</div>
				<!--contact card-->
			</div>
			<!--list-details-->
		</div>
		<!--row-->
	</div>
	<!--container-->
	<?php include 'template/core/footer.php'; ?>
	<?php include 'template/core/includejs.php'; ?>
	<script src="template/chosen/chosen.jquery.js" type="text/javascript"></script> 
	<script charset="utf-8" src="template/chosen/docsupport/init.js" type="text/javascript"></script> 
</body>
</html>