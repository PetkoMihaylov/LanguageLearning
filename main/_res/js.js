"use strict";

function sendCommand(command, callback, json=true)
{
	console.log(command);
	var request = new XMLHttpRequest();
	request.onload = function(e){
		callback(e.target.response);
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

function makeCheckboxes(answers)
{
	var correct_answers = answers.correct_answers;
	var incorrect_answers = answers.incorrect_answers;
	var mixed_answers = correct_answers.concat(incorrect_answers);
	shuffle(mixed_answers);
	console.log(mixed_answers);
	var form = document.createElement("form");
	document.body.appendChild(form);
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
	sendCommand(["get-words-checkbox"], makeCheckboxes);
	
}

function notmaintwo()
{
	sendCommand(["init-db"], function(r) {console.log (r);}, false);
	var state = {};
	state.phrase = undefined;
	
	//playAudio(state);
	
	getWord(state);
	
	var form = document.getElementById('userInput');
	var input = form.getElementsByTagName('input')[0];
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
	
	var registerForm = document.getElementById("userRegister");
	registerForm.onsubmit = function (event) {
		event.preventDefault();
		sendCommand([
			"register-user",
			registerForm.username.value,
			registerForm.email.value,
			registerForm.password.value
			],
			function(response) {console.log(response);}, false);
	};
}

window.onload = main;