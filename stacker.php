<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="google" value="notranslate">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <title>Parliamentary Stacker</title>

  <?php include 'template/headinc.php'; ?>
		<?php

		//get the q parameter from URL
		$m=$_GET["m"];
		$q=$_GET["q"];
		if (!$q){$SearchContactsMessage="Start typing...";}
			else {$SearchContactsMessage=$q;}
		$searchby=$_GET["searchby"];
			if(!$searchby) {$searchby="name";}
		$house=$_GET["house"];
		if (!$house) {$house = "Commons";}
		// Bad fix for if the house of lords is chosen but no member is called
		if ($house == "Lords" && !$m) { $m = "4329";
		  }	
		if (!$m){ $m="8";}	
		$xml=simplexml_load_file("http://data.parliament.uk/membersdataplatform/services/mnis/members/query/id=".$m."/FullBiog") or die("No MP with this id");
		$betaimages =simplexml_load_file("http://leedhammedia.com/parliament/betaimages.xml") or die("Can't load Beta Images");
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
			<form id="mpsearch">
            <div class="search-form">
				<div class="form-group">
				  <input type="text" name="q" form="mpsearch" class="form-control" placeholder="<?php echo $SearchContactsMessage ;?>" size="10" onkeyup="showResult(this.value)">			
				</div>
				<div class="form-group">	
				<input id="choosehouse" <?php if ($house == "Lords") {echo "checked";} ?> type="checkbox" value="Lords" name="house" form="mpsearch" data-toggle="toggle" data-onstyle="danger" data-offstyle="success" data-on="Lords" data-off="Commons">
				<input id="searchby" <?php if ($searchby == "constituency") {echo "checked";} ?> type="checkbox" value="constituency" name="searchby" form="mpsearch" data-toggle="toggle" data-on="Const" data-off="Name">
				<input id="photos" <?php if ($photos == "screenshot") {echo "checked";} ?> type="checkbox" value="screenshot" name="photos" form="mpsearch" data-toggle="toggle" data-onstyle="warning" data-on="ScreenShot" data-off="Stock">
				<a href="#" onclick="load(8);return false;" class="btn btn-info pull-right" role="button">Search</a>
				</div>
            </div>
			</form>	
          </div><!--panel body-->

          <div class="list-group" id="livesearch">
		
		<?php 
         	if (!$q){
				echo file_get_contents("http://leedhammedia.com/parliament/template/initiallist.php?m=".$m."&house=".$house); 
            }
            else {
				$mselected = '&mselected='.$m;
				echo file_get_contents("http://leedhammedia.com/parliament/template/livesearch.php?q=".$q."&house=".$house."&searchby=".$searchby.$mselected.'"'); 
			} 
          ?>  
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

			<?php echo file_get_contents("http://leedhammedia.com/parliament/template/member.php?m=".$m); ?>
	
            </div><!--contact card-->

        </div><!--list-details-->

    </div><!--row-->


  </div><!--container-->

	<?php include 'template/footer.php'; ?>

  
   <?php include 'template/core.php'; ?>
   
  </body>
</html>
