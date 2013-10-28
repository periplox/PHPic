<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Imaginator</title>
	<meta name="description" content="Imaginator beta" />
    <meta name="keywords" content="imaginator, beta, periplo" />
    <meta name="author" content="Periplo" />
    <link rel="stylesheet" type="text/css" href="css/style.css" />
</head>
<body>
	<div id="msg">Processing...</div>

	<div id="container">
		<h1>PHPic manager</h1>
		<h2>beta</h2>

		<form id="imaginator">
			<fieldset>
				<h3>INPUT</h3>
				<label>Enter folder path</label><br>
				<span>(or leave blank to use default: "input/")</span><br>
				<input name="tar" id="tar" type="text">
				<br><br>

				<label>Choose file type:</label><br>
				<select id="inext">
					<option value=".jpg" selected>JPG/JPEG</option>
					<option value=".gif">GIF</option>
					<option value=".png">PNG</option>
					<option value=".*">All file types</option>
				</select>
			</fieldset>

			<fieldset>
				<h3>OUTPUT</h3>
				<label>Enter folder path</label><br>
				<span>(or leave blank to use default: "output/")</span><br>
				<input name="out" id="out" type="text">
				<br><br>

				<label>Choose file type:</label><br>
				<select id="outext">
					<option value="jpg" selected>JPG/JPEG</option>
					<option value="gif">GIF</option>
					<option value="png">PNG</option>
				</select>
			</fieldset>

			<fieldset>
				<h3>RESIZE</h3>
				<label>Enter maximum WIDTH </label><span>(in pixels)</span><br>
				<input name="width" id="width" type="text" value="">
				<br><br>
				<label>Enter maximum HEIGHT </label><span>(in pixels)</span><br>
				<input name="height" id="height" type="text" value="">
				<br><br>
				<input style="width:auto;margin-right:5px;" type="checkbox" name="aspect" id="aspect" checked>
				<span>Preserve aspect ratio</span>
				<br><br>
				<label>Choose image QUALITY </label><span>(0 = worst / 100 = best)</span><br>
				<select id="quality">
					<option value="0">0</option>
					<option value="10">10</option>
					<option value="20">20</option>
					<option value="30">30</option>
					<option value="40">40</option>
					<option value="50">50</option>
					<option value="60">60</option>
					<option value="70">70</option>
					<option value="80">80</option>
					<option value="90">90</option>
					<option value="100" selected>100</option>
				</select>
				<span>Only appliable to jpg or png files.</span>
			</fieldset>
			
			<fieldset>
				<h3>RENAME</h3>
				<label>Entre new name </label><span>(or leave blank to use original)</span><br>
				<input name="rename" id="rename" type="text">
				<br>
				<input name="presu" class="presu" type="radio" value="pre" style="width:auto;" checked="checked"> <span>prefix</span>
				<input name="presu" class="presu" type="radio" value="suf" style="width:auto;"> <span>suffix</span>
				<br><br>	
				<label>Enter separator </label><span>(or leave blank to use none)</span><br>
				<input name="separator" id="separator" type="text">
			</fieldset>

			<fieldset>
				<h3>FILTERS</h3>
				<input style="width:auto;margin-right:5px;" type="checkbox" name="grayscale" id="grayscale">
				<label>Convert to <strong>grayscale</strong></label>
				<br><br>
				<label>Select <strong>brightness</strong> level </label><br>
				<select id="brightness">
					<option value="none" selected>None</option>
					<option value="0">0</option>
					<option value="10">10</option>
					<option value="20">20</option>
					<option value="30">30</option>
					<option value="40">40</option>
					<option value="50">50</option>
					<option value="60">60</option>
					<option value="70">70</option>
					<option value="80">80</option>
					<option value="90">90</option>
					<option value="100">100</option>
				</select>
				<br><br>
				<label>Select <strong>contrast</strong> level </label><br>
				<select id="contrast">
					<option value="none" selected>None</option>
					<option value="0">0</option>
					<option value="10">10</option>
					<option value="20">20</option>
					<option value="30">30</option>
					<option value="40">40</option>
					<option value="50">50</option>
					<option value="60">60</option>
					<option value="70">70</option>
					<option value="80">80</option>
					<option value="90">90</option>
					<option value="100">100</option>
				</select>
			</fieldset>

			<fieldset>
				<h3>PROCESS LOG</h3>
				<textarea id="log"></textarea>
			</fieldset>

			<br><br>
			<input type="button" id="process" value="PROCESS">

		</form>
	</div>
	
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script>
		$('#process').click(function() {
			//Show status
			$("#msg").css("display", "block");

			//Colect vars
			var tar = $("#tar").val();
			var out = $("#out").val();
			var inext = $("#inext").val();
			var outext = $("#outext").val();
			var size = $("#width").val()+"x"+$("#height").val();
			var asp = $("#aspect").is(':checked');
			var qual = $("#quality").val();
			var name = $("#rename").val();
			var sepa = $("#separator").val();
			var presu = $("input:radio[name=presu]:checked").val();
			var gray = $("#grayscale").is(':checked');
			var bri = $("#brightness").val();
			var cont = $("#contrast").val();

			//Process
			$.ajax({
			  	url: "phpic_process.php",
			  	type: "POST",
			  	data: ({ 
			  		tar: tar, 
			  		out: out, 
			  		inext: inext, 
			  		outext: outext,
			  		size: size,
			  		asp: asp,
			  		quality: qual,
			  		name: name,
			  		sepa: sepa,
			  		presu: presu,
			  		gray: gray,
			  		bri: bri,
			  		cont: cont,
				}),
			}).done(function(log) {
				$("#msg").css("display", "none");
				console.log(log);
				$("textarea#log").html(log);
			});
		});
	</script>
</body>
</html>