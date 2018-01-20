<!-- fixed tabbed footer -->
<div class="footer-container">
	<div id="navbar" class="navbar navbar-default navbar-fixed-bottom">
		<div class="bootcards-desktop-footer clearfix">
			<div class="btn-group">
			<a href="search.php" class="btn btn-default <?php if ($_SERVER['REQUEST_URI'] === "/search.php") { echo 'active'; } ?>">
			<i class="fa fa-vcard-o"></i>Member Search
			</a>
			<a href="qs.php" class="btn btn-default <?php if ($_SERVER['REQUEST_URI'] === "/qs.php") { echo 'active'; } ?>">
			<i class="fa fa-users"></i>Questions
			</a>
			<a href="windups.php" class="btn btn-default <?php if ($_SERVER['REQUEST_URI'] === "/windups.php") { echo 'active'; } ?>">
			<i class="fa fa-list-alt "></i>Windups
			</a>
			<a href="who.php" class="btn btn-default <?php if ($_SERVER['REQUEST_URI'] === "/who.php") { echo 'active'; } ?> ">
			<i class="fa fa-address-book-o"></i>Guess Who!
			</a>
			</div>
		</div>
	</div>
</div><!--footer-->