<?php
$DeptId=$_GET["dept"];

if (!$DeptId) { $DeptId = "0"; }

$dept=simplexml_load_file("http://data.parliament.uk/membersdataplatform/services/mnis/ReferenceData/Departments/");

$DepartmentInfo=simplexml_load_file("http://data.parliament.uk/membersdataplatform/services/mnis/Department/".$DeptId."/Government/Current/");


$Departments = array();
for($i = 0; $i < count($dept); $i ++) {
    if (!strtotime($dept->Department[$i]->EndDate[0]) >= time()) {
        $DepartmentName = $dept->Department[$i]->Name;
        $DepartmentId = $dept->Department[$i]->Department_Id;
        $DepartmentAcronym = $dept->Department[$i]->Acronym;
        $Departments[] = $dept->Department[$i]->Name;
	    }
}

$DeptSorted = sort($Departments,2);

?>

<select name="per1" id="per1">
  <option selected="selected">Choose one</option>
  <?php
    foreach($Departments as $department) { ?>
      <option value="<?= $department[0] ?>"><?= $department[0] ?></option>
  <?php
    } ?>
</select> 
<br />
<?php

for($i = 0; $i < count($DepartmentInfo->Posts[0]); $i ++) {
		if ($DepartmentInfo->Posts[0]->Post[$i]->PostHolders->PostHolder[0]->Member[0]->DisplayAs[0] != "")
			{
        echo $DepartmentInfo->Posts[0]->Post[$i]->Name." - ".$DepartmentInfo->Posts[0]->Post[$i]->PostHolders->PostHolder[0]->Member[0]->DisplayAs[0]."</br>"; 
				}
}


?>


