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
		$contents = file_get_contents('home.txt');
	}
	
	if ($page_id == "putting_ift_into_practice") {
		$title = "Putting IFT into Practice";
		$contents = file_get_contents('putting_ift_into_practice.txt');
		
		$category = "";
		$counter = 0;
		
		$patterns = file_get_contents('ift_patterns.json');
		$decoded_patterns = json_decode($patterns);
		foreach ($decoded_patterns as $pattern) {
			if ($pattern->{'category'} != $category) {
				$category = $pattern->{'category'};
				if ($counter > 0) $contents .= "</div></div>"; // end category
				if ($counter % 4 == 0 && $counter > 0) $contents .= "</div>"; //end row
				if ($counter % 4 == 0) $contents .= "<div class='row'>"; //start row
				$contents .= "<div class='col-md-4'><div class='list-group'>";
				$contents .= "<a href='#' class='list-group-item active non-link'>".$pattern->{'category'}.":</a>";
				$counter++;
			}
			$pattern_name = $pattern->{'pattern'};
			$pattern_link = str_replace(' ', '_', $pattern_name);
			$pattern_link = strtolower($pattern_link);
			$contents .= "<a href='$parent_page_title?id=$pattern_link' class='list-group-item'>$pattern_name</a>";
		}
	}
	
	if ($page_id == "about") {
		$title = "About";
		$contents = file_get_contents("profiles/bio.txt");
	}
	
/*
	if ($page_id == "additional_pattern_insights") {
		$contents = file_get_contents("additional_pattern_insights.txt");
	}
*/
	
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
		<?php if ($page_id == "home") echo "<link rel='stylesheet' href='home.css'>"; ?>
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
		<nav id="patternsMenu" class="navmenu navmenu-default navmenu-fixed-right">
			<p class="navmenu-brand">Patterns:</p>
			<ul class="nav navmenu-nav">
				
				<!-- LIST PATTERNS HERE: -->
				
				<?php 
					
					$category = "";
					$counter = 0;
					$patterns = file_get_contents('ift_patterns.json');
					$decoded_patterns = json_decode($patterns);
					foreach ($decoded_patterns as $pattern) {
						if ($pattern->{'category'} != $category) {
							$category = $pattern->{'category'};
							if ($counter > 0) echo "</ul>\n<hr>\n";
							echo "<li class='group_name'>".$pattern->{'category'}.":</li>\n";
							echo "<ul>\n";
							$counter++;
						}
						$pattern_name = $pattern->{'pattern'};
						$pattern_link = str_replace(' ', '_', $pattern_name);
						$pattern_link = strtolower($pattern_link);
						echo "<li><a href='$parent_page_title?id=$pattern_link'>$pattern_name</a></li>\n";
					}
					
				?>
				
				<!-- END PATTERNS LIST -->
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
						<li><a href="<?php echo $parent_page_title; ?>?id=putting_ift_into_practice">Putting IFT into Practice</a>
						<li><a href="<?php echo $parent_page_title; ?>?id=additional_pattern_insights">Additional Pattern Insights</a>
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
		
		<!-- FOOTER -->
		<div id="footer" class="container">
			<hr>
			<p>Except where otherwise noted, content on this wiki is licensed under the following license: <a href="http://www.gnu.org/licenses/fdl-1.3.html">GNU Free Documentation License 1.3</a></p>
			<p>This material is based in part upon work supported by the National Science Foundation under Grant CCF-1302113. Any opinions, findings, and conclusions or recommendations expressed in this material are those of the author(s) and do not necessarily reflect the views of the National Science Foundation.</p>
		</div>
		<!-- END FOOTER -->
	
		<!-- JAVASCRIPT -->
		<script src="bower_components/jquery/dist/jquery.js"></script>
		<script src="bower_components/bootstrap/dist/js/bootstrap.js"></script>
		<script src="bower_components/jasny-bootstrap/dist/js/jasny-bootstrap.js"></script>
		<script src="bower_components/MathJax/MathJax.js"></script>
		<script src="bower_components/MathJax/config/TeX-AMS-MML_HTMLorMML.js"></script>
<!-- 		<script src="bower_components/masonry/dist/masonry.pkgd.min.js"></script> -->
		<script>
			
			//MATHJAX CONFIG
			MathJax.Hub.Config({
				tex2jax: {
					inlineMath: [['$', '$'], ['\\(', '\\)']],
					processEscapes: true
				}
			});			
			
			// BASIC PAGE SETUP
			$(document).ready(function() {
				
				// IF ON MOBILE CHANGE SITE TITLE TO "IFT"
			    var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
				if (width <= 320) {
					$(".navbar-brand").text("IFT");
					$("nav#patternsMenu").addClass("off-canvas");
					$("button.btn.btn-default.navbar-btn").show();
				}
				
				// CATEGORIES LIST UNDER EACH PATTERN
				$.getJSON("ift_patterns.json", function(data) {
					var categories = [];
					$.each(data, function(key, val) {
						if (val['pattern'] == "<?php echo $title; ?>") {
							categories.push("<li><h6>" + val['category'].substring(3) + "</h6></li>");
						}
					});
					
					$("<ul>", {
						"class": "categories-list",
						html: categories.join("")
					}).insertAfter($("#page_title:eq(1)"));
				});
				
			});			
			
		</script>
		<!-- END JAVASCRIPT -->
		
	</body>
</html>