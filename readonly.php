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
		<script src="bower_components/image-map-resizer/js/imageMapResizer.min.js"></script>
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
				
				// CHANGE SITE TITLE TO "IFT" ON MOBILE
			    var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
				if (width <= 325) {
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
						"html": categories.join(", ")
					}).insertAfter($("div #page_title:eq(1)"));
					
				});
				
				// LOAD FILENAME INTO ID FOR EACH IMG
				$("img").each(function () {
					var path = $(this).attr("src");
					var name = path.substr(4, path.length - 8);
					$(this).attr("id", name);
				});
				
				<?php 
					
					if ($page_id == "additional_pattern_insights") {
						
						echo "// GET IMAGE MAPS\n";
						echo "$('img').each(function() {
					var id = $(this).attr('id');
					switch(id) {
						case 'subpatterns':
							$(this).attr('usemap', '#subpatternsmap');
                            $(this).after(\"<map name='subpatternsmap' > <area  alt='Information Feature Decorator' title='Information Feature Decorator' href='?id=information_feature_decorator' shape='rect' coords='24,56,193,103' style='outline:none;' target='_self'     /> <area  alt='Fault Localization' title='Fault Localization' href='?id=fault_localization' shape='rect' coords='207,53,376,100' style='outline:none;' target='_self'     /> <area  alt='Gather Together' title='Gather Together' href='?id=gather_together' shape='rect' coords='403,53,572,100' style='outline:none;' target='_self'     /> <area  alt='Software Visualization' title='Software Visualization' href='?id=software_visualization' shape='rect' coords='599,54,768,101' style='outline:none;' target='_self'     /> <area  alt='Signpost' title='Signpost' href='?id=signpost' shape='rect' coords='798,55,967,102' style='outline:none;' target='_self'     /> <area  alt='Cue Decoration' title='Cue Decoration' href='?id=cue_decoration' shape='rect' coords='26,192,195,239' style='outline:none;' target='_self'     /> <area  alt='Regression Fault Localization' title='Regression Fault Localization' href='?id=regression_fault_localization' shape='rect' coords='206,192,375,239' style='outline:none;' target='_self'     /> <area  alt='Reduce Duplicate Information' title='Reduce Duplicate Information' href='?id=reduce_duplicate_information' shape='rect' coords='290,259,459,306' style='outline:none;' target='_self'     /> <area  alt='Path Search' title='Path Search' href='?id=path_search' shape='rect' coords='508,261,677,308' style='outline:none;' target='_self'     /> <area  alt='Visualize Topology' title='Visualize Topology' href='?id=visualize_topology' shape='rect' coords='599,191,768,238' style='outline:none;' target='_self'     /> <area  alt='Rename Refactoring' title='Rename Refactoring' href='?id=rename_refactoring' shape='rect' coords='800,192,969,239' style='outline:none;' target='_self'     /> <area shape='rect' coords='994,337,996,339' alt='Image Map' style='outline:none;' title='Image Map' href='http://www.image-maps.com/index.php?aff=mapped_users_0' /> </map>\");
							break;
						case 'concurrent':
							$(this).attr('usemap', '#concurrentmap');
							$(this).after(\"<map name='concurrentmap' > <area  alt='Software Visualization' title='Software Visualization' href='?id=software_visualization' shape='rect' coords='302,22,421,137' style='outline:none;' target='_self'     /> <area  alt='Bookmark' title='Bookmark' href='?id=bookmark' shape='rect' coords='513,33,632,148' style='outline:none;' target='_self'     /> <area  alt='Dashboard' title='Dashboard' href='?id=dashboard' shape='rect' coords='87,102,206,217' style='outline:none;' target='_self'     /> <area  alt='Cue Decoration' title='Cue Decoration' href='?id=cue_decoration' shape='rect' coords='404,172,523,287' style='outline:none;' target='_self'     /> <area  alt='Structural Relatedness' title='Structural Relatedness' href='?id=structural_relatedness' shape='rect' coords='729,136,848,251' style='outline:none;' target='_self'     /> <area  alt='Notifier' title='Notifier' href='?id=notifier' shape='rect' coords='37,250,156,365' style='outline:none;' target='_self'     /> <area  alt='Community Portal' title='Community Portal' href='?id=community_portal' shape='rect' coords='236,314,355,429' style='outline:none;' target='_self'     /> <area  alt='Gather Together' title='Gather Together' href='?id=gather_together' shape='rect' coords='654,313,773,428' style='outline:none;' target='_self'     /> <area  alt='Signpost' title='Signpost' href='?id=signpost' shape='rect' coords='833,388,952,503' style='outline:none;' target='_self'     /> <area  alt='Filtering' title='Filtering' href='?id=filtering' shape='rect' coords='600,473,719,588' style='outline:none;' target='_self'     /> <area  alt='Path Search' title='Path Search' href='?id=path_search' shape='rect' coords='196,444,315,559' style='outline:none;' target='_self'     /> <area  alt='Visualize Topology' title='Visualize Topology' href='?id=visualize_topology' shape='rect' coords='25,446,144,561' style='outline:none;' target='_self'     /> <area shape='rect' coords='957,589,959,591' alt='Image Map' style='outline:none;' title='Image Map' href='http://www.image-maps.com/index.php?aff=mapped_users_0' /> </map>\");
							break;
						case 'pipeline':
							$(this).attr('usemap', '#pipelinemap');
							$(this).after(\"<map name='pipelinemap' > <area  alt='Lexical Similarity' title='Lexical Similarity' href='?id=lexical_similarity' shape='rect' coords='33,51,202,100' style='outline:none;' target='_self'     /> <area  alt='Patch Profitability' title='Patch Profitability' href='?id=patch_profitability' shape='rect' coords='623,56,792,105' style='outline:none;' target='_self'     /> <area  alt='Recollection' title='Recollection' href='?id=recollection' shape='rect' coords='623,184,792,233' style='outline:none;' target='_self'     /> <area  alt='Filtering' title='Filtering' href='?id=filtering' shape='rect' coords='34,313,203,362' style='outline:none;' target='_self'     /> <area  alt='Shopping Cart' title='Shopping Cart' href='?id=shopping_cart' shape='rect' coords='623,313,792,362' style='outline:none;' target='_self'     /> <area shape='rect' coords='808,383,810,385' alt='Image Map' style='outline:none;' title='Image Map' href='http://www.image-maps.com/index.php?aff=mapped_users_0' /> </map>\");
							break;
						case 'sameinputs':
							$(this).attr('usemap', '#sameinputsmap');
							$(this).after(\"<map name='sameinputsmap'> <area  alt='Feature Tracing' title='Feature Tracing' href='?id=feature_tracing' shape='rect' coords='194,51,326,98' style='outline:none;' target='_self'     /> <area  alt='Test Coverage' title='Test Coverage' href='?id=test_coverage' shape='rect' coords='495,50,627,97' style='outline:none;' target='_self'     /> <area  alt='Impact Location' title='Impact Location' href='?id=impact_location' shape='rect' coords='646,51,778,98' style='outline:none;' target='_self'     /> <area  alt='Documentation Processing' title='Documentation Processing' href='?id=documentation_processing' shape='rect' coords='795,52,927,99' style='outline:none;' target='_self'     /> <area  alt='Regression Fault Localization' title='Regression Fault Localization' href='?id=regression_fault_localization' shape='rect' coords='42,348,174,395' style='outline:none;' target='_self'     /> <area  alt='Fault Localization' title='Fault Localization' href='?id=fault_localization' shape='rect' coords='193,349,325,396' style='outline:none;' target='_self'     /> <area  alt='Specification Matcher' title='Specification Matcher' href='?id=specification_matcher' shape='rect' coords='342,349,474,396' style='outline:none;' target='_self'     /> <area  alt='Structural Relatedness' title='Structural Relatedness' href='?id=structural_relatedness' shape='rect' coords='645,349,777,396' style='outline:none;' target='_self'     /> <area  alt='Lexical Similarity' title='Lexical Similarity' href='?id=lexical_similarity' shape='rect' coords='794,349,926,396' style='outline:none;' target='_self'     /> <area shape='rect' coords='954,420,956,422' alt='Image Map' style='outline:none;' title='Image Map' href='http://www.image-maps.com/index.php?aff=mapped_users_0' /> </map>\");
							break;
					}
				});";
					
						echo "// UPDATE IMAGE MAPS\n";
						echo "$('map').imageMapResize();\n";
					}
										
				?>
				
			});			
			
		</script>
		<!-- END JAVASCRIPT -->
		
	</body>
</html>