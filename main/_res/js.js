﻿"use strict";

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
			.filter(voice => voice.lang.includes('en'))
			.map(voice => `<option value="${voice.name}">${voice.name} (${voice.lang})</option>`)
			.join('');
	}
	function setVoice() {
		msg.voice = voices.find(voice => voice.name === this.value);
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
	stopButton.addEventListener('click', () => toggle(false));
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

function incrementScore()
{
	sendCommand(["increment-score"]);
}

function makeRadioExercise(div, state)
{
	var p = document.createElement("p");
	var span = document.createElement("span");
}

function makeListeningExercise(div, state)
{
	var p = document.createElement("p");
	div.appendChild(p);
	
	console.log(state.exercises.phrases[state.listeningCounter].phrase);
	var phrase = state.exercises.phrases[state.listeningCounter].phrase;
	//var phraseName = document.createElement("div");
	var t = document.createTextNode("Напишете каквото чуете.");
	p.appendChild(t);
	//do the cycle for each word to show translation db->words<>
	//var interval = "";
		var select = document.createElement("select");
		div.appendChild(select);
		select.name = "voice";
		select.id = "voices";
		var option = document.createElement("option");
		select.appendChild(option);
		option.innerText = "Select";
		option.value = "";
		var input = document.createElement("input");
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
		inputTwo.step = "0.1";
		var stopButton = document.createElement("button");
		div.appendChild(stopButton);
		stopButton.id = "stop";
		var speakButton = document.createElement("button");
		div.appendChild(speakButton);
		speakButton.id = "speak";
		speakButton.innerText = "Пусни изречението";
		speakButton.onclick = playAudio(phrase);
	
	
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
	phraseAnswers.push(phrase);
	console.log(phraseAnswers);
	var phraseInput = document.createElement("input");
	div.appendChild(phraseInput);
	//var listenButton = document.createElement("button");
	//div.appendChild(listenButton);
	//listenButton.innerText="Пусни записа";
	//listenButton.onclick = playAudio(phrase);
	
	var form = document.createElement("form");
	var button = document.createElement("button");
	button.innerText= "Провери";
	button.type = "submit";
	form.appendChild(button);
	div.appendChild(form);
	form.onsubmit = function (event) {
		event.preventDefault();
		sendCommand(["check-phrase", phraseInput.value, phraseAnswers, phrase], 
					function (r){
			
					console.log(r);
					if(r.correct)
					{
						
					}
					else
					{
						state.listeningWrong.push("");
					}
					
					}, false);
	}
	state.listeningCounter++;
}

function makePhraseExercise(div, state)
{
	var p = document.createElement("p");
	div.appendChild(p);
	
	console.log(state.exercises.phrases[state.phraseCounter].phrase);
	var phrase = state.exercises.phrases[state.phraseCounter].phrase;
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
	var phraseInput = document.createElement("input");
	phraseInput.phrase = "";
	div.appendChild(phraseInput);
	
	
	var form = document.createElement("form");
	var button = document.createElement("button");
	button.innerText= "Провери";
	button.type = "submit";
	form.appendChild(button);
	div.appendChild(form);
	form.onsubmit = function (event) {
		event.preventDefault();
		sendCommand(
			["check-phrase", phraseInput.value, phraseAnswers, phrase], 
			function (r){
				if(r.correct)
				{
					
				}
				else
				{
					state.phrasesWrong.push(state.phraseCounter);
				}
			}, false
		);
		console.log(state.exercises.phrases[state.phraseCounter].id);
		getPhraseComments(state.exercises.phrases[state.phraseCounter].id, div, state);
	}
	state.phraseCounter++;
}



function getPhrase(div, state)
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
		makeCheckboxes(phrase, div, state);
	}
	/*var phrase;
	sendCommand(["get-phrase"], function(r) {
		var phrase = r;
		state.phrase = phrase;
		phrase = state.phrase.pop();
		console.log(state.phrase);
		makePhraseExercise(phrase, div); 
		state.phrases = r;
		console.log(r);
		//console.log(state.phrases);
		var phrase = state.phrases;
		makePhraseExercise(phrase, div);
				
	});
	*/
	//console.log(state.phrase);
}

function onSubmit()
{
	alert(0);
}

