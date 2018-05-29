<!DOCTYPE html>
<html>
<head>
	<meta charset="cp1251">
	<script src="audio.js"></script>
	<script src="/_res/js.js"></script>
	<link rel="stylesheet" href="/_res/style.css">
</head>
<body>



<?php

$words = array(
   "I was out today."=>["Бях навън днес.", "Днес бях навън.", "Навън бях днес."],
   "I had work today."=>["Имах работа днес.", "Днес имах работа."]
   //"Това беше"
);

$incorrect_words = array(
	"I was out today." => ["Бях на навън днес.", "Бях навън вчера.", "Бях на вън откъде.", "Навън бях вчера."],
	"I had work today." => ["Днес беше тъмно.","Беше тъмно утре."]
);

//permanent_storage javascript webstorage


print("<br>");
print_r($correct_answers);
print("<br>");
print_r($incorrect_answers);
print($random_number);

//dbInit();



//$word = array_rand ($words);
//print("$word | \n");
/*$curr_word = $word;
print("$score\n");*/




?>
<form name="test" onsubmit="return onSubmit()">
	<div class="question">
		<h2>Which of the following is the correct answer to <?php $word = array_rand($words); print("'$word'")  ?></h2>
	</div>

</form>

<form action="checkboxex.php">
  <input type="checkbox" name="vehicle" value="Bike"><?php print_r( array_values( $words ));?><br>
  <input type="checkbox" name="vehicle" value="Car" checked> <?php print_r( array_values( $words)); ?> <br>
  <input type="submit" value="Провери">
</form>
</body>


