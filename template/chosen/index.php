<link rel="stylesheet" href="chosen.css">
        <select data-placeholder="Choose a Country..." class="chosen-select" tabindex="2">
        <option value=""></option>

<?php
$xmlDoc=new DOMDocument();
$xmlDoc->load('http://data.parliament.uk/membersdataplatform/services/mnis/ReferenceData/Departments/');
	$x=$xmlDoc->getElementsByTagName('Department');
	$departmentcount = $x->length;	
	if ($departmentcount == 0) {
	}
	else {	
		for($i=0; $i<($x->length); $i++) {
				$EndDate=$x->item($i)->getElementsByTagName('EndDate');
					$EndString = $EndDate->item(0)->textContent;					 
				if (!strtotime($EndString) >= time()) {
					$Name=$x->item($i)->getElementsByTagName('Name');
					$DepartmentName=trim($Name->item(0)->textContent);  
					$departmentarray[] = $DepartmentName;
				}			
		}
	
		sort($departmentarray);	
		
		foreach ($departmentarray as $value) {
		   echo '<option value="'. $value.'">'. $value.'</option>';
		   }
	}
?>  
           
          </select>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.js" type="text/javascript"></script>
  <script src="chosen.jquery.js" type="text/javascript"></script>
  <script src="docsupport/prism.js" type="text/javascript" charset="utf-8"></script>
  <script src="docsupport/init.js" type="text/javascript" charset="utf-8"></script>
</body>
</html>
