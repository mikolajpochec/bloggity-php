// Markdown parsing
let inputField = document.getElementById("editor-field");
let preview = document.getElementById("editor-preview");
inputField.addEventListener('input', () => {
	let parser = new MarkdownParser(inputField.value);
	preview.innerHTML = parser.parse();
}) 

// Tab switch
let tabs = document.querySelectorAll('#tabs > div');
let radios = document.querySelectorAll('.multiple-choice-container label');

function switchTabs(evt) {
	console.log(evt.currentTarget.num)
	for(let i = 0; i < tabs.length; i++) {
		if(i === evt.currentTarget.num) {
			tabs[i].style.display = 'flex';
			continue;
		}
		tabs[i].style.display = 'none';
	}
}

for(let i = 0; i < radios.length; i++) {
	radios[i].addEventListener('change', switchTabs);
	radios[i].num = i;
}

switchTabs({currentTarget: {num: 0}});
