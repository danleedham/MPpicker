<!DOCTYPE html>
<html lang="en">
<head>
	<script src="http://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="google" value="notranslate">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<title>Wrapups Lists</title>

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

<script>
	function loadmembers(eventid,section){
		document.getElementById('togglemenu').style.display = 'none';
		document.getElementById('loader').style.display = 'inline';
		console.log('Loading list for program '+location+' and section: '+section);
		if (!document.getElementById("removedupes-input").checked){
			var dupes = 'remove';
		} else {
			var dupes = 'keep';
		}
		$("#wrapups").load('template/wind-list.php?&event='+eventid+'&section='+section+'&keepdupes='+dupes,function() {
   			document.getElementById('loader').style.display = 'none';
   			document.getElementById('togglemenu').style.display = 'inline';
			$("#currentspeakersdiv").load('template/wind-checknew.php?event='+eventid+'&section='+section+'&keepdupes='+dupes);
			console.log('template/wind-list.php?&event='+eventid+'&section='+section+'&keepdupes='+dupes);
   		});
	}
	function loadevents(date){
	   $("#event-input").load('template/wind-events.php?date='+date);
	   $("#sect-input").load('template/wind-chapters.php?date='+date);
	   console.log('Loading events for: '+date);
	}
	function loadsections(){
	   var date = document.getElementById("date-input").value;
	   var event = encodeURI(document.getElementById("event-input").value);
	   $("#sect-input").load('template/wind-chapters.php?date='+date+'&event='+event);
	   console.log('Loading events for: '+date+' to '+event);
	}
	
	function checkformembers(){
		var eventid = document.getElementById('event-input').value;
		var section = document.getElementById('sect-input').value;
		if (!document.getElementById("removedupes-input").checked){
			var dupes = 'remove';
		} else {
			var dupes = 'keep';
		}
		var currentcount = document.getElementById("currentspeakers").value;
		$("#countspeakersdiv").load('template/wind-checknew.php?event='+eventid+'&section='+section+'&keepdupes='+dupes+'&id=countspeakers', function() {
			var newcount = document.getElementById("countspeakers").value;
			console.log('New Count = '+newcount+' Old Count = '+currentcount);
			if(newcount > currentcount) {
				loadmembers(eventid,section);
				console.log('New member logged... reloading list');
			} else {
				// console.log('No new members logged...');
			}	
		});	
		setTimeout(checkformembers, 10000);
	}
	// wait for 5 seconds after page load then go check for members
	window.onload = function () {
		setTimeout(function () {
			checkformembers(); 
		}, 5000);
	}
	
	function togglemenu(){
		var menu = document.getElementById("menu");
		menu.style.display = menu.style.display === 'none' ? '' : 'none';
		var h = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
		var listsize = h - 196;
		console.log('Removing Menu and Resizing list to '+listsize);
	}
	function togglemobilelist(){
		var list = document.getElementById("list");
		list.style.display = list.style.display === 'none' ? 'block' : 'none';
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
									<div id="date-div" class="col-sm-6">
											<input id="date-input" type="date" class="input-sm form-control" onchange="loadevents(this.value)" value="<?php echo $date ?>" name="date" >	
									</div>
									<div id="photos-div" class="col-sm-6">
											<input id="photos-input" class="pull-right" style="float:right !important;" type="checkbox" value="screenshot" name="photos"  data-toggle="toggle" data-onstyle="danger" data-offstyle="success" data-on="ScreenShot" data-width="100%" data-off="Stock">
									</div>
								</div>	
								<div class="form-inline" style="padding-top:6px !important;">
									<label for="event-input">Event:</label><br />
									<select id="event-input" onchange="loadsections()" name="type" class="form-control">
										<?php include 'template/wind-events.php'; 
											$event = $Events[0]['id']; ?>
									</select>			
								</div>
								<div class="form-inline" style="padding-top:6px !important;">
									<label for="sect-input">Section:</label><br />
									<select id="sect-input" name="type" class="form-control">
										<?php include 'template/wind-chapters.php' ?>
									</select>			
								</div>
								<div id="currentspeakersdiv" style="display:none"><input type="number" id="currentspeakers" value="0"></div>
								<div id="countspeakersdiv" style="display:none"><input type="number" id="countspeakers" value="0"></div>
							</div>
							<div id="loadbuttons" style="padding-top:6px !important;">
								<div class="col-sm-4" style="padding-left:0px !important; padding-right:6px !important;">
									<a href="#" onclick="loadmembers(encodeURI(document.getElementById('event-input').value),encodeURI(document.getElementById('sect-input').value));return false;" class="btn btn-success" style="width:100%;" role="button">
									Get List</a>
								</div>
								<div class="col-sm-4" style="padding-left:6px !important; padding-right:6px !important;">
									<input id="removedupes-input" style="float:right !important;" type="checkbox" value="keep" name="photos"  data-toggle="toggle" data-onstyle="warning" data-offstyle="success" data-on="Keep" data-width="100%" data-off="Remove">
								</div>
								<div class="col-sm-4" style="padding-left:6px !important; padding-right: 0px !important;">
									<span id="loader" class="pull-right" style="display:none; padding-top: 6px !important; padding-bottom: 6px; !important">
											<i class="fa fa-refresh fa-spin" class="pull-right" style="font-size:20px"></i>
										</span>
									<span id="togglemenu"><a href="#" onclick="togglemenu();return false;" class="btn btn-info hidemobile" style="display: block;" role="button">
									Toggle</a></span>
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
