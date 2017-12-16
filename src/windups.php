<?php include 'template/headinc.php';
	//get parameters from URL
	if(isset($_GET["date"])){
		$date=$_GET["date"];
	}
	if (!isset($date)) {
		$date = date("Y-m-d");
	}
	if (isset($_GET["house"])){
		$house = $_GET["house"];
	}
?>

	<title>Wrapups Lists</title>

	<script>
		// wait for 5 seconds after page load then go check for members
		window.onload = function () {
			setTimeout(function () {
				windcheckformembers(); 
			}, 5000);
		}
	</script>

</head>

<body>		
	<div class="container-fluid bootcards-container push-right">
		<div class="row">
			<!-- Mobile Menu -->	
				<div class="panel-body" id="mobilemenu">
					<a href="#" onclick="togglemobilelist();return false;" class="btn btn-warning" role="button">
					Toggle List</a>
				</div><!--panel body-->
				<!-- left list column -->
				<div class="col-sm-12 bootcards-list" id="list" data-title="Contacts" style="padding-bottom: 12px;">
					<div class="search-form" id="menu">
						<div class="row">
							<div id="date-div" class="col-sm-2">
								<input id="date-input" type="date" class="input-sm form-control" onchange="windloadevents(this.value)" value="<?php echo $date ?>" name="date" >	
							</div>
							<div class="col-sm-3">
								<select id="event-input" onchange="windloadsections()" name="type" class="form-control">
									<?php include 'template/wind-events.php'; 
										$event = $Events[0]['id']; ?>
								</select>			
							</div>
							<div class="col-sm-7">
								<select id="sect-input" name="type" class="form-control">
									<?php include 'template/wind-chapters.php' ?>
								</select>			
							</div>

						</div>	
						<div id="currentspeakersdiv" style="display:none"><input type="number" id="currentspeakers" value="0"></div>
						<div id="countspeakersdiv" style="display:none"><input type="number" id="countspeakers" value="0"></div>
					</div>
					<div id="loadbuttons" class="row" style="padding-top:6px !important;">
						<div class="col-sm-3">
							<a href="#" onclick="windloadmembers(encodeURI(document.getElementById('event-input').value),encodeURI(document.getElementById('sect-input').value));return false;" class="btn btn-success" style="width:100%;" role="button">
							Get List</a>
						</div>
						<div id="photos-div" class="col-sm-3">
								<input id="photos-input" class="pull-right" style="float:right !important;" type="checkbox" value="screenshot" name="photos"  data-toggle="toggle" data-onstyle="danger" data-offstyle="success" data-on="ScreenShot" data-width="100%" data-off="Stock">
						</div>
						<div class="col-sm-3">
							<input id="removedupes-input" style="float:right !important;" type="checkbox" value="keep" name="photos"  data-toggle="toggle" data-onstyle="warning" data-offstyle="success" data-on="Keep" data-width="100%" data-off="Remove">
						</div>
						<div class="col-sm-3">
							<span id="loader" class="pull-right" style="display:none; padding-top: 6px !important; padding-bottom: 6px; !important">
								<i class="fa fa-refresh fa-spin" class="pull-right" style="font-size:20px"></i>
							</span>
							<span id="togglemenu">
							<a href="#" onclick="togglemenu();return false;" class="btn btn-info hidemobile" style="display: block;" role="button">Toggle</a>
							</span>
						</div>	
					</div>
				</div>
				<div class="col-sm-12">
					<div class="panel panel-default wrapup-panel">		
						<div class="list-group" id="wrapups">
						</div><!--list-group-->
					</div><!--panel-->
				</div><!--list-->
		</div><!--row-->
	</div><!--container-->

	<?php include 'template/footer.php'; ?>

  
   <?php include 'template/core.php'; ?>
   
  </body>
</html>
