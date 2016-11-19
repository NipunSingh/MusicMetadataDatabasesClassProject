<div class="navbar-wrapper">
  <div class="container">
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php">Music Metadata</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-left">
                <li><a href="playlists.php">Playlists</a></li>
            </ul>
            <div class="nav navbar-nav navbar-right">
                <ul class="nav navbar-nav">
                    <li>
                        <?php
                            if (isset($_SESSION['username'])) {
                                echo "<a href='accountEdit.php'>".$_SESSION['username']."   <span class='glyphicon glyphicon-user'></span></a>";
                            } else {
                                echo "<a href='signin.php'>Sign In   <span class='glyphicon glyphicon-log-in'></span></a>";
                            }
                        ?>
                    </li>
                    <li>
                        <?php
                            if (isset($_SESSION['username'])) {
                                echo "<a href='logout.php'>Logout   <span class='glyphicon glyphicon-log-out'></span></a>";
                            } else {
                                echo "<a href='signup.php'>Sign Up   <span class='glyphicon glyphicon glyphicon-plus'></span></a>";
                            }
                        ?>
                    </li>
                </ul>
            </div>
        </div><!--/.navbar-collapse -->
      </div>
    </nav>
  </div>
</div>
<div class="spacer-top"></div>
<div class="container">