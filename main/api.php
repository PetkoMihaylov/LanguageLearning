<?php
//header('Content-Type: application/json');

function incrementScore($db)
{
	$result = $db->query("SELECT points FROM players WHERE id = 1");
	$score = $result->fetch_assoc()["points"];
	$score += 1;
	$db->query("UPDATE players SET points = $score WHERE id = 1");
}

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

//print (json_encode($data, JSON_UNESCAPED_UNICODE));
$command = file_get_contents ("php://input");
if($command == "increment-score")
{
	incrementScore($db);
}

$db->close();
?>

