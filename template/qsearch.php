<?php
$xmlDoc=new DOMDocument();

	//get parameters from URL
	$q=$_GET["q"];
	$date=$_GET["date"];
	$groupone=$_GET["groupone"];
	$grouptwo=$_GET["grouptwo"];
	$groupthree=$_GET["groupthree"];
	$groupfour=$_GET["groupfour"];
	
	
	if (!$date) { $date = "2017-07-18"; }
	
	$xmlDoc->load('http://lda.data.parliament.uk/commonsoralquestions.xml?_view=Commons+Oral+Questions&AnswerDate='.$date.'&_pageSize=500');
	$x=$xmlDoc->getElementsByTagName('item');
		
	$xml=simplexml_load_file("http://data.parliament.uk/membersdataplatform/services/mnis/members/query/House=Commons%7CIsEligible=true/") or die("Can't load MPs");
	$memberscount =  count($xml);

	// Arry with party ID and party color (from BBC Elections coverage)	
	$colors = array (
				"0"	  =>   "#000000",
				"4"	  =>   "#0087DC",
				"7"   =>   "#D46A4C",
				"8"   =>   "#DDDDDD",
				"15"  =>   "#DC241f",
				"17"  =>   "#FDBB30",
				"22"  =>   "#008142",
				"29"  =>   "#FFFF00",
				"30"  =>   "#008800",
				"31"  =>   "#99FF66",
				"35"  =>   "#70147A",
				"38"  =>   "#9999FF",
				"44"  =>   "#6AB023",
				"47"  =>   "#FFFFFF");
		
	$hint="";
	for($i=0; $i<($x->length); $i++) {
		$QText=$x->item($i)->getElementsByTagName('questionText');
		if ($QText[0]->textContent=="") {
		}
		else {
			$QuestionID=$x->item($i)->getElementsByTagName('ID');
			$MemberId=$x->item($i)->getElementsByTagName('tablingMemberPrinted');
				$CurrentQuestioner = trim($MemberId->item(0)->textContent);
			$Const=$x->item($i)->getElementsByTagName('constituency');
				$Constituency = trim($Const['prefLabel']->textContent);
			$TabledDate=$x->item($i)->getElementsByTagName('TabledDate');
			$QuestionType=$x->item($i)->getElementsByTagName('QuestionType');
			$DateDue=$x->item($i)->getElementsByTagName('AnswerDate');
			$BallotNo=$x->item($i)->getElementsByTagName('ballotNumber');
			$Department=$x->item($i)->getElementsByTagName('AnsweringBody');

			for ($y = 0; $y < $memberscount; $y++){
				$CurrentMP = trim($xml->Member[$y]->ListAs);
					if($CurrentQuestioner === $CurrentMP) { 
						$DodsId=$xml->Member[$y]->attributes()->Dods_Id;
						$MemberId=$xml->Member[$y]->attributes()->Member_Id;
						$DisplayAs=$xml->Member[$y]->DisplayAs;
						$party=$xml->Member[$y]->Party;
						$PartyID =$xml->Member[$y]->Party[0]->attributes()->Id;              	          	          	     
						$color = $colors[intval($PartyID)];
	  				}
	  		}
			
			$qarray[] = array('number'=>$BallotNo[0]->textContent,
							  'text'=>$QText[0]->textContent,
							  'type'=>$QuestionType[0]->textContent,
							  'member'=>$CurrentQuestioner,
							  'DodsId'=>$DodsId,
							  'MemberId'=>$MemberId,
							  'constituency'=>$Constituency,
							  'party'=>$party,
							  'color'=>$color);
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
		echo 'Questions: '.$length.' <br />';
		echo 'Members: '.$memberscount.' <br />';

//If no question has been seleted, presume it wants a list		
		if (!$q) { 
				
			for($i=0; $i < $length; $i++) {
				if ($hint=="") {
					$hint='<a class="list-group-item" href="">
					   <img src="http://data.parliament.uk/membersdataplatform/services/images/MemberPhoto/'.$qarray[$i]["MemberId"].'" class="img-rounded mini-member-image pull-left">
					   <h4 class="list-group-item-heading"> <span style="color:'.$qarray[$i]["color"].'">'.$qarray[$i]["number"].' - '.$qarray[$i]["type"].' ('.$qarray[$i]["member"].') '.$qarray[$i]["constituency"].'</span></h4>
					   <p class="list-group-item-text">'.$qarray[$i]["text"].'</p></a>';
				} else {
					$hint=$hint .'<a class="list-group-item" href="">
					   <img src="http://data.parliament.uk/membersdataplatform/services/images/MemberPhoto/'.$qarray[$i]["MemberId"].'" class="img-rounded mini-member-image pull-left">
					   <h4 class="list-group-item-heading"><span style="color:'.$qarray[$i]["color"].'">'.$qarray[$i]["number"].' - '.$qarray[$i]["type"].' ('. $qarray[$i]["member"].') '.$qarray[$i]["constituency"].'</span></h4>
					   <p class="list-group-item-text">'.$qarray[$i]["text"].'</p></a>';
				}
			}
		}
		else {
			$hint=$q;
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