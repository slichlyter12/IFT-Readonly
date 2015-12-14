<?php
	
	$parent_page_title = "readonly.php";
	include_once('wiki_markdown.php');
	
	// Initialize variables
	$page_id = "";
	$datadir = dirname(__FILE__)."/data/pages/";
	$title = "Home";
	$link_array = array();
	$name_array = array();
		
	//check if page given
	if (isset($_GET["id"])) {
		$page_id = htmlspecialchars($_GET["id"]);
	} else {
		$page_id = "home";
		$title = "Home";
		$contents = "
					====== Information Foraging Theory ======
					[{{:poster.png|}}]
					";
	}
	
	if ($page_id == "about") {
		$title = "About";
		$contents = "
					====== About ======
					
					===== Where did this wiki come from? =====
					A lot of research and some secret sauce.
					
					===== Our Team =====
					A grad student, a professor and two undergraduate researchers walk into a bar&hellip;oh, wrong story.
					
					<div class='row'>
						<div class='col-md-3'>
							<div class='thumbnail'>
								<img src='http://i1.rgstatic.net/ii/profile.image/AS%3A279830521237508%401443728203476_l' alt='Tahmid Nabi'>
								<div class='caption'>
									<h3>Tahmid Nabi</h3>
									<p>The Grad Student</p>
								</div>
							</div>
						</div>
						<div class='col-md-3'>
							<div class='thumbnail'>
								<img src='http://eecs.oregonstate.edu/sites/eecs.oregonstate.edu/files/facultystaff/scaffidi_0.jpg' alt='Christopher Scaffidi'>
								<div class='caption'>
									<h3>Christopher Scaffidi</h3>
									<p>The Professor</p>
								</div>
							</div>
						</div>
						<div class='col-md-3'>
							<div class='thumbnail'>
								<img src='http://bloximages.chicago2.vip.townnews.com/gazettetimes.com/content/tncms/assets/v3/editorial/e/78/e786d090-02fe-11e2-9a31-001a4bcf887a/505ad69d00331.preview-699.jpg' alt='Kyle Sweeney'>
								<div class='caption'>
									<h3>Kyle Sweeney</h3>
									<p>Undergraduate #1</p>
								</div>
							</div>
						</div>
						<div class='col-md-3'>
							<div class='thumbnail'>
								<img src='https://pbs.twimg.com/profile_images/624103240850771968/AZuE-N9R_400x400.jpg' alt='Sam Lichlyter'>
								<div class='caption'>
									<h3>Sam Lichlyter</h3>
									<p>Undergraduate #2</p>
								</div>
							</div>
						</div>
					</div>
							
					";
	}
	
	// Check if directory is given, set data directory to given directory
	if (preg_match('/http(.*)/', $page_id, $given_dir)) {
		header("Location: ".$given_dir[0]);
		exit(0);
	} else if (preg_match('/(.*):(.*)/', $page_id, $given_dir)) {
		$datadir = dirname(__FILE__)."/data/pages/".$given_dir[1]."/";
		$page_id = $given_dir[2];
	}
	
	// Iterate through directory to find current page and build navigation
	$dir = new DirectoryIterator($datadir);
	foreach($dir as $fileinfo) {
		if (!$fileinfo->isDot() && !$fileinfo->isDir() && $fileinfo != null && $fileinfo != ".txt") {
			
			//get file path
			$filename = $fileinfo->getFilename();
			
			//turn file path into human readable name
			$readable_filename = str_replace('.txt', '', $filename);
			$readable_filename = str_replace('_', ' ', $readable_filename);
			$readable_filename = ucwords($readable_filename);
			
			//check if requested page found
			if ($filename == $page_id.".txt") {
				$contents = file_get_contents($datadir.$filename);
				$title = $readable_filename;
			}
			
			//take .txt off end of filename
			$filename = str_replace('.txt', '', $filename);
			
			//add name and file path to corresponding arrays
			array_push($link_array, $filename);
			array_push($name_array, $readable_filename);
		}
	}
	
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" type="image/png" href="IFT-Favicon.png">
		<link rel="stylesheet" media="screen" href="bower_components/bootstrap/dist/css/bootstrap.css">
		<link rel="stylesheet" media="screen" href="bower_components/jasny-bootstrap/dist/css/jasny-bootstrap.css">
		<link rel="stylesheet" href="style.css">
		<title>IFT | <?php echo $title; ?></title>
	</head>
	<body>
		
		<!-- MODAL -->
		<div class="modal fade" id="primer_modal" tabindex="-1" role="dialog" aria-labledby="IFT Primer">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">IFT Primer</h4>
					</div>
					<div class="modal-body">
						<?php 
							$primer_path = dirname(__FILE__)."/data/pages/wiki/a_short_primer_to_information_foraging_theory.txt";
							$primer_contents = file_get_contents($primer_path);
							echo wiki_markdown($primer_contents);
						?>						
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
		<!-- END MODAL -->
		
		<!-- PATTERNS MENU -->
		<nav id="patternsMenu" class="navmenu navmenu-default navmenu-fixed-right offcanvas">
			<p class="navmenu-brand">Patterns:</p>
			<ul class="nav navmenu-nav">
