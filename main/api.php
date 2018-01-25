<?php
//header('Content-Type: application/json');


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
					   days_played INTEGER NOT NULL,
					   last_day_played DATE
					   
					   )
					   ");
					   //Нива и раздели!!!!
	if (!$users)
	{
		print($db->error);
	}
   $phrases = $db->query("Create table phrases(
						  id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
						  phrase VARCHAR(256))");
	if (!$phrases)
	{
		print("Phrases:\n");
		print($db->error);
	}

	$answers = $db->query("Create table answers(
	                       id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
						   answer VARCHAR(256) NOT NULL,
						   phraseId INT NOT NULL)");
	if (!$answers)
	{
		print("Answers:\n");
		print($db->error);
		print("\n");
	}

 	$comments = $db->query("Create table comments(
						   id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
						   userID INT NOT NULL,
						   FOREIGN KEY (userID) REFERENCES users(id),
						   phraseID INT NOT NULL,
						   FOREIGN KEY (phraseID) REFERENCES phrases(id),
						   timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");
	// snimka, priqteli, diskusiq, kato forum,
	if (!$comments)
	{
		print("Comments:\n");
		print($db->error);
		print("\n");
	}
}

function registerUser($username, $email, $password)
{
	$db = new mysqli("localhost", "root", "root", "words");
	$salt = "1234rfkgjalvj0q4wjpvaFAFA!@$%kdv;awij09w4fskfer;vner;jlvnaer-";
	$password_hash = hash("sha512", $password . $salt);
	$username = $db->real_escape_string ($username);
	$email = $db->real_escape_string ($email);
	$db->query("INSERT INTO users (username, email, password, language, points, days_played) VALUES ('$username', '$email', '$password_hash', 'en', 0, 0);");
	print ($db->error);
}

function getWordCheckbox($words, $incorrect_words)
{
	$correct_answers_all = $words["I was out today."];
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

function getWord($words)
{
	return array_rand($words);
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
else if($command[0] == "get-word")
{
	$result = getWord($words);
	print (json_encode($result, JSON_UNESCAPED_UNICODE));
}
else if($command[0] == "check-word")
{
	$phrase = $command[1];
	$input = $command[2];
	$printer = "s";
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
	print (json_encode($printer, JSON_UNESCAPED_UNICODE));
}
else if($command[0] == "init-db")
{
	dbInit();
}
else if($command[0] == "register-user")
{
	registerUser($command[1], $command[2], $command[3]);
}
else if($command[0] == "get-words-checkbox")
{
	$result = getWordCheckbox($words, $incorrect_words);
	print (json_encode($result, JSON_UNESCAPED_UNICODE));
}
else
{
	print ('"Unknown Command"');
}

//$db->close();
?>

