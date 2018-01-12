<!DOCTYPE html>
<html>
<head>
	<script src="/_res/js.js"></script>
	<link rel="stylesheet" href="/_res/style.css">
</head>
<body>

<!--
<pre>
cd \ngine\php
php-cgin -b 127.0.0.1:9000

cd \ngine
nginx

cd \ngine
nginx -s reload
nginx -s quit
</pre>
-->

<pre>
<?php
$db = new mysqli("localhost", "root", "root", "words");
/*$table = $db->query("Create table players (
                       id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
					   username VARCHAR(50) UNIQUE,
					   language VARCHAR(20) NOT NULL,
					   points INTEGER NOT NULL,
					   days_played INTEGER NOT NULL,
					   last_day_played DATE)");
				      //dobavi parola 
					  //poshta
					  //snimka
					  //??-priqteli
					  //
//diskusiq, kato forum,
if(!$table)
{
	print($db->error);
}
*/
/*$insert_player = $db->query("INSERT INTO players(username, language, points, days_played) VALUES(\"Wasil\",\"Сръбски\", 0, 0)");

if(!$insert_player)
{
	print($db->error);
}*/

$result = $db->query("SELECT * FROM players");
if(!$result)
{
	print($db->error);
}

while($values = $result->fetch_assoc())
{
	$username = $values['username'];
	$points = $values['points'];
	print("$username = $points\n");
}

$db->close();

$var_1 = trim(strtolower('MichaeL is')); 
$var_2 = trim(strtolower('MichaEl    is')); 

$var_2 = preg_replace ('/ +/', ' ', $var_2);

$sim = similar_text($var_1, $var_2, $percent);
echo "$sim\n";
echo $percent;

$words = array(
   "blue"=>["синьо", "син"],
   "building"=>["сграда"], 
   "horse"=>["кон"],
   "rabbit"=>["заек"]
);

if(isset($_POST["input"]))
{
	$input = $_POST["input"];
	$score = $_POST["score"];
	$correct_word = $_POST["correct_word"];
	print("$input | ");
	print("$correct_word | ");
	
	if(in_array($input, $words[$correct_word]))
	{
		print("correct\n");
		$score ++;
		//print(")
	}
}
else
{
	$score = 0;
}

$word = array_rand ($words);
print("$word\n");
print("$score\n");

?>
</pre>

<svg width="500" height="200" style="border: dotted 1px">
	<rect x="10" y="10" width="<?php print($score*30); ?>" height="30" fill="rgb(255,0,0)"/>	
<?php

for ($i=0; $i < $score; ++$i)
{
	$x = $i * 40 + 10;
	print ("	<rect x=\"$x\" y=\"60\" width=\"30\" height=\"30\" fill=\"rgb(255,0,0)\" />\n");
}

?>
	
</svg>

<form action="test.php" method="POST">
	<input type="text" name="input"/>
	<input type="hidden" name="correct_word" value="<?php print($word); ?>"/>
	<input type="hidden" name="score" value="<?php print($score); ?>"/>
	<button type="submit">Провери</button>
</form>

</body>
</html>