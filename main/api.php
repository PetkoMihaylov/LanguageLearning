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
					   sublevel INTEGER NOT NULL)
					   ");
	if (!$users)
	{
		print($db->error);
	}
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
						   userID INT NOT NULL,
						   FOREIGN KEY (userID) REFERENCES users(id),
						   phraseID INT NOT NULL,
						   FOREIGN KEY (phraseID) REFERENCES phrases(id),
						   timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
						   photo VARCHAR(256)
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
	
	
	$insert = $db-> query("INSERT INTO phrases(phrase, level, sublevel, language) VALUES('I _ out today.','1','1','$language')");
	$phrase_id = $db->insert_id;
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('was', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('had', '$phrase_id', '$language')");
	
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
		$result = $db->query("SELECT * FROM checkbox_answers WHERE phraseId=$phraseId");
		$checkbox_phrases[$i]['answers'] = $result->fetch_all(MYSQLI_ASSOC);
	}
	for($i = 0; $i < count($checkbox_phrases); $i++ )
	{
		$checkbox_phrase = $checkbox_phrases[$i];
		$checkbox_phrase_id = $checkbox_phrase['id'];
		$result = $db->query("SELECT * FROM checkbox_wrong_answers WHERE phraseId=$phraseId");
		$checkbox_phrases[$i]['wrong_answers'] = $result->fetch_all(MYSQLI_ASSOC);
	}

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

function checkPhrase($words, $phrase, $input, $printer)
{
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
		$answer = $answers[$i];
		$answer = preg_replace("/[^[:alnum:][:space:]]/u", '', $answer);
		$answer = mb_strtolower($answer,  mb_detect_encoding($answer));
		$sim = similar_text($answer, $input, $percent);
		
		//check if percent is big enough, then check
		$word_count = str_word_count($answer, 0);
		/*$pizza  = "piece1 piece2 piece3 piece4 piece5 piece6";
		$pieces = explode(" ", $pizza);*/
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
		}
		
		if($input == $answer)
		{
			return true;
		}
		
	
	}
	
	return false;
}


function incrementScore($db)
{
	$result = $db->query("SELECT points FROM users WHERE id = 1");
	$score = $result->fetch_assoc()["points"];
	$score += 1;
	$db->query("UPDATE users SET points = $score WHERE id = 1");
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
	$phrase = $command[1];
	$input = $command[2];
	//$printer = "s";
	$checkPhraseResult = checkPhrase($words, $phrase, $input);
	//print($printer);
	//$comments = getComments($phrase);
	$result = [
		'result' => $checkPhraseResult,
		'correctPhrase' => $words[$phrase][0],
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

