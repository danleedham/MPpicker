<!DOCTYPE html>
<html lang="en">
<head>
	<script src="http://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="google" value="notranslate">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<title>Questions Picker</title>

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
	function load(num,date){
		document.getElementById('togglemenu').style.display = 'none';
		document.getElementById('loader').style.display = 'inline';
		if (!document.getElementById("photos-input").checked){
			var photos = 'Stock';
		} else {
			var photos = "screenshot";
		}
		var next = document.getElementById('next'+num).value;
		var prev = document.getElementById('prev'+num).value;
		console.log('Loading question: '+num+' next: '+next+' prev: '+prev);
		$("#contactCard").load('template/questioner.php?uin='+num+'&date='+date+'&photos='+photos+'&next='+next+'&prev='+prev,function() {
			document.getElementById('loader').style.display = 'none';
			document.getElementById('togglemenu').style.display = 'inline';
		});
		$('.active').removeClass('active');
		$('#q'+num).addClass("active");
	}
	function loadlords(id){
		document.getElementById('togglemenu').style.display = 'none';
		document.getElementById('loader').style.display = 'inline';
		if (!document.getElementById("photos-input").checked){
			var photos = 'Stock';
		} else {
			var photos = "screenshot";
		}
		console.log('Loading Lords Member: '+id);
		$("#contactCard").load('template/member.php?m='+id,function() {
			document.getElementById('loader').style.display = 'none';
			document.getElementById('togglemenu').style.display = 'inline';
		});
		$('.active').removeClass('active');
		$('#q'+id).addClass("active");
	}
	function loadquestions(date,dept,type){
		document.getElementById('togglemenu').style.display = 'none';
		document.getElementById('loader').style.display = 'inline';
		if (!document.getElementById("together-input").checked){
			var together = "together";
		} else {
			var together = "dont";
		}
		var groups = document.getElementById("groups-input").value;
		var withdrawn = document.getElementById("withdrawn-input").value;
		var withoutnotice = document.getElementById("withoutnotice-input").value;
		console.log('Loading '+type+' questions to '+dept+' on '+date+' using groups: '+groups+' and withdrawing (day): '+withdrawn+' withdrawing (before): '+withoutnotice+' grouped: '+together);
		groups = groups.replace(/[\r\n]+/g,",");
		groups = encodeURI(groups);
		withdrawn = encodeURI(withdrawn);
		$("#livesearch").load('template/listquestions.php?date='+date+'&type='+type+'&dept='+dept+'&groups='+groups+'&withdrawn='+withdrawn+'&withoutnotice='+withoutnotice+'&together='+together,function() {
			document.getElementById('loader').style.display = 'none';
			document.getElementById('togglemenu').style.display = 'inline';
   		});
	}
	function loadlordsquestions(date){
		document.getElementById('togglemenu').style.display = 'none';
		document.getElementById('loader').style.display = 'inline';
		$("#livesearch").load('template/listlordsquestions.php?date='+date,function() {
			document.getElementById('loader').style.display = 'none';
			document.getElementById('togglemenu').style.display = 'inline';
		});
		
	}
	function loaddepts(date){
	   $("#dept-input").load('template/questiondepts.php?date='+date);
	   $("#type-input").load('template/questiontypes.php?date='+date);
	   console.log('Loading departments for: '+date);
	}
	function loadtypes(){
	   var date = document.getElementById("date-input").value;
	   var dept = encodeURI(document.getElementById("dept-input").value);
	   $("#type-input").load('template/questiontypes.php?date='+date+'&dept='+dept);
	   console.log('Loading Question Types for: '+date+' to '+dept);
	}
	function gotopicals(){
	   document.getElementById("type-input").value = 'Topical';
	   var date = document.getElementById("date-input").value;
	   date = date.toString();
	   var dept = document.getElementById("dept-input").value;
	   dept = encodeURI(dept);
	   var groups = document.getElementById("groups-input").value;
	   var withdrawn = document.getElementById("withdrawn-input").value;
	   var withoutnotice = document.getElementById("withoutnotice-input").value;
	   console.log('Loading Topical questions to '+dept+' on '+date+' and withdrawing: '+withdrawn);
	   groups = groups.replace(/[\r\n]+/g,",");
	   groups = encodeURI(groups);
	   withdrawn = encodeURI(withdrawn);
	   $("#livesearch").load('template/listquestions.php?date='+date+'&type=Topical&dept='+dept+'&groups='+groups+'&withdrawn='+withdrawn+'&withoutnotice='+withoutnotice);
	}
	function gosubstantive(){
	   document.getElementById("type-input").value = 'Substantive';
	   var date = document.getElementById("date-input").value;
	   date = date.toString();
	   var dept = document.getElementById("dept-input").value;
	   dept = encodeURI(dept);
	   var groups = document.getElementById("groups-input").value;
	   var withdrawn = document.getElementById("withdrawn-input").value;
	   var withoutnotice = document.getElementById("withoutnotice-input").value;
	   console.log('Loading Substantive questions to '+dept+' on '+date+' using groups: '+groups+' and withdrawing: '+withdrawn);
	   groups = groups.replace(/[\r\n]+/g,",");
	   groups = encodeURI(groups);
	   withdrawn = encodeURI(withdrawn);
	   $("#livesearch").load('template/listquestions.php?date='+date+'&type=Substantive&dept='+dept+'&groups='+groups+'&withdrawn='+withdrawn+'&withoutnotice='+withoutnotice);
	}
	function togglemenu(){
		var menu = document.getElementById("menu");
		menu.style.display = menu.style.display === 'none' ? '' : 'none';
		var h = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
		var listsize = h - 154;
		console.log('Removing Menu and Resizing list to '+listsize);
		document.getElementById("livesearch").setAttribute("style","height:"+listsize+"px");
		
	}
	function togglemobilelist(){
		var list = document.getElementById("list");
		list.style.display = list.style.display === 'none' ? 'block' : 'none';
	}

	document.onkeydown = checkKey;
	function checkKey(e) {
		e = e || window.event;
		if (e.keyCode == '37') {
			$('.active').removeClass('active');
			document.getElementById('togglemenu').style.display = 'none';
			document.getElementById('loader').style.display = 'inline';
			if (!document.getElementById("photos-input").checked){
				var photos = 'Stock';
			} else {
				var photos = "screenshot";
			}
			var num = document.getElementById("currentuin").value;
			var thisprev = document.getElementById("currentprev").value;
			var date = document.getElementById("date-input").value;
			var next = document.getElementById('next'+thisprev).value	;
			var prev = document.getElementById('prev'+thisprev).value;
			console.log('Loading question: '+thisprev+' next: '+next+' prev: '+prev);
			$("#contactCard").load('template/questioner.php?uin='+thisprev+'&date='+date+'&photos='+photos+'&next='+next+'&prev='+prev,function() {
				document.getElementById('loader').style.display = 'none';
			});
			$('#q'+thisprev).addClass("active");
			document.getElementById('togglemenu').style.display = 'inline';
		}
		else if (e.keyCode == '39') {
			document.getElementById('togglemenu').style.display = 'none';
			$('.active').removeClass('active');
			document.getElementById('loader').style.display = 'inline';
			if (!document.getElementById("photos-input").checked){
				var photos = 'Stock';
			} else {
				var photos = "screenshot";
			}
			var num = document.getElementById("currentuin").value;
			var thisnext = document.getElementById("currentnext").value;
			var date = document.getElementById("date-input").value;
			var next = document.getElementById('next'+thisnext).value;
			var prev = document.getElementById('prev'+thisnext).value;
			console.log('Loading question: '+thisnext+' next: '+next+' prev: '+prev);
			$("#contactCard").load('template/questioner.php?uin='+thisnext+'&date='+date+'&photos='+photos+'&next='+next+'&prev='+prev,function() {
				document.getElementById('loader').style.display = 'none';
				document.getElementById('togglemenu').style.display = 'inline';
			});
			$('#q'+thisnext).addClass("active");
			
	   }
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
				<div class="col-sm-4 bootcards-list" id="list" data-title="Contacts">
					<div class="panel panel-default">
						<div class="panel-body" id="list-inputs">
							<div class="search-form" id="menu">
								<div class="row">
									<div id="date-div" class="col-sm-6">
											<input id="date-input" type="date" class="input-sm form-control" onchange="loaddepts(this.value)" value="<?php echo $date ?>" name="date" >	
									</div>
									<div id="photos-div" class="col-sm-6">
											<input id="photos-input" class="pull-right" style="float:right !important;" type="checkbox" value="screenshot" name="photos"  data-toggle="toggle" data-onstyle="danger" data-offstyle="success" data-on="ScreenShot" data-width="100%" data-off="Stock">
									</div>
								</div>	
								<div class="form-inline" style="padding-top:6px !important;">
									<label for="dept-input">Department:</label><br />
									<select id="dept-input" onchange="loadtypes()" name="type" class="form-control">
										<?php include 'template/questiondepts.php' ?>
									</select>			
								</div>
								<div class="form-inline" style="padding-top:6px !important;">
								   <label for="type-input">Type:</label><br />
								<select id="type-input" name="type" class="form-control" onchange="loadquestions(document.getElementById('date-input').value,encodeURI(document.getElementById('dept-input').value),encodeURI(this.value));return false;">
										Type: <?php include 'template/questiontypes.php' ?>
								</select>
								</div>
							</div>
							<div id="loadbuttons" style="padding-top:6px !important;">
								<div class="col-sm-4" style="padding-left:0px !important; padding-right:6px !important;">
									<a href="#" onclick="loadquestions(document.getElementById('date-input').value,encodeURI(document.getElementById('dept-input').value),encodeURI(document.getElementById('type-input').value));return false;" class="btn btn-success" role="button" style="width: 100% !important;">
									Load</a>
								</div>
								<div class="col-sm-4" style="padding-left:6px !important; padding-right:6px !important;">
									<button type="button" style="width: 100% !important;" class="btn btn-danger" data-toggle="modal" data-target="#groupCard">Set Groups</button>
								</div>
								<div id="search-toggle-div" class="col-sm-4" style="padding-left:6px !important;">
									<span id="loader" class="pull-right" style="display:none; padding-top: 6px !important; padding-bottom: 6px; !important">
										<i class="fa fa-refresh fa-spin" class="pull-right" style="font-size:20px"></i>
									</span>
									<a href="#" id="togglemenu" onclick="togglemenu();return false;" class="btn btn-info hidemobile" style="display: inline; float:right !important; width: 100% !important;" role="button">
									Toggle Top</a>
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
								<a href="#" onclick="loadlordsquestions(document.getElementById('date-input').value);return false;" class="btn btn-danger" role="button">Load Lords Questions for today</a>
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
	<div id="groupCard" class="modal">
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
										<label for="date-input" class="col-2 col-form-label">Enter groups on seperate lines with questions space delimited</label>
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
											<a href="#" onclick="loadquestions(document.getElementById('date-input').value,encodeURI(document.getElementById('dept-input').value),encodeURI(document.getElementById('type-input').value));return false;" class="btn btn-info" role="button">
											Set Groups & Withdrawn</a>
											<input id="together-input" style="float:right !important;" type="checkbox" value="grouped" name="together-input"  data-toggle="toggle" data-onstyle="danger" data-offstyle="warning" data-on="Don't Group" data-off="Grouped">
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

	<?php include 'template/footer.php'; ?>  
	<?php include 'template/core.php'; ?>
   
</body>
</html>
