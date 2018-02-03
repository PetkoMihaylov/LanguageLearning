<!DOCTYPE html>
<html>
<head>
	<!--<script src="/_res/js.js"></script>-->
	<link rel="stylesheet" href="/_res/style.css">
	<script src="audio.js"></script>
	<script src="/_res/js.js"></script>
</head>
<body>
<div class="voiceaudio">

      <select name="voice" id="voices">
        <option value="">Voice:</option>
      </select>

      <label for="rate">Speed:</label>
      <input name="rate" type="range" min="0" max="3" value="1" step="0.1">

      <label for="pitch">Pitch:</label>

      <input name="pitch" type="range" min="0" max="2" step="0.1">
      <button id="stop">Stop!</button>
      <button id="speak">Speak</button>

    </div>
<pre>
<?php

if(isset($_POST["input"]))
{
	$input = $_POST["input"];
	$score = $_POST["score"];
	$correct_word = $_POST["correct_word"];
	print("$input | ");
	print("$correct_word | ");
	/*reset($words);
	while(list($key, $value) = each($words))
	{
			echo "Key: $key; Value: $value<br />\n";
	}

	foreach ($arr as $key => $value) 
	{
		echo "Key: $key; Value: $value<br />\n";
	}*/
	
	//return $words('key' => "value1");
	/*if(isset($something['say']) && $something['say'] == 'bla') 
	{
	
	}*/
	/*foreach( $words as $key => $value)
	$values = array_values($words);
	print($values[]);
	*/
	//print_r(array_values($words));
	//if the random word is in the array(by checking), then get the values assigned to it
	//acc_words are accepted words
	
	
	
	//print("$input | ");
	
/*	
		$text = "Bonjour, comment allez vous ?";
// Yes French is a beautiful language.
$lang = "fr";

// MP3 filename generated using MD5 hash
// Added things to prevent bug if you want same sentence in two different languages
$file = md5($lang."?".urlencode($text));

// Save MP3 file in folder with .mp3 extension 
$file = "audio/" . $file . ".mp3";


// Check folder exists, if not create it, else verify CHMOD
if (!is_dir("audio/"))
	mkdir("audio/");
else
	if (substr(sprintf('%o', fileperms('audio/')), -4) != "0777")
		chmod("audio/", 0777);


// If MP3 file exists do not create new request
if (!file_exists($file))
{
	// Download content
	/*$mp3 = file_get_contents(
	'http://translate.google.com'. urlencode($text) .'&tl='. $lang .'&total=1&idx=0&textlen=5&prev=input');
	file_put_contents($file, $mp3);*/
//}
	
		
	//print_r($a[$i]);
	
	//echo $words[0]['$correct_word'];
	/*for ($i = 0; $i < count($words); $i++) 
	{
	}
	*/
/*	
	$pizza  = "piece1 piece2 piece3 piece4 piece5 piece6";
	$pieces = explode(" ", $pizza);
	//to analyze word by word
}
else
{
	$score = 0;
}
*/

?>

</pre>
<form id="userInputTest" action="file2.php" method="POST">
	<input type="text" name="input" title="конче"/>
	<input type="hidden" name="correct_word" value="<?php print($word); ?>"/>
	<input type="hidden" name="score" value="<?php print($score); ?>"/>
	<button type="submit">Провери</button>
</form>

</body>
</html>