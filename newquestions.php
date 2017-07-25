<?php $xmlDoc=new DOMDocument();

		$url = "http://lda.data.parliament.uk/commonsoralquestions.xml?_view=Commons+Oral+Questions&AnswerDate=2017-07-18&_pageSize=500";
		$questionxml=simplexml_load_file($url);

		$xmlDoc->load($url);
		$x=$xmlDoc->getElementsByTagName('item');

	print_r($x);

    $hint = "";
    $qarray = array();
	   
	$count = 0;		
    for($i=0; $i<($x->length); $i++) {
		$QuestionID=$x->item($i)->getElementsByTagName('ID');
		$MemberId=$x->item($i)->getElementsByTagName('tablingMember'); //href="http://data.parliament.uk/members/4450"
		foreach ($questionxml->children() as $second_gen) {
			$test1 = $second_gen['href'];
				foreach ($second_gen->children() as $third_gen) {
				$test2 = $third_gen['href'];
				}
			}
		$TabledDate=$x->item($i)->getElementsByTagName('TabledDate');
		$QuestionType=$x->item($i)->getElementsByTagName('QuestionType');
		$DateDue=$x->item($i)->getElementsByTagName('AnswerDate');
		$BallotNo=$x->item($i)->getElementsByTagName('ballotNumber');
		$Department=$x->item($i)->getElementsByTagName('AnsweringBody');
		$QText=$x->item($i)->getElementsByTagName('questionText');
		$House=$x->item($i)->getElementsByTagName('HouseId');
	
		if ($QText[0]->textContent=="") {
		
		}
		else {
			$count = $count + 1;
			$qarray[] = array('number'=>$BallotNo[0]->textContent,'text'=>$QText[0]->textContent,'type'=>$QuestionType[0]->textContent,'member'=>$test1);
			$test = $MemberId;
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

//output the response
	for($i=0; $i<$count; $i++) {
		
		if ($hint=="") {
			
			$hint = $qarray[$i]['number'].') '.$qarray[$i]['text'].'<br />';
		}
		else {
			$hint = $hint.$qarray[$i]['number'].') '.$qarray[$i]['text'].'<br />';
		}
	}
	
	
print_r($hint);

print_r($qarray);

print_r($questionxml);

?>