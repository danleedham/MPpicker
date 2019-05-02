<?php 
$xmlQuestions=new DOMDocument();
$xmlMembers=new DOMDocument();

	//get parameters from URL
	if(!isset($uin) && isset($_GET["uin"])) {
		$Getuin=$_GET["uin"];
	}
	
	if(isset($_GET["next"])) {
		$next=$_GET["next"];
	} else  { 
		$next = $Getuin;
	}
	if(isset($_GET["prev"])) {
		$prev=$_GET["prev"];
	} else  { 
		$prev = $Getuin;
	}
	if(isset($_GET["date"])) {
		$date=$_GET["date"];
	}
	if(!isset($date)) {
		$date=$date = date("Y-m-d");
	}
	$house = "Commons";
		
	if(!isset($photos) && isset($_GET["photos"])){
		$photos=$_GET["photos"];
	}

	//Load quesetions with specified UIN & Date. Should return just a single question.
	include('qs-futuredayorals-questionlist.php');
	
	// Grabs qarray from the include above and continue
	
	$hint="";
    
	// Count how many questions there are
	if(isset($qarray)){
		$length = count($qarray);
	}
		
	for($i=0; $i<$length; $i++){
	    $currentuin = $qarray[$i]["uin"];
        if(intval($currentuin) == intval($Getuin)){
	        $Findi = $i;
	        $PullThisMember = intval($qarray[$i]["MemberId"]);
		}
	}

	// Now load the data for the currently selected member. 
		   $xml=simplexml_load_file("http://data.parliament.uk/membersdataplatform/services/mnis/members/query/id=".$PullThisMember."/FullBiog") or die("Cannot Load Question ".$currentuin." on ".$date);

	?>

	   <div class="panel panel-default">
			<div class="panel-heading clearfix">
				<h3 class="panel-title pull-left">
				<?php 
					if(isset($qarray)) :  ?>
					<span class="partybox-large" style="background:                  
					<?php  $PartyID = $xml->Member[0]->Party[0]->attributes()->Id;              	          	     
						 echo $colors[intval($PartyID)];
					?>"></span>
					<?php echo $xml->Member[0]->DisplayAs;
					echo ' - ';
					echo $xml->Member[0]->MemberFrom ?>
					</h3>
						<input type="hidden" id="currentuin" value="<?php echo $Getuin; ?>">
						<input type="hidden" id="currentnext" value="<?php echo $next; ?>">
						<input type="hidden" id="currentprev" value="<?php echo $prev; ?>">
				<a class="btn btn-primary pull-right questioner-button" onclick="futuredayoralsload(<?php echo '\''.$next.'\',\''.$date.'\''; ?>);return false;" href="#" data-toggle="modal">
				  <i class="fa fa-arrow-right"></i>Next
				</a>
				 <a class="btn btn-primary pull-right questioner-button" onclick="futuredayoralsload(<?php echo '\''.$prev.'\',\''.$date.'\''; ?>);return false;" data-toggle="modal">
				  <i class="fa fa-arrow-left"></i>Previous
				</a>
			</div>
			<div class="list-group">
				<div class="list-group-item list-group-item-image">
				<?php 
					$DodsId=$xml->Member[0]->attributes()->Dods_Id;
					$m=$xml->Member[0]->attributes()->Member_Id;
					if (!isset($photos) or $photos  !== "screenshot") {	
                        for($ii=0; $ii < $imagescount; $ii++) {
                            if (intval($betaimages->member[$ii]->memberid) == intval($xml->Member[0]->attributes()->Member_Id)){
                                $BetaId = $betaimages->member[$ii]->imageid;
                            }
                        }
                        if(isset($BetaId) && $BetaId !== "") {
                            $imageurl = 'images/stock/'.$BetaId.'.jpeg';
                        }
                    }
                    if(!isset($imageurl)) {
                        // If the user has asked for a screenshot then...
                        $count = "1";
                        require("latestscreenshot.php");
                        if(isset($screenshotoutput)) {
                            $imageurl = $screenshotoutput['url'];
                            $screenshotused = true;
                            echo '<input id="current-photo" type="hidden" value="'.$screenshotoutput['imagenumber'].'">';
                            echo '<input id="m" type="hidden" value="'.$m.'">';
                        } else {
                            $imageurl = "";
                        }
					}	
				?>
				<img id="questioner-img" src="<?php echo $imageurl; ?>" class="main-question-image">
			</div>
			<div class="list-group-item">
			<label><?php echo $qarray[$Findi]["type"]; ?> Question</label>
			<?php if(isset($screenshotused)): ?>
					 <a href="#" onclick="anotherphoto(document.getElementById('current-photo').value,<?php echo $m ?>);return false;" class="btn btn-info pull-right" role="button">
									 Get us another photo please</a>
			<?php endif; ?>
			
			  
			  <h4 class="list-group-item-heading"><?php echo $qarray[$Findi]["text"]; ?></h4>
			</div>

	<?php 
		else:  
	?>
		<h3> Please use the search tools </h3> 
		</div>
			
	<?php 
		endif;
	?>
	<script src="/js/functions.js"></script>
