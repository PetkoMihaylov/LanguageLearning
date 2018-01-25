<!DOCTYPE html>
<html>
<head>
	<!--<script src="/_res/js.js"></script>-->
	<link rel="stylesheet" href="/_res/style.css">
</head>
<body>
<pre>
<?php
$words = array(
   //"blue"=>["Синьо", "Син"],
   "building"=>["Сграда"],
   "horse"=>["Кон", "Конче"],
   "rabbit"=>["Заек", "Зайче"],
   "I was out today."=>["Бях навън днес.", "Днес бях навън.", "Навън бях днес."]
);

if(isset($_POST["input"]))
{
	$input = $_POST["input"];
	$score = $_POST["score"];
	$phrase = $_POST["correct_word"];
	print("$input | ");
	print("$phrase | ");
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
	
	stripslashes($input);
	//$input = preg_replace(")", "", $input);
	//($GLOBALS['ctormv'], $GLOBALS['contplace'], $input);
	//$input = preg_replace('/\./', '', $input);
	$input = preg_replace('/ ,/', '', $input);
	$input = preg_replace('/\./', '', $input);
	$input = trim($input);
	$input = preg_replace ('/ +/', ' ', $input);
	$input = preg_replace("/[^[:alnum:][:space:]]/u", '', $input);
	$input = mb_strtolower($input,  mb_detect_encoding($input));
	$input = preg_replace('/\s+\./', '.', $input);
	//$input = str_replace(array(' .',' ,'),array('.',','), $input);
	/* if($input != null)
	{
		str_replace(array(".", "", $input);
	} */
	$answers = $words[$phrase];
	
	for($i = 0; $i < count($answers); $i++)
	{
		$typo = 0;
		$answer = $answers[$i];
		$answer = preg_replace("/[^[:alnum:][:space:]]/u", '', $answer);
		$answer = mb_strtolower($answer,  mb_detect_encoding($answer));
		$sim = similar_text($answer, $input, $percent);
		
		//check if percent is big enough, then check
		
		/*$pizza  = "piece1 piece2 piece3 piece4 piece5 piece6";
		$pieces = explode(" ", $pizza);*/
		/* if($word_count == 1)
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
			//print($input . " | " . $answer );
			//$printer = "you shall pass";
			//$inputWords = explode(" ", $input);
			//$answerWords = explode(" ", $answer);
			/* for($i = 0; i < count(answerWords); i++)
			{
				print($answerWords[i]);
			} */
			//check the strlen of both input and answers every time
			//check word by word to see if there is a typo in some word
			//thsen
		//}

		print("<br><br>$answer | $input <br><br>");
		$sim = similar_text($answer, $input, $percent);
		//check if percent is big enough, then check
		//print("<br>$word_count[0]<br>");
		/* $word_count_input = str_word_count($input, 0);
		$word_count = mb_str_word_count($answer, 1);
		print("<br> $word_count[0]<br>");*/
		$answerWords = explode(" ", $answer);
		$inputWords = explode(" ", $input);
		//print($answerWords[0]);
		if(count($answerWords) == 1)
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
		else if(count($answerWords) > 1)
		{
			
			
			for($i = 0; $i < count($answerWords); $i++)
			{
				print("<br>$answerWords[$i] => $inputWords[$i]<br>");
				$sim = similar_text($answerWords[$i], $inputWords[$i], $percent);
				if($answerWords[$i] == $inputWords[$i])
				{
					//correct word
					print("all is good=>$percent");
				}
				else
				{
					print("typo");
					$typo++;
					//not correct the current word
					//print it in the create div UNDERLINED____
				}
			}
			//check the strlen of both input and answers every time
			//check word by word to see if there is a typo in some word
			//then
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
	$michael = "Michael";
	$michaela = "Michael  ";
	$sim = similar_text($michael, $michaela, $percent);
	print("<br>$percent || $sim<br>");
	//echo $words[0]['$correct_word'];
	/*for ($i = 0; $i < count($words); $i++) 
	{
	}*/
	
	
	
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