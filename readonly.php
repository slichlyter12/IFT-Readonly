<?php
	
	$parent_page_title = "readonly.php";
	include_once('wiki_markdown.php');
	
	// Define variables
	$page_id = "";
	$datadir = dirname(__FILE__)."/data/pages/";
	$title = "Home";
	$link_array = array();
	$name_array = array();
		
	//check if page given
	if (isset($_GET["id"])) {
		$page_id = htmlspecialchars($_GET["id"]);
	}
	
	// Check if directory is given, set data directory to given directory
	if (preg_match('/http:(.*)/', $page_id, $given_dir)) {
		header("Location: ".$given_dir[0]);
		exit();
	} else if (preg_match('/(.*):(.*)/', $page_id, $given_dir)) {
		$datadir = dirname(__FILE__)."/data/pages/".$given_dir[1]."/";
		$page_id = $given_dir[2];
	}
	
	// Iterate through directory to find current page and build navigation
	$dir = new DirectoryIterator($datadir);
	foreach($dir as $fileinfo) {
		if (!$fileinfo->isDot() && !$fileinfo->isDir() && $fileinfo != null) {
			
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
<html>
	<head>
		<title>IFT | <?php echo $title; ?></title>
		<link rel="stylesheet" type="text/css" href="style.css">
		<link rel="icon" type="image/png" href="IFT-Favicon.png">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<script>
			// Show and hide pattern list menu
			$(document).ready(function() {				
				$("#patterns_list_parent").mouseenter(function() {
					$("#patterns_list").show();
				});
				$("#patterns_list_parent").mouseleave(function() {
					$("#patterns_list").hide();
				});
				$("#patterns_list").mouseenter(function() {
					$("#patterns_list").show();
				});
				$("#patterns_list").mouseleave(function() {
					$("#patterns_list").hide();
				})
			});
		</script>
	</head>
	<body>
		<div id="header">
			<h2 id="site_title">Information Foraging Theory</h2>
			<div id="menu">
				<a href="readonly.php?id=wiki:a_short_primer_to_information_foraging_theory">IFT Primer</a>
				<a href="readonly.php?id=about">About</a>
				<a id="patterns_list_parent" href="#">Patterns &#x25BE;</a>
				<ul id="patterns_list">
					<?php
						for ($index = 1; $index < count($link_array); $index++) {
							if ($name_array[$index] != "Start" && !empty($name_array[$index])) {
								echo "<li><a href='readonly.php?id=".$link_array[$index]."'>".$name_array[$index]."</a></li>\n";
								if ($index % 5 == 0) {
									echo "<hr class='pattern_separator'>";
								}
							}
						}
					?>
				</ul>
			</div>
		</div>
		<div id="content">
			<?php if (isset($contents)) echo wiki_markdown($contents); ?>
		</div>
	</body>
</html>
