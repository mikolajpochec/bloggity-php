function deleteArticleAPI(id) {
	let xhr = new XMLHttpRequest();
	xhr.open("POST", "/api/category/delete.php", true);
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.onreadystatechange = () => {
		if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
			const json = JSON.parse(xhr.response);
			if(json.result === 'success') {
				let node = document.querySelector(`#cat-panel-${id}`);
				node.remove();
			}
		}
	};
	xhr.send(`category_id=${id}`);
}
