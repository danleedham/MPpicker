<?php
if(!$house){$house = $_GET["house"];}
if(!$house){$house = "Commons";}

$xmlDoc=new DOMDocument();
$xmlDoc->load("http://data.parliament.uk/membersdataplatform/services/mnis/HouseOverview/".$house."/".date("Y-m-d")."/");
	$x=$xmlDoc->getElementsByTagName('Party');
	$partycount = $x->length;	
	if ($partycount == 0) {
	}
	else {	
		$mcount = 0;
		$fcont = 0;
		$allcount = 0;
		for($i=0; $i<($x->length); $i++) {
				$Name = $x->item($i)->getElementsByTagName('Name');				
				$MaleCount = $x->item($i)->getElementsByTagName('MaleCount');
				$FemaleCount = $x->item($i)->getElementsByTagName('FemaleCount');
				$TotalCount = $x->item($i)->getElementsByTagName('TotalCount');
				$mcount = intval($MaleCount->item(0)->textContent)+$mcount;
				$fcount = intval($FemaleCount->item(0)->textContent)+$fcount;
				$allcount = intval($TotalCount->item(0)->textContent)+$allcount;

			}
		   echo '<option value="all">Both - '.$allcount.'</option>';
		   echo '<option value="m">Male - '.$mcount.'</option>';
		   echo '<option value="f">Female - '.$fcount.'</option>';
	}
?>
