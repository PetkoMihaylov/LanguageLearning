<?php


function dbInit()
{
	$db = new mysqli("localhost", "root", "root");
	if(!$db)
	{
		print($db->error);
	}
	$drop = $db->query("DROP DATABASE words IF EXISTS;");
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
	/*$language = $db->query("Create table languageprogress(
						  name VARCHAR(30) UNIQUE NOT NUL,
						  userID INTEGER NOT NULL,
						  level INTEGER NOT NULL,
						  sublevel INTEGER NOT NULL
						  )
						  ");*/
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
						   language VARCHAR(30) NOT NULL,
						   FOREIGN KEY(phraseId) REFERENCES phrases(id),
						   FOREIGN KEY(language) REFERENCES phrases(language)
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
									from_language VARCHAR(30) NOT NULL,
									FOREIGN KEY(wordId) REFERENCES words(id),
									FOREIGN KEY(from_language) REFERENCES words(language)
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
								language VARCHAR(30) NOT NULL,
								FOREIGN KEY(phraseId) REFERENCES checkbox_phrases(id),
								FOREIGN KEY(language) REFERENCES checkbox_phrases(language)
								)
								");
								
	$checkbox_wrong_answers = $db->query("Create table checkbox_wrong_answers(
								id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
								answer VARCHAR(256),
								phraseId INT NOT NULL,
								language VARCHAR(30) NOT NULL,
								FOREIGN KEY(phraseId) REFERENCES phrases(id),
								FOREIGN KEY(from_language) REFERENCES words(language)
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
	
	
	$language = "fr";
	
	
	$insert = $db-> query("INSERT INTO checkbox_phrases(phrase, level, sublevel, language) VALUES('Bleu', '1', '1', '$language')");
	$phrase_id = $db->insert_id;
	$insert = $db-> query("INSERT INTO checkbox_answers(answer, phraseId, language) VALUES('Синьо', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO checkbox_answers(answer, phraseId, language) VALUES('Син', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO checkbox_wrong_answers(answer, phraseId, language) VALUES('Червено', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO checkbox_wrong_answers(answer, phraseId, language) VALUES('Зелено', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO checkbox_wrong_answers(answer, phraseId, language) VALUES('Бяло', '$phrase_id'), '$language'");
	
	$insert = $db-> query("INSERT INTO checkbox_phrases(phrase, level, sublevel, language) VALUES('Le batiment', '1', '1', '$language')");
	$phrase_id = $db->insert_id;
	$insert = $db-> query("INSERT INTO checkbox_answers(answer, phraseId, language) VALUES('Сградата', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO checkbox_wrong_answers(answer, phraseId, language) VALUES('Колата', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO checkbox_wrong_answers(answer, phraseId, language) VALUES('Влакът', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO checkbox_wrong_answers(answer, phraseId, language) VALUES('Картината', '$phrase_id', '$language')");
	
	
	$insert = $db-> query("INSERT INTO phrases(phrase, level, sublevel, language) VALUES('J'etais dehors aujourd'hui.','1','1','$language')");
	$phrase_id = $db->insert_id;
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Бях навън днес.', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Аз бях навън днес.', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Навън бях днес.', '$phrase_id', '$language')");
	
	$insert = $db-> query("INSERT INTO phrases(phrase, level, sublevel, language) VALUES('Lapin','1','1','$language')");
	$phrase_id = $db->insert_id;
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Заек', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Зайче', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO phrases(phrase, level, sublevel, language) VALUES('La fille est ici.','1','1','$language')");
	$phrase_id = $db->insert_id;
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Момичето е тук.', '$phrase_id', '$language')");
	
	$insert = $db->query("INSERT INTO radio_phrases(phrase, correct_answer, wrong_answer0, wrong_answer1, wrong_answer2, wrong_answer3, level, sublevel, language) VALUES('I _ out today', 'was', 'had', 'were', 'blue', 'car', '1', '1', '$language')");
	
	$insert = $db->query("INSERT INTO image_words(word, incorrect_word0, incorrect_word1, level, sublevel, language) VALUES('lapin','batiment','cheval','1','1','$language')");
	$insert = $db->query("INSERT INTO image_words(word, incorrect_word0, incorrect_word1, level, sublevel, language) VALUES('cheval','araignee','mur','1','1','$language')");
	$insert = $db->query("INSERT INTO image_words(word, incorrect_word0, incorrect_word1, level, sublevel, language) VALUES('poupee','mur','voiture','1','1','$language')");
	$insert = $db->query("INSERT INTO image_words(word, incorrect_word0, incorrect_word1, level, sublevel, language) VALUES('bleu','voiture','rouge','1','1','$language')");
	$insert = $db->query("INSERT INTO image_words(word, incorrect_word0, incorrect_word1, level, sublevel, language) VALUES('batiment','voiture','cheval','1','1','$language')");
	$insert = $db->query("INSERT INTO image_words(word, incorrect_word0, incorrect_word1,level, sublevel, language) VALUES('arbre','plante','lapin','1','1','$language')");
	
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('lapin','1','1','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('заек','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('зайче','$word_id','$language')");
	
	
	
	
	
	
	
	
	$password = "vasko3";
	$password_hash = hash("sha512", $password . DB_SALT);
	$db->query("INSERT INTO users (username, email, password, language, points, score, days_played, level, sublevel) VALUES ('Vasko2', 'vasko@mail.com', '$password_hash', 'en', 0, 0, 0, 1, 1);");
	
	/* $db->query("INSERT INTO comments () VALUES ('Vasko2', 'vasko@mail.com',	'$password_hash', 'en', 0, 0, 0, 1, 1);"); */
	
	$db->close();
	
	
}

dbInit();





?>