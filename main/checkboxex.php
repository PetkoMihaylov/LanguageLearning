<!DOCTYPE html>
<html>
<head>
	<script src="/_res/js.js"></script>
	<link rel="stylesheet" href="/_res/style.css">
</head>
<body>
<?php

$words = array(
   "I was out today."=>["��� ����� ����.", "���� ��� �����.", "����� ��� ����."]
   "I had work today."=>["���� ������ ����.", "���� ���� ������."]
   "���� ����"
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
  <input type="submit" value="�������">
</form>
</body>


