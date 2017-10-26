 <?php 				
// Do checks to make sure the application can access the various API bits
	// Can we get data from the membersdataplatform?
	$xmlmember = @simplexml_load_file('http://data.parliament.uk/membersdataplatform/xml/BasicDetails.xml');
	if (!$xmlmember) {
		$members = "danger";
	} else { 
		$members = "success";
	}
	// Can we get data from the Commons Oral Questions api?
	$xmloral = @simplexml_load_file('http://lda.data.parliament.uk/commonsoralquestions.xml?_view=basic&_pageSize=1');
	if (!$xmloral) {
		$oral = "danger";
	} else { 
		$oral = "success";
	}
	// Can we get data from the AV Visual Live Logging api?
	$xmlav = @simplexml_load_file('http://lda.data.parliament.uk/tvprogrammes.xml?_view=basic&_pageSize=1');
	if (!$xmlav) {
		$av = "danger";
	} else { 
		$av = "success";
	}
	// Can we access the new MP images api?
	$imageurl = 'https://api-parliament-uk.azure-api.net/Live/photo/7xQEDGdb.jpeg?crop=CU_5:2&width=732&quality=80';
	if (@getimagesize($imageurl)){
		$beta = "success";
	} else { 
		$beta = "danger";
	} 
?>
 				<div class="col-xs-12 col-sm-6">
						  <h4><span class="label label-<?php echo $members; ?>">&nbsp</span> Members Data Platform</h4>
						  <h4><span class="label label-<?php echo $oral; ?>">&nbsp</span> Commons Oral Questions</h4>
						  <h4><span class="label label-<?php echo $av; ?>">&nbsp</span> Screenshots</h4>
						  <h4><span class="label label-<?php echo $beta; ?>">&nbsp</span> Member Beta Images</h4>
					  </div>