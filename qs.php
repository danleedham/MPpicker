<!DOCTYPE html>
<html lang="en">
<head>
	<script
  src="http://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="google" value="notranslate">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <title>Questions Picker</title>

  <?php include 'template/headinc.php';

$xmlDoc=new DOMDocument();

	//get parameters from URL
	$qtype=$_GET["type"];
	$date=$_GET["date"];
		if (!$date) {$date = date("Y-m-d");}
	$groups=$_GET["groups"];
	$m=$_GET["m"];
	$house = "Commons";
	$photos=$_GET["photos"];
		if(!$photos) { $photos = "stock"; }
	$xmlDoc->load('http://lda.data.parliament.uk/commonsoralquestions.xml?_view=Commons+Oral+Questions&AnswerDate='.$date.'&_pageSize=500');
	$x=$xmlDoc->getElementsByTagName('item');
	$questionscount = $x->length;
	
	if ($questionscount == 1) {
			$hint = "";
	}
	else {	
		$hint="";
		for($i=0; $i<($x->length); $i++) {
			$QText=$x->item($i)->getElementsByTagName('questionText');
			if ($QText[0]->textContent=="") {
			}
			else {
				$uin=$x->item($i)->getElementsByTagName('uin');
				$QuestionType=$x->item($i)->getElementsByTagName('QuestionType');
				$Dept=$x->item($i)->getElementsByTagName('AnsweringBody');
					$Department=trim($Dept->item(0)->textContent);
				}
				$qarray[] = array('uin'=>$uin[0]->textContent,
								  'dept'=>$Department,
								  'type'=>$QuestionType[0]->textContent
								  );
				$deptarray[] = array('dept' => $Department);
				$typearray[] = array('type' => $QuestionType[0]->textContent);			
			}
		}
		
				// This gives us a unique list of departments & question types
		if (count($deptarray) !== 0) {
			$deptarray = array_map("unserialize", array_unique(array_map("serialize", $deptarray)));
		}
		if ($deptarray[0]["dept"] == '') {array_shift($deptarray);}
		if (count($typearray) !== 0) {
			$typearray = array_map("unserialize", array_unique(array_map("serialize", $typearray)));
		}
		if ($deptarray[0]["dept"] == '') {array_shift($deptarray);}
		// Count how many unique departments/types there are
		$deptscount = count($deptarray);
		$typecount = count($typearray);
?>

<script>
function load(num,date,photos){
	if (!document.getElementById("photos-input").checked){
	 var photos = 'Stock';
	}
   $("#contactCard").load('template/questioner.php?uin='+num+'&date='+date+'&photos='+photos);
   $('.active').removeClass('active');
   $('#q'+num).addClass("active");
   
}
function loadquestions(date,dept,type){
   var groups = document.getElementById("groups-input").value;
   var withdrawn = document.getElementById("withdrawn-input").value;
   console.log('Loading questions using groups: '+groups+' and withdrawing: '+withdrawn);
   groups = groups.replace(/[\r\n]+/g,",");
   groups = encodeURI(groups);
   withdrawn = encodeURI(withdrawn);
   $("#livesearch").load('template/listquestions.php?date='+date+'&type='+type+'&dept='+dept+'&groups='+groups+'&withdrawn='+withdrawn);
}
function loaddepts(date){
   $("#dept-input").load('template/questiondepts.php?date='+date);
   $("#type-input").load('template/questiontypes.php?date='+date);
}
function loadtypes(dept){
   var date = document.getElementById("date-input").value;
   $("#type-input").load('template/questiontypes.php?date='+date+'&dept='+dept);
   $("#livesearch").load('template/listquestions.php?date='+date+'&dept='+dept);
}
function gotopicals(){
   document.getElementById("type-input").value = 'Topical';
   var date = document.getElementById("date-input").value;
   date = date.toString();
   var dept = document.getElementById("dept-input").value;
   dept = encodeURI(dept);
   var groups = document.getElementById("groups-input").value;
   var withdrawn = document.getElementById("withdrawn-input").value;
   console.log('Loading questions using groups: '+groups+' and withdrawing: '+withdrawn);
   groups = groups.replace(/[\r\n]+/g,",");
   groups = encodeURI(groups);
   withdrawn = encodeURI(withdrawn);
   $("#livesearch").load('template/listquestions.php?date='+date+'&type=Topical&dept='+dept+'&groups='+groups+'&withdrawn='+withdrawn);
}
</script>

</head>

