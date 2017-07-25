<?php $xmlDoc=new DOMDocument();

		$url = "http://hansard.services.digiminster.com/questions/oral/list.xml?date=lastmonth";
		$questionxml=simplexml_load_file($url);

		$xmlDoc->load($url);
		$x=$xmlDoc->getElementsByTagName('Question');

print_r($x);

       $hint = "";

    	for($i=0; $i<($x->length); $i++) {
		$QuestionID=$x->item($i)->getElementsByTagName('ID');
		$MemberId=$x->item($i)->getElementsByTagName('MemberId');
		$TabledDate=$x->item($i)->getElementsByTagName('TabledDate');
		$DateDue=$x->item($i)->getElementsByTagName('DueForAnswerDate');
		$Department=$x->item($i)->getElementsByTagName('AnsweringDepartment');
		$QText=$x->item($i)->getElementsByTagName('QuestionText');
		$House=$x->item($i)->getElementsByTagName('HouseId');
			
	if ($hint=="") {
			  $hint=$QText->textContent.'';	
			} 
		else {
			  $hint=$hint .$QText[0].'';
			}
	    }
	    
if ($hint=="") {
  $response='Nothing set to return';
} else {
  $response=$hint;
}
//output the response
echo $response;

	  ?>