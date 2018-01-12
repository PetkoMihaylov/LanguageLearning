<?php
//header('Content-Type: application/json');


$words = [
   "blue"=>["Синьо", "Син"],
   "building"=>["Сграда"], 
   "horse"=>["Кон", "Конче"],
   "rabbit"=>["Заек", "Зайче"],
   "I was out today."=>["Бях навън днес.", "Днес бях навън.", "Навън бях днес."]
];

function checkPhrase($words, $phrase, $input)
{
	$input = trim($input);
	$input = preg_replace ('/ +/', ' ', $input);
	$input = preg_replace("/[^[:alnum:][:space:]]/u", '', $input);
	$input = mb_strtolower($input,  mb_detect_encoding($input));
	
	$answers = $words[$phrase]; 
	for($i = 0; $i < count($answers); $i++)
	{
		$answer = $answers[$i];
		$answer = preg_replace("/[^[:alnum:][:space:]]/u", '', $answer);
		$answer = mb_strtolower($answer,  mb_detect_encoding($answer));
		$sim = similar_text($answer, $input, $percent);
		
		//check if percent is big enough, then check
		$word_count = str_word_count($answer, 0);
		
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
	
		if($input == $answer)
		{
			return true;
		}
	}
	
	return false;
}


function incrementScore($db)
{
	$result = $db->query("SELECT points FROM players WHERE id = 1");
	$score = $result->fetch_assoc()["points"];
	$score += 1;
	$db->query("UPDATE players SET points = $score WHERE id = 1");
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
	$checkPhraseResult = checkPhrase($words, $phrase, $input);
	//$comments = getComments($phrase);
	$result = [
		'result' => $checkPhraseResult,
		'correctPhrase' => $words[$phrase][0],
		//'comments' => $comments]
	];
	print (json_encode($result, JSON_UNESCAPED_UNICODE));
}

//$db->close();
?>

