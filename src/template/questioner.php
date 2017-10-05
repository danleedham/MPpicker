<?php 
$xmlQuestions=new DOMDocument();
$xmlMembers=new DOMDocument();

	//get parameters from URL
	if(!isset($uin) && isset($_GET["uin"])) {
		$uin=$_GET["uin"];
	}
	
	if(isset($_GET["next"])) {
		$next=$_GET["next"];
	} else  { 
		$next = $uin;
	}
	if(isset($_GET["prev"])) {
		$prev=$_GET["prev"];
	} else  { 
		$prev = $uin;
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
	$xmlQuestions->load('http://lda.data.parliament.uk/commonsoralquestions.xml?_view=Commons+Oral+Questions&uin='.$uin.'&AnswerDate='.$date);
	$xQuestions=$xmlQuestions->getElementsByTagName('item');
	$questionscount = $xQuestions->length;
	
	//Load xml with codes for new Parliament Beta images
	$feed = file_get_contents("betaimages.xml");
	$betaimages = simplexml_load_string($feed) or die("Can't load Beta Images");
	$imagescount =  count($betaimages);
	// Arry with party ID and party colors
	require_once('colors.php');
	
	$hint="";
	for($i=0; $i<($questionscount); $i++) {
		$QText=$xQuestions->item($i)->getElementsByTagName('questionText');
		if (!isset($QText[0]->textContent) or $QText[0]->textContent=="") {
		} else {
			$QuestionID=$xQuestions->item($i)->getElementsByTagName('ID');
			$AnswerDate=$xQuestions->item($i)->getElementsByTagName('AnswerDate');
			$MemberId=$xQuestions->item($i)->getElementsByTagName('tablingMemberPrinted');
				$CurrentQuestioner = trim($MemberId->item(0)->textContent);
			$Const=$xQuestions->item($i)->getElementsByTagName('constituency');
				$Constituency = trim($Const['prefLabel']->textContent);
			$TabledDate=$xQuestions->item($i)->getElementsByTagName('TabledDate');
			$QuestionType=$xQuestions->item($i)->getElementsByTagName('QuestionType');
			$DateDue=$xQuestions->item($i)->getElementsByTagName('AnswerDate');
			$BallotNo=$xQuestions->item($i)->getElementsByTagName('ballotNumber');
			$Dept=$xQuestions->item($i)->getElementsByTagName('AnsweringBody');
				$Department=trim($Dept->item(0)->textContent);			

				$tablingMember=$xQuestions->item($i)->getElementsByTagName('tablingMember');
				$tablingMemberId = str_replace("http://data.parliament.uk/members/","",$tablingMember->item(0)->getAttribute('href'));	
				$xmlMembers->load('http://data.parliament.uk/membersdataplatform/services/mnis/members/query/id='.$tablingMemberId);
				$xMembers=$xmlMembers->getElementsByTagName('Member');
				$memberscount = $xMembers->length;
			// backup to search via constituency if the member id method doesn't work
			if($memberscount == 0) {			
				$xmlMembers->load('http://data.parliament.uk/membersdataplatform/services/mnis/members/query/constituency='.$Constituency);
				$xMembers=$xmlMembers->getElementsByTagName('Member');
				$memberscount = $xMembers->length;
			}
			
			for ($y = 0; $y < $memberscount; $y++){
						$DisplayAs=$xMembers->item($y)->getElementsByTagName('DisplayAs');
						$party=$xMembers->item($y)->getElementsByTagName('Party');
						$DodsId=$xMembers->item($y)->getAttribute('Dods_Id');
						$MemberId=$xMembers->item($y)->getAttribute('Member_Id');
						$PartyID=$party->item($y)->getAttribute('Id');           	          	          	     
						$color = $colors[intval($PartyID)];
			}				
			$qarray[] = array('number'=>$BallotNo[0]->textContent,
							  'date'=>$AnswerDate[0]->textContent,
							  'dept'=>$Department,
							  'text'=>$QText[0]->textContent,
							  'type'=>$QuestionType[0]->textContent,
							  'member'=>$CurrentQuestioner,
							  'DisplayAs'=>$DisplayAs[0]->textContent,
							  'DodsId'=>$DodsId,
							  'MemberId'=>$MemberId,
							  'constituency'=>$Constituency,
							  'party'=>$party[0]->textContent,
							  'color'=>$color);		
		}
	}
	
	// Function to sort questions by date
	function compsortqs( $a, $b ) {
		return strtotime($b["date"]) - strtotime($a["date"]);
	}
	// Count how many questions there are
	if(isset($qarray)){
		$length = count($qarray);
	}
		
	// If there are questions, sort the questions
	if (isset($length) && $length !== 0) {
			usort($qarray, 'compsortqs');
		}  
	if (isset($qarray)) {
		$PullThisMember = intval($qarray[0]["MemberId"]);
	
		// Now load the data for the currently selected member. 
		$xml=simplexml_load_file("http://data.parliament.uk/membersdataplatform/services/mnis/members/query/id=".$PullThisMember."/FullBiog") or die("Cannot Load Question ".$uin." on ".$date);
	}
	?>
			
	   <div class="panel panel-default">
			<div class="panel-heading clearfix">
				<h3 class="panel-title pull-left">
				<?php 
					if(isset($qarray)) : 
					echo $xml->Member[0]->DisplayAs ?>
					<span class="partybox-large" style="background:                  
					<?php  $PartyID = $xml->Member[0]->Party[0]->attributes()->Id;              	          	     
						 echo $colors[intval($PartyID)];
					?>"></span>
					<?php echo $xml->Member[0]->Party ?> 
					(<?php echo $xml->Member[0]->MemberFrom ?>)
					</h3>
						<input type="hidden" id="currentuin" value="<?php echo $uin; ?>">
						<input type="hidden" id="currentnext" value="<?php echo $next; ?>">
						<input type="hidden" id="currentprev" value="<?php echo $prev; ?>">
				<a class="btn btn-primary pull-right" onclick="load(<?php echo '\''.$next.'\',\''.$date.'\''; ?>);return false;" href="#" data-toggle="modal">
				  <i class="fa fa-arrow-right"></i>Next
				</a>
				 <a class="btn btn-primary pull-right" onclick="load(<?php echo '\''.$prev.'\',\''.$date.'\''; ?>);return false;" data-toggle="modal">
				  <i class="fa fa-arrow-left"></i>Previous
				</a>
				 <a class="btn btn-warning pull-right" style="margin-right: 6px;" onclick="gotopicals()">
				  <i class="fa fa-refresh"></i>To Topicals
				</a>
			</div>
			<div class="list-group">
				<div class="list-group-item list-group-item-image">
				<?php 
					$DodsId=$xml->Member[0]->attributes()->Dods_Id;
					$m=$xml->Member[0]->attributes()->Member_Id;
					// If the user hasn't explicitly asked for screenshots	
					if (!isset($photos) or $photos  !== "screenshot") {	
						if ($house == "Commons") {
							for($ii=0; $ii < $imagescount; $ii++) {
								if (trim($betaimages->member[$ii]->KnownAs) == trim($xml->Member[0]->DisplayAs)){
									$BetaId = $betaimages->member[$ii]->imageid;
								}
							}
							$imageurl = 'images/stock/'.$BetaId.'.jpeg';
							// If the member doesn't yet have a nice new photo, use the MNDP image
							if (isset($BetaId) && $BetaId == ""){
								$imageurl = 'http://data.parliament.uk/membersdataplatform/services/images/MemberPhoto/'.$m;
							}

						} else { 
							// If the house selected isn't the house of Commons
							$imageurl = 'https://assets3.parliament.uk/ext/mnis-bio-person/www.dodspeople.com/photos/'.$DodsId.'.jpg.jpg';
						}
					} else {
						// If the user has asked for a screenshot then...
						$count = "1";
						require("latestscreenshot.php");
						if(isset($screenshotoutput)) {
							$imageurl = $screenshotoutput['url'];
							$screenshotused = true;
							echo '<input id="current-photo" type="hidden" value="'.$screenshotoutput['imagenumber'].'">';
							echo '<input id="m" type="hidden" value="'.$m.'">';
						} else {
							$imageurl = 'images/screenshot/'.$DodsId.'.jpg';
						}
					}	
				?>
				<img id="questioner-img" src="<?php echo $imageurl; ?>" class="main-question-image">
			</div>
			<div class="list-group-item">
			<label>Question <?php echo $qarray[0]["type"]; ?> <?php echo $qarray[0]["number"]; ?></label>
			<?php if(isset($screenshotused)): ?>
					 <a href="#" onclick="anotherphoto(document.getElementById('current-photo').value,<?php echo $m ?>);return false;" class="btn btn-info pull-right" role="button">
									 Get us another photo please</a>
			<?php endif; ?>
			
			  
			  <h4 class="list-group-item-heading"><?php echo $qarray[0]["text"]; ?></h4>
			</div>

	<?php 
		else:  
	?>
		<h3> Please use the search tools </h3> 
		</div>
			
	<?php 
		endif;
	?>
	<script>
	function anotherphoto(current,m){
		var toget = parseInt(current)+1;
		var url = "template/latestscreenshot.php?imagenumber="+toget+"&m="+m;
		console.log('URL requested: '+url);		
		$.get(url, function(response) {
		  var imgs = response.replace('<div id="data" style="display: none;">',"");
		  var imgs = imgs.replace('</div>',"");
		  var imgarray = imgs.split(",");
		  $("#current-photo").val(imgarray[1]);
		  $("#questioner-img").attr("src",imgarray[0]);
		});	
	}
	</script>

