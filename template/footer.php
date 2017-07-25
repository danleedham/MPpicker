  <!-- fixed tabbed footer -->
  <div class="navbar navbar-default navbar-fixed-bottom">

    <div class="container">

      <div class="bootcards-desktop-footer clearfix">

      <div class="btn-group">
        <a href="index.php" class="btn btn-default <?php if ($_SERVER['REQUEST_URI'] === "/parliament/test/index.php") { echo 'active'; } ?>">
          <i class="fa fa-2x fa-font"></i>Member Picker
        </a>
        <a href="committee.php" class="btn btn-default <?php if ($_SERVER['REQUEST_URI'] === "/parliament/test/committee.php") { echo 'active'; } ?>">
          <i class="fa fa-2x fa-users"></i>Committees and Departments
        </a>
        <a href="who.php" class="btn btn-default <?php if ($_SERVER['REQUEST_URI'] === "/parliament/test/who.php") { echo 'active'; } ?> ">
          <i class="fa fa-2x fa-dashboard"></i>Guess Who!
        </a>
      </div>
    </div>
   </div>

  </div><!--footer-->