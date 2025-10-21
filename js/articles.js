var searchField = document.querySelector("#search-field");
var checkBox = document.querySelectorAll("input[type='checkbox']");
var articlesContainer = document.querySelector("#articles-preview-container");
const MAX_MD_CONTENT_PREVIEW_LENGTH = 100;
function fetchArticlesAsync() {
	var xhr = new XMLHttpRequest();
	let query = "?status=";
	checkBox.forEach((e) => {
		if(e.checked) query += `${e.getAttribute("data-status")},`;
	});
	query += `&title=${searchField.value}`;
	xhr.open("GET", `/api/article${query}`, true);
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.onreadystatechange = () => {
		if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
			const json = JSON.parse(xhr.response);
			if(json.result === 'success') {
				json.data.forEach(e => {
					let content = e.md_content.slice(0, MAX_MD_CONTENT_PREVIEW_LENGTH);
					if(content.length >= MAX_MD_CONTENT_PREVIEW_LENGTH) {
						content = `${content.slice(0, content.length - 3)}...`;
					}
					articlesContainer.innerHTML += `
					<a class="elevated" href="/editor.php?id=${e.id}">
						<b>${e.title}</b>
						<p><i>${content}</i></p>
						<p><i class="category">${e.category_id == null ? "No category" : e.category_id}</i></p>
					</a>
					`;
				});
			}
		}
	}
	xhr.send();
}

fetchArticlesAsync();

// TODO: Add fetching on change (with some interval lol) and do smth about those categories
// TODO: Add pages
