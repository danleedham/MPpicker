<?php include 'template/core/header.php'; ?>

  	<title>Parliamentary Stacker</title>
  	<script>
	window.onload = function() {
		$("#test").load('template/fixed-queries/networktests.php',function() {
		    console.log('Tests Complete');
		});		
	};
  	</script>
  </head>
  <body>	
 
	<div class="container bootcards-container push-right">
		<div class="row">
			<div class="col-sm-12 bootcards-list" data-title="Mobile Menu">
				<div class="panel panel-default bootcards-summary">
				  <div class="panel-heading">
					<h3 class="panel-title">Parliamentary Stacker</h3>
				  </div>
				  <div class="panel-body">
					<div class="row">
					  <div class="col-xs-6 col-md-3">
						<a class="bootcards-summary-item" href="search.php">
						  <img src="favicons/android-chrome-192x192.png" height="64" width="64">
						  <h4>Member Search <span class="label label-info"><?php $house = "Commons"; include "template/fixed-queries/fixed-numberofmembers.php" ?></span></h4>
						</a>
					  </div>
					  <div class="col-xs-6 col-md-3">
						<a class="bootcards-summary-item" href="qs.php">
						  <img src="favicons/android-chrome-192x192.png" height="64" width="64">
						  <h4>Question Stacker <span class="label label-danger"><?php echo date("Y-m-d");?></span></h4>
						</a>
					  </div>
					  <div class="col-xs-6 col-md-3">
						<a class="bootcards-summary-item" href="who.php">
						  <img src="favicons/android-chrome-192x192.png" height="64" width="64">
						  <h4>Guess Who? <span class="label label-warning"><?php $house = "Lords"; include "template/fixed-queries/fixed-numberofmembers.php" ?></span></h4>
						</a>
					  </div>
					   <div class="col-xs-6 col-md-3">
						<a class="bootcards-summary-item" href="windups.php">
						  <img src="favicons/android-chrome-192x192.png" height="64" width="64">
						  <h4>Wind ups <span class="label label-success"><?php include "template/fixed-queries/fixed-latestleftlords.php" ?></span></h4>
						</a>
					  </div>
					 </div>
					<div class="row" id="test">
					 
					 </div>
				  </div>
				  <div class="panel-footer">
					 <small>Data from UK Parliament - <a href="http://data.parliament.uk/">Data Dot Parliament (DDP)</a></small>
				  </div>
				</div>
			</div>	
		</div>
	</div>		


<?php include 'template/core/header.php'; ?>
