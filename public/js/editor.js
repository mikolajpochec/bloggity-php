// Markdown parsing
let inputField = document.getElementById("editor-field");
let preview = document.querySelector("#editor-preview");
let previewFull = document.querySelector("#editor-preview-full");
inputField.addEventListener('input', () => {
	let parser = new MarkdownParser(inputField.value);
	let html = parser.parse();
	preview.innerHTML = html;
	previewFull.innerHTML = html;
}) 

// Tab switch
const tabs = document.querySelectorAll('#tabs > div');
const radios = document.querySelectorAll('.multiple-choice-container label');
const previewRadio = document.querySelector('#toggle-preview');

// Reactivity
addEventListener('resize', () => {
	const displayStatus = getComputedStyle(previewRadio).display;
	if(previewRadio.checked && displayStatus === 'none') {
		radios[0].click();
	}
})

function switchTabs(evt) {
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
