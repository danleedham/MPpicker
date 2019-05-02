<?php 

// Load all the Parties
$xmlDoc=new DOMDocument();

$xmlDoc->load("http://data.parliament.uk/membersdataplatform/services/mnis/ReferenceData/Parties/");
$x=$xmlDoc->getElementsByTagName('Party');
$count = $x->length;

$colors = array();


for($icolor=0; $icolor<$count; $icolor++){
	$Party_Id = strval($x->item($icolor)->getElementsByTagName('Party_Id')->item(0)->textContent);
	if($x->item($icolor)->getElementsByTagName('Colour')->item(0)->textContent == ""){
		$colors[$Party_Id] = "#000";
	} else {
		$colors[$Party_Id] = $x->item($icolor)->getElementsByTagName('Colour')->item(0)->textContent;
	}
	
}

// Override certain Parties 
// Party Colors with key Party_ID and value the Hex color of the party
	$colors = array_replace($colors, 
			array (
					"0"	  =>   "#000000", //
					"3"   =>   "#4d4d4d", // Bishops
					"4"	  =>   "#0087DC", // Conservative
					"6"   =>   "#4d4d4d", // Crossbench
					"7"   =>   "#D46A4C", // DUP
					"8"   =>   "#000000", // Independent
					"10"  =>   "#4d4d4d", // Independent Labour
					"15"  =>   "#DC241f", // Labour
					"17"  =>   "#FAA61A", // Liberal Democrats
					"22"  =>   "#008142", // Plaid Cymru 
					"29"  =>   "#FEF48B", // Scottish National Party 
					"30"  =>   "#008800", // Sinn Féin
					"35"  =>   "#70147A", // UK Independence Party
					"38"  =>   "#9999FF", // Ulster Unionist Party
					"44"  =>   "#6AB023", // Green Party
					"47"  =>   "#4d4d4d", // Speaker
					"49"  =>   "#4d4d4d", // Non-affiliated
					"52"  =>   "#4d4d4d", // Independent Ulster Unionist
					"53"  =>   "#4d4d4d" // Independent Social Democrat
			));

?>