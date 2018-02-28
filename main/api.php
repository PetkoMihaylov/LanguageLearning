<?php
//header('Content-Type: application/json');
define("DB_SALT", "1234rfkgjalvj0q4wjpvaFAFA!@$%kdv;awij09w4fskfer;vner;jlvnaer-");

$words = [
   "blue"=>["Синьо", "Син"],
   "building"=>["Сграда"], 
   "horse"=>["Кон", "Конче"],
   "rabbit"=>["Заек", "Зайче"],
   "I was out today."=>["Бях навън днес.", "Днес бях навън.", "Навън бях днес."],
   "I had work today."=>["Имах работа днес.", "Днес имах работа."]
];


$incorrect_words = [
	"I was out today." => ["Бях на навън днес.", "Бях навън вчера.", "Бях на вън откъде.", "Навън бях вчера."],
	"I had work today." => ["Днес беше тъмно.","Беше тъмно утре."]
];
//$printer = "babati";
//protected $ctormv = ('/ ,/' , '/= /', '/ =/', '/ -/', '/- /');
//protected $contplace = ('', '', '', '', '');

function dbConnect()
{
	$db = new mysqli("localhost", "root", "root", "words");
	if(!$db)
	{
		print($db->error);
	}
	return $db;
}

function dbInit()
{
	$db = new mysqli("localhost", "root", "root");
	if(!$db)
	{
		print($db->error);
	}
	$drop = $db->query("DROP DATABASE words;");
	if(!$drop)
	{
		print($db->error);
	}
	$create = $db->query("CREATE DATABASE words;");
	if(!$create)
	{
		print($db->error);
	}
	$db->close();
	$db = new mysqli("localhost", "root", "root", "words");
	
	$users = $db->query("Create table users (
                       id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
					   username VARCHAR(50) UNIQUE,
					   password VARCHAR(128),
					   email VARCHAR(255),
					   language VARCHAR(30) NOT NULL,
					   points INTEGER NOT NULL,
					   score INTEGER NOT NULL,
					   days_played INTEGER NOT NULL,
					   last_day_played DATE,
					   level INTEGER NOT NULL,
					   sublevel INTEGER NOT NULL
					   )
					   ");
	if (!$users)
	{
		print($db->error);
	}
	$language = $db->query("Create table languageprogress(
						  name VARCHAR(30) UNIQUE NOT NUL,
						  userID INTEGER NOT NULL,
						  level INTEGER NOT NULL,
						  sublevel INTEGER NOT NULL
						  
						  )
						  ");
	//
   $phrases = $db->query("Create table phrases(
						id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
						phrase VARCHAR(256),
						level INTEGER NOT NULL,
						sublevel INTEGER NOT NULL,
						language VARCHAR(30) NOT NULL
						)
						");
						//language and check if it is needed for wrong_answers
						//
	if (!$phrases)
	{
		print("Phrases:\n");
		print($db->error);
	}
	
	$answers = $db->query("Create table answers(
	                       id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
						   answer VARCHAR(256),
						   phraseId INT NOT NULL,
						   language VARCHAR(30) NOT NULL
						   )
						   ");
	if (!$answers)
	{
		print("Answers:\n");
		print($db->error);
		print("\n");
	}
	$image_words = $db->query("Create table image_words(
						id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
						word VARCHAR(256),
						incorrect_word0 VARCHAR(256),
						incorrect_word1 VARCHAR(256),
						level INTEGER NOT NULL,
						sublevel INTEGER NOT NULL,
						language VARCHAR(30) NOT NULL
						)
						");
	
	$words = $db->query("Create table words(
						id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
						word VARCHAR(50),
						level INTEGER NOT NULL,
						sublevel INTEGER NOT NULL,
						language VARCHAR(30) NOT NULL
						)
						");
	
	$words_translations = $db->query("Create table words_translations(
									id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
									word VARCHAR(256),
									wordId INT NOT NULL,
									from_language VARCHAR(30) NOT NULL
									)
									");
	
	
	
	/* $wrong_answers = $db->query("Create table wrong_answers(
	                       id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
						   answer VARCHAR(256) NOT NULL,
						   phraseId INT NOT NULL,
						   language VARCHAR(30) NOT NULL
						   )
						   ");
						   
	if (!$wrong_answers)
	{
		print("Answers:\n");
		print($db->error);
		print("\n");
	} */
	
								
	$radio_phrases = $db->query("Create table radio_phrases(
								id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
								phrase VARCHAR(256),
								correct_answer VARCHAR(256),
								wrong_answer0 VARCHAR(256),
								wrong_answer1 VARCHAR(256),
								wrong_answer2 VARCHAR(256),
								wrong_answer3 VARCHAR(256),
								level INTEGER NOT NULL,
								sublevel INTEGER NOT NULL,
								language VARCHAR(30) NOT NULL
								)
								");
	
	if (!$radio_phrases)
	{
		print("Answers:\n");
		print($db->error);
		print("\n");
	}	
	
	/* $radio_answers = $db->query("Create table radio_answers(
								id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
								phraseId INT NOT NULL,
								language VARCHAR(30) NOT NULL
								)
								"); */
								
	/* $radio_wrong_answers = $db->query("Create table radio_wrong_answers(
								id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
								answer VARCHAR(256),
								phraseId INT NOT NULL,
								language VARCHAR(30) NOT NULL
								)
								"); */

	$checkbox_phrases = $db->query("Create table checkbox_phrases(
								id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
								phrase VARCHAR(256),
								level INTEGER NOT NULL,
								sublevel INTEGER NOT NULL,
								language VARCHAR(30) NOT NULL
								)
								");
	
	if (!$checkbox_phrases)
	{
		print("Answers:\n");
		print($db->error);
		print("\n");
	}	
	
	$checkbox_answers = $db->query("Create table checkbox_answers(
								id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
								answer VARCHAR(256),
								phraseId INT NOT NULL,
								language VARCHAR(30) NOT NULL
								)
								");
								
	$checkbox_wrong_answers = $db->query("Create table checkbox_wrong_answers(
								id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
								answer VARCHAR(256),
								phraseId INT NOT NULL,
								language VARCHAR(30) NOT NULL
								)
								");
	
 	$comments = $db->query("Create table comments(
						   id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
						   comment VARCHAR(256),
						   userID INT NOT NULL,
						   FOREIGN KEY (userID) REFERENCES users(id),
						   phraseID INT NOT NULL,
						   FOREIGN KEY (phraseID) REFERENCES phrases(id),
						   timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
						   )
						   ");
						   //phraseID how it can connect to the name of the phrase
	// snimka, priqteli, diskusiq, kato forum,
	if (!$comments)
	{
		print("Comments:\n");
		print($db->error);
		print("\n");
	}
	
	print ($db->error);
	$language = "en";
	$insert = $db-> query("INSERT INTO checkbox_phrases(phrase, level, sublevel, language) VALUES('Blue', '1', '1', '$language')");
	$phrase_id = $db->insert_id;
	$insert = $db-> query("INSERT INTO checkbox_answers(answer, phraseId, language) VALUES('Синьо', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO checkbox_answers(answer, phraseId, language) VALUES('Син', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO checkbox_wrong_answers(answer, phraseId, language) VALUES('Червено', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO checkbox_wrong_answers(answer, phraseId, language) VALUES('Зелено', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO checkbox_wrong_answers(answer, phraseId, language) VALUES('Бяло', '$phrase_id'), '$language'");
	
	$insert = $db-> query("INSERT INTO checkbox_phrases(phrase, level, sublevel, language) VALUES('Building', '1', '1', '$language')");
	$phrase_id = $db->insert_id;
	$insert = $db-> query("INSERT INTO checkbox_answers(answer, phraseId, language) VALUES('Сграда', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO checkbox_wrong_answers(answer, phraseId, language) VALUES('Кола', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO checkbox_wrong_answers(answer, phraseId, language) VALUES('Влак', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO checkbox_wrong_answers(answer, phraseId, language) VALUES('Картина', '$phrase_id', '$language')");
	
	
	$insert = $db-> query("INSERT INTO phrases(phrase, level, sublevel, language) VALUES('I was out today.','1','1','$language')");
	$phrase_id = $db->insert_id;
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Бях навън днес.', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Аз бях навън днес.', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Навън бях днес.', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO phrases(phrase, level, sublevel, language) VALUES('Rabbit','1','1','$language')");
	$phrase_id = $db->insert_id;
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Заек', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Зайче', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO phrases(phrase, level, sublevel, language) VALUES('А girl is here.','1','1','$language')");
	$phrase_id = $db->insert_id;
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Момичето е тук.', '$phrase_id', '$language')");
	
	$insert = $db->query("INSERT INTO radio_phrases(phrase, correct_answer, wrong_answer0, wrong_answer1, wrong_answer2, wrong_answer3, level, sublevel, language) VALUES('I _ out today', 'was', 'had', 'were', 'blue', 'car', '1', '1', '$language')");
	
	$insert = $db->query("INSERT INTO image_words(word, incorrect_word0, incorrect_word1, level, sublevel, language) VALUES('rabbit','building','horse','1','1','$language')");
	$insert = $db->query("INSERT INTO image_words(word, incorrect_word0, incorrect_word1, level, sublevel, language) VALUES('horse','spider','wall','1','1','$language')");
	$insert = $db->query("INSERT INTO image_words(word, incorrect_word0, incorrect_word1, level, sublevel, language) VALUES('doll','wall','car','1','1','$language')");
	$insert = $db->query("INSERT INTO image_words(word, incorrect_word0, incorrect_word1, level, sublevel, language) VALUES('blue','car','red','1','1','$language')");
	$insert = $db->query("INSERT INTO image_words(word, incorrect_word0, incorrect_word1, level, sublevel, language) VALUES('building','car','horse','1','1','$language')");
	$insert = $db->query("INSERT INTO image_words(word, incorrect_word0, incorrect_word1,level, sublevel, language) VALUES('tree','plant','rabbit','1','1','$language')");
	
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('rabbit','1','1','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('заек','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('зайче','$word_id','$language')");
	
	$password = "vasko3";
	$password_hash = hash("sha512", $password . DB_SALT);
	$db->query("INSERT INTO users (username, email, password, language, points, score, days_played, level, sublevel) VALUES ('Vasko2', 'vasko@mail.com', '$password_hash', 'en', 0, 0, 0, 1, 1);");
	
	/* $db->query("INSERT INTO comments () VALUES ('Vasko2', 'vasko@mail.com',	'$password_hash', 'en', 0, 0, 0, 1, 1);"); */
	
	$db->close();
	
	/*$insert = $db-> query("INSERT INTO phrases(phrase, level, sublevel) VALUES('Rabbit', '1', '1')");
	$insert = $db-> query("INSERT INTO phrases(phrase, level, sublevel) VALUES('Blue', '1', '1')");*/
	
	
}

function getLevel($level, $sublevel)
{
	$db = dbConnect();
	$level = $db->real_escape_string ($level);
	$sublevel = $db->real_escape_string ($sublevel);

	$images_result = $db->query("SELECT * FROM image_words WHERE level='$level' and sublevel='$sublevel'");
	$images = $images_result->fetch_all(MYSQLI_ASSOC);
	
	$words_result = $db->query("SELECT * FROM words WHERE level='$level' and sublevel='$sublevel'");
	$words = $words_result->fetch_all(MYSQLI_ASSOC);
	for($i = 0; $i < count($words); $i++)
	{
		$wordId = $words[$i]['id'];
		$words_translations_result = $db->query("SELECT * FROM words_translations WHERE wordId='$wordId'");
		$words[$i]['translations'] = $words_translations_result->fetch_all(MYSQLI_ASSOC);
	}
	
	$phrases_result = $db->query("SELECT * FROM phrases WHERE level='$level' and sublevel='$sublevel'");
	$phrases = $phrases_result->fetch_all(MYSQLI_ASSOC);
	for($i = 0; $i < count($phrases); $i++)
	{
		$phraseId = $phrases[$i]['id'];
		$answers_result = $db->query("SELECT * FROM answers WHERE phraseId='$phraseId'");
		$phrases[$i]['answers'] = $answers_result->fetch_all(MYSQLI_ASSOC);
	}

	$checkbox_result = $db -> query("SELECT * FROM checkbox_phrases WHERE level='$level' and sublevel='$sublevel'");
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
	$word_result = $db -> query("SELECT * FROM words WHERE level='$level' and sublevel='$sublevel'");

	$radio_result = $db -> query("SELECT * FROM radio_phrases WHERE level='$level' and sublevel='$sublevel'");
	$radio_phrases = $radio_result->fetch_all(MYSQLI_ASSOC);
	

	$exercises = [
		"images" => $images,
		"words" => $words,
		"phrases" => $phrases,
		"radio" => $radio_phrases,
		"checkbox_phrases" => $checkbox_phrases,
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
	print($username);
	$username = $db->real_escape_string ($username);
	$result = $db->query("SELECT level, sublevel FROM users WHERE username='$username'");
	$userLevel = $result->fetch_all(MYSQLI_ASSOC);
	print_r($userLevel);
	return $userLevel;
}

function registerUser($username, $email, $password)
{
	$db = new mysqli("localhost", "root", "root", "words");
	$password_hash = hash("sha512", $password . DB_SALT);
	$username = $db->real_escape_string ($username);
	$email = $db->real_escape_string ($email);
	$db->query("INSERT INTO users (username, email, password, language, points, score, days_played, level, sublevel) VALUES ('$username', '$email', '$password_hash', 'en', 0, 0, 0, 1, 1);");
	if(!$db->error)
	{
		return true;
	}
	else
	{
		return false;
	}
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
	print($phrase);
	print_r($answers);
	$db = dbConnect();
	print($level);
	print("\n");
	print($sublevel);
	print("\n");
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
require __DIR__ . '/vendor/autoload.php';

function checkPhrase($input, $answers, $phrase)
{
	
	
	var_dump($answers);
	//print_r($answers->answer);
	//print($answers->answer[0]);
	//print($answers->answer[1]);
	/* $input = $_POST["input"];
	$score = $_POST["score"];
	$phrase = $_POST["correct_word"]; */
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
	
	if($correct == 1)
	{
		print("correct | $typo | ");
		$score++;
		return true;
	}
	else
	{
		$score = 0;
		return false;
	}
}


function incrementScore($db)
{
	$result = $db->query("SELECT points FROM users WHERE id = 1");
	$score = $result->fetch_assoc()["points"];
	$score += 1;
	$db->query("UPDATE users SET points = $score WHERE id = 1");
}

function getPhraseComments($phraseId, $from, $commentCount)
{
	$db = dbConnect();
	$phraseId = $db->real_escape_string($phraseId);
	$from = $db->real_escape_string($from);
	$commentCount = $db->real_escape_string($commentCount);
	$result = $db->query("SELECT * FROM comments WHERE phraseId=$phraseId LIMIT $commentCount");
	$comments = $result->fetch_all(MYSQLI_ASSOC);
	return $comments;
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
	print($command[4]);
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
else if($command[0] == "init-db")
{
	//print("2");
	dbInit();
}
else if($command[0] == "get-level")
{
	$level = $command[1];
	$sublevel = $command[2];
	$result = getLevel($level, $sublevel);
	print (json_encode($result, JSON_UNESCAPED_UNICODE));
}
else if($command[0] == "register-user")
{
	$result = registerUser($command[1], $command[2], $command[3]);
	print (json_encode($result, JSON_UNESCAPED_UNICODE));
}
else if($command[0] == "get-user-level")
{
	$result = getUserLevel($command[1]);
	print (json_encode($result, JSON_UNESCAPED_UNICODE));
}
else if($command[0] == "login")
{
	$result = login($command[1], $command[2]);
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

