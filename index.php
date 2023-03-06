<!DOCTYPE html>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
Wyszukaj dokumenty po frazie zawartej w identyfikatorze
<form action="/index_filtered.php">
  <label for="pattern">Fraza: </label>
  <input type="text" id="pattern" name="pattern" value="4ed">
  <input type="submit" value="Filtruj">
</form> 
<script>

fetch("conf.json")
  .then(response => response.json())
  .then(json => urlconnect(json));
  
function urlconnect(json){

	var theUrl = json.url+json.db_name+"_all_docs";
	const xhr = new XMLHttpRequest()
	xhr.open("GET", theUrl)
	if(json.authentication=="true"){
		xhr.setRequestHeader("Authorization", "Basic " + window.btoa(json.username+":"+json.password))
	}
	xhr.send()

	xhr.onload = function() {
	  if (xhr.status === 200) {
		data = JSON.parse(xhr.responseText)
		console.log(data)
		console.log(data.products)
		writeContent(xhr.responseText)
	  } else if (xhr.status === 404) {
		console.log("No records found")
	  }
	}

	xhr.onerror = function() {
	  console.log("Network error occurred")
	}

	xhr.onprogress = function(e) {
	  if (e.lengthComputable) {
		console.log(`${e.loaded} B of ${e.total} B loaded!`)
	  } else {
		console.log(`${e.loaded} B loaded!`)
	  }
	}

	function writeContent(data){
		//document.write(data);
		data = JSON.parse(data);

		const body = document.body;
		const tbl = document.createElement('table');
		tbl.style.width = '100px';
		tbl.style.border = '1px solid black';
		data.rows.forEach(function(obj) {
			const tr = tbl.insertRow();
			const td = tr.insertCell();
			td.appendChild(document.createTextNode(JSON.stringify(obj.id).replaceAll('"','')));
			td.style.border = '1px solid black';
			const td2 = tr.insertCell();

			var a = document.createElement('a');
			var linkText = document.createTextNode("Zobacz");
			a.appendChild(linkText);
			a.title = "Zobacz";
			a.href = "/doc.php?id=" + JSON.stringify(obj.id).replaceAll('"','');
			//document.body.appendChild(a);

			td2.appendChild(a);
			td2.style.border = '1px solid black';
		});
		body.appendChild(tbl);
	}
}



</script>