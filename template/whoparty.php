<?php
if(!$house){$house = $_GET["house"];}
if(!$house){$house = "Commons";}
if(!$sex){$sex = $_GET["sex"];}
if(!$sex){$sex = "both";}

$xmlDoc=new DOMDocument();
$xmlDoc->load("http://data.parliament.uk/membersdataplatform/services/mnis/HouseOverview/".$house."/".date("Y-m-d")."/");
	$x=$xmlDoc->getElementsByTagName('Party');
	$partycount = $x->length;	
	if ($partycount == 0) {
	}
	else {	
		for($i=0; $i<($x->length); $i++) {
				$Name = $x->item($i)->getElementsByTagName('Name');				
				$MaleCount = $x->item($i)->getElementsByTagName('MaleCount');
				$FemaleCount = $x->item($i)->getElementsByTagName('FemaleCount');
				$TotalCount = $x->item($i)->getElementsByTagName('TotalCount');
				if ($sex == "m") {
					$count = $MaleCount->item(0)->textContent;
				}
				elseif ($sex == "f") {
					$count = $FemaleCount->item(0)->textContent;
				}
				else { 
					$count = $TotalCount->item(0)->textContent;
				}
				
				if (!$count == 0) { 				
					$PartyName=trim($Name->item(0)->textContent);  
					$partyarray[] = array('Name'  => $PartyName,
										  'Seats' => $count);
				}
		}
		   echo '<option value="">All Parties</option>';
		foreach ($partyarray as $key => $value) {
		   echo '<option value="'. $value["Name"].'">'. $value["Name"].'</option>';
		}

	}
?>
