<!DOCTYPE html>
<html>
<head>
	<!--<script src="/_res/js.js"></script>-->
	<link rel="stylesheet" href="/_res/style.css">
</head>
<body>

<?php

$words = array(
   "blue"=>["синьо", "син"],
   "building"=>["сграда"], 
   "horse"=>["кон"],
   "rabbit"=>["заек"],
   "I was out today."=>["Бях навън днес.", "Днес бях навън.", "Навън бях днес."]
);


if(isset($_POST["input"]))
{
	$input = $_POST["input"];
	$score = $_POST["score"];
	$correct_word = $_POST["correct_word"];
	print("$input | ");
	print("$correct_word | ");
	$answer = $words[$correct_word];
	$value = array_shift($answer);
	trim(strtolower($value));
	print("$input");
	$input = trim($input);
	$input = preg_replace ('/ +/', ' ', $input);
	//$input = mb_strtolower($input);
	echo mb_strtolower($input,  mb_detect_encoding($input));
	//$input = strtolower($input);
	preg_replace("/[^[:alnum:][:space:]]/u", '', $input);
	preg_replace("/[^[:alnum:][:space:]]/u", '', $answer);
	//print("\n\n$input || $answer[$correct_word]\n\n");
	print("<br><br>$value | $input <br><br>");
	$sim = similar_text($value, $input, $percent);
	print("<br><br><br>$percent<br><br><br>");
	if($input == $value)
	{
		//print("correct\n");
		print("correct");
		$score ++;
		//print(")
	}
}
else
{
	$score = 0;
}

$word = array_rand ($words);
print("$word | \n");
print("$score\n");



?>

<form action="file1.php" method="POST">
	<input type="text" name="input"/>
	<input type="hidden" name="correct_word" value="<?php print($word); ?>"/>
	<input type="hidden" name="score" value="<?php print($score); ?>"/>
	<button type="submit">Провери</button>
</form>

</body>
</html>