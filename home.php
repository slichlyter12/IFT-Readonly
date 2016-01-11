<!DOCTYPE html>
<html>
	<head>
		<title>Home</title>
		<link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
		<style>
			
			html, body {
				width: 95%;
			}
			
			#header {
				background-color: #0D3182;
				color: white;
				padding: 7px;
				text-align: center;
				margin: 20px;
				height: auto;
			}
			
			#header a {
				color: white;
				text-decoration: none;
			}
			
			#header a:hover {
				text-decoration: underline;
			}
			
			#desc {
				font-size: 14pt;
			}
			
			#contributors {
				font-size: 12pt;
			}
			
			#citations {
				padding-top: 20px;
				padding-left: 10%;
				padding-right: 10%;
				font-size: 10pt;
				text-align: left;
			}
			
			.left {
				float: left;
			}
			
			.right {
				float: right;
			}
			
			.centered {
				text-align: center;
			}
			
			.align {
				margin-left: 20px;
			}
			
			.clear {
				clear: both;
			}
			
			.focus-rect {
				border: 1px solid black;
				border-radius: 30px;
				width: 100%;
				height: auto;
				padding: 10px;
				margin: 5px;
			}
			
			.round-rect {
				border: 1px solid black;
				border-radius: 60px;
				width: 100%;
				height: auto;
				padding: 20px;
				margin: 5px;
				background-color: #dedede;
			}
			
			.left-rects {
				display: inline-block;
				width: 52%;
				float: left;
			}
			
			.right-rects {
				width: 40%;
				display: inline-block;
				float: right;
			}
			
			#orange {
				background-color: #C34500;
				color: white;
			}
			
			#contact {
				clear: both;
			}
			
			@media (max-width: 710px) {
				#citations .left, #citations .right {
					float: none;
					display: block;
					margin-left: auto;
					margin-right: auto;
					text-align: center;
				}
			}
			
		</style>
	</head>
	<body>
		<div id="content">
			<h1 class="centered">Information Foraging Theory</h1>
			<div id="header">
				<p id="desc">A Wiki to Help Tool Designers who Want to Put Information Foraging Theory into Practice</p>
				<p id="contributors">Tahmid Nabi<sup>1</sup>, Chris Scaffidi<sup>1</sup>, David Piorkowski<sup>1</sup>, Margaret Burnett<sup>1</sup>, Scott Flemming<sup>2</sup></p>
				<div id="citations">
					<div class="left">
						<p><sup>1</sup>Center for Applied Systems and Software<br>School of Electrical Engineering and Computer Science<br>Oregon State University<br>Corvallis, OR, USA</p>
					</div>
					<div class="right">
						<p><sup>2</sup>Department of Computer Science<br>University of Memphis<br>Memphis, TN, USA</p>
					</div>
				</div>
				<div class="clear"></div>
			</div>
			<p class="align"><b>Objective:</b> Map Information Foraging Theory (IFT) to design patterns for development tools</p>
			<p class="align"><b>Why?</b> To help tool designers get ideas for how to help developers find information</p>
			<div class="left-rects"><div class="focus-rect align" id="orange">
				<h2>Theory &rarr; Design Patterns &rarr; Tools</h2>
			</div>
			<div class="round-rect align">
				<p><b>Example:</b> Suppose you're an IDE designer interested in helping developers find information related to a project's status... e.g., keeping abreast of feature requests, bug counts, etc.</p>
				<p><b>Your Problem:</b> What should your tool look like? What should it do?</p>
				<p><b>Solution:</b> Go to the IFT wiki for ideas. In particular, you'll find the Dashboard design patter...</p>
				<p><b>Aha!</b> Your tool could include a Dashboard!</p>
				<p>So then you create and evaluate your tool, publish your research, and change the world.</p>
			</div></div>
			<div class="right-rects">
				<div class="round-rect">
					<p><u><b>Super-quick IFT primer</b></u></p>
					<p><b>Developers</b> hunt for information in an information <b>topology: patches</b> of code or other views connected by navigable <b>links</b></p>
					<p><b>Patches</b> contain information <b>features</b> that have <b>value</b>.</p>
					<p>A <b>link</b> has a certain <b>cost</b>. Links are often annotated with certain <b>cues</b> (e.g. labels). The developer tries to maximize <b>expected</b> value relative to <b>expected</b> cost.</p>
				</div>
				<div class="round-rect">
					<p><b><u>Wiki status</u></b></p>
					<p>32 different design patterns</p>
					<p>9 different patter contributors, in addition to our own research group</p>
					<p>Distribution of pattern purposes</p>
				</div>
			</div>
			<h3 id="contact">For more information on the wiki:<br>Contact Chris Scaffidi, <a href="mailto:cscaffid@eecs.oregonstate.edu">cscaffid@eecs.oregonstate.edu</a></h3>
		</div>
		
	</body>
</html>