<!--
				LIST PATTERNS HERE:
				
				<li><a href="<?php echo $parent_page_title; ?>?id=cue_decoration">Cue Decoration</a></li>
				<li><a href="<?php echo $parent_page_title; ?>?id=bookmark">Bookmark</a></li>
				<li><a href="<?php echo $parent_page_title; ?>?id=shopping_cart">Shopping Cart</a></li>
-->

				<!-- TEMPORARY LIST OF PATTERNS: -->
				<?php
					for ($index = 1; $index < count($link_array); $index++) {
						if ($name_array[$index] != "Start" && !empty($name_array[$index]) && $name_array[index] != "Patterns List") {
							echo "<li><a href='$parent_page_title?id=".$link_array[$index]."'>".$name_array[$index]."</a></li>\n";
							if ($index % 5 == 0) {
								echo "<hr class='pattern_separator'>";
							}
						}
					}
				?>
			</ul>
		</nav>
		<!-- END PATTERNS MENU -->

		<!-- NAV BAR -->
		<nav class="navbar navbar-default navbar-fixed-top">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="<?php echo $parent_page_title; ?>">Information Foraging Theory</a>
				</div>
		
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<li><a href="#" data-toggle="modal" data-target="#primer_modal">IFT Primer</a></li>
						<li><a href="<?php echo $parent_page_title; ?>?id=about">About</a></li>
					</ul>
					<ul class="navbar navbar-nav navbar-right">
						<button type="button" class="btn btn-default navbar-btn" data-toggle="offcanvas" data-target="#patternsMenu" data-canvas="body">
							Patterns
						</button>
					</ul>
				</div>
			</div>
		</nav>
		<!-- END NAV BAR -->
					
		<!-- CONTENT -->
		<div id="content" class="container">
			<?php if (isset($contents)) echo wiki_markdown($contents); ?>
		</div>
		<!-- END CONTENT -->
		
		<div id="footer" class="container">
			<hr>
			<p>Except where otherwise noted, content on this wiki is licensed under the following license: <a href="http://www.gnu.org/licenses/fdl-1.3.html">GNU Free Documentation License 1.3</a></p>
			<p>This material is based in part upon work supported by the National Science Foundation under Grant CCF-1302113. Any opinions, findings, and conclusions or recommendations expressed in this material are those of the author(s) and do not necessarily reflect the views of the National Science Foundation.</p>
		</div>
	
		<!-- JAVASCRIPT -->
		<script src="bower_components/jquery/dist/jquery.js"></script>
		<script src="bower_components/bootstrap/dist/js/bootstrap.js"></script>
		<script src="bower_components/jasny-bootstrap/dist/js/jasny-bootstrap.js"></script>
		<script src="bower_components/MathJax/MathJax.js"></script>
		<script src="bower_components/MathJax/config/TeX-AMS-MML_HTMLorMML.js"></script>
		<script>
			
			//MATHJAX CONFIG
			MathJax.Hub.Config({
				tex2jax: {
					inlineMath: [['$', '$'], ['\\(', '\\)']],
					processEscapes: true
				}
			});
			
			//TODO: JASNY BOOTSTRAP OFF CANVAS MANIPULATION --> DOWN AND OVER ~80%
			
			
			// BASIC PAGE SETUP
			$(document).ready(function() {
				
				// IF ON MOBILE CHANGE SITE TITLE TO "IFT"
			    var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
				if (width <= 320) {
					$(".navbar-brand").text("IFT");
				}
			});
			
		</script>
		<!-- END JAVASCRIPT -->
		
	</body>
</html>