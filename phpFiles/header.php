<?php
/**
 * Header.php
 * Contains the head information and nav bar needed in every
 * php page for the Hotel Serenity website.
 *
 * Created by: LeYing Tran
 * Date: 15/09/2015
 * Last Modified: 15/09/2015
 */

/* Include commonly used php pages */
    include("config.php");
    include("checkFunctions.php");
    include("bookingsManager.php");
    include("hotelManager.php");
?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">     
    <title>Hotel Serenity</title>
    
    <?php
    if (isset($styleList) && is_array($styleList)) {
        foreach ($styleList as $style) {
            echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"$style\">";   
        }
    }

    if (isset($scriptList) && is_array($scriptList)) {
			foreach ($scriptList as $script) {
				echo "<script src=\"$script\"></script>";
			}
		}
	?>
</head>

<body>
    <nav class="navbar navbar-transparent">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-options">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
                
            <div class="collapse navbar-collapse" id="navbar-options">
                <ul class="nav navbar-nav">
                    <?php
                        $currentPage = array(
                            "index.php" => "Home",
                            "guestrooms.php" => "Guestrooms and Suites",
                            "reservations.php" => "Reserve"
                        );
        
if (isset($currentPage) && is_array($currentPage)) {
    foreach($currentPage as $page => $navtitle) {
        if ($page === basename($_SERVER['PHP_SELF'])) {
                                echo "<li class=\"notAllowed\">$navtitle</li>";
							} else {
								echo "<li><a href=\"$page\">$navtitle</a></li>";
							}
						}
					}
                        ?>
                </ul>
            </div>
        </div>
    </nav>
