﻿"use strict";

var state={};
var globalLanguage = "en"; //by default 'en', but changed to the user specific one

var usernameField = document.getElementById("username");
var userLanguageField = document.getElementById("userLanguage");
var userLevelField = document.getElementById("userLevel");
var userConsecutiveDaysField = document.getElementById("userConsecutiveDays");
state.playedToday;
state.cameFromExercise;
	var div = document.querySelector('body>main');

/*  var calculate = function() {
    var deferred = new $.Deferred();
    var test_data = "Hello World!"

    // SIMULATE SOME TIME TO PROCESS FUNCTION
    setTimeout(function () {
        // This line is what resolves the deferred object
        // and it triggers the .done to execute
        deferred.resolve(test_data);
    }, 2000) // AFTER 2 SECONDS IT WILL RETURN
    
    return deferred.promise();
}

calculate().done(function (result) {
    alert(result)
}) */
	
window.onbeforeunload = function() {
return "Data will be lost if you leave the page, are you sure?";
};

function getUserLanguage(callback)
{
	
		sendCommand(["get-user-language", state.username], 
					function (r){
						console.log(r);
						globalLanguage = r;
						console.log(globalLanguage);
						if(globalLanguage != "en" && globalLanguage != "fr")
						{
							globalLanguage = globalLanguage.replace(/['"]+/g, '');
							globalLanguage = globalLanguage.replace(/\s/g,'');
							globalLanguage = globalLanguage.toString();
							console.log(globalLanguage);
						}
						console.log(globalLanguage);
						var userLanguageField = document.getElementById("userLanguage");
						state.languageName = getLanguageName(globalLanguage);
						userLanguageField.innerText = "Език: " + state.languageName;
						callback ();
						//console.log(state.languageName);
					}
					
					
		);
	
	//console.log(globalLanguage);
	//return globalLanguage;
	//globalLanguage = "fr";
}

function getLanguageName(language)
{
	console.log(language);
	if(language == "en")
	{
		return "Английски";
	}
	else if(language == "fr")
	{
		return "Френски";
	}
	else
	{
		return false;
	}
}

function playAudio(phrase)
{
	const msg = new SpeechSynthesisUtterance();
	let voices = [];
	const voicesDropdown = document.querySelector('[name="voice"]');
	const options = document.querySelectorAll('[type="range"], [name="text"]');
	const speakButton = document.querySelector('#speak');
	const stopButton = document.querySelector('#stop');
	msg.text = phrase;
	function populateVoices() {
		voices = this.getVoices();
		voicesDropdown.innerHTML = voices
			.filter(voice => voice.lang.includes(globalLanguage))
			.map(voice => `<option value="${voice.name}">${voice.name} (${voice.lang})</option>`)
			.join('');
			
		if(globalLanguage == "fr")
		{
			msg.voice = voices.find(voice => voice.name === 'Google français');
		}
		else
		{
			
		}
		toggle();
	}
	function setVoice() {
		console.log(this.value);
		msg.voice = voices.find(voice => voice.name === this.value);
		if(globalLanguage == "fr")
		{
			msg.voice = voices.find(voice => voice.name === 'Google français');
		}
		else
		{
			
		}
		toggle();
	}
	function toggle(startOver = true) {
		speechSynthesis.cancel();
		if (startOver) 
		{
		  speechSynthesis.speak(msg);
		}
	}
	function setOption() {
		console.log(this.name, this.value);
		msg[this.name] = this.value;
		toggle();
	}
	speechSynthesis.addEventListener('voiceschanged', populateVoices);
	voicesDropdown.addEventListener('change', setVoice);
	options.forEach(option => option.addEventListener('change', setOption));
	speakButton.addEventListener('click', toggle);
	//stopButton.addEventListener('click', () => toggle(false));
}

function sendCommand(command, callback, json=true)
{
	console.log(command);
	var request = new XMLHttpRequest();
	request.onload = function(e){
		if(callback)
		{
			callback(e.target.response);
		}
		else
		{
			console.log(e.target.response);
		}
	}

	request.open("POST", "/api.php", true);
	if (json)
	{
		request.responseType = "json";
	}
	request.send(JSON.stringify(command));
}

/* function incrementScore()
{
	sendCommand(["increment-score"]);
} */
function clearButtonFinish()
{
	var otherDiv = document.getElementById("otherDiv");
	if (typeof(otherDiv) != 'undefined' && otherDiv != null)
	{
		document.body.removeChild(otherDiv);
	}
	else
	{
		
	}
}
function switchLanguageEn()//state
{
	clearButtonFinish();
	globalLanguage = "en";
	console.log(state.username);
	document.getElementById("languageDropdown").className = "dropdown-content hidden";
	sendCommand(["change-language", globalLanguage, state.username], 
					function (r){
						console.log(r);
						if(r)
						{
							getUserInfo(function(){
								console.log(state.userInfo)
								clearDiv(div);
								showMainPage(div);
							});
							console.log("Language changed");
						}
						else
						{
							console.log("Not changed");
						}
								
						state.languageName = getLanguageName(globalLanguage);
						var userLanguageField = document.getElementById("userLanguage");
						userLanguageField.innerText = "Eзик: " + state.languageName;
						setCookie("language", globalLanguage, 365);
						var div = document.querySelector('body>main');
						clearDiv(div);
						showMainPage(div);
					});
					
	
					
}



function switchLanguageFr()
{
	clearButtonFinish();
	globalLanguage = "fr";
	console.log(state.username);
	console.log(globalLanguage);
	document.getElementById("languageDropdown").className = "dropdown-content hidden";

	sendCommand(["change-language", globalLanguage, state.username], 
					function (r){
						console.log(r);
						if(r)
						{
							getUserInfo(function(){
								console.log(state.userInfo)
								clearDiv(div);
								showMainPage(div);
							});
							console.log("Language changed");
						}
						else
						{
							console.log("Not changed");
						}
						
						state.languageName = getLanguageName(globalLanguage);
						var userLanguageField = document.getElementById("userLanguage");
						userLanguageField.innerText = "Eзик: " + state.languageName;
						setCookie("language", globalLanguage, 365);
						var div = document.querySelector('body>main');
						clearDiv(div);
						showMainPage(div);
						
					});
	
}

/* function makeRadioExercise(div)
{
	var p = document.createElement("p");
	var span = document.createElement("span");
} */
function rabbit(){

	var div = document.querySelector('body>main');
	var button = document.getElementById("butonche");
	var div2 = document.querySelector('body');
	div2.appendChild(button);
	console.log("vasiliy2");
	console.log(state.wrongExercises.length);
	button.onclick = function ()
	{
		if(state.counter == 0 && !state.doWrongExercise)
		{
			console.log("continuing");
			clearDiv(div);
			startExercises(div);
		}
		else if(!state.doWrongExercise)
		{
			clearDiv(div);
			showImages(div);
		}
		else if(state.wrongExercises.length > 0)
		{
			clearDiv(div);
			startWrongExercises(div);
		}
	}
}
function showButtonContinue()
{
	state.button.style.visibility = "visible";
}

function makeListeningExercise(div)
{
	showButtonContinue();
	console.log(state.wrongExercises);
	var p = document.createElement("p");
	div.appendChild(p);
	var phrase;
	if(!state.doWrongExercise)
	{
		console.log(state.exercises.phrases[state.listeningCounter].phrase);
		phrase = state.exercises.phrases[state.listeningCounter].phrase;
	}
	else
	{
		console.log(state.phraseCounter);
		state.listeningCounter = state.wrongExercises.shift().index;
		phrase = state.exercises.phrases[state.listeningCounter].phrase;
	}
	//var phraseName = document.createElement("div");
	var t = document.createTextNode("Напишете каквото чуете.");
	p.appendChild(t);
	//do the cycle for each word to show translation db->words<>
	//var interval = "";
		var select = document.createElement("select");
		div.appendChild(select);
		select.name = "voice";
		select.id = "voices";
		/* var option = document.createElement("option");
		select.appendChild(option);
		option.innerText = "Select";
		option.value = ""; */
		/*var input = document.createElement("input");
		div.appendChild(input);
		input.name = "rate";
		input.type = "range";
		input.min = "0";
		input.max = "2";
		input.value = "1"
		input.step = "0.1";
		var inputTwo = document.createElement("input");
		div.appendChild(inputTwo);
		inputTwo.name = "pitch";
		inputTwo.type = "range";
		inputTwo.min = "0";
		inputTwo.max = "2";
		inputTwo.step = "0.1"; */
		/* var stopButton = document.createElement("button");
		div.appendChild(stopButton);
		stopButton.id = "stop"; */
		var speakButton = document.createElement("button");
		div.appendChild(speakButton);
		speakButton.id = "speak";
		speakButton.innerText = "Пусни изречението";
		speakButton.className = "btn btn-secondary";
		speakButton.onclick = playAudio(phrase);
	
	
	var phraseWords = phrase.split(" ");
	for(var wordCount = 0; wordCount < phraseWords.length; wordCount++)
	{
		var showPhrase = document.createElement("span");
		var space = document.createElement("span");
		space.innerText = ' ';
		showPhrase.innerText = `${phraseWords[wordCount]}`;
		//div.appendChild(Phrase);
		if(wordCount + 1 != phraseWords.length)
		{
			//div.appendChild(space);
		}
		//showPhrase.innerText
		//p.appendChild(showPhrase)
	}
	
	
	//var phraseAnswers = state.exercises.phrases[state.phraseCounter].answers;
	var phraseAnswers = [];
	//console.log(phraseAnswers);
	phraseAnswers.push(phrase);
	console.log(phraseAnswers);
	var phraseInput = document.createElement("textarea");
	div.appendChild(phraseInput);
	//var listenButton = document.createElement("button");
	//div.appendChild(listenButton);
	//listenButton.innerText="Пусни записа";
	//listenButton.onclick = playAudio(phrase);
	/* var buttonContinue = document.createElement("button");
	buttonContinue.innerText="Пропусни";
	buttonContinue.disabled = true;
	buttonContinue.onclick = function(){
		console.log("halelluyas234")
		console.log(state.wrongExercises.length);
		if(state.counter == 0 && !state.doWrongExercise)
		{
			console.log("continuing");
			clearDiv(div);
			startExercises(div);
		}
		else if(!state.doWrongExercise)
		{
			clearDiv(div);
			showImages(div);
		}
		else if(state.wrongExercises.length > 0)
		{
			clearDiv(div);
			startWrongExercises(div);
		}
	} */
	
	
	var form = document.createElement("form");
	var button = document.createElement("button");
	button.innerText= "Провери";
	button.className = "btn btn-secondary";
	button.type = "submit";
	button.disabled = true;
	form.appendChild(button);
	//form.appendChild(buttonContinue);
	div.appendChild(form);
	console.log(typeof phraseInput.value);
	phraseInput.addEventListener(
		'input',
		function(e){
			if(phraseInput.value.length < 1)
			{
				button.disabled = true;
			}
			else
			{
				button.disabled = false;
			}
		}
	)
	var answer;
	var previousCounter = state.listeningCounter;
	var pContainer = document.createElement("p");
	var text = document.createElement("text");
	var textContainer = document.createElement("span");
	div.appendChild(pContainer);
	pContainer.appendChild(textContainer);
	textContainer.appendChild(text);
	form.onsubmit = function (event) {
		event.preventDefault();
		phraseInput.disabled = true;
		sendCommand(["check-phrase", 
					phraseInput.value, 
					phraseAnswers, 
					phrase], 
					function (r){
						console.log(r);
						if(r.result)
						{
							console.log("-16");
							answer = "correct";
							text.innerText = "Правилно!";
						}
						else
						{
							state.wrongExercises.push({"index" : previousCounter, "phrase" : phrase, "exercise" : makeListeningExercise});
							console.log(state.wrongExercises);
							text.innerText = "Неправилно! Отговорът е:\n" + phrase;
						}
						//buttonContinue.disabled = false;
						button.disabled = true;
						state.button.disabled = false;
					});
	}
	state.listeningCounter++;
}

function makePhraseExercise(div)
{ 
	showButtonContinue();
	var p = document.createElement("p");
	div.appendChild(p);
	console.log(state.wrongExercises);
	console.log(state.phraseCounter);
	var phrase;
	console.log(state.exercises);
	if(!state.doWrongExercise)
	{
		console.log(state.exercises.phrases[state.phraseCounter].phrase);
		phrase = state.exercises.phrases[state.phraseCounter].phrase;
	}
	else
	{
		console.log(state.phraseCounter);
		state.phraseCounter = state.wrongExercises.shift().index;
		phrase = state.exercises.phrases[state.phraseCounter].phrase;
	}
	console.log(phrase);
	//var phraseName = document.createElement("div");
	var t = document.createTextNode("Какъв е преводът на изречението?");
	p.appendChild(t);
	//do the cycle for each word to show translation db->words<>
	//var interval = "";
	
	
	var phraseWords = phrase.split(" ");
	for(var wordCount = 0; wordCount < phraseWords.length; wordCount++)
	{
		var showPhrase = document.createElement("span");
		var space = document.createElement("span");
		space.innerText = ' ';
		showPhrase.innerText = `${phraseWords[wordCount]}`;
		div.appendChild(showPhrase);
		if(wordCount + 1 != phraseWords.length)
		{
			div.appendChild(space);
		}
		//showPhrase.innerText
		//p.appendChild(showPhrase)
	}
	
	
	//var phraseAnswers = state.exercises.phrases[state.phraseCounter].answers;
	var phraseAnswers = [];
	//console.log(phraseAnswers);
	for(var answerCount = 0; answerCount < state.exercises.phrases[state.phraseCounter].answers.length; answerCount++)
	{
		phraseAnswers.push(state.exercises.phrases[state.phraseCounter].answers[answerCount].answer);
	}
	console.log(phraseAnswers);
	var phraseInput = document.createElement("textarea");
	phraseInput.phrase = "";
	div.appendChild(phraseInput);
	
	
	
	var form = document.createElement("form");
	/* var buttonContinue = document.createElement("button");
	buttonContinue.innerText= "Пропусни";
	buttonContinue.className = "btn btn-secondary";
	buttonContinue.style.visibility = 'visible';
	buttonContinue.disabled = true;
	
	buttonContinue.onclick = function()
	{
		console.log("halelluyas")
		
		console.log(state.wrongExercises.length);
		if(state.counter == 0 && !state.doWrongExercise)
		{
			console.log("continuing");
			clearDiv(div);
			startExercises(div);
		}
		else if(!state.doWrongExercise)
		{
			clearDiv(div);
			showImages(div);
		}
		else if(state.wrongExercises.length > 0)
		{
			clearDiv(div);
			startWrongExercises(div);
		}
	} */
	
	var button = document.createElement("button");
	button.innerText= "Провери";
	button.className = "btn btn-secondary";
	button.type = "submit";
	form.appendChild(button);
	//form.appendChild(buttonContinue);
	form.type = "button";
	div.appendChild(form);
	phraseInput.addEventListener(
		'input',
		function(e){
			if(phraseInput.value.length < 1)
			{
				button.disabled = true;
			}
			else
			{
				button.disabled = false;
			}
		}
	)
	
	var previousCounter = state.phraseCounter;
	var text = document.createElement("text");
	var textContainer = document.createElement("span");
	var pContainer = document.createElement("p");
	div.appendChild(pContainer);
	pContainer.appendChild(textContainer);
	textContainer.appendChild(text);
	form.onsubmit = function (event) {
		event.preventDefault();
		phraseInput.disabled = true;
		sendCommand(
			["check-phrase", phraseInput.value, phraseAnswers, phrase], 
			function (r){
				console.log(r);
				if(r.result)
				{
					console.log(phraseInput.value);
					console.log(phraseAnswers);
					console.log(phrase);
					text.innerText = "Правилен отговор!";
				}
				else
				{
					state.phrasesWrong.push(previousCounter);
					state.wrongExercises.push({"index" : previousCounter, "phrase" : phrase, "exercise" : makePhraseExercise});
					console.log(state.wrongExercises);
					text.innerText = "Неправилно! Възможен верен отговор е:\n" + phraseAnswers[0];
				}
				button.disabled = true;
			//	buttonContinue.disabled = false;
				state.button.disabled = false;
			}
		);
		//buttonContinue.onclick = continue
		////console.log(state.exercises.phrases[state.phraseCounter].id);
		var buttonComments = document.createElement("button");
		buttonComments.className = "btn btn-secondary";
		var buttonCommentsContainer = document.createElement("div");
		div.appendChild(buttonCommentsContainer);
		buttonCommentsContainer.appendChild(buttonComments);
		buttonComments.innerText = "Коментари";
		buttonComments.onclick = function()
		{
			getPhraseComments(state.exercises.phrases[previousCounter].id, div, state);
		}
	}
	state.phraseCounter++;
}



function getPhrase(div)
{
	if(state.phrases.length == 0)
	{
		console.log(state.phrases.length);
		sendCommand(
			["get-words-checkbox"],
			function (r){
				state.phrases = r;
				console.log(r);
				console.log(state.phrases);
				var phrase = state.phrases.pop();
				console.log(phrase);
				//clearDiv(div);
				makeCheckboxes(phrase, div, state);
			}
		);
	}
	else
	{
		var phrase = state.phrases.pop();
		makeCheckboxes(phrase, div);
	}
}

function onSubmit()
{
	alert(0);
}

function showImages(div)
{
	state.myCounter = 0;

	var container = div;
	var div = document.createElement("div");
	div.className = "button-container";
	var itemContainer = document.createElement("div");
	var p = document.createElement("p");
	p.id = "imageContainer";
	itemContainer.appendChild(p);
	
	var unlock = 0;
	var words = [];
	var incorrectWords = [];
	var emptyJoin = [];
	var allWords = [];
	var afterWord = [];
	var imageCount = state.imageCount;
	var counter = state.counter;
	//for(var i = 0; i < state.exercises.images.length; i++)
	//{
	
	var imageIndex;
	console.log(state.wrongExercises);
	if(state.doWrongExercise)
	{
		console.log("here");
		imageIndex = state.wrongExercises.shift().index;
	}
	else
	{
	    imageIndex = imageCount - counter;
	}

	console.log(imageIndex);
	var word = state.exercises.images[imageIndex].word;
	var translation;
	console.log(state.exercises.words);
	for(var j = 0; j < state.exercises.words.length; j++)
	{
		if(state.exercises.words[j].word == word)
		{
			console.log("vlezeee");
			translation = state.exercises.words[j].translation[0].word;
			console.log(translation);
		}
		else
		{
			console.log("da ama ne");
		}
	}
	
	var spanContainer = document.createElement("span");
	spanContainer.style = "display: inline; text-align: center";
	var translationQueestion = document.createElement("text");
	translationQueestion.innerText = "Изберете изображението за думата - "
	var translationWord = document.createElement("text");
	translationWord.innerText = "'" + translation + "'" + " .";
	spanContainer.appendChild(translationQueestion);
	spanContainer.appendChild(translationWord);
	p.appendChild(spanContainer);
	
	
	var ul = document.createElement("ul");
	itemContainer.appendChild(ul);
	container.appendChild(itemContainer);
	itemContainer.id = "itemContainer";
	//document.body.appendChild(container);
	console.log(state.exercises.images[0].word);
	console.log(state.exercises.images[0].incorrect_word0);
	console.log(state.exercises.images[0].incorrect_word1);
	
	
	
	
	
	
	
	words.push(word);
	var incorrectWordOne = state.exercises.images[imageIndex].incorrect_word0;
	var incorrectWordTwo = state.exercises.images[imageIndex].incorrect_word1;
	incorrectWords.push(incorrectWordOne, incorrectWordTwo);
	allWords = emptyJoin.concat(words, incorrectWords);
	
	
	//p.innerText = translation;
	//}
	//console.log(words);
	//console.log(incorrectWords);
	//console.log(allWords);
	//console.log(imageExercises);
	//var words = ["tree", "rabbit", "building", "horse", "blue", "doll"];
	var inps = [];
	var imgs = [];
	shuffle(allWords);
	var correctWord = allWords.indexOf(word); 
	console.log(allWords[correctWord]);

	for(var i = 0; i < 3; i++)
	{
		var li = document.createElement("li");
		var label = document.createElement("label");
		li.appendChild(label);
		ul.appendChild(li);
		var img = document.createElement("img");
		li.appendChild(img);
		imgs.push(img);
		console.log(imgs);
		var inp = document.createElement("input");
		li.appendChild(inp);
		inps.push(inp);
		inp.type = "radio";
		inp.name = "choice";
		inp.value = `${allWords[i]}`;
		console.log (i + allWords[i]);
		img._name = words[i];
		img.src = `/../images/${allWords[i]}.jpg`;
		img.height = 120;
		img.width = 120;
		img.id = `img${i}`;
		//img.style = "float: centre";
		var span = document.createElement("span");
		span.id = `spanimage${i}`;
		span.innerText = `${allWords[i]}`;
		//label.text = `${allWords[i]}`;
		//p.style = "display: inline-block;";
		label.appendChild(img);
		label.appendChild(inp);
		label.appendChild(span);
		
		label.addEventListener (
			'click',
			function buttonContinuation(e)
			{
				if(state.myCounter <= 1)
				{
					button.disabled = false;
					state.myCounter = 2;
					console.log(state.myCounter);
				}
				if(unlock == 1)
				{
					label.removeEventListener('click', buttonContinuation);
				}
			}
		);
		
	}
	
	var button = document.createElement("button");
	button.id = "checkButton";
	button.className = "btn btn-secondary";
	button.disabled = true;
	
	var buttonC = document.createElement("button");
	buttonC.textContent="Продължи";
	buttonC.id = "continueButton";
	buttonC.className = "btn btn-secondary";
	buttonC.disabled = true;
	
	itemContainer.appendChild(div);
	div.appendChild(button);
	button.textContent = "Провери";
	button.onclick = function() 
	{
		var newLine = document.createElement("text");
		newLine.innerText = '\n';
		var answerText = document.createElement("text");
		var answerTextContainer = document.createElement("p");
		if(inps[correctWord].checked)
		{
			//incrementScore();
			unlock = 1;
			console.log("correct");
			answerText.innerText = "Правилно!";
		}
		else
		{
			console.log("-15");
			state.wrongExercises.push({"index" : imageIndex, "exercise" : showImages});
			console.log(state.wrongExercises);
			answerText.innerText = "Неправилно. Верният отговор е " + "'" + word + "'";
		}
		
		p.appendChild(newLine);
		p.appendChild(answerText);
		//label.removeEventListener('click', buttonContinuation());
		button.disabled = true;
		buttonC.disabled = false;
	}
	div.appendChild(buttonC);
	if(!state.doWrongExercise)
	{
		state.counter--;
	}
	if(state.wrongExercises != 0)
	{
		console.log("vleze2");
		var otherDiv = document.getElementById("otherDiv");
		if (typeof(otherDiv) != 'undefined' && otherDiv != null)
		{
			var but = document.getElementById("theContinueButton");
			console.log("vlezepak, ama tupiq button ne se smenq");
			but.style.visibility = "hidden";
		}
	}
	else
	{
		clearButtonFinish();
	}
	//console.log(state.counter);
	buttonC.onclick = function ()
	{
		console.log(state.wrongExercises.length);
		
		if(state.counter == 0 && !state.doWrongExercise)
		{
			console.log("continuing");
			clearDiv(container);
			startExercises(container);
		}
		else if(!state.doWrongExercise)
		{
			clearDiv(container);
			showImages(container);
		}
		else if(state.wrongExercises.length > 0)
		{
			clearDiv(container);
			startWrongExercises(container);
		}
		else
		{
			clearButtonFinish();
			buttonC.innerText = "Finish";
			buttonC.style="bottom: 30%, right: 10%";
			
			buttonC.addEventListener (
				'click',
				function(e)
				{
					var level = state.level;
					var sublevel = state.sublevel;
					sendCommand(["update-user-score-and-level", state.username, 10, globalLanguage, level, sublevel],
						function(r)
						{
							console.log(r);
							state.userAttributes = r;
						},false
					);
					clearButtonFinish();
					buttonC.disabled = false;
					var cookieExists = getCookie("playedToday");
					if(!cookieExists)
					{
						createCookiePlayed("playedToday", 1);
						state.playedToday = true;
					}
					clearDiv(container);
					console.log(level);
					clearButtonFinish();
					state.cameFromExercise = true;
					alert("Прибавихте резултат към текущия език!");
					showLevel(container, level);
					//checked = true;
					/*var p = document.createElement("p");
					p.className = "photo-caption";
					p.textContent = e.target._name;
					//console.log(e.target.parentElement);
					e.target.parentElement.appendChild(p); */
					//console.log(e.target.src);
				}
			);
		}
	}
}

function makeCheckboxes(phraseIndex, div)
{
	//clearDiv(div);
	//console.log(phrase.phrase);
	console.log(state.wrongExercises);
	console.log(phraseIndex);
	/* if(doWrongExercise)
	{
		phraseIndex = state.exercise.wrongExercises
	}
	else
	{
		
	} */
	showButtonContinue();
	var phrase = state.exercises.checkbox_phrases[phraseIndex];
	console.log(phrase);
	var correct_answers = phrase.answers;
	var incorrect_answers = phrase.wrong_answers;
	var phraseName = phrase.phrase;
	var mixed_answers = correct_answers.concat(incorrect_answers);
	shuffle(mixed_answers);
	var textPhrase = document.createElement("text");
	textPhrase.innerText = phraseName;
	textPhrase.style = "text-decoration: underline;"
	console.log(mixed_answers);
	var p = document.createElement("p");
	var t = document.createTextNode("Какви са възможните преводи на думата - ");
	//var questionMark = 
	//p.innerHTML = phraseName;
	p.appendChild(t);
	p.appendChild(textPhrase);
	//t.innerText = phraseName;
	//print(phraseName);
	var q = document.createTextNode(" ? ");
	p.appendChild(q);
	var span = document.createElement("span");
	var mark = document.createElement("text");
	mark.innerText = "Отбележете ";
	var all = document.createElement("text");
	all.innerText = "ВСИЧКИ";
	all.style = "text-decoration: underline; font-style: italic;";
	var possibleAnswers = document.createElement("text");
	possibleAnswers.innerText = " възможни преводи!";
	span.appendChild(mark);
	span.appendChild(all);
	span.appendChild(possibleAnswers);
	p.appendChild(span);
	
	var form = document.createElement("form");
	div.appendChild(p);
	
	div.appendChild(form);
	var ul = document.createElement("ul");
	ul.id = "checkbox-ul";
	form.appendChild(ul);
	var checkboxes = [];
	for(var i = 0; i < mixed_answers.length; i++)
	{
		var li = document.createElement("li");
		ul.appendChild(li);
		var label = document.createElement("label");
		li.appendChild(label);
		var checkbox = document.createElement("input");
		checkbox.type = "checkbox";
		checkbox.onchange = function(event){
			for(var i = 0; i < checkboxes.length; i++)
			{
				if(checkboxes[i].checked)
				{
					button.disabled = false;
					return;
				}
			}
			button.disabled = true;
		}
		label.appendChild(checkbox);
		var span = document.createElement("span");
		span.innerText = `${mixed_answers[i].answer}`;
		label.appendChild(span);
		//label.appendChild(document.createTextNode(mixed_answers[i].answer));
		checkboxes.push(checkbox);		
	}
	/* var buttonC = document.createElement("button");
	
	buttonC.disabled = true;
	buttonC.innerText = "Пропусни";
	buttonC.className = "btn btn-secondary"; */
	//form.appendChild(buttonC);
	var button = document.createElement("button");
	
	//button.id = "";
	button.disabled = true;
	button.type = "submit";
	button.innerText = "Провери";
	button.className = "btn btn-secondary";
	form.appendChild(button);
	
	form.onsubmit = function (event) {
		event.preventDefault();
		var correct = true;
		var text = document.createElement("text");
		var rightChecks = 0;
		var wrongChecks = 0;
		var textContainer = document.createElement("span"); 
		for(var i = 0; i < checkboxes.length; i++)
		{
			checkboxes[i].disabled = true;
		}
		for(var i = 0; i < checkboxes.length; i++)
		{
			if(correct_answers.indexOf(mixed_answers[i])!=-1)
			{
				if(checkboxes[i].checked)
				{
					//correct answer was checked
					checkboxes[i].parentNode.style = "background: lightgreen;";
					console.log(checkboxes[i]);
					rightChecks++;
				}
				else
				{
					//correct answer was not checked 
					checkboxes[i].parentNode.style = "background: pink;";
					correct = false;
				}
			}
			else
			{
				if(checkboxes[i].checked)
				{
					//incorrect answer was checked
					//checkboxes[i].parentNode.style = "background: red;";
					wrongChecks++;
					correct = false;
				}
				else
				{
					//incorrect answer was not checked
					//checkboxes[i].parentNode.style = "background: darkgreen;";
				}
			}
			
			if((rightChecks == 2 || rightChecks == 1) && correct == true)
			{
				text.innerText = "Правилно!";
			}
			else if(rightChecks == 1 && correct == false && wrongChecks == 0)
			{
				text.innerText = "Не избрахте всички възможни отговори. В червено е показан пропуснатият.";
			}
			else if(rightChecks == 1 && correct == false && wrongChecks > 0)
			{
				text.innerText = "Неправилно. Избрахте верен и поне един грешен отговор.";
			}
			else
			{
				text.innerText = "Неправилно!";
			}
			
			var answerContainer = document.createElement("p");
			div.appendChild(answerContainer);
			answerContainer.appendChild(textContainer);
			textContainer.appendChild(text);
			
			
			//console.log(checkboxes[i].checked);
			button.disabled = true;
			//buttonC.disabled = false;
			state.button.disabled = false;
		}
		
		console.log("jere");
		console.log(phraseIndex);
		if(!correct)
		{	
			state.wrongExercises.push({"index" : phraseIndex, "exercise" : checkboxesFour});
			console.log(state.wrongExercises);
		}
		/* buttonC.onclick = function() {
			console.log(state.wrongExercises.length);
			if(state.counter == 0 && !state.doWrongExercise)
			{
				console.log("continuing");
				clearDiv(div);
				startExercises(div);
			}
			else if(!state.doWrongExercise)
			{
				clearDiv(div);
				showImages(div);
			}
			else if(state.wrongExercises.length > 0)
			{
				clearDiv(div);
				startWrongExercises(div);
			}
		} */
	}
}


function shuffle(array)
{
	var counter = array.length;
	
	while(counter > 0)
	{
		var index = Math.floor(Math.random() * counter);
		counter--;
		var temp = array[counter];
		array[counter] = array[index];
		array[index] = temp;
	}
	
	return array;
}

/* function addPhrasesMode(div)
{
	var form = document.createElement("form");
	var phraseInput = document.createElement("textarea");
	phraseInput.id = "phraseInput";
	var answerInputs = [document.createElement("input")];
	var button = document.createElement("button");
	var levelInput = document.createElement("input");
	var subLevelInput = document.createElement("input");
	var divB = document.createElement("div");
	var formB = document.createElement("form");
	var phraseLabel = document.createElement("label");
	phraseLabel.setAttribute("for", phraseInput);
	phraseLabel.innerHTML = "Phrase: ";
	document.body.appendChild(divB);
	formB.appendChild(levelInput);
	formB.appendChild(subLevelInput);
	divB.appendChild(formB);
	
	button.type = "submit";
	button.innerText = "check";
	button.className = "btn btn-secondary";
	div.appendChild(form);
	form.appendChild(button);
	form.appendChild(phraseLabel);
	form.appendChild(phraseInput);
	form.appendChild(answerInputs[0]);
	phraseInput.required = true;
	levelInput.required = true;
	subLevelInput.required = true;
	
	function addAnswerInput(event){
		var newAnswerInput = document.createElement("textarea");
		form.appendChild(newAnswerInput);
		event.target.oninput = undefined;
		newAnswerInput.oninput = addAnswerInput;
		answerInputs.push(newAnswerInput);
	}
	
	answerInputs[0].oninput = addAnswerInput;
	
	form.onsubmit = function(event){
		event.preventDefault();
		var answers = [];
		for(var i = 0; i < answerInputs.length; i++)
		{
			if(answerInputs[i].value)
			{
				answers.push(answerInputs[i].value);
			}
		}
		if(answers.length == 0) return;
		console.log(answers);
		sendCommand(["add-phrase", phraseInput.value, levelInput.value, subLevelInput.value].concat(answers), undefined, false);
	}
} */


function checkboxesFour(div)
{
	var phraseIndex;

	if(state.doWrongExercise)
	{
		phraseIndex = state.wrongExercises.shift().index;
	}
	else
	{
		phraseIndex = state.checkboxCounter;
		state.checkboxCounter++;
	}

	makeCheckboxes(phraseIndex, div);
}

function validateEmail(email) {
	var re = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;

	return re.test(email.value);
}

function registrationForm(div)
{
	
	var button = document.getElementById("log");
	button.addEventListener('click', function(){
		clearDiv(div);
		showLogin(div);
	})
	var form = document.createElement("form");
	div.appendChild(form);
	var input = document.createElement("input");
	var registerForm = document.createElement("form");
	var username = document.createElement("input");
	var email = document.createElement("input");
	var password = document.createElement("input");
	var button = document.createElement("button");
	username.placeholder = "Desired username";
	email.placeholder = "Email";
	password.placeholder = "Password";
	button.type = "submit";
	button.innerText = "Register";
	button.className = "btn btn-primary";
	password.type = "password";
	div.appendChild(registerForm);
	registerForm.appendChild(username);
	registerForm.appendChild(email);
	registerForm.appendChild(password);
	registerForm.appendChild(button);
	username.required = true;
	password.required = true;
	var correctEmail;
	registerForm.onsubmit = function (event) {
		event.preventDefault();
		correctEmail = validateEmail(email);
		console.log(correctEmail);
		if(!correctEmail)// && email !== document.activeElement && 
		{
			alert("You have not provided a correct email address!");
		}
		else
		{
			sendCommand([
				"register-user",
				username.value,
				email.value,
				password.value
				],
				function(response) {
					console.log(response);
					if(response)
					{
						alert("Registration was a success!");
						clearDiv(div);
						state.registration = 1;
						showLogin(div);
					}
					else
					{
						alert("Registration failed, try again!");
						alert("Perhaps try a different username?");
					}
				});
		}
			
	};
}

function getPhraseComments(phraseId, div)
{
	var blackfield = document.createElement("div");
	document.body.appendChild(blackfield);
	blackfield.style = "position: fixed; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); top: 0; left: 0;";
	blackfield.onclick = function(e){
		if(e.target == blackfield)
		{
			document.body.removeChild(blackfield);
		}
	}
	var commentsDiv = document.createElement("div");
	blackfield.appendChild(commentsDiv);
	commentsDiv.className = "commentsDiv";
	var form = document.createElement("form");
	commentsDiv.appendChild(form);
	var commentField = document.createElement("textarea");
	form.appendChild(commentField);
	var button = document.createElement("button");
	button.type = "submit";
	commentField.style = "width: 80%; height: 10em; margin: 0 auto; resize: vertical";
	commentField.addEventListener(
		'input',
		function(e){
			if(commentField.value.length < 1)
			{
				button.disabled = true;
			}
			else
			{
				button.disabled = false;
			}
		}
	)
	form.appendChild(button);
	console.log(state.username);
	button.innerText = "Постави";
	form.onsubmit = function(e){
		e.preventDefault();
		sendCommand(
		["post-comment", commentField.value, state.username, phraseId],
		function(r){
			document.body.removeChild(blackfield);
			getPhraseComments(phraseId, div);
			
		}
		
		);
	}
	
	
	sendCommand(
		["get-phrase-comments", phraseId],
		function (comments){
			
			console.log(phraseId);
			console.log(comments);
			for(var i = 0; i < comments.length; i++)
			{
				var commentContent = document.createElement("div");
				commentContent.className = "commentContent";
				commentsDiv.appendChild(commentContent);
				var userP = document.createElement("p");
				userP.innerText = comments[i].username + ":";
				commentContent.appendChild(userP);
				var p = document.createElement("p");
				commentContent.appendChild(p);
				p.innerText = comments[i].comment;
			}
			console.log(comments);
		}
	);
	
	
}
function showAbout()
{
	var div = document.querySelector('body>main');
	clearDiv(div);
	clearButtonFinish();
	var p = document.createElement("p");
	div.appendChild(p);
	var textContainer = document.createElement("span");
	var text = document.createElement("text");
	p.appendChild(textContainer);
	textContainer.appendChild(text);
	var otherText = document.createElement("text");
	textContainer.appendChild(otherText);
	otherText.innerText = "Désolé à tous!";
	otherText.style = "float: right; margin-right: 50px; font-size: 30px;";
	text.innerText = "Това приложение има за цел да спомогне на хората, които не разбират друг език освен български, да научат постепенно чрез упражнения и представяне на думи избрания чужд език. \n За момента има два езика - английски и френски - и работят първите две поднива на първото ниво, тъй като в базата данни няма въведена информация за другите." + "\n";
}
function showHome()
{
	clearButtonFinish();
	main();
}
function showProgress()
{
	var username = getCookie("username");
	var password = getCookie("password");
	if(username && password)
	{
		clearButtonFinish();
		sendCommand(
			["get-user-progress", state.username, globalLanguage],
			function(r)
			{
				console.log(r);
				state.progress = r;
				console.log(state.progress);
				var div = document.querySelector('body>main');
				clearDiv(div);
				for(var i = 0; state.progress.length > i; i++)
				{
					var languageName = getLanguageName(state.progress[i].name);
					console.log(languageName);
					var newLine = document.createElement("text");
					newLine.innerText = '\n';
					
					var p = document.createElement("p");
					p.className = "progressInfoContainer";
					div.appendChild(p);
					var img = document.createElement("img");
					img.src = `/../images/${languageName}.jpg`;
					img.height = 120;
					img.width = 120;
					p.appendChild(img);
					
					var languageNameText = document.createElement("text");
					var languageLevelText = document.createElement("text");
					var languageSublevelText = document.createElement("text");
					var languageScoreText = document.createElement("text");
					//var imageContainer
					
					languageNameText.innerText = "Език: " + languageName + "\n";
					languageLevelText.innerText = "Ниво: " + state.progress[i].level + "\n";
					languageSublevelText.innerText = "Подниво: " + state.progress[i].sublevel +"\n";
					languageScoreText.innerText = "Резултат към този език: " + state.progress[i].score;
					
					p.appendChild(languageNameText);
					//p.appendChild(newLine);
					p.appendChild(languageLevelText);
					p.appendChild(languageSublevelText);
					p.appendChild(languageScoreText);
					
				}
			}
		);
	}
}

function showWords()
{
	var username = getCookie("username");
	var password = getCookie("password");
	if(username && password)
	{
		clearButtonFinish();
		sendCommand(
			["get-words-user", state.username, globalLanguage, state.userInfo.level, state.userInfo.sublevel],
			function (r)
			{
				var div = document.querySelector('body>main');
				clearDiv(div);
				console.log(r);
				state.words = r;
				console.log(state.words.length);
				for(var i = 0; state.words.length > i; i++)
				{
					var p = document.createElement("p");
					p.className = "wordContent";
					div.appendChild(p);
					div.style = "position: relative";
					
					console.log(i);
					var spanContainer = document.createElement("span");
					spanContainer.className = "spanContainer";
					var textWord = document.createElement("text");
					var levelsContainer = document.createElement("text");
					levelsContainer.id = "levelsContainer";
					levelsContainer.innerText = "Ниво: " + state.words[i].level + " | " + "Подниво: " + state.words[i].sublevel;
					p.appendChild(spanContainer);
					
					var textDivision = document.createElement("text");
					textDivision.innerText = " | ";
					spanContainer.appendChild(textWord);
					textWord.innerText = state.words[i].word;
					spanContainer.appendChild(textDivision);
					for(var j = 0; state.words[i].translation.length > j; j++)
					{
						var textTranslation = document.createElement("text");
						textTranslation.innerText = state.words[i].translation[j].word;
						spanContainer.appendChild(textTranslation);
						if(j + 1 < state.words[i].translation.length)
						{
							var comma = document.createElement("text");
							comma.innerText = ", ";
							spanContainer.appendChild(comma);
						}
						console.log(state.words[i].translation.length);
					}
					p.appendChild(levelsContainer);
					
				}
			}
		);
	}
}

function startWrongExercises(div)
{
	console.log(state.wrongExercises);
	console.log("vlizaWGreshnite");
	if(state.wrongExercises.length > 0)
	{
		state.doWrongExercise = true;
		state.wrongExercises[0].exercise(div);
	}	
}

function startExercises(div)
{
	if(state.counter > 0)
	{
		showImages(div);
	}
	else
	{
		var exercises = [
			makeListeningExercise,
			checkboxesFour,
			checkboxesFour,
			makePhraseExercise,
			makePhraseExercise,
			makePhraseExercise,
		];
		console.log("harambe");
		shuffle(exercises);
		var otherDiv = document.createElement("div");
		/*var mainElement = document.getElementById("main");
		mainElement.appendChild(otherDiv);*/ //to work without the "пропусни" button
		var button = document.createElement("button");
		button.id = "theContinueButton";
		otherDiv.appendChild(button);
		otherDiv.id = "otherDiv";
		otherDiv.style="align-items: center; justify-content: center;"
		document.body.appendChild(otherDiv);
		button.innerText = "Продължиs";
		button.style = "position: absolute; float: right;";
		state.exerciseCounter = 0;
		button.className = "btn btn-secondary";
		button.disabled = true;
		//button.addEventListener("")
		button.onclick = function() {
				//clearDivs();
				state.button.disabled = true;
				var exercise = exercises.pop();
				state.exerciseCounter++;
				button.className = "btn btn-secondary";
				console.log(state.exerciseCounter);
				
				console.log(state.wrongExercises.length);
				console.log(exercises.length);
				console.log(exercise);
				if(exercise && state.counter == 0)
				{
					clearDiv(div);
					console.log(state.counter);
					exercise(div);
				}
				else if(state.wrongExercises.length > 0)
				{
					clearDiv(div);
					console.log("happy");
					startWrongExercises(div);
				}
				else
				{
					console.log("mort");
					state.button.disabled = false;
					button.disabled = false;
					button.innerText = "Finish";
					button.style="bottom: 30%, right: 10%";
					
					button.addEventListener (
						'click',
						function(e)
						{
							var level = state.level;
							var sublevel = state.sublevel;
							sendCommand(["update-user-score-and-level", state.username, 10, globalLanguage, level, sublevel],
								function(r)
								{
									console.log(r);
									state.userAttributes = r;
								},false
							);
							var cookieExists = getCookie("playedToday");
							if(!cookieExists)
							{
								createCookiePlayed("playedToday", 1 );
								state.playedToday = true;
							}
							clearDiv(div);
							console.log(level);
							clearButtonFinish();
							state.cameFromExercise = true;
							alert("Прибавихте резултат към текущия език!");
							showLevel(div, level);
							//checked = true;
							/*var p = document.createElement("p");
							p.className = "photo-caption";
							p.textContent = e.target._name;
							//console.log(e.target.parentElement);
							e.target.parentElement.appendChild(p); */
							//console.log(e.target.src);
						}
					);
				}
		}
		state.button = button;
		exercises.pop()(div);
	}
}



function showLevel(div, level)
{
	if(state.cameFromExercise)
	{
		getUserInfo(function()
		{
			clearDiv(div);
			state.cameFromExercise = false;
			showLevel(div, level);
		})
	}
	var ul = document.createElement("ul");
	div.appendChild(ul);
	//state.level = level;
	//var sub = 3;
	for(var i = 0; i < 5; i++)
	{
		var li = document.createElement("li");
		ul.appendChild(li);
		var a = document.createElement("a");
		li.appendChild(a);
		a.href = "#";
		a.sublevel = i+1;

			a.innerText = `Подниво ${a.sublevel}`;
			var img = document.createElement("img");
		img.src = `/../images/level_${level}_${a.sublevel}.jpg`;
		//state.sulevel = this.sublevel;
		img.height = 120;
		img.width = 120;
		a.appendChild(img);
		
		
		console.log(level);
		//console.log(sub);
		//console.log(state.userInfo.sublevel);
		if(state.userInfo.sublevel > i && level == 1)
		{
			if(a.sublevel < 3)
			{
				a.onclick = function (event){
					event.preventDefault();
					clearDiv(div);
					console.log(globalLanguage);
					globalLanguage = globalLanguage.replace(/(\r\n|\n|\r)/gm, "");
					globalLanguage = globalLanguage.replace(/["']/g, "");
					state.level = level;
					state.sublevel = this.sublevel;
					sendCommand(
						["get-level", `${level}`, `${this.sublevel}`, globalLanguage],
						function (r){
							console.log(r);
							state.exercises = r;
							state.checkboxPhrases = [];
							state.radioPhrases = [];
							state.phrases = [];
							state.listeningPhrases = [];
							state.imagePhrases=[];
							clearDiv(div);
							
							//var translationWords = getWords(level);
							
							state.wrongExercises = [];
							state.doWrongExercise = false;
							console.log(state.wrongExercises);
							state.imageCount = state.exercises.images.length;
							state.counter = state.exercises.images.length;
							console.log(state.exercises.checkbox_phrases.length);
							state.checkboxCounter = 0;
							state.phraseCounter = 0;
							state.listeningCounter = 0;
							state.radioCounter = 0;
							state.useWrongImages = false;
							//state.phrase.length = 0;
							state.imagesWrong = [];
							state.phrasesWrong = [];
							state.checkboxesWrong = [];
							state.radiosWrong = [];
							state.listeningWrong = [];
							state.phrases = [];
							
							startExercises(div);
						}
					);
				};
			}
			else
			{
				a.onclick = function()
				{
					alert("Нивото не е готово!");
				}
			}
		}
		else
		{
			a.onclick = function()
			{
				alert("Направи другите поднива първо!");
			}
		}
	}
}

function showMainPage(div)
{
	getUserInfo(function(){
		var no = document.createElement("p");
		clearDiv(div);
		
		var ul = document.createElement("ul");
		div.appendChild(ul);
		for(var i = 0; i < 5; i++)
		{
			var li = document.createElement("li");
			ul.appendChild(li);
			var a = document.createElement("a");
			li.appendChild(a);
			a.href = "#";
			a.level = i+1;
			var img = document.createElement("img");
			img.src = `/../images/level_${a.level}.jpg`;
			
			img.height = 120;
			img.width = 120;
			
			img.className = "levels";
			a.appendChild(img);
			a.className = "disabled";
			a.appendChild(document.createTextNode( `Ниво ${a.level}`));
			//console.log(state.userInfo.level);
			//console.log(state.userInfo.sublevel);
			
			if(state.userInfo.level > i)
			{
				a.onclick = function (event){
					event.preventDefault();
					clearDiv(div);
					console.log(this.level);
					state.level = this.level;
					showLevel(div, this.level);
				};
			}
			else
			{
				a.onclick = function()
				{
					alert("Направи другите нива първо!");
				}
			}
		}
	});
}

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
	//setCookie("username", "", 0); delete cookie;
}

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function languageChoice() {
	
	if(document.getElementById("languageDropdown").className == "dropdown-content hidden")
	{
		document.getElementById("languageDropdown").className = "dropdown-content show";
	}
	else if(document.getElementById("languageDropdown").className == "dropdown-content show")
	{
		document.getElementById("languageDropdown").className = "dropdown-content hidden";
	}
	else
	{
		document.getElementById("languageDropdown").className = "dropdown-content show";
	}
}

function filterFunction() {
    var input, filter, ul, li, a, i;
    input = document.getElementById("myInput");
    filter = input.value.toUpperCase();
    var div = document.getElementById("languageDropdown");
    a = div.getElementsByTagName("a");
    for (i = 0; i < a.length; i++) 
	{
        if (a[i].innerHTML.toUpperCase().indexOf(filter) > -1) 
		{
            a[i].style.display = "";
        } 
		else 
		{
            a[i].style.display = "none";
        }
    }
}

function clearDiv(div)
{
	div.innerHTML = "";
}

/* function hasOneDayPassed(){
	var date = new Date().toLocaleDateString();

	if( localStorage.yourapp_date == date ) 
	{
		return false;
	}
	
	localStorage.yourapp_date = date;
	return true;
} */

function resetAtMidnight() {
    var now = new Date();
    var night = new Date(
        now.getFullYear(),
        now.getMonth(),
        now.getDate() + 1, // the next day, ...
        0, 0, 0 // ...at 00:00:00 hours
    );
    var msToMidnight = night.getTime() - now.getTime();

    setTimeout(function() {
		var cookieExists = getCookie("playedToday");
		var username = getCookie("username");
		if(cookieExists)
		{
			var result;
			var daysPlayed = 1;
			sendCommand(["update-date-user", username, daysPlayed],
				function(r){
					console.log(r);
					if(r)
					{
						result = true;
					}
					else
					{
						result = false;
					}
					//state.lostStreak = false;
				}
			);
			getUserInfo(function()
			{
				clear(div);
				main();
			});
		}
		else
		{
			var daysPlayed = 0;
			sendCommand(["update-date-user", username, daysPlayed],
				function(r){
					console.log(r);
					//state.lostStreak = true;
				}
			);
			getUserInfo(function()
			{
				clear(div);
				main();
			});
		}
        reset();              //      <-- This is the function being called at midnight.
        resetAtMidnight();    //      Then, reset again next midnight.
    }, msToMidnight);
}


function createCookiePlayed(name, value, path) {
	var expires = "";
	var date = new Date();
	var midnight = new Date(date.getFullYear(), date.getMonth(), date.getDate(), 23, 59, 59);
	expires = "; expires=" + midnight.toGMTString();
	if (!path) {
		path = "/";
	}
	document.cookie = name + "=" + value + expires + "; path=" + path;
	console.log(getCookie("playedToday"));
}

//function dateDaysPlayed()
//{
	/* var start = moment().startOf('day');
	var time = moment();
	var end = moment().endOf('day'); */
/* 	var initialDate = new Date(2018, 4, 29); // Attention: month is zero-based
	var now = Date.now();
	var difference = now - initialDate;
	var daysPlayed = 0;
	
	if(state.todayPlayed != true)
	{
		send("")
	}
	else if(state.todayPlayed == true)
	(
		daysPlayed = 1;
		sendCommand(["update-date-user", state.username, daysPlayed],
			function(r){
				console.log(r);
			}
		);
	)
	var millisecondsPerDay = 24 * 60 * 60 * 1000;
	var daysSince = Math.floor(difference / millisecondsPerDay
	alert(daysSince); // */
//}

function getUserInfo(callback) //level, sublevel, consecutive days, fiskato
{
	if(globalLanguage != 'null' && globalLanguage != undefined)
	{
		sendCommand(["get-user-info", state.username, globalLanguage],
					function (r){
						console.log(r);
						state.userInfo = r;
						var userLevelField = document.getElementById("userLevel");
						userLevelField.innerText = "Ниво: " + state.userInfo.level + "\n" + "Подниво: " + state.userInfo.sublevel;
						var userDaysField = document.getElementById("userDays"); 
						userDaysField.innerText = "Играни поред: " + state.userInfo.days_played + "\n" + " Резултат: " + state.userInfo.score + "\n";
						callback();
					}
		);
	}
}
function clearFields()
{
	var userLevelField = document.getElementById("userLevel");
	userLevelField.innerText = "";
	var userDaysField = document.getElementById("userDays"); 
	userDaysField.innerText = "";
}



function showLogin(div)
{
	
	
	clearDiv(div);
	if(state.registration == 1)
	{
		var registered = document.createElement("p");
		div.appendChild(registered);
		registered.innerHTML = "You can now login with the username and password you provided!";
		state.registration = 0;
	}
	var p = document.createElement("p");
	div.appendChild(p);
	var a = document.createElement("a");
	a.innerText = "Register";
	a.href = "#";
	p.appendChild(a);
	console.log(1);
	var form = document.createElement("form");
	var username = document.createElement("input");
	//var label = document.createElement("label");
	username.placeholder="Username";
	
	username.type = "text";
	var password = document.createElement("input");
	password.placeholder="Password";
	password.type = "password";
	var button = document.createElement("button");
	button.innerText = "Login";
	button.className = "btn btn-primary";
	button.type = "submit";
	div.appendChild(form);
	form.appendChild(username);
	form.appendChild(password);
	form.appendChild(button);
	var errorMessage = document.createElement("p");
	form.onsubmit = function(event){
		event.preventDefault();
		sendCommand([
			"login",
			username.value,
			password.value
			],
			function(r){
				if(r)
				{
					state.username = username.value;
					console.log(globalLanguage);
					globalLanguage = getUserLanguage(
					function()
					{
						state.globalLanguage = globalLanguage;
						console.log(globalLanguage);
						console.log(state.languageName);
						var usernameField = document.getElementById("username");
						usernameField.innerText = username.value;
						var userLanguageField = document.getElementById("userLanguage");
						userLanguageField.innerText = "Eзик: " + state.languageName;
						console.log(state.username);
						getUserInfo(function(){
							console.log(state.getUserInfo);
						});
						document.getElementById("languageChoice").disabled = false;
						setCookie("username", username.value, 365);
						setCookie("password", password.value, 365);
						clearDiv(div);
						var button = document.getElementById("log");
						button.innerText = "Излез";
						//dateDaysPlayed();
						
						button.addEventListener (
							'click',
							function(e)
							{
								clearButtonFinish();
								setCookie("username", "", 0);
								setCookie("password", "", 0);
								usernameField.innerText = "";
								userLanguageField.innerText = "";
								clearDiv(div);
								document.getElementById("languageChoice").disabled = true;
								document.getElementById("languageDropdown").className = "dropdown-content hidden";
								globalLanguage = "en";
								button.innerText = "Влез";
								showLogin(div);
							}
						);
						showMainPage(div);
					}
					);
				}
				else
				{
					errorMessage.classname = "error";
					errorMessage.innerText = "Invalid username or password";
					div.appendChild(errorMessage);
				}
			}
		);
		
	}
	
	
	a.onclick = function(event) {
		event.preventDefault();
		div.innerHTML="";
		registrationForm(div);
	}
	
	
}




function main()
{	
	
	var div = document.querySelector('body>main');
	clearDiv(div);
	div.className = "blocky";
	div.class = "blocky";
	var button = document.getElementById("log");
	button.innerText = "Влез";
	var username = getCookie("username");
	var password = getCookie("password");
	var language = getCookie("language");
	console.log(language);
	if(language)
	{
		globalLanguage = language;
	}
	else
	{
		setCookie("language", globalLanguage, 365);
	}
	if(username && password)
	{
		sendCommand([
			"login",
			username,
			password
			],
			function(r){
				if(r)
				{
					var button = document.getElementById("log");
					button.innerText = "Излез";
					button.addEventListener (
						'click',
						function(e)
						{
							setCookie("username", "", 0);
							setCookie("password", "", 0);
							var usernameField = document.getElementById("username");
							usernameField.innerText = "";
							var userLanguageField = document.getElementById("userLanguage");
							clearFields();
							userLanguageField.innerText = "";
							globalLanguage = "en";
							document.getElementById("languageChoice").disabled = true;
							/* var userLanguageField = document.getElementById("userLanguage");
							userLanguageField = ""; */
							clearDiv(div);
							button.innerText = "Влез";
							clearButtonFinish();
							showLogin(div);
						}
					);
					
					state.username = username;
					console.log(state.username);
					console.log(globalLanguage);
					
					globalLanguage = getUserLanguage(
						function ()
						{
							state.globalLanguage = globalLanguage;
							getUserInfo(function(){
							});

							console.log(globalLanguage);
							state.languageName = getLanguageName(globalLanguage);
							console.log(state.languageName)
							var usernameField = document.getElementById("username");
							usernameField.innerText = username + "\n"; //state.languageName;
							var userLanguageField = document.getElementById("userLanguage");
							//var userLevelField = document.getElementById("userLevel");
							var userConsecutiveDaysField = document.getElementById("userConsecutiveDays");
							//getUserInfo
							userLanguageField.innerText = "Eзик: " + state.languageName;
							document.getElementById("languageChoice").disabled = false;
							//userLevelField = "Ниво: " + state + ", Раздел: " + state;//тук
							console.log(globalLanguage);
							console.log(state.username);
							console.log(state.wrongExercises);
							/* state.today = date();
							console.log(state.today); */
							showMainPage(div);
						}
					);
				}
				else
				{
					console.log(r);
					state.username = username;
					showLogin(div);
				}
			}
		);
	}
	else
	{
		showLogin(div);
	}
	
	return;
}


window.onload = main;