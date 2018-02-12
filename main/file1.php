<!DOCTYPE html>
<html>
<head>
	<!--<script src="/_res/js.js"></script>-->
	<script src="audio.js"></script>
	<script>
	//alert(0);
	</script>
	<link rel="stylesheet" href="/_res/style.css">
</head>
<body>
<div class="voiceaudio">

       <select name="voice" id="voices">
        <option value="">Select A Voice</option>
      </select>

      <label for="rate">Rate:</label>
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
   //"blue"=>["Синьо", "Син"],
   "building"=>["Сграда"],
   "horse"=>["Кон", "Конче"],
   "rabbit"=>["Заек", "Зайче"],
   "I was out today."=>["Бях навън днес.", "Днес бях навън.", "Навън бях днес."],
   "They are among us."=>["Те са сред нас.", "Те са измежду нас.", "Измежду нас са.", "Сред нас са."],
   "They are with me."=>["Те са с мен.", "С мен са"]
   );

require __DIR__ . '/vendor/autoload.php';
/* function mb_split_str($str) 
{
	preg_match_all("/./u", $str, $arr);
	return $arr[0];
} */
/* //based on http://www.phperz.com/article/14/1029/31806.html, added percent
function mb_similar_text($str1, $str2, &$percent) {
    $arr_1 = array_unique(mb_split_str($str1));
    $arr_2 = array_unique(mb_split_str($str2));
    $similarity = count($arr_2) - count(array_diff($arr_2, $arr_1));
    $percent = ($similarity * 200) / (strlen($str1) + strlen($str2) );
    return $similarity;
} */

