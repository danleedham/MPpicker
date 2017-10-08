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
	function load(member){
		document.getElementById('loader').style.display = 'inline';
		if (!document.getElementById("photos-input").checked){
			var photos = 'Stock';
		} else {
			var photos = "screenshot";
		}
		if (!document.getElementById("removedupes-input").checked){
			var dupes = 'keep';
		} else {
			var dupes = "remove";
		}
		console.log('Loading member: '+member);
		$("#contactCard").load('template/member.php?m='+member+'&photos='+photos+'&keepdupes='+dupes,function() {
			document.getElementById('loader').style.display = 'none';
		});
		$('.active').removeClass('active');
		$('#m'+num).addClass("active");
	}
	function loadmembers(location,section){
		console.log('Loading list for program '+location+' and section: '+section);
		$("#livesearch").load('template/wrap-list.php?&location='+location+'&section='+section,function() {
   		document.getElementById('loader').style.display = 'none';
   		});
	}
	function loadlocs(date){
	   $("#loc-input").load('template/wrap-location.php?date='+date);
	   $("#sect-input").load('template/wrap-sections.php?date='+date);
	   console.log('Loading locations for: '+date);
	}
	function loadsections(){
	   var date = document.getElementById("date-input").value;
	   var element = encodeURI(document.getElementById("loc-input").value);
	   $("#sect-input").load('template/wrap-sections.php?date='+date+'&element='+element);
	   console.log('Loading locations for: '+date+' to '+element);
	}
	function togglemenu(){
		var menu = document.getElementById("menu");
		menu.style.display = menu.style.display === 'none' ? '' : 'none';
		var h = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
		var listsize = h - 165;
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
		}
		else if (e.keyCode == '39') {
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
						<div class="panel-body">
							<div class="search-form" id="menu">
								<div class="form-inline">
									<input id="date-input" type="date" class="input-sm form-control" onchange="loadlocs(this.value)" value="<?php echo $date ?>" name="date" >	
									<input id="photos-input" style="float:right !important;" type="checkbox" value="screenshot" name="photos"  data-toggle="toggle" data-onstyle="danger" data-offstyle="success" data-on="ScreenShot" data-off="Stock">
								<span id="loader" style="display:none;">
									<i class="fa fa-refresh fa-spin" class="pull-right" style="font-size:20px"></i>
								</span>
								</div>
								<div class="form-inline" style="padding-top:6px !important;">
									<label for="loc-input">Location:</label><br />
									<select id="loc-input" onchange="loadsections()" name="type" class="form-control">
										<?php include 'template/wrap-location.php' ?>
									</select>			
								</div>
								<div class="form-inline" style="padding-top:6px !important;">
									<label for="sect-input">Section:</label><br />
									<select id="sect-input" name="type" class="form-control">
										<?php include 'template/wrap-sections.php' ?>
									</select>			
								</div>
							</div>
							<div class="form-inline" id="loadbuttons" style="padding-top:6px !important;">
								<a href="#" onclick="loadmembers(encodeURI(document.getElementById('loc-input').value),encodeURI(document.getElementById('sect-input').value));return false;" class="btn btn-success" role="button">
								Get wrapups</a>
								<input id="removedupes-input" style="float:right !important;" type="checkbox" value="keep" name="photos"  data-toggle="toggle" data-onstyle="warning" data-offstyle="success" data-on="Keep Dupes" data-off="Bin Dupes">
								<a href="#" onclick="togglemenu();return false;" class="btn btn-info hidemobile" style="float:right !important;" role="button">
								Toggle Search</a>
							</div>
						</div><!--panel body-->
						
						<div class="list-group" id="livesearch">
						
						</div><!--list-group-->

						<div class="panel-footer">
							<small class="pull-left">This section auto-populates by magic (and php).</small>
							<a class="btn btn-link btn-xs pull-right" href="http://data.parliament.uk/membersdataplatform/">Live Data</a>
						</div>
					</div><!--panel-->

				</div><!--list-->

			<!--list details column-->
			<div class="col-sm-8 bootcards-cards">

          <!--contact details -->
          <div id="contactCard">
             <div class="panel panel-default">
              <div class="panel-heading clearfix">
                <h3 class="panel-title pull-left">Select section to wrap up</h3> 
		 	  </div>
		 	  <div class="list-group">
                <div class="list-group-item">
					<div class="search-form">
						<div class="form-group">	
							<div class="col-12">
								<p>Use the tools on the left and MP details will appear here. </p>
								<p><a href="https://www.parliament.uk/documents/commons-table-office/Oral-questions-rota.pdf">Click here to view the Oral Questions Rota (external).</a href></p>
							</div>
						</div>
					</div>
                </div>
              </div>
		 	  <div class="panel-footer">
                  <small>Data from UK Parliament - <a href="http://data.parliament.uk/membersdataplatform/">Members' Names Data Platform</a></small>
                </div>
              </div>
		</div>

      </div><!--list-details-->

    </div><!--row-->


  </div><!--container-->

	<?php include 'template/footer.php'; ?>

  
   <?php include 'template/core.php'; ?>
   
  </body>
</html>
