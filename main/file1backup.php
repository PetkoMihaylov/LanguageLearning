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
   "I was out today."=>["Бях навън днес.", "Днес бях навън.", "Навън бях днес."],
   "They are among us"=>["Те са сред нас.", "Те са измежду нас.", "Измежду нас са.", "Сред нас са."]
);

if(isset($_POST["input"]))
{
	$input = $_POST["input"];
	$score = $_POST["score"];
	$phrase = $_POST["correct_word"];
	print("$input -1| ");
	print("$phrase -2| ");
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
	
	
	
	print("$input -3| ");
	
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
	print_r($answers);
	
	for($k = 0; $k < count($answers); $k++)
	{
		$correct = 1;
		$typo = 0;
		$answer = $answers[$k];
		$answer = preg_replace("/[^[:alnum:][:space:]]/u", '', $answer);
		$answer = mb_strtolower($answer,  mb_detect_encoding($answer));
		/*$sim = similar_text($answer, $input, $percent);
		$sim = $sim/2
		;*/
		
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
		print("<br><br>$answer -4| $input -5<br><br>");
		//check if percent is big enough, then check
		//print("<br>$word_count[0]<br>");
		/* $word_count_input = str_word_count($input, 0);
		$word_count = mb_str_word_count($answer, 1);
		print("<br> $word_count[0]<br>");*/
		$answerWords = explode(" ", $answer);
		$inputWords = explode(" ", $input);
		//print($answerWords[0]);
		$answer_length = strlen($answer);
		$phrase_length = strlen($input);
		//print($phrase_length);
		if($phrase_length == 0)
		{
			$correct = 0;
			break;
		}
		if(count($answerWords) == 1)
		{
				/*print("<br>$answerWords[0] -6=> $inputWords[0] -7<br>");
				$sim = similar_text($answerWords[0], $inputWords[0], $percent);
				
				if($answerWords[0] == $inputWords[0])
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
				
				$shortest = -1;
				$lev = levenshtein($inputWords[0], $answerWords[0]);

				// check for an exact match
				if ($lev == 0) {

					// closest word is this one (exact match)
					$closest = $answerWords[0];
					$shortest = 0;

					// break out of the loop; we've found an exact match
				}

				// if this distance is less than the next found shortest
				// distance, OR if a next shortest word has not yet been found
				if ($lev <= $shortest || $shortest < 0) 
				{
					// set the closest match, and shortest distance
					$closest  = $answerWords[0];
					$shortest = $lev;
				}
				
				
				echo "Input word: $inputWords[0]\n";
				if ($shortest == 0)
				{
					print("Exact match found: $closest\n");
				} 
				else 
				{
					print("Did you mean: $closest?\n");
				}
				
			*/
			
			$sim = similar_text($answer, $input, $percent);
			$sim = $sim/2;
			$sim = floor($sim);
			if($answer_length == $phrase_length)
			{
				if($percent > 75)
				{
					$correct = 0;
				}
				if($sim < 4)
				{
					if($phrase_length == 3)
					{
						if($sim == 2)
						{
							print("$answerWords[$j] => $inputWords[$j] => typo => $sim");
							//typo
							$typo++;
						}
					}
					else if($phrase_length == 2)
					{
						if($sim == 2)
						{
							print("$answerWords[$j] => $inputWords[$j] => typo => $sim");
							//typo
							$typo++;
						}
					}
					else if($phrase_length == 4)
					{
							if($sim == 3)
							{
								print("$answerWords[$j] => $inputWords[$j] => typo => $sim");
								//typo
								$typo++;
							}
					}
					else
					{
						//incorrect the whole exercise
						//$incorrect
						$correct = 0;
					}
				}
				else if($answer_length == 2)
				{
					if($phrase_length == 2)
					{
						if($sim == 1)
						{
							print("$answerWords[$j] => $inputWords[$j] => typo => $sim");
							//typo
							$typo++;
						}
						else
						{
							//incorrect()
							$correct = 0;
						}
					}
					else if($phrase_length == 3)
					{
						if($sim == 2)
						{
							print("$answerWords[$j] => $inputWords[$j] => typo => $sim");
							$typo++;
						}
						else
						{
							//incorrect
							$correct = 0;
						}
					}
				}
					//mistake
			}
			else if($sim >= 4 && $percent >= 75)
			{
				//typo and the answer is correct
				print("$answer => $input => typo => $sim");
				$typo++;
			}
		}
			
		else if(count($answerWords) > 1)
		{
			if(count($answerWords) == count($inputWords))
			{
				for($j = 0; $j < count($answerWords), $j < count($inputWords); $j++)
				{
					print("<br>$answerWords[$j] -6=> $inputWords[$j] -7<br>");
					$sim = similar_text($answerWords[$j], $inputWords[$j], $percent);
					$sim = $sim/2;
					$sim = floor($sim);
					if($answerWords[$j] == $inputWords[$j])
					{
						//correct word
						print("all is good=>$percent");
					}
					else
					{
						$phrase_length = strlen($answerWords[$j]);
						$answer_length = strlen($answerWords[$j]);
						if($answer_length > 3)
						{
							if($sim + 1 == $answer_length)
							{
								//typo
								print("$answerWords[$j] => $inputWords[$j] => typo => $sim");
								$typo++;
							}
						}
						else if($sim < 4 && $percent > 75)
						{
							if($answer_length == 3)
							{
								if($phrase_length == 3)
								{
									if($sim == 2)
									{
										print("$answerWords[$j] => $inputWords[$j] => typo => $sim");
										//typo
										$typo++;
									}
								}
								else if($phrase_length == 2)
								{
									if($sim == 2)
									{
										print("$answerWords[$j] => $inputWords[$j] => typo => $sim");
										//typo
										$typo++;
									}
								}
								else if($phrase_length == 4)
								{
										if($sim == 3)
										{
											print("$answerWords[$j] => $inputWords[$j] => typo => $sim");
											//typo
											$typo++;
										}
								}
								else
								{
									//incorrect the whole exercise
									//$incorrect
									$correct = 0;
								}
							}
							else if($answer_length == 2)
							{
								if($phrase_length == 2)
								{
									if($sim == 1)
									{
										print("$answerWords[$j] => $inputWords[$j] => typo => $sim");
										//typo
										$typo++;
									}
									else
									{
										//incorrect()
										$correct = 0;
									}
								}
								else if($phrase_length == 3)
								{
									if($sim == 2)
									{
										print("$answerWords[$j] => $inputWords[$j] => typo => $sim");
										$typo++;
									}
									else
									{
										//incorrect
										$correct = 0;
									}
								}
							}
						}
						//print();
						/* print("typo");
						$typo++; */
						//not correct the current word
						//print it in the create div UNDERLINED____
					}
					/* $shortest = -1;
					$lev = levenshtein($inputWords[$j], $answerWords[$j]);

					// check for an exact match
					if ($lev == 0) {

						// closest word is this one (exact match)
						$closest = $answerWords[$j];
						$shortest = 0;

						// break out of the loop; we've found an exact match
					}

					// if this distance is less than the next found shortest
					// distance, OR if a next shortest word has not yet been found
					if ($lev <= $shortest || $shortest < 0) {
						// set the closest match, and shortest distance
						$closest  = $answerWords[$j];
						$shortest = $lev;
					}
					//консдафнйн //кон //кон
					
					echo "Input word: $inputWords[$j]\n";
					if ($shortest == 0) {
						print("Exact match found: $closest\n");
					} else {
						print("Did you mean: $closest?\n");
					}
					 */
				}
			}
			else
			{
				$correct = 0;
			}
		}
	}

			// _ children are gone.
			// The
			// A
			//check the strlen of both input and answers every time
			//check word by word to see if there is a typo in some word
			//then
			
		
		
	
	/*if(value != $input)
	{
		$value = array_shift($answer);
		//continue;
	}*/
	
		
			//print($a[$i]);
		//$shortest = -1;
		// loop through words to find the closest
		//foreach ($words as $word) {

			// calculate the distance between the input word,
			// and the current word
			//print_r($a[$i]);
	$michael = "кон";
	$michaela = "конн";
	$sim = similar_text($michael, $michaela, $percent);
	$sim = $sim/2;
	$sim = floor($sim);
	print("<br>$michael || $michaela || $percent || $sim<br>");
	//echo $words[0]['$correct_word'];
	/*for ($i = 0; $i < count($words); $i++) 
	{
	}*/
	if($correct == 1)
	{
		print("correct");
		$score++;
	}
	else
	{
		$score = 0;
	}
	/*
	if($input == $answer)
	{
		//print("correct\n");
		print("correct");
		$score ++;
		//print(")
	}*/
	
	
}
	

	$word = array_rand ($words);
	print("$word | \n");
	$curr_word = $word;
	//print("$score\n");
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