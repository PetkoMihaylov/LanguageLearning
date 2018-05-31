<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL
//header('Content-Type: application/json');
define("DB_SALT", "1234rfkgjalvj0q4wjpvaFAFA!@$%kdv;awij09w4fskfer;vner;jlvnaer-");


function dbConnect()
{
	$db = new mysqli("localhost", "root", "root", "words");
	if(!$db)
	{
		print($db->error);
	}
	return $db;
}



function getLevel($level, $sublevel, $language)
{
	$db = dbConnect();
	$level = $db->real_escape_string ($level);
	$sublevel = $db->real_escape_string ($sublevel);
	$images_result = $db->query("SELECT * FROM image_words WHERE level='$level' and sublevel='$sublevel' and language = '$language'");
	$images = $images_result->fetch_all(MYSQLI_ASSOC);
	$words_result = $db->query("SELECT * FROM words WHERE level='$level' and sublevel='$sublevel' and language = '$language'");
	$words = $words_result->fetch_all(MYSQLI_ASSOC);
	for($i = 0; $i < count($words); $i++)
	{
		$wordId = $words[$i]['id'];
		$words_translations_result = $db->query("SELECT * FROM words_translations WHERE wordId='$wordId'");
		$words[$i]['translations'] = $words_translations_result->fetch_all(MYSQLI_ASSOC);
	}
	
	$phrases_result = $db->query("SELECT * FROM phrases WHERE level='$level' and sublevel='$sublevel' and language = '$language'");
	$phrases = $phrases_result->fetch_all(MYSQLI_ASSOC);
	for($i = 0; $i < count($phrases); $i++)
	{
		$phraseId = $phrases[$i]['id'];
		$answers_result = $db->query("SELECT * FROM answers WHERE phraseId='$phraseId'");
		$phrases[$i]['answers'] = $answers_result->fetch_all(MYSQLI_ASSOC);
	}

	$checkbox_result = $db -> query("SELECT * FROM checkbox_phrases WHERE level='$level' and sublevel='$sublevel' and language = '$language'");
	$checkbox_phrases = $checkbox_result->fetch_all(MYSQLI_ASSOC);
	for($i = 0; $i < count($checkbox_phrases); $i++ )
	{
		$checkbox_phrase = $checkbox_phrases[$i];
		$checkbox_phrase_id = $checkbox_phrase['id'];
		$result = $db->query("SELECT * FROM checkbox_answers WHERE phraseId=$checkbox_phrase_id");
		$checkbox_phrases[$i]['answers'] = $result->fetch_all(MYSQLI_ASSOC);
	}
	for($i = 0; $i < count($checkbox_phrases); $i++ )
	{
		$checkbox_phrase = $checkbox_phrases[$i];
		$checkbox_phrase_id = $checkbox_phrase['id'];
		$result = $db->query("SELECT * FROM checkbox_wrong_answers WHERE phraseId=$checkbox_phrase_id");
		$checkbox_phrases[$i]['wrong_answers'] = $result->fetch_all(MYSQLI_ASSOC);
	}

	$radio_result = $db -> query("SELECT * FROM radio_phrases WHERE level='$level' and sublevel='$sublevel' and language = '$language'");
	$radio_phrases = $radio_result->fetch_all(MYSQLI_ASSOC);
	
	$words_result = $db -> query("SELECT * FROM words WHERE level='$level' and sublevel='$sublevel' and language = '$language'");
	$words = $words_result->fetch_all(MYSQLI_ASSOC);
	//$words_translations_result = $db->query
	for($i = 0; $i < count($words); $i++)
	{	
		$word = $words[$i]['word'];
		$wordID = $words[$i]['id'];
		$words_translations_result = $db->query("SELECT word FROM words_translations WHERE  from_language = '$language' and wordID = '$wordID'");
		$words[$i]['translation'] = $words_translations_result->fetch_all(MYSQLI_ASSOC);
	}

	$exercises = [
		"images" => $images,
		"words" => $words,
		"phrases" => $phrases,
		"radio" => $radio_phrases,
		"checkbox_phrases" => $checkbox_phrases,
		"words" => $words
	];
	
	return $exercises;
}

