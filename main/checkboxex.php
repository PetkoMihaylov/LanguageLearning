<!DOCTYPE html>
<html>
<head>
	<script src="/_res/js.js"></script>
	<link rel="stylesheet" href="/_res/style.css">
</head>
<body>
<?php

$words = array(
   "I was out today."=>["Бях навън днес.", "Днес бях навън.", "Навън бях днес."]
   "I had work today."=>["Имах работа днес.", "Днес имах работа."]
   "Това беше"
);
$inc_words



$word = array_rand ($words);
print("$word | \n");
$curr_word = $word;
print("$score\n");




?>
<form action="checkboxex.php">
  <input type="checkbox" name="vehicle" value="Bike"><?php $correct_answer?><br>
  <input type="checkbox" name="vehicle" value="Car" checked> <?php $correct_answer ?> <br>
  <input type="submit" value="Провери">
</form>
</body>


