<?php include 'template/core/header.php'; ?>

	<title>Questions Picker</title>

	<?php 
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
	if (!isset($house)) {
		$house = "Commons";
	}
	if(isset($_GET["futuredayorals"])){
	    $futuredayorals=$_GET["futuredayorals"];
	}
	?>

<?php if(isset($futuredayorals) && $futuredayorals == "use"): ?> 
<script>
	window.onload = function() {
		var date = document.getElementById("date-input").value;
		futuredayoralsloaddepts(date);
	};
	document.onkeydown = checkKey;
	function checkKey(e) {
		e = e || window.event;
		if (e.keyCode == '37') {
			var thisprev = document.getElementById("currentprev").value;
			var date = document.getElementById("date-input").value;
			console.log('Previous Question demanded. Loading UIN: '+thisprev);
			futuredayoralsload(thisprev,date)	
		}
		else if (e.keyCode == '39') {
			var thisnext = document.getElementById("currentnext").value;
			var date = document.getElementById("date-input").value;
			console.log('Next Question demanded. Loading UIN: '+thisnext);
			futuredayoralsload(thisnext,date)
	   }
	}
</script>

<?php elseif(isset($house) && $house == "Lords"): ?> 
<script>
	window.onload = function() {
		qsloadlordsquestions();
		qstogglemenu();
	};
</script>

<?php else: ?>
<script>
	window.onload = function() {
		var date = document.getElementById("date-input").value;
		qsloadtypes();
		qsloaddepts(date);
		qsloadsuggestedgroups(date);
	};
	
	document.onkeydown = checkKey;
	function checkKey(e) {
		e = e || window.event;
		if (e.keyCode == '37') {
			var thisprev = document.getElementById("currentprev").value;
			var date = document.getElementById("date-input").value;
			console.log('Previous Question demanded. Loading UIN: '+thisprev);
			qsload(thisprev,date)	
		}
		else if (e.keyCode == '39') {
			var thisnext = document.getElementById("currentnext").value;
			var date = document.getElementById("date-input").value;
			console.log('Next Question demanded. Loading UIN: '+thisnext);
			qsload(thisnext,date)
	   }
	}
