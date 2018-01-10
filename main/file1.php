<!DOCTYPE html>
<html>
<head>
	<!--<script src="/_res/js.js"></script>-->
	<link rel="stylesheet" href="/_res/style.css">
	<script src="/audio.js"></script>
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
      <textarea name="text">Hello! I love JavaScript ??</textarea>
      <button id="stop">Stop!</button>
      <button id="speak">Speak</button>

    </div>
<pre>
<?php

$words = array(
   "blue"=>["Синьо", "Син"],
   "building"=>["Сграда"], 
   "horse"=>["Кон", "Конче"],
   "rabbit"=>["Заек", "Зайче"],
   "I was out today."=>["Бях навън днес.", "Днес бях навън.", "Навън бях днес."]
);


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
	
	
	
	print("$input | ");
	
	$input = trim($input);
	$input = preg_replace ('/ +/', ' ', $input);
	$input = preg_replace("/[^[:alnum:][:space:]]/u", '', $input);
	$input = mb_strtolower($input,  mb_detect_encoding($input));
	
	$acc_words = array_values($words[$correct_word]); 
	for($i = 0; $i < count($acc_words); $i++)
	{
		/*$answer = $words[$correct_word];
		$value = array_shift($answer);
		trim(strtolower($value));*/
		$answer = $acc_words[$i];
		//$input = mb_strtolower($input);
		//echo 
		//$input = strtolower($input);
		preg_replace("/[^[:alnum:][:space:]]/u", '', $answer);
		//print("\n\n$input || $answer[$correct_word]\n\n");
		$answer = preg_replace("/[^[:alnum:][:space:]]/u", '', $answer);
		$answer = mb_strtolower($answer,  mb_detect_encoding($answer));
		print("<br><br>$answer | $input <br><br>");
		$sim = similar_text($answer, $input, $percent);
		//check if percent is big enough, then check
		$word_count = str_word_count($answer, 0);
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
	$mp3 = file_get_contents(
	'http://translate.google.com'. urlencode($text) .'&tl='. $lang .'&total=1&idx=0&textlen=5&prev=input');
	file_put_contents($file, $mp3);
}
	
		if($word_count == 1)
		{
			if($sim < 4)
			{
				//mistake
			}
			else 
			{
				//typo
			}
			
			
		}
		else if($word_count == 2)
		{
			//check the strlen of both input and answers every time
			//check word by word to see if there is a typo in some word
			//thsen
		}
		
		print("<br><br><br>$percent<br><br><br>");
	
	/*if(value != $input)
	{
		$value = array_shift($answer);
		//continue;
	}*/
	
		if($input == $answer)
		{
			//print("correct\n");
			print("correct");
			$score ++;
			//print(")
		}
			//print($a[$i]);
	}
	//print_r($a[$i]);
	
	//echo $words[0]['$correct_word'];
	/*for ($i = 0; $i < count($words); $i++) 
	{
	}*/
	
	
	$pizza  = "piece1 piece2 piece3 piece4 piece5 piece6";
	$pieces = explode(" ", $pizza);
}
else
{
	$score = 0;
}

$word = array_rand ($words);
print("$word | \n");
$curr_word = $word;
print("$score\n");



?>
</pre>
<form action="file1.php" method="POST">
	<input type="text" name="input"/>
	<input type="hidden" name="correct_word" value="<?php print($word); ?>"/>
	<input type="hidden" name="score" value="<?php print($score); ?>"/>
	<button type="submit">Провери</button>
</form>

</body>
</html>