<body>		
   <div class="container-fluid bootcards-container push-right">
    <div class="row">
      <!-- left list column -->
      <div class="col-sm-4 bootcards-list" id="list" data-title="Contacts">
        <div class="panel panel-default">     
          <div class="panel-body">
            <div class="search-form">
				<div class="form-inline">
					<input id="date-input" type="date" class="input-sm form-control" onchange="loaddepts(this.value)" value="<?php echo $date ?>" name="date" >
					<input id="photos-input" style="float:right !important;" class="pull-right" <?php if ($photos == "screenshot") {echo "checked";} ?> type="checkbox" value="screenshot" name="photos"  data-toggle="toggle" data-onstyle="danger" data-offstyle="success" data-on="ScreenShot" data-off="Stock">
				</div>
				<div class="form-inline" style="padding-top:6px !important;">
				 	<label for="dept-input">Department:</label><br />
					<select id="dept-input" name="type" class="form-control">
						<?php include 'template/questiondepts.php' ?>
					</select>			
				</div>
				<div class="form-inline" style="padding-top:6px !important;">
				   <label for="type-input">Type:</label><br />
				<select id="type-input" name="type" class="form-control" onchange="loadquestions(document.getElementById('date-input').value,encodeURI(document.getElementById('dept-input').value),encodeURI(this.value));return false;">
						Type: <?php include 'template/questiontypes.php' ?>
				</select>
				</div>
            </div>
			<br />
			<a href="#" onclick="loadquestions(document.getElementById('date-input').value,encodeURI(document.getElementById('dept-input').value),encodeURI(document.getElementById('type-input').value));return false;" class="btn btn-info" role="button">Load Questions</a>
          </div><!--panel body-->
          <div class="list-group" id="livesearch">
          </div><!--list-group-->

          <div class="panel-footer">
            <small class="pull-left">This section auto-populates by magic (and php).</small>
            <a class="btn btn-link btn-xs pull-right" href="http://data.parliament.uk/membersdataplatform/">Live Data</a>
          </div>
        </div><!--panel-->

        </div><!--list-->

        <!--list details column-->
        <div class="col-sm-8 bootcards-cards">

          <!--contact details -->
          <div id="contactCard">
             <div class="panel panel-default">
              <div class="panel-heading clearfix">
                <h3 class="panel-title pull-left">Please use the search tools </h3> 
		 	  </div>
		 	  <div class="list-group">
                <div class="list-group-item">
					<div class="search-form">
						<div class="form-group">	
							<div class="col-12">
								Use the search tools on the left and MP details will appear here
							</div>
						</div>
					</div>
                </div>
              </div>
		 	  <div class="panel-footer">
                  <small>Data from UK Parliament - <a href="http://data.parliament.uk/membersdataplatform/">Members' Names Data Platform</a></small>
                </div>
              </div>
		</div>
	
          <!-- Group details -->
          <div id="groupCard">

            <div class="panel panel-default">
              <div class="panel-heading clearfix">
                <h3 class="panel-title pull-left">Substantive Question Group Details</h3>
              </div>
              <div class="list-group">
                <div class="list-group-item">
					<form id="groups">
						<div class="search-form">
							<div class="form-group">	
							<label for="date-input" class="col-2 col-form-label">Enter groups on seperate lines with questions space delimited</label>
								<div class="col-10">
									 <textarea class="form-control" rows="3" id="groups-input" form="groups"></textarea>
								</div>
							</div>
						</div>
					</form>	
                </div>
              
                 <div class="panel-footer">
                  <small>Please enter the question groups on seperate lines using spaces to split each question in the group</small>
                </div>
              </div>
              </div>

            </div><!--Group card-->
            
            <!-- Withdrawn details -->
          <div id="withdrawnCard">

            <div class="panel panel-default">
              <div class="panel-heading clearfix">
                <h3 class="panel-title pull-left">Withdrawn Questions</h3>
              </div>
              <div class="list-group">
                <div class="list-group-item">
					<form id="groups">
						<div class="search-form">
							<div class="form-group">	
							<label for="date-input" class="col-2 col-form-label">Enter withdrawn questions (s1 t1) seperated by spaces</label>
								<div class="col-10">
									 <input type="text" class="form-control" id="withdrawn-input" form="withdrawn"></input>
									 <br />
								</div>
							</div>
						</div>
					</form>	
                </div>
              
                 <div class="panel-footer">
                  <small>To un-withdraw any questions just reload questions with them removed from the box</small>
                </div>
              </div>
              </div>

            </div><!--Group card-->

        </div><!--list-details-->

    </div><!--row-->


  </div><!--container-->

	<?php include 'template/footer.php'; ?>

  
   <?php include 'template/core.php'; ?>
   
  </body>
</html>