</script>
<?php endif; ?>
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
				<div class="col-sm-4 bootcards-list" id="list" data-title="Contacts">
					<div class="panel panel-default">
						<div class="panel-body" id="list-inputs">
							<div class="search-form" id="menu">
								<?php if($house !== "Lords"): ?>
								<div class="row">
									<div id="date-div" class="col-sm-6">
									<?php if(isset($futuredayorals) && $futuredayorals == "use"): ?>
										<input id="date-input" type="date" class="input-sm form-control" onchange="futuredayoralsloaddepts(this.value)" value="<?php echo $date ?>" name="date" >	
									<?php else: ?>
										<input id="date-input" type="date" class="input-sm form-control" onchange="qsloaddepts(this.value)" value="<?php echo $date ?>" name="date" >	
									<?php endif; ?>
									</div>
									<div id="photos-div" class="col-sm-6">
										<input id="photos-input" class="pull-right" style="float:right !important;" type="checkbox" value="screenshot" name="photos"  data-toggle="toggle" data-onstyle="danger" data-offstyle="success" data-on="ScreenShot" data-width="100%" data-off="Stock">
									</div>
								</div>	
								<?php endif; ?>
								
								<div class="form-inline" style="padding-top:6px !important;">
									<?php if($house == "Lords"): ?>
									<select id="sect-input" onchange="qsloadlordsquestions()" name="type" class="form-control">
										<?php include 'template/qs-lordsquestionsections.php' ?>
									</select>	
									<?php else: ?>
									    <?php if(isset($futuredayorals) && $futuredayorals == "use"): ?>
									    <select id="dept-input" name="type" class="form-control">
									    <option value=""> Loading Departments...</option>
									    </select>	
									    <?php else: ?>
									<select id="dept-input" onchange="qsloadtypes()" name="type" class="form-control">
										<option value="">Loading Departments...</option>
									</select>	
									    <?php endif; ?>
									<?php endif; ?>
								</div>
								<?php if($house !== "Lords" && !isset($futuredayorals)): ?>
								<div class="row"  style="padding-top:6px !important;">
									<div class="col-sm-6">
										<select id="type-input" name="type" class="form-control" onchange="qsloadquestions(document.getElementById('date-input').value,encodeURI(document.getElementById('dept-input').value),encodeURI(this.value));return false;">
											<option value="">Loading Types...</option>
										</select>
									</div>
									<div id="livelog-div" class="col-sm-6">
										<input id="uselive" onchange="qsuseliveadvance();" class="pull-right" style="float:right !important;" type="checkbox" value="screenshot" name="photos"  data-toggle="toggle" data-onstyle="danger" data-offstyle="info" data-on="Auto Advance Qs" data-width="100%" data-off="Don't Advance">
									</div>
								</div>		
								<?php endif; ?>
							</div>
							<div id="loadbuttons" style="padding-top:6px !important;">
								<div class="col-sm-4" style="padding-left:0px !important; padding-right:6px !important;">
								<?php if($house == "Lords"): ?>
								<a href="#" onclick="qsloadlordsquestions();return false;" class="btn btn-danger" role="button" style="width: 100% !important;">
									Load Lords</a>
								<?php else: ?>
								    <?php if(isset($futuredayorals) && $futuredayorals == "use"): ?>
								    <a href="#" onclick="futuredayoralsloadquestions(document.getElementById('date-input').value,encodeURI(document.getElementById('dept-input').value));return false;" class="btn btn-success" role="button" style="width: 100% !important;">
									Use Backup</a>
								    <?php else: ?>
								    <a href="#" onclick="qsloadquestions(document.getElementById('date-input').value,encodeURI(document.getElementById('dept-input').value),encodeURI(document.getElementById('type-input').value));return false;" class="btn btn-success" role="button" style="width: 100% !important;">
									Load</a>
								    <?php endif; ?>
								<?php endif; ?>
								</div>
								<?php if($house !== "Lords"): ?>
								<div class="col-sm-4" style="padding-left:6px !important; padding-right:6px !important;">
									<button type="button" style="width: 100% !important;" class="btn btn-warning" data-toggle="modal" data-keyboard="true" data-target="#groupCard">Groups</button>
								</div>
								<?php endif; ?>
								<div id="search-toggle-div" class="col-sm-4" style="padding-left:6px !important;">
									<span id="loader" class="pull-right" style="display:none; padding-top: 6px !important; padding-bottom: 6px; !important">
										<i class="fa fa-refresh fa-spin" class="pull-right" style="font-size:20px"></i>
									</span>
									<a href="#" id="togglemenu" onclick="qstogglemenu();return false;" class="btn btn-info hidemobile" style="display: inline; float:right !important; width: 100% !important;" role="button">
									Toggle Options</a>
								</div>
							</div>
						</div><!--panel body-->
						
						<div class="list-group" id="livesearch">
						
						</div><!--list-group-->

					<!-- <div class="panel-footer" id="list-footer">
							<small class="pull-left">This section auto-populates by magic (and php).</small>
							<a class="btn btn-link btn-xs pull-right" href="http://data.parliament.uk/membersdataplatform/">Live Data</a>
						</div> -->
					</div><!--panel-->

				</div><!--list-->

			<!--list details column-->
			<div id="currentlivequestiondiv" style="display:none"><input type="number" id="currentlivequestion" value="0"></div>
			<div class="col-sm-8 bootcards-cards" id="bootcards-cards">

          <!--contact details -->
          <div id="contactCard">
             <div class="panel panel-default">
              <div class="panel-heading clearfix">
                <h3 class="panel-title pull-left">Please use the search tools </h3> 
		 	  </div>
		 	  <div class="list-group">
                <div class="list-group-item">
					<div class="search-form">
						<div class="form-group">	
							<div class="col-12">
								<p>Use the search tools on the left and MP details will appear here. </p>
								<p><a href="https://www.parliament.uk/documents/commons-table-office/Oral-questions-rota.pdf">Click here to view the Oral Questions Rota (external).</a href></p>
								<a href="#" onclick="printQuestions();return false;" class="btn btn-info" role="button">Print Questions as listed</a>
								<a href="?house=Lords" class="btn btn-danger" role="button">Switch to House of Lords</a>
								<a href="?futuredayorals=use" class="btn btn-success" role="button">Use Backup Questions Data</a>
							</div>
						</div>
					</div>
                </div>
              </div>
           </div>
		</div>
		</div><!--list-details-->
	</div><!--row-->