function login($username, $password)
{
	$db = dbConnect();
	$password_hash = hash("sha512", $password . DB_SALT);
	$username = $db->real_escape_string ($username);
	$result = $db->query("SELECT * FROM users WHERE username='$username' and password='$password_hash'");
	$login = $result->fetch_all(MYSQLI_ASSOC);
	if($login)
	{
		return true;
	}
	else
	{
		return false;
	}
}
function getUserLevel($username)
{
	$db = dbConnect();
	$username = $db->real_escape_string ($username);
	$result = $db->query("SELECT level FROM users WHERE username='$username'");
	$userLevel = $result->fetch_all(MYSQLI_ASSOC)[0]["level"];
	return $userLevel;
}

function getUserInfo($username)
{
	$db = dbConnect();
	$username = $db->real_escape_string($username);
	$result = $db->query("SELECT level FROM users WHERE username = '$username'");
	$level = $result->fetch_all(MYSQLI_ASSOC)[0]["level"];
	$result = $db->query("SELECT sublevel FROM users WHERE username = '$username'");
	$sublevel = $result->fetch_all(MYSQLI_ASSOC)[0]["sublevel"];
	$result = $db->query("SELECT days_played FROM users WHERE username = '$username'");
	$days_played = $result->fetch_all(MYSQLI_ASSOC)[0]["days_played"];
	$result = $db->query("SELECT points FROM users WHERE username = '$username'");
	$points = $result->fetch_all(MYSQLI_ASSOC)[0]["points"];
	$result = $db->query("SELECT score FROM users WHERE username = '$username'");
	$score = $result->fetch_all(MYSQLI_ASSOC)[0]["score"];
	
	$userInfo = [
		"level" => $level,
		"sublevel" => $sublevel,
		"days_played" => $days_played,
		"points" => $points,
		"score" => $score,
	];
	
	return $userInfo;
	
}

