<?php
#
# Wiki Markdown - A DokuWiki-to-HTML conversion function
#
# Copyright (c) 2015 Samuel Lichlyter
# <http://lichlyterinc.com/>
# 
# Copyright (c) 2015 Oregon State University
# <http://oregonstate.edu/>
#

	# GLOBALS:
	$page_title = 'readonly.php';
	
	// Check if run via command line, check file else use first argument
	if (isset($argv) && !empty($argv[1])) {
		if (is_file($argv[1])) {
			$content = file_get_contents($argv[1]);
		} else {
			$content = $argv[1];
		}
		
		echo wiki_markdown($content);
	}
	
	
	# FUNCTION DEFINITIONS:
	
	function wiki_markdown($content) {
		$content = __removeExtras($content);
		$content = __convertHeaders($content);
		$content = __convertParagraphs($content);
		$content = __convertImages($content);
		$content = __convertLists($content);
		$content = __convertLinks($content);
		$content = __convertCitations($content);
		$content = __convertBold($content);
		$content = __convertItalics($content);
		$content = __fixHeaders($content);
		
		$content = __fixASCIIChars($content);
		
		return $content;
	}
	
	function __removeExtras($content) {
		
		$content = preg_replace('/~~.+?~~/', '', $content);
		$content = preg_replace('/<html\b[^>]*>(.*?)<\/html>/i', '', $content);
		return $content;
	}
	
	function __convertHeaders($content) {
		
		## regex101: https://regex101.com/r/nQ8oJ9/1
		
		## Loop through headers from largest to smallest; $n is the number of '=' on each side
		for ($n = 6; $n > 3; $n--) {
			
			## Find header
			preg_match_all("/={".$n."}(.+?)={".$n."}(.+?)(?=={".$n."}|\Z)/ms", $content, $headers);
			
			## Determine which header value is being replaced
			$h = abs(7 - $n);
						
			## For each header found in that header style/number
			for ($x = 0; $x < count($headers[0]); $x++) {
				
				## if header is style 2 make main section
				if ($h == 2) {
					
					$content = str_replace($headers[0][$x], "<div class='main_section'><h2>".$headers[1][$x]."</h2>\n".$headers[2][$x]."</div>", $content);
									
				## If header is style 3 make subsection
				} else if ($h == 3) {
					
					$content = str_replace($headers[0][$x], "<div class='sub_section'><h3>".$headers[1][$x]."</h3>\n".$headers[2][$x]."</div>", $content);
														
				## If header is anything but 2 or 3
				} else {
					$content = str_replace($headers[0][$x], "<div class='other_section'><h$h>".$headers[1][$x]."</h$h>".$headers[2][$x]."</div>", $content);
				}
			}
			
			$headers = null;
		}
		
		return $content;
	}
	
	function __fixHeaders($content) {
		## Turn H1 into page_title because it should be the only H1 element
		$content = str_replace('<h1>', "<h1 id='page_title'>", $content);
		
		return $content;
	}
	
	function __convertBold($content) {
		preg_match_all('/\*{2}(.+?)\*{2}/', $content, $objects);
		for ($x = 0; $x < count($objects[0]); $x++) {
			$content = str_replace($objects[0][$x], '<b>'.$objects[1][$x].'</b>', $content);
		}
		
		return $content;
	}
	
	function __convertItalics($content) {
		preg_match_all('/\/\/(.+?)\/\//', $content, $objects);
		for ($x = 0; $x < count($objects[0]); $x++) {
			$content = str_replace($objects[0][$x], '<i>'.$objects[1][$x].'</i>', $content);
		}
		
		return $content;
	}
	
	function __convertLists($content) {
		
		## Ordered Lists
		for ($i = 0; $i < 2; $i++) {
			if ($i == 0)
				preg_match_all('/^\s{2}\-\s(.*)/m', $content, $oLists);
			else if ($i == 1)
				preg_match_all('/^\s{4}\-\s(.*)/m', $content, $oLists);
				
			for ($x = 0; $x < count($oLists[0]); $x++) {
				if ($x == 0) {
					$content = str_replace($oLists[0][$x], '<ol><li>'.$oLists[1][$x].'</li>', $content);
				} else if ($x == count($oLists[0]) - 1) {
					
					// check if nested lists are found
					if (preg_match_all('/^\s{4}\-\s(.*)/', $content))
						$content = str_replace($oLists[0][$x], '<li>'.$oLists[1][$x].'</li>', $content);
					else
						$content = str_replace($oLists[0][$x], '<li>'.$oLists[1][$x].'</li></ol>', $content);
						
				} else {
					$content = str_replace($oLists[0][$x], '<li>'.$oLists[1][$x].'</li>', $content);
				}
			}
			
			// clear list for next iteration
			unset($oLists);
		}
		
		
		## Unordered Lists
		for ($i = 0; $i < 2; $i++) {
			if ($i == 0)
				preg_match_all('/^\s{2}\*\s(.*)/m', $content, $uLists);
			else if ($i == 1)
				preg_match_all('/^\s{4}\*\s(.*)/m', $content, $uLists);
				
			// conversion
			for ($x = 0; $x < count($uLists[0]); $x++) {
				if ($x == 0) {
					$content = str_replace($uLists[0][$x], '<ul><li>'.$uLists[1][$x].'</li>', $content);
				} else if ($x == count($uLists[0]) - 1) {
					
					// check if nested lists are found
					if (preg_match_all('/^\s{4}\*\s(.*)/m', $content))
						$content = str_replace($uLists[0][$x], '<li>'.$uLists[1][$x].'</li>', $content);
					else
						$content = str_replace($uLists[0][$x], '<li>'.$uLists[1][$x].'</li></ul>', $content);
					
				} else {
					$content = str_replace($uLists[0][$x], '<li>'.$uLists[1][$x].'</li>', $content);
				}
			}
			
			// clear list for next iteration
			unset($uLists);
		}
		
		return $content;
	}
	
	function __convertLinks($content) {
		
		// Links with titles
		preg_match_all('/\[{2}(.+?)\|([\w\s]+?)\]{2}/', $content, $links);
		for ($x = 0; $x < count($links[0]); $x++) {
			$content = str_replace($links[0][$x], "<a href='$page_title?id=".$links[1][$x]."'>".$links[2][$x]."</a>", $content);
		}
		
		// Links w/o titles, use name
		preg_match_all('/\[\[(.+?)\]\]/', $content, $links);
		for ($x = 0; $x < count($links[0]); $x++) {
			$link = strtolower($links[1][$x]);
			$link = str_replace(' ', '_', $link);
			$content = str_replace($links[0][$x], "<a href='$page_title?id=$link'>".$links[1][$x]."</a>", $content);
		}
		
		return $content;
	}
	
	function __convertCitations($content) {
		if (preg_match_all('/\[\((.+?)\)\]/', $content, $citations)) {
			$content .= "<hr class='citation_separator'><ol>";
			for ($x = 0; $x < count($citations[0]); $x++) {
				$c = $x + 1;
				$content = str_replace($citations[0][$x], "<sup><a class='citation_link' href='#$c'>$c)</a></sup>", $content);
				$content .= "<li class='citation' id='$c'>".$citations[1][$x]."</li>";
			}
			$content .= "</ol></div>";
		}
		
		return $content;
	}
	
	function __convertParagraphs($content) {
		preg_match_all('/(\n|^)(\w.*?)(?=\n)/s', $content, $paragraphs);
		for ($x = 0; $x < count($paragraphs[0]); $x++) {
			$content = str_replace($paragraphs[0][$x], '<p>'.$paragraphs[2][$x].'</p>', $content);
		}
		
		return $content;
	}
	
	function __convertImages($content) {
		
		// get images --> [1] = image path, maybe size; [2] = maybe image description;
		preg_match_all('/\[?\{{2}(.*)\|(.*)\}{2}\]?/m', $content, $images);
		
		// loop through and replace images
		for ($x = 0; $x < count($images); $x++) {
			
			// check if false positive
			if (empty($images[$x]))
				return $content;
			
			// flag to check if image found
			$pass = 1;
			
			// start image tag
// 			$image_tag = "<img src='data/media/";
			$image_tag = "<img src='img/";
			
			// get image details --> [1] = directory name; [2] = filename; [3] = width; [4] = height;
			if (!preg_match('/(.*):(.*)[\?|\|]([[:digit:]]*)\*?([[:digit:]]*)/', $images[1][$x], $image_details))
				preg_match('/:(.*)/', $images[1][$x], $image_details);
			
			// set image path
			if (isset($image_details[1]) && isset($image_details[2])) {
				$image_tag .= $image_details[2]."'";
			} else if (isset($image_details[1])) {
				$image_tag .= $image_details[1]."'";
			} else {
				$pass = 0;
			}
/*
			if (isset($image_details[1]) && isset($image_details[2])) {
				$image_tag .= $image_details[1]."/".$image_details[2]."'";
			} else if (isset($image_details[1])) {
				$image_tag .= $image_details[1]."'";
			} else {
				//image not found; set flag to null
				$pass = 0;
			}
*/
			
			// set image size; default = 800W X autoH
			if (isset($image_details[3]) && !empty($image_details[3])) {
				$image_tag .= " style='width: ".$image_details[3]."px; height: auto;'";
			} else if (isset($image_details[4]) && !empty($image_details[4])) {
				$image_tag .= " style='height: ".$image_details[4]."px; width: auto;'";
			} else {
				$image_tag .= " style='width: 100%; height: auto;'";
			}
			
			// set alt text
			if (isset($images[2][$x]) && !empty($images[2][$x])) {
				$image_tag .= " alt='".$images[2][$x]."'";
			}
			
			// close image tag
			$image_tag .= " >";
			
			// check if image found; if so, display; if not, show error;
			if ($pass)
				$content = str_replace($images[0][$x], $image_tag, $content);
			else
				$content = str_replace($images[0][$x], '<p><b>Missing Image</b></p>', $content);
			
			// clear image_tag for next image
			unset($image_tag);
		}
		
		return $content;
	}
	
	function __fixASCIIChars($content) {
		$content = preg_replace('/’/', '&rsquo;', $content);
		$content = preg_replace('/é/', '&eacute;', $content);
		$content = preg_replace('/ö/', '&ouml;', $content);
		$content = preg_replace('/“/', '&ldquo;', $content);
		$content = preg_replace('/”/', '&rdquo;', $content);
		$content = preg_replace('/—/', '&mdash;', $content);
		$content = preg_replace('/–/', '&ndash;', $content);
		
		return $content;
	}

?>