</div><!--container-->
	
	<!-- Group details -->
	<div id="groupCard" class="modal" tabindex='-1'>
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h3 class="modal-title">Grouped & Withdrawn Questions</h3>
				</div>
				<div class="modal-body">
					<div class="list-group">
						<div class="list-group-item">
							<form id="groups">
								<div class="search-form">
									<div class="form-group">
										<label for="dept-group-input">Department:</label><br />
										<select id="dept-group-input" onchange="setgroups()" name="type" class="form-control">
										<?php include 'template/qs-deptslist.php' ?>
										</select>	
									</div>
									<div class="form-group">	
										<label class="col-2 col-form-label">The system suggests the following groups:</label>
										<div class="col-10">
											<textarea class="form-control" id="suggested-groups" form="groups"></textarea>
										</div>
									</div>
									<div class="form-group">	
										<label class="col-2 col-form-label">Enter groups on seperate lines with questions space delimited</label>
										<div class="col-10">
											<textarea class="form-control" rows="3" id="groups-input" form="groups"></textarea>
										</div>
									</div>
									<div class="form-group">						
										<div class="col-10">
											<label for="withdrawn-input" class="col-2 col-form-label">Withdrawn <strong>on the day</strong> (s1 t1) seperated by spaces</label>
											<input type="text" class="form-control" id="withdrawn-input" form="withdrawn"></input>
											<br />
											<label for="withoutnotice-input" class="col-2 col-form-label">Withdrawn <strong>Before Order Paper Printed</strong> (s1 t1) seperated by spaces</label>
											<input type="text" class="form-control" id="withoutnotice-input" form="withoutnotice"></input>
											<br />
											<div class="col-sm-4" style="padding-left: 0px !important;">
												<?php if(isset($futuredayorals) && $futuredayorals == "use"): ?> <a href="#" onclick="futuredayoralsloadquestions(document.getElementById('date-input').value,encodeURI(document.getElementById('dept-input').value));return false;" style="width:100%" class="btn btn-info" role="button">
												Set Groups</a>
												<?php else: ?>
												<a href="#" onclick="qsloadquestions(document.getElementById('date-input').value,encodeURI(document.getElementById('dept-input').value),encodeURI(document.getElementById('type-input').value));return false;" class="btn btn-info" role="button">
							                    Set Groups</a>
												<?php endif; ?> 
											</div>
											<div class="col-sm-4" style="padding-left: 0px! important; padding-right: 0px !important;">
												<input id="topicals-together" style="float:right !important;" type="checkbox" value="grouped" name="topicals-together"  data-toggle="toggle" data-onstyle="danger" data-offstyle="warning" data-width="100%" data-on="Topicals by Number" data-off="Topicals by Party">
											</div>
											<div class="col-sm-4" style="padding-right: 0px !important;">
												<input id="together-input" style="float:right !important;" type="checkbox" value="grouped" name="together-input"  data-toggle="toggle" data-onstyle="danger" data-offstyle="warning" data-width="100%" data-on="Don't reorders" data-off="Reorder by groups">
											</div>
											<br />
										</div>
									</div>
								</div>
							</form>	
						</div>        
					</div>
				</div> <!-- Modal Body -->
			</div><!-- Modal Content -->
		</div><!-- Modal Dialog -->
	</div><!--Group / withdrawn card-->

	<?php include 'template/core/footer.php'; ?>  
	<?php include 'template/core/includejs.php'; ?>
   
</body>
</html>