function registerUser($username, $email, $password)
{
	$db = dbConnect();
	$password_hash = hash("sha512", $password . DB_SALT);
	$username = $db->real_escape_string ($username);
	$email = $db->real_escape_string ($email);
	$db->query("INSERT INTO users (username, email, password, language, points, score, days_played, level, sublevel)
				VALUES ('$username', '$email','$password_hash', 'en', 0, 0, 0, 1, 1)");
	if(!$db->error)
	{
		
	}
	else
	{
		return false;
	}
	
	$result = $db->query("SELECT id FROM users WHERE username = '$username'");
	$userID = $result->fetch_all(MYSQLI_ASSOC)[0]["id"];
	//print($userID);
	$languages = array("en", "fr");
	for($i = 0; $i < count($languages); $i++)
	{
		$language = $languages[$i];
		$db->query("INSERT INTO language(name, userID, score, level, sublevel) VALUES ('$languages[$i]', '$userID', 0, 1, 1)");
		if(!$db->error)
		{
			
		}
		else
		{
			return false;
		}
	}
	
	return true;
	
}

function getPhrase()
{
	$db = dbConnect();
	$limit = 1;
	$result = $db->query("SELECT * FROM phrases ORDER BY RAND() LIMIT $limit");
	$phrases = $result->fetch_all(MYSQLI_ASSOC);
	for($i = 0; $i < count($phrases); $i++)
	{
		$phrase = $phrases[$i];
		$phraseId = $phrase['id'];
		$result = $db->query("SELECT * FROM answers WHERE phraseId=$phraseId ORDER BY RAND()");
		$phrases[$i]['answers'] = $result->fetch_all(MYSQLI_ASSOC);
	}
	return $phrases;
}

function getWordCheckbox($limit)
{
	$db = dbConnect();
	$result = $db->query("SELECT * FROM checkbox_phrases ORDER BY RAND() LIMIT $limit");
	$phrases = $result->fetch_all(MYSQLI_ASSOC);	
	//print_r($phrases);
	$answers_count = rand(1, 2);
	$wrong_answers_count = 4-$answers_count;

	for($i = 0; $i < count($phrases); $i++)
	{
		$phrase = $phrases[$i];
		$phraseId = $phrase['id'];
		$result = $db->query("SELECT * FROM checkbox_answers WHERE phraseId=$phraseId ORDER BY RAND() LIMIT $answers_count");
		$phrases[$i]['answers'] = $result->fetch_all(MYSQLI_ASSOC);
	}
	
	for($i = 0; $i < count($phrases); $i++)
	{
		$phrase = $phrases[$i];
		$phraseId = $phrase['id'];
		$result = $db->query("SELECT * FROM checkbox_wrong_answers WHERE phraseId=$phraseId ORDER BY RAND() LIMIT $wrong_answers_count");
		$phrases[$i]['wrong_answers'] = $result->fetch_all(MYSQLI_ASSOC);
	}
	
	
	//print_r($phrases);
	return $phrases;
	
	
	/*$correct_answers_all = $words["I was out today."];
	$incorrect_answers_all = $incorrect_words["I was out today."];
	$correct_answers = [];
	$incorrect_answers = [];
	$random_number = rand(1, 2);
	for($i = 0; $i < $random_number; $i++)
	{
		array_push($correct_answers,
				   array_splice($correct_answers_all,
								array_rand($correct_answers_all), 1)[0]);
	}

	for($i = $random_number; $i < 4; $i++)
	{
		array_push($incorrect_answers,
				   array_splice($incorrect_answers_all,
								array_rand($incorrect_answers_all), 1)[0]);
	}
	
	$result = ["correct_answers"=>$correct_answers,
			   "incorrect_answers"=>$incorrect_answers];
	return $result;
	*/
}

function addPhrase($phrase, $level, $sublevel, $answers)
{
	$db = dbConnect();
	//check  if phrase already exists;
	//update
	
	$phrase = $db->real_escape_string ($phrase);
	$level = $db->real_escape_string ($level);
	$sublevel = $db->real_escape_string ($sublevel);
	$add_phrase = $db->query("INSERT INTO phrases(phrase, level, sublevel) VALUES('$phrase', '$level', '$sublevel')");
	$phrase_id = $db->insert_id;
	print($db->error);
	for($i = 0; $i < count($answers); $i++)
	{
		$answer = $db->real_escape_string ($answers[$i]);
		$add_answers = $db->query("INSERT INTO answers(answer, phraseId) VALUES('$answer', $phrase_id)");
	}
	
	$db->close();
}

function check_words($answerWords, $inputWords, $typo, &$correct)
{
	for($j = 0; $j < count($answerWords); $j++)
	{
		
		$answer_length = mb_strlen($answerWords[$j]);
		$input_length = mb_strlen($inputWords[$j]);
		$sim = mb_similar_text($answerWords[$j], $inputWords[$j], $percent);
		if($percent < 75 && $answer_length > 3)
		{
			$correct = 0;
		}
		else if($answerWords[$j] == $inputWords[$j])
		{
			//correct word
			
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
								
								//typo
								$typo++;
							}
					}
					else if($answer_length == 3)
					{
						if($sim == 2)
						{
							
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
							
							//typo
							$typo++;
						}
						else
						{
							
							$correct = 0;
						}
					}
					else if($answer_length == 1)
					{
						//print("show");
						if($sim == 1)
						{
							//correct
						}
						else
						{
							
							$typo++;
						}
					}
					else
					{
						//incorrect the whole exercise
						
						$correct = 0;
					}
				}
				else if($answer_length - 1 == $sim)
				{
					
					$typo++;
				}
				else
				{
					
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
						
						$typo++;
					}
					else
					{
						
						$correct = 0;
					}
				}
				else if($input_length == 1)
				{
					if($sim == 1)
					{
						
						//$correct = 0; // 'y' is not 'ya' désolé but maybe a missed letter won't be fatal
					}
					else
					{
						
						$correct = 0;
						
					}
				}
				else if($input_length == 2)
				{
					if($sim == 1)
					{
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
					
					$typo++;
				}
				else
				{
					
					$correct = 0;
				}
			}
			else if($answer_length + 1 == $input_length)
			{
				if($answer_length == $sim)
				{
					$typo++;
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
		}
		
		
	}
	return $correct;
}
require __DIR__ . '/vendor/autoload.php';

