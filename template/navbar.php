  <!-- fixed top navbar -->
  <div class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
        <!--right aligned button-->
      <button type="button" class="btn btn-primary hidden-sm" style="float:right;margin-right:15px;margin-top:7px;" onclick="location.reload();">
        <i class="fa fa-history btn-primary"></i><span>Reset</span>
      </button> 

      <!--navbar menu options: shown on desktop only -->
      <div class="navbar-collapse">
        <ul class="nav navbar-nav">
          <li <?php if ($_GET["house"] != "Lords") { echo 'class="active"'; } ?>>
            <a href="?house=Commons" style="color:#447a1c">
              <i class="fa fa-university"></i> House of Commons
            </a>
          <li <?php if ($_GET["house"] === "Lords") { echo 'class="active"'; } ?>>
            <a href="?house=Lords" style="color:#b50938">
              <i class="fa fa-university"></i> House of Lords
            </a>
          </li>
          </li>
        </ul>
      </div>          
    </div>
  </div>  