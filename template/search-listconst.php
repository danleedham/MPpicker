
<select class="chosen-select my_select_box form-control custom-select mb-2 mr-sm-2 mb-sm-0" data-placeholder="Choose a Constituency..." id="const-pick" tabindex="2">
	<?php
	
	// This file returs a list of all constituencies
	$xmlDoc=new DOMDocument();
	$xmlDoc->load('http://lda.data.parliament.uk/constituencies.xml?_pageSize=650&_view=basic&exists-endedDate=false');
	$x=$xmlDoc->getElementsByTagName('item');
	$constarray = Array();
	for($i=0; $i<($x->length); $i++) {
		$Label=$x->item($i)->getElementsByTagName('label')->item(0)->textContent;
		if(strlen($Label)>1) {
			$constarray[] = $Label;
		}				
	}
	echo '<option value=""></option>';	   
	foreach ($constarray as $value) {
	   echo '<option value="'. $value.'">'. $value.'</option>';
	  }
	?>
</select>