function checkPhrase($input, $answers, $phrase)
{
	
	
	//var_dump($answers);
	//print_r($answers->answer);
	//print($answers->answer[0]);
	//print($answers->answer[1]);
	/* $input = $_POST["input"];
	$score = $_POST["score"];
	$phrase = $_POST["correct_word"]; */
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
	*/
	//print_r(array_values($words));
	//if the random word is in the array(by checking), then get the values assigned to it
	//acc_words are accepted words
	
	
	
	
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
	
	for($k = 0; $k < count($answers); $k++)
	{
		$correct = 1;
		$typo = 0;
		$curr_correct = [];
		$answer = $answers[$k];
		$answer = preg_replace("/[^[:alnum:][:space:]]/u", '', $answer);
		$answer = mb_strtolower($answer,  mb_detect_encoding($answer));
		
		$answerWords = explode(" ", $answer);
		$inputWords = explode(" ", $input);
		$answer_length = mb_strlen($answer);
		$input_length = mb_strlen($input);
		if($input_length == 0)
		{
			$correct = 0;
			break;
		}
		else if(count($answerWords) > 0)
		{
			if(count($answerWords) == count($inputWords))
			{
				check_words($answerWords, $inputWords, $typo, $correct);
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
				}
				//$correct = 0;
				$whole_answer_length = mb_strlen($whole_answer);
				$whole_input_length = mb_strlen($whole_input);
				$sim = mb_similar_text($whole_answer, $whole_input, $percent);
				
				if($whole_answer_length == $whole_input_length)
				{
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
	
	if($correct == 1)
	{
		//print("correct | $typo | ");
		//$score++;
		return true;
	}
	else
	{
		//$score = 0;
		return false;
	}
}


function incrementScore($username)
{
	$db = $dbConnect();
	$result = $db->query("SELECT score FROM users WHERE username = '$username'");
	$score = $result->fetch_assoc()["score"];
	$score += 1;
	$db->query("UPDATE users SET score = $score WHERE username = '$username'");
}

function postComment($content, $username, $phraseID)
{
	$db = dbConnect();
	$result = $db->query("SELECT * FROM users WHERE username = '$username'");
	
	print($db->error);
	if(!$result)
	{
		print("Error");
	}
	$userID = $result->fetch_all(MYSQLI_ASSOC)[0]["id"];
	
	$postcomment = $db->query("INSERT INTO comments(comment, userID, phraseID) VALUES('$content', $userID, $phraseID)");
	
	if($postcomment)
	{
		return true;
	}
	else
	{
		return false;
	}
	
	
}

function getPhraseComments($phraseId, $from, $commentCount)
{
	$db = dbConnect();
	$phraseId = $db->real_escape_string($phraseId);
	$from = $db->real_escape_string($from);
	$commentCount = $db->real_escape_string($commentCount);
	$result = $db->query("SELECT * FROM comments WHERE phraseId=$phraseId LIMIT $commentCount");
	if(!$result)
	{
		print("Error");
	}
	$comments = $result->fetch_all(MYSQLI_ASSOC);
	for($i = 0; $i < count($comments); $i++)
	{
		$userID = $comments[$i]["userID"];
		$result = $db->query("SELECT username FROM users WHERE id = $userID");
		if(!$result)
		{
			print("Error");
		}
		$username = $result->fetch_all(MYSQLI_ASSOC)[0]["username"];
		$comments[$i]["username"] = $username;
	}
	return $comments;
}

function changeUserLanguage($language, $username)
{
	$db = dbConnect();
	$language = $db->real_escape_string($language);
	$username = $db->real_escape_string($username);
	$result = $db->query("SELECT id FROM users WHERE username = '$username'");
	$userID = $result->fetch_all(MYSQLI_ASSOC)[0]["id"];
	$result = $db->query("UPDATE users SET language = '$language' WHERE username = '$username'");
	if(!$result)
	{
		return false;
	}
	$result = $db->query("SELECT level FROM language WHERE userId = '$userID' and name = '$language'");
	if(!$result)
	{
		return false;
	}
	$level = $result->fetch_all(MYSQLI_ASSOC)[0]["level"];
	$result = $db->query("SELECT sublevel FROM language WHERE userId = '$userID' and name = '$language'");
	$sublevel = $result->fetch_all(MYSQLI_ASSOC)[0]["sublevel"];
	$result = $db->query("UPDATE users SET level = '$level', sublevel = '$sublevel' WHERE id = '$userID'");
	if(!$result)
	{
		return false;
	}
	return true;
	
}

function getWordsUser($username, $language, $level, $sublevel)
{
	$db = dbConnect();
	$username = $db->real_escape_string($username);
	$language = $db->real_escape_string($language);
	$level = $db->real_escape_string($level);
	$sublevel = $db->real_escape_string($sublevel);
	
	$words_result = $db -> query("SELECT * FROM words WHERE level<='$level' and sublevel<='$sublevel' and language = '$language'");
	$words = $words_result->fetch_all(MYSQLI_ASSOC);
	//$words_translations_result = $db->query
	for($i = 0; $i < count($words); $i++)
	{	
		$word = $words[$i]['word'];
		$wordID = $words[$i]['id'];
		$words_translations_result = $db->query("SELECT word FROM words_translations WHERE  from_language = '$language' and wordID = '$wordID'");
		$words[$i]['translation'] = $words_translations_result->fetch_all(MYSQLI_ASSOC);
	}
	
	//print_r($words);
	return $words;	
}

function getUserLanguage($username)
{
	$db = dbConnect();
	$username = $db->real_escape_string($username);
	$result = $db->query("SELECT language FROM users WHERE username = '$username'");
	if(!$result)
	{
		print("Error");
	}
	$language = $result->fetch_all(MYSQLI_ASSOC)[0]["language"];
	
	return $language;
}

function updateUserScoreAndLevel($username, $score_to_update, $language, $level, $sublevel)
{
	$db = dbConnect();
	$language = $db->real_escape_string($language);
	$username = $db->real_escape_string($username);
	$level = $db->real_escape_string($level);
	$sublevel = $db->real_escape_string($sublevel);
	
	$score_to_update = $db->real_escape_string($score_to_update);
	
	$userID_result = $db->query("SELECT id FROM users WHERE username = '$username'");
	$userID = $userID_result->fetch_all(MYSQLI_ASSOC)[0]["id"];
	
	$userLevel_result = $db->query("SELECT level FROM users WHERE id = '$userID'");
	$userLevel = $userLevel_result->fetch_all(MYSQLI_ASSOC)[0]["level"];
	$userSublevel_result = $db->query("SELECT sublevel FROM users WHERE id = '$userID'");
	$userSublevel = $userSublevel_result->fetch_all(MYSQLI_ASSOC)[0]["sublevel"];
	
	print("$level - $userLevel");
	print("\n$sublevel - $userSublevel");
	if($sublevel < 5 && $sublevel == $userSublevel && $level == $userLevel)
	{
		$sublevel+=1;
		$language_result = $db->query("UPDATE language SET sublevel = '$sublevel' WHERE userID = '$userID' and name = '$language'");
		
		$language_result = $db->query("UPDATE users SET sublevel = '$sublevel' WHERE id = '$userID' and language = '$language'");
	}
	else if($sublevel == 5 && $sublevel == $userSublevel && $level == $userLevel)
	{
		if($level <= 4)
		{
			$level+=1;
			$sublevel = 1;
			$language_result = $db->query("UPDATE language SET level = '$level' WHERE name = '$language' and userID = '$userID'");
			$language_result = $db->query("UPDATE language SET sublevel = '$sublevel' WHERE name = '$language' and userID = '$userID'");
			
			$language_result = $db->query("UPDATE users SET level = '$level' WHERE language = '$language' and id = '$userID'");
			$language_result = $db->query("UPDATE users SET sublevel = '$sublevel' WHERE language = '$language' and id = '$userID'");
		}
		else
		{
			//maximum level reached;
		}
		
	}
	/* if($level > $userLevel)
	{
		if($level < 6)
		{
			$language_result = $db->query("UPDATE language SET level = '$level' WHERE userID = '$userID' and name='$language'");
			$sublevel = 1;
			$language_result = $db->query("UPDATE language SET sublevel = '$sublevel' WHERE userID = '$userID' and name = '$language'");
		}
	} */
	

	$score_result = $db->query("SELECT score FROM users WHERE username = '$username'");
	if(!$score_result)
	{
		print("Error");
		return false;
	}
	$userScore = $score_result->fetch_all(MYSQLI_ASSOC)[0]["score"];
	
	$userScore += $score_to_update;
	$result = $db->query("UPDATE users SET score = '$userScore' WHERE username = '$username'");
	if(!$result)
	{
		print("Error");
		return false;
	}
	
	$result = $db->query("UPDATE language SET score ='$userScore' WHERE userID = '$userID' and name = '$language'");
	if(!$result)
	{
		print("Error");
		return false;
	}
	return $userScore;
}
function getUserProgress($username, $language)
{
	$db = dbConnect();
	$username = $db->real_escape_string($username);
	$language = $db->real_escape_string($language);
	
	$userID_result = $db->query("SELECT id FROM users WHERE username = '$username'");
	$userID = $userID_result->fetch_all(MYSQLI_ASSOC)[0]["id"];
	
	$language_result = $db->query("SELECT * FROM language WHERE userID = '$userID'");
	$language = $language_result->fetch_all(MYSQLI_ASSOC);
	return $language;
}

function updateDateUser($username, $days_played_to_add)
{
	$db = dbConnect();
	$username = $db->real_escape_string($username);
	$days_played_to_add = $db->real_escape_string($days_played_to_add);
	if($days_played_to_add == 1)
	{
		$days_played_result = $db->query("SELECT days_played FROM users WHERE username = '$username'");
		if(!$days_played_result)
		{
			print("Error");
			return false;
		}
		$days_played = $days_played_result->fetch_all(MYSQLI_ASSOC)[0]["days_played"];
		$days_played += $days_played_to_add;
		$result = $db->query("UPDATE users SET days_played = '$days_played' WHERE username='$username'");
		if(!$result)
		{
			print("Error");
			return false;
		}
		return true;
	}
	else if($days_played_result == 0)
	{
		$result = $db->query("UPDATE users SET days_played = 0 WHERE username = '$username'");
		if(!$result)
		{
			print("Error");
			return false;
		}
		return true;
	}
}
/*
$db = new mysqli("localhost", "root", "root", "words");
$result = $db->query("SELECT * FROM players");
if(!$result)
{
	print($db->error);
}

$data = [];
while($values = $result->fetch_assoc())
{
	array_push($data, $values);
}
*/

$command = file_get_contents ("php://input");
$command = json_decode($command);

if($command[0] == "increment-score")
{
	//incrementScore($db);
}
else if($command[0] == "get-phrase")
{
	$result = getPhrase();
	print (json_encode($result, JSON_UNESCAPED_UNICODE));
}
else if($command[0] == "check-phrase")
{
	$input = $command[1];
	$answers = $command[2];
	$phrase = $command[3];
	//print($command[4]);
	//$printer = "s";
	$checkPhraseResult = checkPhrase($input, $answers, $phrase);
	//print($printer);
	//$comments = getComments($phrase);
	$result = [
		'result' => $checkPhraseResult,
		//'correctPhrase' => $words[$phrase][0],
		//'comments' => $comments]
	];
	/*$printer = [
		'resultoffunc' => $checkCodePhrase
		'correctPhrase' => $$words[$phrase][0]
	]*/
	print (json_encode($result, JSON_UNESCAPED_UNICODE));
	//print (json_encode($printer, JSON_UNESCAPED_UNICODE));
}
/* else if($command[0] == "init-db")
{
	//print("2");
	dbInit();
} */
else if($command[0] == "get-user-progress")
{
	$username = $command[1];
	$language = $command[2];
	$result = getUserProgress($username, $language);
	print (json_encode($result, JSON_UNESCAPED_UNICODE));
}
else if($command[0] == "get-user-language")
{
	$result = getUserLanguage($command[1]);
	print (json_encode($result, JSON_UNESCAPED_UNICODE));
}
else if($command[0] == "update-date-user")
{
	$username = $command[1];
	$days_played_to_add = $command[2];
	$result = updateDateUser($username, $days_played_to_add);
	print (json_encode($result, JSON_UNESCAPED_UNICODE));
}
else if($command[0] == "update-user-score-and-level")
{
	$username = $command[1];
	$score_to_update = $command[2];
	$language = $command[3];
	$level = $command[4];
	$sublevel = $command[5];
	$result = updateUserScoreAndLevel($username, $score_to_update, $language, $level, $sublevel);
	print (json_encode($result, JSON_UNESCAPED_UNICODE));
}
else if($command[0] == "get-level")
{
	$level = $command[1];
	$sublevel = $command[2];
	$language = $command[3];
	$result = getLevel($level, $sublevel, $language);
	print (json_encode($result, JSON_UNESCAPED_UNICODE));
}
else if($command[0] == "register-user")
{
	$result = registerUser($command[1], $command[2], $command[3]);
	print (json_encode($result, JSON_UNESCAPED_UNICODE));
}
else if($command[0] == "get-user-info")
{
	$username = $command[1];
	//$language = $command[2];
	$result = getUserInfo($username);
	print (json_encode($result, JSON_UNESCAPED_UNICODE));
}
else if($command[0] == "get-user-level")
{
	$result = getUserLevel($command[1]);
	print (json_encode($result, JSON_UNESCAPED_UNICODE));
}
else if($command[0] == "change-language")
{
	$result = changeUserLanguage($command[1], $command[2]);
	print (json_encode($result, JSON_UNESCAPED_UNICODE));
}
else if($command[0] == "login")
{
	$result = login($command[1], $command[2]);
	print (json_encode($result, JSON_UNESCAPED_UNICODE));
}
else if($command[0] == "get-words-user")
{
	$username = $command[1];
	$language = $command[2];
	$level = $command[3];
	$sublevel = $command[4];
	$result = getWordsUser($username, $language, $level, $sublevel);
	print (json_encode($result, JSON_UNESCAPED_UNICODE));
}
else if($command[0] == "get-words-checkbox")
{
	$limit = 2;
	$result = getWordCheckbox($limit);
	print (json_encode($result, JSON_UNESCAPED_UNICODE));
}
else if($command[0] == "get-phrase-comments")
{
	$result = getPhraseComments($command[1], 0, 30);
	print (json_encode($result, JSON_UNESCAPED_UNICODE));
}
else if($command[0] == "post-comment")
{
	$result = postComment($command[1], $command[2], $command[3]);
	print(json_encode($result, JSON_UNESCAPED_UNICODE));
}
else if($command[0] == "add-phrase")
{
	$phrase = $command[1];
	$level = $command[2];
	$sublevel = $command[3];
	for($i = 0; $i < 4; $i++)
	{
		array_shift($command);
	}
	addPhrase($phrase, $level, $sublevel, $command);
}
else
{
	print ('"Unknown Command"');
}

//$db->close();
?>

