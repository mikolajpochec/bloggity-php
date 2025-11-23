// Markdown parsing
let inputField = document.getElementById("editor-field");
let preview = document.querySelector("#editor-preview");
let previewFull = document.querySelector("#editor-preview-full");
let articleTitleHtml = document.querySelector("#article-title");

function updatePreview() {
	let parser = new MarkdownParser(inputField.value);
	let html = parser.parse();
	let inner = `<h1 class="article-title">${title}<h1>${html}`;
	preview.innerHTML = inner;
	previewFull.innerHTML = inner;
}

inputField.addEventListener('input', updatePreview) 

//Autosave
var timeoutId;
const AUTOSAVE_TIME_MS = 2000
let changesStatus = document.querySelector("#changes-status")
let unsaved = false

function setAutosaveTimer() {
	clearTimeout(timeoutId);
	timeoutId = setTimeout(() => {
		var xhr = new XMLHttpRequest();
		xhr.open("POST", "/api/article/update.php", true);
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xhr.onreadystatechange = () => {
			if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
				const json = JSON.parse(xhr.response);
				if(json.result === 'success') {
					changesStatus.innerHTML = "✅ all changes saved"
					unsaved = false
					return
				}
				changesStatus.innerHTML = "❌ error"
			}
		};
		xhr.send(`id=${articleId}&md_content=${inputField.value}`);
	}, AUTOSAVE_TIME_MS)
}

inputField.addEventListener('input', () => { 
	changesStatus.innerHTML = "❗ unsaved changes"
	unsaved = true
	setAutosaveTimer()
})

// Unsaved changes warning
window.addEventListener("beforeunload", (event) => {
	if (!unsaved) return;
	event.preventDefault();
});

// Tab switch
const tabs = document.querySelectorAll('#tabs > div');
const radios = document.querySelectorAll('.multiple-choice-container label');
const previewRadio = document.querySelector('#toggle-preview');

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
// Reactivity
addEventListener('resize', () => {
	const displayStatus = getComputedStyle(previewRadio).display;
	if(previewRadio.checked && displayStatus === 'none') {
		radios[0].click();
	}
})

// Metadata user update
function updateArticleMetadata() {
	let form = document.querySelector("#publish-form");
	let formData = new FormData(form);
	let payload = "";
	let addAnd = false;
	if(formData.get("status") == "public") {
		let htmlContent = document.querySelector("#editor-preview");
		formData.set("html", htmlContent.innerHTML);
		formData.set("md_content_latest_published", inputField.value);
	}
	console.log(formData);
	for (const [key, value] of formData) {
		if(addAnd) {
			payload += `&`;
		}
		payload += `${key}=${value}`;
		if(!addAnd) {
			addAnd = true;
		}
	}
	var xhr = new XMLHttpRequest();
	xhr.open("POST", "/api/article/update.php", true);
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.onreadystatechange = () => {
		if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
			const json = JSON.parse(xhr.response);
			if(json.result === 'success') {
				articleTitleHtml.innerHTML = formData.get("title");
				title = formData.get("title");
				updatePreview();
			}
		}
	};
	xhr.send(payload);
}
