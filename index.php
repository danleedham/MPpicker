<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="google" value="notranslate">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <title>Parliamentary Stacker</title>
	<div class="container bootcards-container push-right">
		<div class="row">
			<div class="col-sm-12 bootcards-list" data-title="Mobile Menu">
				<div class="panel panel-default bootcards-summary">
				  <div class="panel-heading">
					<h3 class="panel-title">Parliamentary Stacker</h3>
				  </div>
				  <div class="panel-body">
					<div class="row">
					  <div class="col-xs-6 col-sm-4">
						<a class="bootcards-summary-item" href="stacker.php">
						  <i class="fa fa-3x fa-users"></i>
						  <h4>Stacker <span class="label label-info">650</span></h4>
						</a>
					  </div>
					  <div class="col-xs-6 col-sm-4">
						<a class="bootcards-summary-item" href="qs.php">
						  <i class="fa fa-3x fa-building-o"></i>
						  <h4>Questions <span class="label label-danger"><?php echo date("Y-m-d");?></span></h4>
						</a>
					  </div>
					  <div class="col-xs-6 col-sm-4">
						<a class="bootcards-summary-item" href="who.php">
						  <i class="fa fa-3x fa-clipboard"></i>
						  <h4>Guess Who? <span class="label label-warning">650</span></h4>
						</a>
					  </div>
					 </div>
					<div class="row">
					  <div class="col-xs-12 col-sm-6">
					<?php 
					 	$xmlmember = @simplexml_load_file('http://data.parliament.uk/membersdataplatform/xml/BasicDetails.xml');
						if (!$xmlmember) {
							$members = "danger";
						} else { 
							$members = "success";
						}
					?>
					 <?php 
						$xmloral = @simplexml_load_file('http://lda.data.parliament.uk/commonsoralquestions.xml?_view=Commons+Oral+Questions&AnswerDate=2017-07-12&&_pageSize=1');
						if (!$xmloral) {
							$oral = "danger";
						} else { 
							$oral = "success";
						}
					?>
					 <?php 
					 	$imageurl = 'https://api20170418155059.azure-api.net/photo/S3bGSTqn.jpeg?crop=CU_1:1&width=186&quality=80';
						if (@getimagesize($imageurl)){
							$beta = "success";
						} else { 
							$beta = "danger";
						} 
					?>
						  <h4><span class="label label-<?php echo $members; ?>">&nbsp</span> Members Data Platform</h4>
						  <h4><span class="label label-<?php echo $oral; ?>">&nbsp</span> Commons Oral Questions</h4>
						  <h4><span class="label label-<?php echo $beta; ?>">&nbsp</span> Member Beta Images</h4>
					  </div>
					 </div>
				  </div>
				  <div class="panel-footer">
					 <small>Data from UK Parliament - <a href="http://data.parliament.uk/membersdataplatform/">Members' Names Data Platform</a></small>
				  </div>
				</div>
			</div>	
		</div>
	</div>		


<?php include 'template/headinc.php'; ?>
