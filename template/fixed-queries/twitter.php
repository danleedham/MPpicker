<?php
	if(isset($_GET["handle"])){
		$twitter = $_GET["handle"];
	
		echo '<div class="list-group-item">
		<a class="twitter-timeline" href="'.$twitter.'" 
					data-chrome="nofooter noheader noborders"  
					data-tweet-limit="4"
					data-show-replies="true"
					data-theme="dark"
				>Tweets</a> 
		<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script></div> ';
	}
?>