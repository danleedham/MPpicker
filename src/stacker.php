<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="google" value="notranslate">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <title>Parliamentary Stacker</title>

  <?php include 'template/headinc.php';
  	
		$xml=simplexml_load_file("http://data.parliament.uk/membersdataplatform/services/mnis/members/query/id=8/FullBiog");
		$feed = file_get_contents("betaimages.xml");
		$betaimages = simplexml_load_string($feed);
	    $imagescount =  count($betaimages);
?>

<!-- Here's the script that *should* get the relevant members from the search. Note search string must be greater than 2 -->		
		<script>
			function showResult(str) {
			
			  if (!document.getElementById("choosehouse").checked){
				 var house = "Commons";
				}
				 else {
				 var house = "Lords";
				 }
			  if (!document.getElementById("searchby").checked){
				 var searchby = "name";
				}
				 else {
				 var searchby = "constituency";
				 } 
			  if (str.length<=2) { 
				document.getElementById("livesearch").innerHTML="";
				document.getElementById("livesearch").style.border="0px";
				return;
			  }
			  if (window.XMLHttpRequest) {
				// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			  } else {  // code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			  }
			  xmlhttp.onreadystatechange=function() {
				if (this.readyState==4 && this.status==200) {
				  document.getElementById("livesearch").innerHTML=this.responseText;
				}
			  }
			  xmlhttp.open("GET","template/livesearch.php?house="+house+"&searchby="+searchby+"&q="+str,true);
			  xmlhttp.send();
			}
			
			function load(id){
				if (!document.getElementById("photos").checked){
				 var photos = 'Stock';
				}
				else { 
				var photos = document.getElementById("photos").value; 
				}
				if (!document.getElementById("searchby").checked){
				 var searchby = 'name';
				}	
			   $("#contactCard").load('template/member.php?m='+id+'&photos='+photos);
			   $('.active').removeClass('active');
			   $('#m'+id).addClass("active");
			}
			</script>
						
</head>

<body>

   <div class="container-fluid bootcards-container push-right">

    <div class="row">

      <!-- left list column -->
      <div class="col-sm-4 bootcards-list" id="list" data-title="Contacts">
        <div class="panel panel-default">       
          <div class="panel-body">
            <div class="search-form">
            	<div class="col-sm-9 input-toggle">
					<div class="form-group">
					  <input type="text" name="q" form="mpsearch" class="form-control" placeholder="Start Typing..." size="10" onkeyup="showResult(this.value)">			
					</div>
				</div>
				<div class="col-sm-3" style="padding-left: 2px !important; padding-right: 2px !important;">
					<a href="#" onclick="load(8);return false;" class="btn btn-block btn-info col-sm-12 pull-right" role="button">Search</a>
				</div>
				<div class="col-sm-4 input-toggle">	
					<input id="choosehouse" type="checkbox" value="Lords" name="house" data-toggle="toggle" data-onstyle="danger" data-offstyle="success" data-on="Lords" data-off="Commons">
				</div>
				<div class="col-sm-4 input-toggle">		
					<input id="searchby" type="checkbox" value="constituency" name="searchby" data-toggle="toggle" data-onstyle="warning" data-offstyle="primary" data-on="Const" data-off="Name">
				</div>
				<div class="col-sm-4 input-toggle" >		
					<input id="photos" type="checkbox" value="screenshot" name="photos" data-toggle="toggle" data-onstyle="warning" data-offstyle="primary" data-on="ScreenShot" data-off="Stock">
				</div>
            </div>
	          </div><!--panel body-->

          <div class="list-group" id="livesearch">
		
		<?php require ("template/initiallist.php"); ?>
          </div><!--list-group-->

          <div class="panel-footer">
            <small class="pull-left">This section auto-populates by magic (and JavaScript).</small>
            <a class="btn btn-link btn-xs pull-right" href="data.parliament.uk/membersdataplatform/">
              PDS Live Data</a>
          </div>
        </div><!--panel-->

        </div><!--list-->

        <!--list details column-->
        <div class="col-sm-8 bootcards-cards">

          <!--contact details -->
          <div id="contactCard">

			<?php require("template/member.php"); ?>
	
            </div><!--contact card-->

        </div><!--list-details-->

    </div><!--row-->


  </div><!--container-->

	<?php include 'template/footer.php'; ?>

  
   <?php include 'template/core.php'; ?>
   
  </body>
</html>
