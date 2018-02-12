"use strict";

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
	//request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	request.send(JSON.stringify(command));
}

function incrementScore()
{
	sendCommand(["increment-score"]);
}

function makePhraseExercise(phrase, div)
{
	
}

function getPhrase(div, state)
{
	var phrase;
	sendCommand(["get-phrase"], function(r) {
		/* var phrase = r;
		state.phrase = phrase;
		phrase = state.phrase.pop();
		console.log(state.phrase);
		makePhraseExercise(phrase, div); */
		state.phrases = r;
		console.log(r);
		//console.log(state.phrases);
		var phrase = state.phrases;
		makePhraseExercise(phrase, div);
				
	});
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
function getImages(div, state)
{
	//var container = document.createElement("div");
	var container = div;
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
	var i = imageCount - counter;
		console.log(i);
		var word = state.exercises.images[i].word;
		words.push(word);
		var incorrectWordOne = state.exercises.images[i].incorrect_word0;
		var incorrectWordTwo = state.exercises.images[i].incorrect_word1;
		incorrectWords.push(incorrectWordOne, incorrectWordTwo);
		allWords = emptyJoin.concat(words, incorrectWords);
		i++;
	//}
	//console.log(words);
	//console.log(incorrectWords);
	//console.log(allWords);
	//console.log(imageExercises);
	//var words = ["tree", "rabbit", "building", "horse", "blue", "doll"];
	var inps = [];
	var imgs = [];
	var correctWord = 0;
	console.log(allWords[0]);
	for(var i = 0; i < 3; i++)
	{
		var div = document.createElement("div");
		container.appendChild(div);
		div.class = "inline-block";
		var label = document.createElement("label");
		
		
		var img = document.createElement("img");
		imgs.push(img);
		console.log(imgs);
		var inp = document.createElement("input");
		inps.push(inp);
		inp.type = "radio";
		inp.name = "choice";
		inp.style = "display:block; cursor:pointer;  margin-top: -15px; margin-bottom: 10px; margin-right: -100px; margin-left: 100px;"
		inp.value = allWords[i];
		console.log (i + allWords[i]);
		img._name = words[i];
		img.src = `/_res/${allWords[i]}.jpg`;
		img.height = 120;
		img.width = 120;
		img.style = "display: inline-block; float: left";
		div.appendChild(img);
		div.appendChild(label);
		var span = document.createElement("span");
		div.appendChild(span);
		span.innerText = `${allWords[i]}`;
		//label.text = `${allWords[i]}`;
		span.style = "display:block;";
		label.appendChild(img);
		label.appendChild(inp);
		
		div.appendChild(inp);
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
	document.body.appendChild(button);
	button.textContent = "Check";
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
			console.log("not correct");
		}
	}
	var buttonC = document.createElement("button");
	div.appendChild(buttonC);
	buttonC.textContent="Continue";
	state.counter--;
	buttonC.onclick = function()
	{
		if(state.counter + state.exercises.images.length == state.exercises.images.length)
		{
			console.log("continuing");
			clearDiv(div);
			startExercises(div, state);
		}
		else
		{
			clearDiv(container);
			getImages(container, state);
		}
	}
	
	
}

function makeCheckboxes(phrase, div)
{
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
	var questionMark = 
	//p.innerHTML = phraseName;
	p.appendChild(t);
	p.appendChild(textPhrase);
	
	//t.innerText = phraseName;
	//print(phraseName);
	var t = (" ?");
	var form = document.createElement("form");
	div.appendChild(p);
	
	div.appendChild(form);
	var ul = document.createElement("ul");
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
		label.appendChild(document.createTextNode(mixed_answers[i].answer));
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
				}
			}
			else
			{
				if(checkboxes[i].checked)
				{
					//incorrect answer was checked
					checkboxes[i].parentNode.style = "background: red;";
				}
				else
				{
					//incorrect answer was not checked
					checkboxes[i].parentNode.style = "background: darkgreen;"; 
				}
			}
			
			//console.log(checkboxes[i].checked);
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
				makeCheckboxes(phrase, div);
			}
		);
	}
	else
	{
		var phrase = state.phrases.pop();
		makeCheckboxes(phrase, div);
	}
}

function registrationForm(div)
{
	//sendCommand(["init-db"], function(r) {console.log (r);}, false);
	//var state = {};
	//state.phrase = undefined;
	
	//playAudio(state);
	
	//getPhrase(state);
	
	var form = document.createElement("form");
	div.appendChild(form);
	var input = document.createElement("input");
	/* form.onsubmit = function(event){
		event.preventDefault(); //always first
		console.log(2);
		sendCommand(
			["check-phrase", state.phrase, input.value],
			function (response)
			{
				callback (state, response);
			}
		);
	}; */
	
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
	//make them a type
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

function startExercises(div, state)
{
	console.log(state.counter);
	if(state.counter == 0)
	{
		
	}
	else
	{
		getImages(div, state);
	}
	
	//add table in api.php
	//console.log(state.exercises.radio);
	
	var exercises = [
	//	registrationForm,
	//	registrationForm,
		getPhrase,
		getPhrase,
	//	addPhrasesMode,
	//	addPhrasesMode,
		getImages,
	//	getImages,
		checkboxesFour,
		checkboxesFour,
	];

	shuffle(exercises);
	
	button.onclick = function(){
		div.innerHTML = "";
		//divB.innerHTML = "";
    	var exercise = exercises.pop();
		if (exercises.length == 0)
		{
			button.innerText = "Finish";
			//button.parentNode.removeChild (button);
		}
		if(exercise)
		{
			exercise(div, state);
		}
		else
		{
			document.body.innerHTML = "";
		}
	}
	
	exercises.pop()(div, state);

}

function showLevel(div, state, level)
{
	for(var i = 0; i < 5; i++)
	{
		var a = document.createElement("a");
		a.href = "#";
		a.sublevel = i+1;
		a.innerText = `SubLevel ${a.sublevel}`;
		div.appendChild(a);
		a.onclick = function (event){
			event.preventDefault();
			clearDiv(div);
			sendCommand(
				["get-level", `${level}`, `${this.sublevel}`],
				function (r){
					console.log(r);
					state.exercises = r;
					clearDiv(div);
					state.imageCount = state.exercises.images.length;
					state.counter = state.exercises.images.length;
					startExercises(div, state);
				}
			);
		};
	}
}

function showMainPage(div, state)
{
	for(var i = 0; i < 5; i++)
	{
		var a = document.createElement("a");
		a.href = "#";
		a.level = i+1;
		a.innerText = `Level ${a.level}`;
		var img = document.createElement("img");
		img.src = `/_res/level_${a.level}.jpg`;
		img.height = 60;
		img.width = 60;
		img.style = "display: block;"
		a.appendChild(img);
		div.appendChild(a);
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

function main()
{	
	//sendCommand(["init-db"], function(r) {console.log (r);}, false);

	var state={};
	
	var div = document.querySelector('body>main');
	
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