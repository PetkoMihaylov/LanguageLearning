"use strict";

function incrementScore()
{
	var request = new XMLHttpRequest();
	request.onload = function(e){
		console.log(e.target.response);
	}
	
	request.open("POST", "/api.php", true);
	//request.responseType = "json";
	//request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	request.send("increment-score");	
}

function main()
{
	var container = document.createElement("div");
	document.body.appendChild(container);
	
	var words = ["tree", "rabbit", "building", "horse", "blue", "doll"];
	var inps = [];
	var imgs = [];
	var correctWord = Math.trunc(Math.random()*3);
	console.log(words[correctWord]);
	for(var i = 0; i < 3; i++)
	{
		var div = document.createElement("div");
		container.appendChild(div);
		
		var label = document.createElement("label");
		
		
		var img = document.createElement("img");
		imgs.push(img);
		console.log(imgs);
		var inp = document.createElement("input");
		inps.push(inp);
		inp.type = "radio";
		inp.name = "choice";
		inp.value = words[i];
		console.log (i + words[i]);
		img._name = words[i];
		img.src = `/_res/${words[i]}.jpg`;
		//div.appendChild(img);
		div.appendChild(label);
		label.appendChild(img);
		label.appendChild(inp);
		//div.appendChild(inp);
		img.addEventListener (
			'click',
			function(e)
			{
				img.onclick = undefined;
				var p = document.createElement("p");
				p.className = "photo-caption";
				p.textContent = e.target._name;
				//console.log(e.target.parentElement);
				e.target.parentElement.appendChild(p);
				console.log(e.target.src);
			}
		);
	}
	var button = document.createElement("button");
	document.body.appendChild(button);
	button.textContent = "Check";
	button.onclick = function() 
	{
		if(inps[correctWord].checked)
		{
			incrementScore();
			correctWord = Math.trunc(Math.random()*3);
			console.log(words[correctWord+3]);
			console.log("correct");
			for(var i = 0; i < 3; i++ )
			{
				imgs[i].src = `/_res/${words[i+3]}.jpg`;
			}
		}
		else
		{
			console.log("not correct");
		}
	}
}
window.onload = main;
