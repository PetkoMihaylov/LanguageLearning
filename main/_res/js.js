﻿"use strict";

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

function getWord(state)
{
	sendCommand(
		["get-word"],
		function(response)
		{
			state.phrase = response;
		}
	);
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
		getWord(state);
	}
	
}

function makeCheckboxes(answers, div)
{
	var correct_answers = answers.correct_answers;
	var incorrect_answers = answers.incorrect_answers;
	var mixed_answers = correct_answers.concat(incorrect_answers);
	shuffle(mixed_answers);
	console.log(mixed_answers);
	var form = document.createElement("form");
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
		label.appendChild(document.createTextNode(mixed_answers[i]));
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

function main()
{
	sendCommand(["init-db"], function(r) {console.log (r);}, false);
	var div = document.createElement("div");
	document.body.appendChild(div);
	var button = document.createElement("button");
	document.body.appendChild(button);
	button.innerText = "Next";
	
	var exercises = [notmaintwo, notmaintwo, addPhrasesMode, addPhrasesMode, checkboxes, checkboxes];
	shuffle(exercises);
	
	button.onclick = function(){
		div.innerHTML = "";
    	var exercise = exercises.pop();
		if (exercises.length == 0) {
			button.innerText = "Finish";
			//button.parentNode.removeChild (button);
		}
		if(exercise)
		{
			exercise(div);
		}
		else
		{
			document.body.innerHTML = "";
		}
	}
	
	exercises.pop()(div);
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
		sendCommand(["add-phrase", phraseInput.value, levelInput, subLevelInput].concat(answers), undefined, false);
	}
}


function checkboxes(div)
{
	sendCommand(["get-words-checkbox"], function (r){makeCheckboxes(r,div)});
	
}

function notmaintwo(div)
{
	//sendCommand(["init-db"], function(r) {console.log (r);}, false);
	var state = {};
	state.phrase = undefined;
	
	//playAudio(state);
	
	getWord(state);
	
	var form = document.createElement("form");
	div.appendChild(form);
	var input = document.createElement("input");
	form.onsubmit = function(event){
		event.preventDefault(); //always first
		sendCommand(
			["check-word", state.phrase, input.value],
			function (response)
			{
				callback (state, response);
			}
		);
	};
	
	var registerForm = document.createElement("form");
	var username = document.createElement("input");
	var email = document.createElement("input");
	var password = document.createElement("input");
	var button = document.createElement("button");
	button.type = "submit";
	button.innerText = "Register"
	div.appendChild(registerForm);
	registerForm.appendChild(username);
	//make them a type
	registerForm.appendChild(email);
	registerForm.appendChild(password);
	registerForm.appendChild(button);
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

window.onload = main;