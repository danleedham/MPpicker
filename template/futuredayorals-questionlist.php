<?php

    // This include retuns $QuestionsByDepartment with an array: department,questions
    
	include("futuredayorals-returndepts.php");
	
	//$date = date('Y-m-d',$date);
	// print_r($QuestionsByDepartment);
	
	// Itterate across each department
	for ($i=0; $i<count($QuestionsByDepartment); $i++){
	    $iQuestions = explode('<p class="paraQuestion">',$QuestionsByDepartment[$i]['questions']);
	    
	    // Remove any first elements not containing questions
	    if(!strpos($iQuestions[0],"UIN")){
	        array_shift($iQuestions);
	    }
	    $department = $QuestionsByDepartment[$i]['department'];
	  
	  
	    $jQuestions = array();
	    // Now itterate across each question and pull the elements
	    for($j=0; $j<count($iQuestions); $j++){
	        $jQuestions[$j]['department'] = $department;
	        $uin = explode('<span class="charUIN">',$iQuestions[$j]);
	        $uin = explode('</span>',$uin[1]);
	        $uin = intval(trim(str_replace(["(",")</span></p>"],"",$uin[0])));
	        $jQuestions[$j]['uin'] = $uin;
	        $qref = explode('<span class="charBallotNumber">',$iQuestions[$j]);
	        $qref = explode('</span>',$qref[1]);
	        $jQuestions[$j]['qref'] = $qref[0];
	        if(strstr($qref[0],'T')){
	            $type = "Topical";
	        } else {
	            $type = "Substantive";
	        } 
	        $jQuestions[$j]['type'] = $type;
	        $DisplayAs = explode('<span class="charMember">',$iQuestions[$j]);
	        $DisplayAs = explode('</span>',$DisplayAs[1]);
	        $jQuestions[$j]['DisplayAs'] = $DisplayAs[0];
	        $constituency = explode('<span class="charConstituency"> (',$iQuestions[$j]);
	        $constituency = @explode(')</span>',$constituency[1]);
	        $jQuestions[$j]['constituency'] = $constituency[0];   
	        $text = explode('<br />',$iQuestions[$j]);
	        if(count($text) > 1) {
	            $text = explode('<span',$text[1]);
	            $jQuestions[$j]['text'] = $text[0];
	        } else {
	            $jQuestions[$j]['text'] = "";
	        }
	        $jQuestions[$j]['i'] = $j;
	    }
	   $Questions[$i] = $jQuestions;
	}
	// Resort into single array
	$AllQuestions = array();
	for($i=0; $i<count($Questions); $i++){
	    for($j=0; $j<count($Questions[$i]); $j++){
	        $AllQuestions[] = $Questions[$i][$j];
	    }
	}
	
	
	// Now let's build an XML type array similar to the one we would use in the DDP API
    $XML = new SimpleXMLElement("<items></items>");
    for($i=0; $i<count($AllQuestions); $i++){
        $topLevelItem = $XML->addChild('item');
        $topLevelItem->addAttribute('href', 'http://data.parliament.uk/resources/');
        $topLevelItem->addChild('AnswerDate',$date);
        $AnsweringBody = $topLevelItem->addChild('AnsweringBody');
        $AnsweringBody->addChild('item',$AllQuestions[$i]['department']);
        $CommonsQuestionTime = $topLevelItem->addChild('CommonsQuestionTime');
        $CommonsQuestionTime->addChild('QuestionType',$AllQuestions[$i]['type']);
        $topLevelItem->addChild('QuestionStatus','Tabled');
        $topLevelItem->addChild('ballotNumber',$AllQuestions[$i]['i']+1);
        $topLevelItem->addChild('questionText',$AllQuestions[$i]['text']);
        $tablingMember = $topLevelItem->addChild('tablingMember');
        $tablingMember->addAttribute('href', 'http://data.parliament.uk/members/');
        $constituency = $tablingMember->addChild('constituency');
        $constituency->addAttribute('href',"http://data.parliament.uk/resources/");
        $constituency->addChild('prefLabel',$AllQuestions[$i]['constituency']);
        $tablingMemberPrinted = $topLevelItem->addChild('tablingMemberPrinted');
        $tablingMemberPrinted->addChild('item',$AllQuestions[$i]['DisplayAs']);
        $topLevelItem->addChild('uin',$AllQuestions[$i]['uin']);
    }      
    //Header('Content-type: text/xml');
    $XML->asXML();
    
    $xmlDoc=new DOMDocument();
    
    $xmlDoc = dom_import_simplexml($XML);
    
    $x= $xmlDoc->getElementsByTagName('item');
	// Count how many question items we've loaded
	$questionscount = $x->length;

	// Load XML file containing all current MP's data 
	$qxml=simplexml_load_file("http://data.parliament.uk/membersdataplatform/services/mnis/members/query/House=Commons%7CIsEligible=true/") or die("Can't load MPs");
	
	// Just in case let's count how many members we've just loaded above
	$memberscount =  count($qxml);

	// Array with party ID and party color
	require_once('colors.php');	
	
	// If there are no questions, an empty 'item' is presented. 
	// If there are more than one questions there are 2+ items. 
	$hint = "";
	if ($questionscount == 1) {
			$hint = "";
	} else {	
		
		// If beta images are loaded prior to this then skip
		if(!isset($feed)){
			$feed = file_get_contents("betaimages.xml");
			$betaimages = simplexml_load_string($feed) or die("Can't load Beta Images");
			$imagescount = count($betaimages);
		}

		// Now let's go through each question and extract the helpful bits of information
		for($i=0; $i<($x->length); $i++) {
			
			// If an item doesn't have the element questionText it's not actually a question
			$QText=$x->item($i)->getElementsByTagName('questionText');
			if (!isset($QText[0]->textContent)) {
			} else {
				$QuestionID=$x->item($i)->getElementsByTagName('ID');
				$uinObject=$x->item($i)->getElementsByTagName('uin');
				$uinArray=iterator_to_array($uinObject);
				$uin = $uinArray[0]->textContent;
				// print_r($uin);
				// Some elements are subnodes and require the textContent to be extracted then trimmed
				$MemberId=$x->item($i)->getElementsByTagName('tablingMember')->item(0)->getAttribute('href');
					$CurrentQuestioner = intval(str_replace("http://data.parliament.uk/members/","",$MemberId));
				$tablingMemberPrinted=$x->item($i)->getElementsByTagName('tablingMemberPrinted');
				$Const=$x->item($i)->getElementsByTagName('constituency');
					$Constituency = trim($Const['prefLabel']->textContent);
				//$TabledDate=$x->item($i)->getElementsByTagName('TabledDate');
				$QuestionType=$x->item($i)->getElementsByTagName('QuestionType');
				$DateDue=$x->item($i)->getElementsByTagName('AnswerDate');
				$BallotNo=$x->item($i)->getElementsByTagName('ballotNumber');
				$Dept=$x->item($i)->getElementsByTagName('AnsweringBody');
					$Department=trim($Dept->item(0)->textContent);
				$QuestionStatus=$x->item($i)->getElementsByTagName('QuestionStatus');	

				// Let's now check each MP to find which MP asked the question 
				for ($y = 0; $y < $memberscount; $y++){
					$CurrentMPConstituency = trim($qxml->Member[$y]->MemberFrom);
						if($Constituency == $CurrentMPConstituency) { 
							$DodsId=$qxml->Member[$y]->attributes()->Dods_Id;
							$MemberId=$qxml->Member[$y]->attributes()->Member_Id;
							$DisplayAs=$qxml->Member[$y]->DisplayAs;
							$party=$qxml->Member[$y]->Party;
							$PartyID =$qxml->Member[$y]->Party[0]->attributes()->Id;              	          	          	     
							$color = $colors[intval($PartyID)];
						}
				}
				
				if($QuestionType[0]->textContent == "Topical"){
					$typeletter = 't';
				} else {
					$typeletter = 's';
				}
				$ballotnumber = $BallotNo[0]->textContent;
				$qref = $typeletter.$ballotnumber;	

				// Just a check to make sure our query got the questions from the right department
				if((isset($qdept) && $Department == $qdept) or !isset($qdept) or $qdept == "all") {	
					// Now build an array with all the information we want	
				   $qarray[] = array( 'number'=>$BallotNo[0]->textContent,
									  'uin'=>$uin,
									  'dept'=>$Department,
									  'text'=>$QText[0]->textContent,
									  'type'=>$QuestionType[0]->textContent,
									  'typeletter'=>$typeletter,
									  'member'=>$CurrentQuestioner,
									  'DisplayAs'=>$DisplayAs,
									  'DodsId'=>$DodsId,
									  'MemberId'=>intval($MemberId),
									  'constituency'=>$Constituency,
									  'party'=>$party,
									  'partyid'=>$PartyID,
									  'color'=>$color,
									  'qref'=>$qref,
									  'QuestionStatus'=>$QuestionStatus[0]->textContent
								);
				}
			}
		}
	}
	
?>