function check_words($answerWords, $inputWords, $typo, &$correct)
{
	for($j = 0; $j < count($answerWords); $j++)
	{
		
		$answer_length = mb_strlen($answerWords[$j]);
		$input_length = mb_strlen($inputWords[$j]);
		print("<br>$answerWords[$j] -6=> $inputWords[$j] -7<br>");
		$sim = mb_similar_text($answerWords[$j], $inputWords[$j], $percent);
		if($percent < 75 && $answer_length > 3)
		{
			$correct = 0;
			print($correct);
			print("you");
		}
		else if($answerWords[$j] == $inputWords[$j])
		{
			//correct word
			print("all is good=>$percent");
		}
		else
		{
			//check_words($answerWords, $inputWords, $answer_length, $input_length, $sim, $typo, $j);
			if($answer_length == $input_length)
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
						print("show");
						if($sim == 1)
						{
							//correct
						}
						else
						{
							print("$answerWords[$j] => $inputWords[$j] => typo na 1-bukvena duma => $sim");
							$typo++;
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
					print("$answerWords[$j] => $inputWords[$j] => finalfirstcycle => $answer_length => $input_length => $sim");
					$correct == 0;
				}
					//always answer_length = phrase_length
			}
			else if($answer_length == 2)
			{
				if($input_length == 3)
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
				else if($input_length == 1)
				{
					if($sim == 1)
					{
						print("$answerWords[$j] => $inputWords[$j] => mistake-3.5 but now a typo => $sim");
						//$correct = 0; // 'y' is not 'ya' désolé but maybe a missed letter won't be fatal
					}
					else
					{
						print("$answerWords[$j] => $inputWords[$j] => mistake-3.5 => $sim");
						$correct = 0;
						
					}
				}
				else if($input_length == 2)
				{
					if($sim == 1)
					{
						print("$answerWords[$j] => $inputWords[$j] => typo-5.5 => $sim");
						$typo++;
					}
				}
			}
			/*else if($answer_length == 3)
			{
				
			}*/
			else if($answer_length - 1 == $input_length)
			{
				if($answer_length - 1 == $sim)
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
			else if($answer_length + 1 == $input_length)
			{
				if($answer_length == $sim)
				{
					print("$answerWords[$j] => $inputWords[$j] => typo-7 => $sim");
					$typo++;
				}
				else
				{
					print("$answerWords[$j] => $inputWords[$j] => mistake-5 => $answer_length => $input_length => $sim");
					$correct = 0;
				}
			}
			else
			{
				print("$answer_length =< $input_length =< $typo");
				print("$answerWords[$j] => $inputWords[$j] => final => $answer_length => $input_length => $sim");
				$correct = 0;
			}
		}
		
		
	}
	return $correct;
}
   
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
		$answer_length = mb_strlen($answer);
		$input_length = mb_strlen($input);
		if($input_length == 0)
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
				check_words($answerWords, $inputWords, $typo, $correct);
				print($correct);
			}
			else if(count($answerWords) != count($inputWords))
			{
				$words_spaces = [];
				$extra_spaces_typo = 0;
				$words_spaces_new = [];
				$words_extra_space = [];
				$input_space_count = substr_count($input, ' ');
				$answer_space_count = substr_count($input, ' ');
				$whole_answer = str_replace(' ', '', $answer);
				$whole_input = str_replace(' ', '', $input);
				for($j = 0; $j < count($answerWords); $j++)
				{
					print("<br>$answerWords[$j] > ");
				}
				//$correct = 0;
				$whole_answer_length = mb_strlen($whole_answer);
				$whole_input_length = mb_strlen($whole_input);
				print("$whole_answer => $whole_input");
				$sim = mb_similar_text($whole_answer, $whole_input, $percent);
				
				if($whole_answer_length == $whole_input_length)
				{
					print("Whole answer and input => $sim => $percent");
					if(($whole_answer_length == $sim || $whole_answer_length - 1 == $sim) && $percent > 90)
					{
						/* for($v = 0; $v < count($answerWords); $v++)
						{
							//$new_input = str_replace($healthy)
						} */
						for($j = 0; $j < count($answerWords); $j++)
						{
							if($j == 0)
							{
								if (strstr($input, "$answerWords[$j] "))
								{						
									echo "<br>$input -> non $answerWords[$j] yes ";
									$words_spaces[$j] = $answerWords[$j];
								}
								else
								{
									$extra_spaces_typo += 1;
								}
							}
							else if($j == count($answerWords) - 1)
							{
								if (strstr($input, " $answerWords[$j]"))
								{						
									echo "<br>$input -> yes $answerWords[$j] non";
									$words_spaces[$j] = $answerWords[$j];
								}
								else
								{
									$words_spaces[$j] = "_";
									$extra_spaces_typo += 1;
								}
								
							}
							else if($j>0 && $j < count($answerWords) - 1)
							{
								if (strstr($input, " $answerWords[$j] "))
								{						
									echo "<br>$input -> yes $answerWords[$j] yes";
									$words_spaces[$j] = $answerWords[$j];
								}
								else
								{
									$words_spaces[$j] = "_";
									$extra_spaces_typo += 1;
								}
								$all_words = $answerWords[$j];
								
							}
							else
							{
								$correct = 0;
							}
							
							for($i = 0; $i < count($words_spaces); $i++)
							{
								if($words_spaces[$j] == "_")
								{
									$words_extra_space[$j] = $answerWords[$j];
								}
								else
								{
									$words_extra_space[$j] = "_";
								}
							}
							print($extra_spaces_typo);
							print_r($words_spaces);
							print_r($words_extra_space);
							//$correct = 1;
							
							
						}
					}
					else
					{
						$correct = 0;
					}
					
				}
				else
				{
					$correct = 0;
					$counter = 0;
					$extra_spaces = 0;
				}
			}
			
			else
			{
				$correct = 0;
			}
		}
		else
		{
			$correct = 0;
		}
		//$curr_correct[$j] = $correct;
		if($correct == 1)
		{
			break;
		}
	}
	
	$michael = "заек";
	$michaela = "заекккк";
	//$sim = similar_text($michael, $michaela, $percent);
	//$count_michael = preg_match_all('/\X/u', html_entity_decode($michael, ENT_QUOTES, 'UTF-8'));
	$count_mb = mb_strlen($michael);
	$count = mb_strlen($michaela);
	//$count_michaela = preg_match_all('/\X/u', html_entity_decode($michaela, ENT_QUOTES, 'UTF-8'));
	$sim = mb_similar_text($michael, $michaela, $percent);
	//$sim = $sim/2;
	print("<br>$michael || $michaela || $sim || $percent || $count || $count_mb<br>");
	function mbStringToArray ($string) 
	{ 
		$strlen = mb_strlen($string); 
		while ($strlen) 
		{ 
			$array[] = mb_substr($string,0,1,"UTF-8"); 
			$string = mb_substr($string,1,$strlen,"UTF-8"); 
			$strlen = mb_strlen($string); 
		} 
		return $array; 
	}
	$count = mbStringToArray($michael);
	print_r($count);
	
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
	
//You missed a space - "There is no_wind" <<----
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