<HTML>
	<form action="." method="POST">
		<input name="password" type="password">
	</form>
<?php
define("DB_SALT", "1234rfkgjalvj0q4wjpvaFAFA!@$%kdv;awij09w4fskfer;vner;jlvnaer-");

function dbInit($password)
{
	if(filesize('password.php') == 0)
	{
		$password_file = fopen('password.php', 'w');
		$password_php = "<?php\n$password\n?>";
		$password_write = fwrite($password_file, $password_php);
		fclose($password_file);
		if(!$password_write)
		{
			print("Password not written");
			return;
		}
	}
	else
	{
		$password_file = fopen ('password.php', 'r');
		$password_file_content = fread($password_file, filesize('password.php'));
		fclose($password_file);
		
		$password_file_content = explode("\n",$password_file_content)[1];
		$password_file_content = trim($password_file_content);
		if($password_file_content != $password)
		{
			print("The database did not initialize correctly.");
			return;
		}
	}
	
	
	
	$db = new mysqli("localhost", "root", "root");
	if(!$db)
	{
		print($db->error);
	}
	$drop = $db->query("DROP DATABASE IF EXISTS words;");
	if(!$drop)
	{
		print($db->error);
	}
	$create = $db->query("CREATE DATABASE words
	CHARACTER SET utf8 COLLATE utf8_general_ci;");
	$db->set_charset("utf8");
	if(!$create)
	{
		print($db->error);
	}
	$db->close();
	$db = new mysqli("localhost", "root", "root", "words");
	
	$users = $db->query("
						Create table users (
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
					   //the score is for completed levels
					   //the points are for right/wrong comparison -maybe, because je n'ai pas d'idée comment faire ça
	if (!$users)
	{
		print($db->error);
	}
	$levelContent = $db->query("Create table levelContent(
						text VARCHAR(512) NOT NULL,
						level INTEGER NOT NULL
						)
						");
	
	$language = $db->query("Create table language(
						  name VARCHAR(30) NOT NULL,
						  userID INTEGER NOT NULL,
						  score INTEGER NOT NULL,
						  level INTEGER NOT NULL,
						  sublevel INTEGER NOT NULL,
						  FOREIGN KEY(userId) REFERENCES users(id)
						  )
						  ");
						  // the name of the language should not be unique--
	//
	if (!$language)
	{
		print("Phrases:\n");
		print($db->error);
	}
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
						   FOREIGN KEY(phraseId) REFERENCES phrases(id)
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
									FOREIGN KEY(wordId) REFERENCES words(id)
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
								#foreign key(phraseID) REFERENCE (od checkbox-phraseza)
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
								FOREIGN KEY(phraseId) REFERENCES checkbox_phrases(id)
								)
								");
								
	$checkbox_wrong_answers = $db->query("Create table checkbox_wrong_answers(
								id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
								answer VARCHAR(256),
								phraseId INT NOT NULL,
								language VARCHAR(30) NOT NULL,
								FOREIGN KEY(phraseId) REFERENCES checkbox_phrases(id)
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
	#english#level_1#sublevel_1#
	$language = "en";
	$insert = $db-> query("INSERT INTO checkbox_phrases(phrase, level, sublevel, language) VALUES('Blue', '1', '1', '$language')");
	$phrase_id = $db->insert_id;
	$insert = $db-> query("INSERT INTO checkbox_answers(answer, phraseId, language) VALUES('Синьо', '$phrase_id', '$language')");
	print($db->error);
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
	$insert = $db-> query("INSERT INTO phrases(phrase, level, sublevel, language) VALUES('The girl is here.','1','1','$language')");
	$phrase_id = $db->insert_id;
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Момичето е тук.', '$phrase_id', '$language')");
	
	$insert = $db->query("INSERT INTO radio_phrases(phrase, correct_answer, wrong_answer0, wrong_answer1, wrong_answer2, wrong_answer3, level, sublevel, language) VALUES('I _ out today', 'was', 'had', 'were', 'blue', 'car', '1', '1', '$language')");
	
	$insert = $db->query("INSERT INTO image_words(word, incorrect_word0, incorrect_word1, level, sublevel, language) VALUES('rabbit','building','horse','1','1','$language')");
	$insert = $db->query("INSERT INTO image_words(word, incorrect_word0, incorrect_word1, level, sublevel, language) VALUES('horse','spider','wall','1','1','$language')");
	$insert = $db->query("INSERT INTO image_words(word, incorrect_word0, incorrect_word1, level, sublevel, language) VALUES('doll','wall','car','1','1','$language')");
	$insert = $db->query("INSERT INTO image_words(word, incorrect_word0, incorrect_word1, level, sublevel, language) VALUES('blue','car','red','1','1','$language')");
	$insert = $db->query("INSERT INTO image_words(word, incorrect_word0, incorrect_word1, level, sublevel, language) VALUES('building','car','horse','1','1','$language')");
	$insert = $db->query("INSERT INTO image_words(word, incorrect_word0, incorrect_word1,level, sublevel, language) VALUES('tree','plant','rabbit','1','1','$language')");
	
	#words
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('rabbit','1','1','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('заек','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('зайче','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('blue','1','1','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('син','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('синьо','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('doll','1','1','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('кукла','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('horse','1','1','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('кон','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('wall','1','1','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('стена','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('building','1','1','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('сграда','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('постройка','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('tree','1','1','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('дърво','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('I','1','1','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('аз','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('I was','1','1','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('аз бях','$word_id','$language')");
	
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('was','1','1','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('бях','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('беше','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('girl','1','1','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('момиче','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('the girl','1','1','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('момичето','$word_id','$language')");
	
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('today','1','1','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('днес','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('out','1','1','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('навън','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('отвън','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('is','1','1','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('е','$word_id','$language')");
	
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('she is','1','1','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('тя е','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('he is','1','1','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('той е','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('it is','1','1','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('то е','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('here','1','1','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('тук','$word_id','$language')");
	
	#english#level_1#sublevel_1#
		$insert = $db-> query("INSERT INTO checkbox_phrases(phrase, level, sublevel, language) VALUES('Green', '1', '2', '$language')");
	$phrase_id = $db->insert_id;
	$insert = $db-> query("INSERT INTO checkbox_answers(answer, phraseId, language) VALUES('Зелено', '$phrase_id', '$language')");
	print($db->error);
	$insert = $db-> query("INSERT INTO checkbox_wrong_answers(answer, phraseId, language) VALUES('Червено', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO checkbox_wrong_answers(answer, phraseId, language) VALUES('Синьо', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO checkbox_wrong_answers(answer, phraseId, language) VALUES('Бяло', '$phrase_id', '$language')");
	
	$insert = $db-> query("INSERT INTO checkbox_phrases(phrase, level, sublevel, language) VALUES('The train', '1', '2', '$language')");
	$phrase_id = $db->insert_id;
	$insert = $db-> query("INSERT INTO checkbox_answers(answer, phraseId, language) VALUES('Влакът', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO checkbox_wrong_answers(answer, phraseId, language) VALUES('Моторът', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO checkbox_wrong_answers(answer, phraseId, language) VALUES('Колелото', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO checkbox_wrong_answers(answer, phraseId, language) VALUES('Паякът', '$phrase_id', '$language')");
	
	
	$insert = $db-> query("INSERT INTO phrases(phrase, level, sublevel, language) VALUES('I cleaned the car today.','1','2','$language')");
	$phrase_id = $db->insert_id;
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Почистих колата днес.', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Днес почистих колата.', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Аз почистих колата днес.', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Аз днес почистих колата.', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Изчистих колата днес.', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Днес изчистих колата.', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Аз изчистих колата днес.', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Аз днес изчистих колата.', '$phrase_id', '$language')");
	
	$insert = $db-> query("INSERT INTO phrases(phrase, level, sublevel, language) VALUES('I went home.','1','2','$language')");
	$phrase_id = $db->insert_id;
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Аз отидох вкъщи.', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Отидох вкъщи.', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Аз си отидох вкъщи.', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Отидох си вкъщи.', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Аз си отидох у дома', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Аз отидох у дома', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Отидох у дома.', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Отидох си у дома.', '$phrase_id', '$language')");
	

	$insert = $db-> query("INSERT INTO phrases(phrase, level, sublevel, language) VALUES('He was not there, he was here.','1','2','$language')");
	$phrase_id = $db->insert_id;
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Той не беше там, а беше тук', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Той не беше там, беше тук', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Той не беше там, беше тук', '$phrase_id', '$language')");
	
	$insert = $db->query("INSERT INTO radio_phrases(phrase, correct_answer, wrong_answer0, wrong_answer1, wrong_answer2, wrong_answer3, level, sublevel, language) VALUES('I _ out today', 'was', 'had', 'were', 'blue', 'car', '1', '2', '$language')");
	
	$insert = $db->query("INSERT INTO image_words(word, incorrect_word0, incorrect_word1, level, sublevel, language) VALUES('there','here','door','1','2','$language')");
	$insert = $db->query("INSERT INTO image_words(word, incorrect_word0, incorrect_word1, level, sublevel, language) VALUES('here','train','wall','1','2','$language')");
	$insert = $db->query("INSERT INTO image_words(word, incorrect_word0, incorrect_word1, level, sublevel, language) VALUES('train','motorcycle','bike','1','2','$language')");
	$insert = $db->query("INSERT INTO image_words(word, incorrect_word0, incorrect_word1, level, sublevel, language) VALUES('green','red','white','1','2','$language')");
	$insert = $db->query("INSERT INTO image_words(word, incorrect_word0, incorrect_word1, level, sublevel, language) VALUES('the man','the boy','the worker','1','2','$language')");
	$insert = $db->query("INSERT INTO image_words(word, incorrect_word0, incorrect_word1,level, sublevel, language) VALUES('the woman','the girl','the camel','1','2','$language')");
	
	#words
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('train','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('влак','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('the train','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('влакът','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('a train','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('влак','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('един влак','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('here','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('тук','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('green','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('зелено','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('зелен','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('woman','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('жена','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('a woman','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('жена','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('една жена','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('the woman','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('жената','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('man','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('мъж','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('a man','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('мъж','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('един мъж','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('the man','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('мъжът','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('мъжа','$word_id','$language')");

	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('camel','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('камила','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('a camel','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('камила','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('една камила','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('the camel','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('камилата','$word_id','$language')");

	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('white','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('бяло','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('бял','$word_id','$language')");
	
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('I cleaned','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('аз почистих','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('аз изчистих','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('I am cleaning','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('аз чистя [в момента]','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('чистя [в момента]','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('I clean','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('аз чистя [по принцип]','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('чистя [по принцип]','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('Clean here!','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('Изчисти тук!','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('Изчистете тук!','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('I went','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('аз отидох','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('отидох','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('you went','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('ти','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('теб','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('you','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('ти','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('вие','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('вас','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('Вие','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('Вас','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('you are','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('ти си','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('вие сте','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('Вие сте','$word_id','$language')");
	
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('there','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('там','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('motorcycle','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('мотор','$word_id','$language')");
	
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('the motorcycle','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('моторът','$word_id','$language')");
	
	
	
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('it is not','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('не е','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('то не е','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('he is not','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('той не е','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('she is not','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('тя не е','$word_id','$language')");
	
	
	
	#french#level_1#sublevel_1#
	#<<
	$language = "fr";
	
	
	$insert = $db-> query("INSERT INTO checkbox_phrases(phrase, level, sublevel, language) VALUES('Bleu', '1', '1', '$language')");
	$phrase_id = $db->insert_id;
	$insert = $db-> query("INSERT INTO checkbox_answers(answer, phraseId, language) VALUES('Синьо', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO checkbox_answers(answer, phraseId, language) VALUES('Син', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO checkbox_wrong_answers(answer, phraseId, language) VALUES('Червено', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO checkbox_wrong_answers(answer, phraseId, language) VALUES('Зелено', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO checkbox_wrong_answers(answer, phraseId, language) VALUES('Бяло', '$phrase_id'), '$language'");
	
	$insert = $db-> query("INSERT INTO checkbox_phrases(phrase, level, sublevel, language) VALUES('Le bâtiment', '1', '1', '$language')");
	$phrase_id = $db->insert_id;
	$insert = $db-> query("INSERT INTO checkbox_answers(answer, phraseId, language) VALUES('Сградата', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO checkbox_wrong_answers(answer, phraseId, language) VALUES('Колата', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO checkbox_wrong_answers(answer, phraseId, language) VALUES('Влакът', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO checkbox_wrong_answers(answer, phraseId, language) VALUES('Картината', '$phrase_id', '$language')");
	
	
	$insert = $db-> query("INSERT INTO phrases(phrase, level, sublevel, language) VALUES('J''étais dehors aujourd''hui.','1','1','$language')");
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
	
	$insert = $db->query("INSERT INTO image_words(word, incorrect_word0, incorrect_word1, level, sublevel, language) VALUES('lapin','bâtiment','cheval','1','1','$language')");
	$insert = $db->query("INSERT INTO image_words(word, incorrect_word0, incorrect_word1, level, sublevel, language) VALUES('cheval','araignée','mur','1','1','$language')");
	$insert = $db->query("INSERT INTO image_words(word, incorrect_word0, incorrect_word1, level, sublevel, language) VALUES('poupée','mur','voiture','1','1','$language')");
	$insert = $db->query("INSERT INTO image_words(word, incorrect_word0, incorrect_word1, level, sublevel, language) VALUES('bleu','voiture','rouge','1','1','$language')");
	$insert = $db->query("INSERT INTO image_words(word, incorrect_word0, incorrect_word1, level, sublevel, language) VALUES('bâtiment','voiture','cheval','1','1','$language')");
	$insert = $db->query("INSERT INTO image_words(word, incorrect_word0, incorrect_word1,level, sublevel, language) VALUES('arbre','plante','lapin','1','1','$language')");
	
	#words
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('lapin','1','1','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('заек','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('зайче','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('le','1','1','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('окончание -то,-я,-ят,-та в зависимост от рода на думата в българския','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('la','1','1','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('окончание -то,-я,-ят,-та в зависимост от рода на думата в българския','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('bleu','1','1','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('син','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('синьо','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('poupée','1','1','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('кукла','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('cheval','1','1','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('кон','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('mur','1','1','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('стена','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('bâtiment','1','1','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('сграда','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('постройка','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('arbre','1','1','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('дърво','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('je','1','1','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('аз','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('j''étais','1','1','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('аз бях','$word_id','$language')");
	
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('étais','1','1','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('бях','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('беше','$word_id','$language')");
	
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('fille','1','1','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('момиче','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('la fille','1','1','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('момичето','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('le garçon','1','1','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('момчето','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('aujourd''hui','1','1','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('днес','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('dehors','1','1','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('навън','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('отвън','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('est','1','1','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('е','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('je suis','1','1','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('аз съм','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('съм','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('tu es','1','1','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('е','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('elle est','1','1','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('тя е','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('il est','1','1','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('той е','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('c''est','1','1','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('то е','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('това е','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('ici','1','1','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('тук','$word_id','$language')");
	
	#french#level_1#sublevel_2#
		$insert = $db-> query("INSERT INTO checkbox_phrases(phrase, level, sublevel, language) VALUES('Vert', '1', '2', '$language')");
	$phrase_id = $db->insert_id;
	$insert = $db-> query("INSERT INTO checkbox_answers(answer, phraseId, language) VALUES('Зелено', '$phrase_id', '$language')");
	print($db->error);
	$insert = $db-> query("INSERT INTO checkbox_wrong_answers(answer, phraseId, language) VALUES('Червено', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO checkbox_wrong_answers(answer, phraseId, language) VALUES('Синьо', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO checkbox_wrong_answers(answer, phraseId, language) VALUES('Бяло', '$phrase_id', '$language')");
	
	$insert = $db-> query("INSERT INTO checkbox_phrases(phrase, level, sublevel, language) VALUES('Le train', '1', '2', '$language')");
	$phrase_id = $db->insert_id;
	$insert = $db-> query("INSERT INTO checkbox_answers(answer, phraseId, language) VALUES('Влакът', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO checkbox_wrong_answers(answer, phraseId, language) VALUES('Моторът', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO checkbox_wrong_answers(answer, phraseId, language) VALUES('Колелото', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO checkbox_wrong_answers(answer, phraseId, language) VALUES('Паякът', '$phrase_id', '$language')");
	
	
	$insert = $db-> query("INSERT INTO phrases(phrase, level, sublevel, language) VALUES('J''ai nettoyé la voiture aujourd'hui.','1','2','$language')");
	$phrase_id = $db->insert_id;
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Почистих колата днес.', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Днес почистих колата.', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Почистих колата днес.', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Днес изчистих колата.', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Изчистих колата днес.', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Днес изчистих колата.', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Аз Изчистих колата днес.', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Аз днес изчистих колата.', '$phrase_id', '$language')");
	
	$insert = $db-> query("INSERT INTO phrases(phrase, level, sublevel, language) VALUES('Je me suis allé à la maison.','1','2','$language')");
	$phrase_id = $db->insert_id;
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Аз си отидох вкъщи.', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Отидох си вкъщи.', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Аз си отидох у дома', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Отидох си у дома.', '$phrase_id', '$language')");
	

	$insert = $db-> query("INSERT INTO phrases(phrase, level, sublevel, language) VALUES('Il n''était pas là, il était ici.','1','2','$language')");
	$phrase_id = $db->insert_id;
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Той не беше там, а беше тук', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Той не беше там, беше тук', '$phrase_id', '$language')");
	$insert = $db-> query("INSERT INTO answers(answer, phraseId, language) VALUES('Той не беше там, беше тук', '$phrase_id', '$language')");
	
	/* $insert = $db->query("INSERT INTO radio_phrases(phrase, correct_answer, wrong_answer0, wrong_answer1, wrong_answer2, wrong_answer3, level, sublevel, language) VALUES('I _ out today', 'was', 'had', 'were', 'blue', 'car', '1', '2', '$language')"); */
	
	$insert = $db->query("INSERT INTO image_words(word, incorrect_word0, incorrect_word1, level, sublevel, language) VALUES('là','ici','porte','1','2','$language')");
	$insert = $db->query("INSERT INTO image_words(word, incorrect_word0, incorrect_word1, level, sublevel, language) VALUES('ici','train','mur','1','2','$language')");
	$insert = $db->query("INSERT INTO image_words(word, incorrect_word0, incorrect_word1, level, sublevel, language) VALUES('train','moto','vélo','1','2','$language')");
	$insert = $db->query("INSERT INTO image_words(word, incorrect_word0, incorrect_word1, level, sublevel, language) VALUES('vert','rouge','blanc','1','2','$language')");
	$insert = $db->query("INSERT INTO image_words(word, incorrect_word0, incorrect_word1, level, sublevel, language) VALUES('l''homme','le garçon','le travailleur','1','2','$language')");
	$insert = $db->query("INSERT INTO image_words(word, incorrect_word0, incorrect_word1,level, sublevel, language) VALUES('la femme','la fille','le chameau','1','2','$language')");
	
	#words
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('train','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('влак','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('le train','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('влакът','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('un train','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('влак','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('един влак','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('ici','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('тук','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('vert','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('зелено','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('зелен','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('femme','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('жена','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('une femme','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('жена','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('една жена','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('la femme','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('жената','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('homme','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('мъж','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('un homme','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('мъж','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('един мъж','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('l''homme','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('мъжът','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('мъжа','$word_id','$language')");

	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('chameau','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('камила','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('un chameau','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('камила','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('една камила','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('le chameau','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('камилата','$word_id','$language')");

	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('vélo','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('колело','$word_id','$language')");
	
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('blanc','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('бяло','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('бял','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('j''ai nettoyé','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('аз почистих','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('изчистих','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('je nettoie','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('аз чистя','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('чистя','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('Nettoie ici!','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('Изчисти тук!','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('je suis allé','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('аз отидох','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('отидох','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('tu as allé','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('ти отиде','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('je','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('аз','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('tu','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('ти','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('vous','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('вие','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('вас','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('Вие','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('Вас','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('tu es','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('ти си','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('il est, elle est','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('той е, тя е','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('nous sommes','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('ние сме','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('vous êtes','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('вие сте','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('Вие сте','$word_id','$language')");
	
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('là','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('там','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('moto','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('мотор','$word_id','$language')");
	
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('le moto','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('моторът','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('je me','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('аз се','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('аз си','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('ce n''est pas','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('не е','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('то не е','$word_id','$language')");
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('това не е','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('il n''est pas','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('той не е','$word_id','$language')");
	
	$insert = $db->query("INSERT INTO words(word, level, sublevel, language) VALUES('elle n''est pas','1','2','$language')");
	$word_id = $db->insert_id;
	$insert = $db->query("INSERT INTO words_translations(word, wordId, from_language) VALUES('тя не е','$word_id','$language')");
	#>>	
	
	
	
	
	$password = "vasko3";
	$password_hash = hash("sha512", $password . DB_SALT);
	$db->query("INSERT INTO users (username, email, password, language, points, score, days_played, level, sublevel) VALUES ('Vasko2', 'vasko@mail.com', '$password_hash', 'en', 0, 0, 0, 1, 3);");
	$db->query("INSERT INTO language(name, userID, score, level, sublevel) VALUES ('en', 1, 20, 2, 4)");
	$db->query("INSERT INTO language(name, userID, score, level, sublevel) VALUES ('fr', 1, 0, 1, 3)");
	
	$password = "adminadmin";
	$password_hash = hash("sha512", $password . DB_SALT);
	
	$db->query("INSERT INTO users (username, email, password, language, points, score, days_played, level, sublevel) VALUES ('admin', 'admin@mail.com', '$password_hash', 'en', 0, 0, 0, 5, 5);");
	$db->query("INSERT INTO language(name, userID, score, level, sublevel) VALUES ('en', 2, 0, 5, 5)");
	$db->query("INSERT INTO language(name, userID, score, level, sublevel) VALUES ('fr', 2, 0, 5, 5)");
	
	
	$db->query("INSERT INTO levelContent(text, level) VALUES ('В тези уроци са представени начални думи. Можете да отидете в думи по-горе и да прочетете всичките за даденото ниво и подниво и да се пробвате след това в упражненията тук.', 1");
	/* $db->query("INSERT INTO comments () VALUES ('Vasko2', 'vasko@mail.com',	'$password_hash', 'en', 0, 0, 0, 1, 1);"); */
	
	$db->close();
	
	/*$insert = $db-> query("INSERT INTO phrases(phrase, level, sublevel) VALUES('Rabbit', '1', '1')");
	$insert = $db-> query("INSERT INTO phrases(phrase, level, sublevel) VALUES('Blue', '1', '1')");*/
	print("Initialized successfully.");
	
}

if(isset($_POST['password']))
{
	$password = trim($_POST['password']);
	if(strlen($password) > 4 )
	{
		dbInit($password);
	}
	else
	{
		print("Password is too short, must be at least 5 characters.");
	}
}

	
	
	



?>
</HTML>