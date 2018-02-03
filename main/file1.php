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
   "They are among us."=>["Те са сред нас.", "Те са измежду нас.", "Измежду нас са.", "Сред нас са."],
   "They are with me."=>["Тe сa с мен.", "С мен сa"]
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
		$curr_correct = [];
		$answer = $answers[$k];
		$answer = preg_replace("/[^[:alnum:][:space:]]/u", '', $answer);
		$answer = mb_strtolower($answer,  mb_detect_encoding($answer));
		
		print("<br><br>$answer -4| $input -5<br><br>");
		$answerWords = explode(" ", $answer);
		$inputWords = explode(" ", $input);
		$answer_length = strlen($answer);
		$answer_length = $answer_length/2;
		$phrase_length = strlen($input);
		$phrase_length = $phrase_length/2;
		if($phrase_length == 0)
		{
			$correct = 0;
			break;
		}
		/* if(count($answerWords) == 1)
		{
			
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
						print("$answer => $input => mistake => $sim");
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
							print("$answer => $input => mistake => $sim");
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
							print("$answer => $input => mistake => $sim");
							$correct = 0;
						}
					}
				}
					//mistake
			}
			else if($answer_length == $phrase_length - 1)
			{
				if($answer_length == $sim - 1)
				{
					print("$answer => $input => typo => $sim");
					$typo++;
				}
				else
				{
					print("$answer => $input => mistake => $sim");
					$correct = 0;
				}
			}
			else if($answer_length == $phrase_length + 1)
			{
				if($answer_length == $sim)
				{
					print("$answer => $input => typo => $sim");
					$typo++;
				}
				else
				{
					print("$answer => $input => mistake => $sim");
					$correct = 0;
				}
			}
			else if($answer_length == $phrase_length + 2)
			{
				if($answer_length == $sim)
				{
					print("$answer => $input => typo => $sim");
					$typo++;
				}
				else
				{
					print("$answer => $input => mistake => $sim");
					$correct = 0;
				}
			}
			else
			{
				print("$answer => $input => final => $sim");
				$correct = 0;
			}
		} */
		
			
		else if(count($answerWords) > 0)
		{
			if(count($answerWords) == count($inputWords))
			{
				for($j = 0; $j < count($answerWords), $j < count($inputWords); $j++)
				{
					
					$answer_length = strlen($answerWords[$j]);
					$answer_length = $answer_length/2;
					$phrase_length = strlen($inputWords[$j]);
					$phrase_length = $phrase_length/2;
					print("<br>$answerWords[$j] -6=> $inputWords[$j] -7<br>");
					$sim = similar_text($answerWords[$j], $inputWords[$j], $percent);
					$sim = $sim/2;
					$sim = floor($sim);
					if($percent < 75)
					{
						$correct = 0;
					}
					else if($answerWords[$j] == $inputWords[$j])
					{
						//correct word
						print("all is good=>$percent");
					}
					else
					{
						if($answer_length == $phrase_length)
						{
							if($sim < 4)
							{
								
								if($answer_length == 4)
								{
										if($sim == 3)
										{
											print("$answerWords[$j] => $inputWords[$j] => typo-1 => $sim");
											//typo
											$typo++;
										}
								}
								else if($answer_length == 3)
								{
									if($sim == 2)
									{
										print("$answerWords[$j] => $inputWords[$j] => typo-2 => $sim");
										//typo
										$typo++;
									}
								}
								else if($answer_length == 2)
								{
									/*if($sim == 2)
									{
										print("$answerWords[$j] => $inputWords[$j] => typo => $sim");
										//typo
										$typo++;
										//correct = 1 still
									}*/
									/* if($sim == 2)
									{
										//correct
									} */
									if($sim == 1)
									{
										print("$answerWords[$j] => $inputWords[$j] => typo-3 => $sim");
										//typo
										$typo++;
									}
									else
									{
										print("$answerWords[$j] => $inputWords[$j] => mistake-1 => $sim");
										$correct = 0;
									}
								}
								else if($answer_length == 1)
								{
									if($sim != 1)
									{
										$correct = 0;
									}
								}
								else
								{
									//incorrect the whole exercise
									print("$answerWords[$j] => $inputWords[$j] => mistake-2 => $sim");
									$correct = 0;
								}
							}
							else if($answer_length - 1 == $sim)
							{
								print("$answerWords[$j] => $inputWords[$j] => typo-4 => $sim");
								$typo++;
							}
							else
							{
								print("$answerWords[$j] => $inputWords[$j] => finalfirstcycle => $answer_length => $phrase_length => $sim");
								$correct == 0;
							}
								//always answer_length = phrase_length
						}
						else if($answer_length == 2)
						{
							if($phrase_length == 3)
							{
								if($sim == 2)
								{
									print("$answerWords[$j] => $inputWords[$j] => typo-5 => $sim");
									$typo++;
								}
								else
								{
									print("$answerWords[$j] => $inputWords[$j] => mistake-3 => $sim");
									$correct = 0;
								}
							}
							else if($phrase_length == 1)
							{
								if($sim == 1)
								{
									$correct = 0; // 'y' is not 'ya' désolé
								}
							}
						}
						/*else if($answer_length == 3)
						{
							
						}*/
						else if($answer_length - 1 == $phrase_length)
						{
							if($answer_length == $sim - 1)
							{
								print("$answerWords[$j] => $inputWords[$j] => typo-6 => $sim");
								$typo++;
							}
							else
							{
								print("$answerWords[$j] => $inputWords[$j] => mistake-4 => $sim");
								$correct = 0;
							}
						}
						else if($answer_length + 1 == $phrase_length)
						{
							if($answer_length == $sim)
							{
								print("$answerWords[$j] => $inputWords[$j] => typo-7 => $sim");
								$typo++;
							}
							else
							{
								print("$answerWords[$j] => $inputWords[$j] => mistake-5 => $answer_length => $phrase_length => $sim");
								$correct = 0;
							}
						}
						else
						{
							print("$answer_length =< $phrase_length =< $typo");
							print("$answerWords[$j] => $inputWords[$j] => final => $answer_length => $phrase_length => $sim");
							$correct = 0;
						}
						/* else if($answer_length + 2 == $phrase_length)
						{
							if($answer_length == $sim)
							{
								print("$answer => $input => typo => $sim");
								$typo++;
							}
							else
							{
								print("$answer => $input => mistake => $sim");
								$correct = 0;
							}
						} */
						/* $phrase_length = strlen($answerWords[$j]);
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
						else if($sim < 4)
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
						else if($answer_length > 3)
						{
							if($phrase_length == $answer_length)
							{
								if($answer_length == $sim)
								{
									
								}
								else if($answer_length == $sim - 1)
								{
									
								}
								else if($answer_length == $sim + 1)
								{
									
								}
							}
						}
					 */
					 }
					
					
				}
			}
			else if(count($answerWords) + 1 == count($inputWords))
			{
				for($j = 0; $j < count($answerWords); $j++)
				{
					print("$answerWords[$j] > ");
				}
				$correct = 0;
				$whole_answer = str_replace(' ', '', $answer);
				$whole_input = str_replace(' ', '', $input);
				$whole_answer_length = strlen($whole_answer);
				$whole_input_length = strlen($whole_input);
				print("$whole_answer => $whole_input");
				
				if($whole_answer_length == $whole_input_length)
				{
					$sim = similar_text($whole_answer, $whole_input, $percent);
					print("Whole answer and input => $sim => $percent");
					if($whole_answer_length == $sim && $percent > 75)
					{
						for($v = 0; $v < count($answerWords); $v++)
						{
							
						}
					}
					
				}
			}
			else if(count($answerWords) - 1 == count($inputWords))
			{
				for($j = 0; $j < count($answerWords); $j++)
				{
					print("$answerWords[$j] > ");
				}
				$correct = 0;
				$whole_answer = str_replace(' ', '', $answer);
				$whole_input = str_replace(' ', '', $input);
				$whole_answer_length = strlen($whole_answer);
				$whole_input_length = strlen($whole_input);
				print("$whole_answer => $whole_input");
			}
			else
			{
				$correct = 0;
			}
		}
		//$curr_correct[$j] = $correct;
		if($correct == 1)
		{
			break;
		}
	}
	
	$michael = "не";
	$michaela = "ен";
	$sim = similar_text($michael, $michaela, $percent);
	$sim = $sim/2;
	$sim = floor($sim);
	print("<br>$michael || $michaela || $percent || $sim<br>");
	if($correct == 1)
	{
		print("correct | $typo | ");
		$score++;
	}
	else
	{
		$score = 0;
	}
	
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