function callback(state, response)
{
	var div = document.createElement("div");
	div.className = "correctionbar";
	document.body.appendChild(div);
	
	if(result.response)
	{
		div.className += " correct"; //
		div.textContent = "You are correct";
	}
	else
	{
		div.className += " incorrect";
		div.textContent = "You are not correct";
	}
	
	var button = document.createElement("button");
	div.appendChild(button);
	button.textContent="Continue";
	button.onclick = function()
	{
		getPhrase(state);
	}
	
}
function showImages(div, state)
{
	var container = div;
	var div = document.createElement("div");
	div.className = "button-container";
	var itemContainer = document.createElement("div");
	var p = document.createElement("p");
	itemContainer.appendChild(p);
	var ul = document.createElement("ul");
	itemContainer.appendChild(ul);
	container.appendChild(itemContainer);
	itemContainer.id = "itemContainer";
	//document.body.appendChild(container);
	console.log(state.exercises.images[0].word);
	console.log(state.exercises.images[0].incorrect_word0);
	console.log(state.exercises.images[0].incorrect_word1);
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
	console.log(state);
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
	words.push(word);
	var incorrectWordOne = state.exercises.images[imageIndex].incorrect_word0;
	var incorrectWordTwo = state.exercises.images[imageIndex].incorrect_word1;
	incorrectWords.push(incorrectWordOne, incorrectWordTwo);
	allWords = emptyJoin.concat(words, incorrectWords);
	
	
	p.innerText = word;
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
		img.src = `/_res/${allWords[i]}.jpg`;
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
		
		img.addEventListener (
			'click',
			function(e)
			{
				//img.onclick = undefined;
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
	var button = document.createElement("button");
	itemContainer.appendChild(div);
	div.appendChild(button);
	button.textContent = "Провери";
	button.onclick = function() 
	{
		button.parentNode.removeChild(button);
		if(inps[correctWord].checked)
		{
			//incrementScore();
			unlock = 1;
			console.log("correct");
		}
		else
		{
			state.wrongExercises.push({"index" : imageIndex, "exercise" : showImages});
			console.log(state);
		}
	}
	var buttonC = document.createElement("button");
	buttonC.textContent="Continue";
	div.appendChild(buttonC);
	if(!state.doWrongExercise)
	{
		state.counter--;
	}
	//console.log(state.counter);
	buttonC.onclick = function()
	{
		if(state.counter == 0 && !state.doWrongExercise)
		{
			console.log("continuing");
			clearDiv(container);
			startExercises(container, state);
		}
		else if(!state.doWrongExercise)
		{
			clearDiv(container);
			showImages(container, state);
		}
		else if(state.wrongExercises.length > 0)
		{
			clearDiv(container);
			startWrongExercises(container, state);
		}
	}
	
	
}

function makeCheckboxes(phraseIndex, div, state)
{
	//clearDiv(div);
	//console.log(phrase.phrase);
	
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
	var t = document.createTextNode("What is the translation of ");
	//var questionMark = 
	//p.innerHTML = phraseName;
	p.appendChild(t);
	p.appendChild(textPhrase);
	
	//t.innerText = phraseName;
	//print(phraseName);
	var q = document.createTextNode("?");
	p.appendChild(q);
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
	var button = document.createElement("button");
	
	//button.id = "";
	button.disabled = true;
	button.type = "submit";
	button.innerText = "Провери";
	form.appendChild(button);
	form.onsubmit = function (event) {
		event.preventDefault();
		var correct = true;
		for(var i = 0; i < checkboxes.length; i++)
		{
			if(correct_answers.indexOf(mixed_answers[i])!=-1)
			{
				if(checkboxes[i].checked)
				{
					//correct answer was checked
					checkboxes[i].parentNode.style = "background: lightgreen;";
					console.log(checkboxes[i]);
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
					checkboxes[i].parentNode.style = "background: red;";
					correct = false;
				}
				else
				{
					//incorrect answer was not checked
					checkboxes[i].parentNode.style = "background: darkgreen;";
				}
			}
			
			//console.log(checkboxes[i].checked);
		}
		console.log("jere");
		if(!correct)
		{	
			state.wrongExercises.push({"index" : phraseIndex, "exercise" : checkboxesFour});
			console.log(state);
		}
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

function addPhrasesMode(div)
{
	var form = document.createElement("form");
	var phraseInput = document.createElement("input");
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
	div.appendChild(form);
	form.appendChild(button);
	form.appendChild(phraseLabel);
	form.appendChild(phraseInput);
	form.appendChild(answerInputs[0]);
	phraseInput.required = true;
	levelInput.required = true;
	subLevelInput.required = true;
	
	function addAnswerInput(event){
		var newAnswerInput = document.createElement("input");
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
}


function checkboxesFour(div, state)
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

	makeCheckboxes(phraseIndex, div, state);
}

function registrationForm(div)
{
	var form = document.createElement("form");
	div.appendChild(form);
	var input = document.createElement("input");
	var registerForm = document.createElement("form");
	var username = document.createElement("input");
	var email = document.createElement("input");
	var password = document.createElement("input");
	var button = document.createElement("button");
	
	button.type = "submit";
	button.innerText = "Register"
	password.type = "password";
	div.appendChild(registerForm);
	registerForm.appendChild(username);
	registerForm.appendChild(email);
	registerForm.appendChild(password);
	registerForm.appendChild(button);
	username.required = true;
	password.required = true;
	registerForm.onsubmit = function (event) {
		event.preventDefault();
		sendCommand([
			"register-user",
			username.value,
			email.value,
			password.value
			],
			function(response) {console.log(response);}, false);
	};
}

function getPhraseComments(phraseId, div, state)
{
	
	sendCommand(
		["get-phrase-comments", phraseId],
		function (comments){
			console.log(comments);
			for(var i = 0; i < comments.length; i++)
			{
				var p = document.createElement("p");
				div.appendChild(p);
				p.innerText = comments[i].comment;
			}
			console.log(comments);
		}
	);
	
}

function startWrongExercises(div, state)
{
	if(state.wrongExercises.length > 0)
	{
		state.doWrongExercise = true;
		state.wrongExercises[0].exercise(div, state);
	}	
}

function startExercises(div, state)
{
	if(state.counter > 0)
	{
		showImages(div, state);
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
		shuffle(exercises);
		var otherDiv = document.createElement("div");
		document.body.appendChild(otherDiv);
		var button = document.createElement("button");
		div.appendChild(button);
		otherDiv.appendChild(button);
		button.innerText = "Пропусни";
		button.onclick = function(){
			div.innerHTML = "";
			var exercise = exercises.pop();
			
			if(exercise && state.counter == 0)
			{
				console.log(state.counter);
				exercise(div, state);
			}
			else if(state.wrongExercises.length > 0)
			{
				startWrongExercises(div, state);
			}
			else
			{
				button.innerText = "Finish";
			}
		}
		exercises.pop()(div, state);
	}
}



function showLevel(div, state, level)
{
	
	var ul = document.createElement("ul");
	div.appendChild(ul);
	for(var i = 0; i < 5; i++)
	{
		var li = document.createElement("li");
		ul.appendChild(li);
		var a = document.createElement("a");
		li.appendChild(a);
		a.href = "#";
		a.sublevel = i+1;

			a.innerText = `SubLevel ${a.sublevel}`;
		a.onclick = function (event){
			event.preventDefault();
			clearDiv(div);
			sendCommand(
				["get-level", `${level}`, `${this.sublevel}`],
				function (r){
					console.log(r);
					
					state.exercises = r;
					state.checkboxPhrases = [];
					state.radioPhrases = [];
					state.phrases = [];
					state.listeningPhrases = [];
					state.imagePhrases=[];
					clearDiv(div);
					
					state.wrongExercises = [];
					console.log(state);
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
					
					startExercises(div, state);
				}
			);
		};
	}
}

function showMainPage(div, state)
{
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
		img.src = `/_res/level_${a.level}.jpg`;
		
		img.height = 120;
		img.width = 120;
		
		
		img.className = "levels";
		a.appendChild(img);
		a.appendChild(document.createTextNode( `Level ${a.level}`));
		a.onclick = function (event){
			event.preventDefault();
			clearDiv(div);
			showLevel(div, state, this.level);
		};
	}
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
    document.getElementById("languageDropdown").classList.toggle("show");
}

function filterFunction() {
    var input, filter, ul, li, a, i;
    input = document.getElementById("myInput");
    filter = input.value.toUpperCase();
    div = document.getElementById("myDropdown");
    a = div.getElementsByTagName("a");
    for (i = 0; i < a.length; i++) {
        if (a[i].innerHTML.toUpperCase().indexOf(filter) > -1) {
            a[i].style.display = "";
        } else {
            a[i].style.display = "none";
        }
    }
}

function clearDiv(div)
{
	div.innerHTML = "";
}

function showLogin(div, state)
{
	console.log(1);
	var form = document.createElement("form");
	var username = document.createElement("input");
	username.type = "text";
	var password = document.createElement("input");
	password.type = "password";
	var button = document.createElement("button");
	button.innerText = "Login";
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
					console.log(state.username);
					setCookie("username", username.value, 365);
					setCookie("password", password.value, 365);
					
					clearDiv(div);
					
					showMainPage(div, state);
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
	
	var p = document.createElement("p");
	div.appendChild(p);
	var a = document.createElement("a");
	a.innerText = "Register";
	a.href = "#";
	p.appendChild(a);
	a.onclick = function(event) {
		event.preventDefault();
		div.innerHTML="";
		registrationForm(div, state);
	}
	
	
}

function addNextButton()
{
	
}

function main()
{	
	
	sendCommand(["init-db"], function(r) {console.log (r);}, false);

	var state={};
	
	var div = document.querySelector('body>main');
	div.className = "blocky";
	div.class = "blocky";
	
	var username = getCookie("username");
	var password = getCookie("password");
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
					showMainPage(div, state);
				}
				else
				{
					console.log(r);
					showLogin(div, state);
				}
			}
		);
	}
	else
	{
		showLogin(div, state);
	}
	
	return;
}

window.onload = main;