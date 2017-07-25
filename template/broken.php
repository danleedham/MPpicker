<?php
$xmlDoc=new DOMDocument();
$mpsDoc=new DOMDocument();

//get the q parameter from URL
$q=$_GET["q"];
$date=$_GET["date"];
	if (!$date) { $date = "2017-07-18"; }
	
	$xmlDoc->load("http://lda.data.parliament.uk/commonsoralquestions.xml?_view=Commons+Oral+Questions&AnswerDate=2017-07-18&_pageSize=500");
	$x=$xmlDoc->getElementsByTagName('item');
	
	$mpsDoc->load("http://data.parliament.uk/membersdataplatform/services/mnis/members/query/House=Commons%7CIsEligible=true/");
	$mps=$mpsDoc->getElementsByTagName('Member');
	
	$hint="";
	

	
	for($i=0; $i<($x->length); $i++) {
		$QuestionID=$x->item($i)->getElementsByTagName('ID');
		$MemberId=$x->item($i)->getElementsByTagName('tablingMemberPrinted');
		$TabledDate=$x->item($i)->getElementsByTagName('TabledDate');
		$QuestionType=$x->item($i)->getElementsByTagName('QuestionType');
		$DateDue=$x->item($i)->getElementsByTagName('AnswerDate');
		$BallotNo=$x->item($i)->getElementsByTagName('ballotNumber');
		$Department=$x->item($i)->getElementsByTagName('AnsweringBody');
		$QText=$x->item($i)->getElementsByTagName('questionText');
		 
		if (stristr($MemberId->item(0)->textContent,$q)) {
			if ($QText[0]->textContent=="") {
			}
			else {
				$count = $count + 1;
				$qarray[] = array('number'=>$BallotNo[0]->textContent,'text'=>$QText[0]->textContent,'type'=>$QuestionType[0]->textContent,'member'=>$MemberId->item(0)->textContent);
				}
			}
	}
	   
		usort($qarray, function($a, $b) {
				return $a['number'] - $b['number'];
			});

		function comp($a, $b) {
			if ($a['type'] == $b['type']) {
				return $a['number'] - $b['number'];
			}
			return strcmp($a['type'], $b['type']);
		}

		usort($qarray, 'comp');

		$length = count($qarray);
		echo $length;
		for($i=0; $i < $length; $i++) {
			if ($hint=="") {
				$hint='<a class="list-group-item" href="">
			   	   <img src="http://data.parliament.uk/membersdataplatform/services/images/MemberPhoto/47" class="img-rounded mini-member-image pull-left">
				   <h4 class="list-group-item-heading">'.$qarray[$i]["number"].' - '.$qarray[$i]["type"].' ('. $qarray[$i]["member"].' )'.'</h4>
				   <p class="list-group-item-text">'.$qarray[$i]["text"].'</p></a>';
			} else {
				$hint=$hint .'<a class="list-group-item" href="">
				   <img src="https://assets3.parliament.uk/ext/mnis-bio-person/www.dodspeople.com/photos/.jpg.jpg" class="img-rounded mini-member-image pull-left">
				   <h4 class="list-group-item-heading">'.$qarray[$i]["number"].' - '.$qarray[$i]["type"].' ('. $qarray[$i]["member"].' )'.'</h4>
				   <p class="list-group-item-text">'.$qarray[$i]["text"].'</p></a>';
			}
		}
		  

// Set output if no members were found or to the correct values
if ($hint=="") {
  $response='<a class="list-group-item">
			 <h4 class ="list-group-item-heading">Please try again</h4></a>';
} else {
  $response=$hint;
}

//output the response
echo $response;

?>