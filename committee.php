<?php

$xml=simplexml_load_file("http://data.parliament.uk/membersdataplatform/services/mnis/members/query/holdsgovernmentpost=True%7Chouse=Commons%7CIsEligible=true/GovernmentPosts/") or die("Error: Cannot create object");

$ComId=$_GET["com"];

if (!$ComId) { $ComId = "158"; }

$com=simplexml_load_file("http://data.parliament.uk/membersdataplatform/services/mnis/ReferenceData/Committees/");

$CommitteeInfo=simplexml_load_file("http://data.parliament.uk/membersdataplatform/services/mnis/Committee/$ComId/Current/");

?>

<?php
$Committees = array();
for($i = 0; $i < count($com); $i ++) {
    if (!strtotime($com->Committee[$i]->EndDate[0]) >= time()) {
        $CommitteeName = $com->Committee[$i]->Name;
        $CommitteeId = $com->Committee[$i]->Committee_Id;
        $CommitteeType = $com->Committee[$i]->CommitteeType;
        $Committees[] = $com->Committee[$i]->Name;
    }
}
?>

<?php 
$ComSorted = sort($Committees,2);

$CommitteeChairName = $CommitteeInfo->Details[0]->Chair_Member;
$CommitteeChairId   = $CommitteeInfo->Details[0]->Chair_Member_Id;

?>

<select name="per1" id="per1">
  <option selected="selected">Choose one</option>
  <?php
    foreach($Committees as $committee) { ?>
      <option value="<?= $committee[0] ?>"><?= $committee[0] ?></option>
  <?php
    } ?>
</select> 
<br />
<?php

echo $CommitteeName."</br>";
echo $CommitteeChairName."</br>";
echo $CommitteeType."</br>";

for($i = 0; $i < count($CommitteeInfo->Members[0]); $i ++) {
        echo $CommitteeInfo->Members[0]->Member[$i]->FullTitle." - ".$CommitteeInfo->Members[0]->Member[$i]->Party."</br>";
}


echo "</br> </br>Work in Progress, y'all"
?>


