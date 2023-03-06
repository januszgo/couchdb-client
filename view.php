<!DOCTYPE html>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<form action="/">
    <input type="submit" value="Strona główna" />
</form>
<head>
<script>
fetch("conf.json")
  .then(response => response.json())
  .then(json => urlconnect(json));
  
function urlconnect(json){

	var url_string = window.location.search.substring(1);
    var value = url_string.replace("url=","");
	var theUrl = json.url+json.db_name+value;
	console.log(theUrl);
	const xhr = new XMLHttpRequest()
	xhr.open("GET", theUrl)
	if(json.authentication=="true"){
		xhr.setRequestHeader("Authorization", "Basic " + window.btoa(json.username+":"+json.password))
	}
	xhr.send()


    xhr.onload = function() {
    if (xhr.status === 200) {
        data = JSON.parse(xhr.responseText)
        console.log(data.count)
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
        data = JSON.parse(data);
        
        const body = document.body;
        const tbl = document.createElement('table');
        tbl.style.width = '100px';
        tbl.style.border = '1px solid black';
        
        for(var i in data){
            //document.write(JSON.stringify(data[i]));
            for(var j in data[i]){
                //document.write(JSON.stringify(data[i][j]));
                const tr = tbl.insertRow();
                var td = tr.insertCell();
                td.style.border = '1px solid black';
                td.appendChild(document.createTextNode(JSON.stringify(data[i][j].id).replaceAll('"','')));
                td = tr.insertCell();
                td.style.border = '1px solid black';
                td.appendChild(document.createTextNode(JSON.stringify(data[i][j].key).replaceAll('"','')));
                for(var k in data[i][j].value){
                    td = tr.insertCell();
                    td.style.border = '1px solid black';
                    td.appendChild(document.createTextNode(JSON.stringify(data[i][j].value[k]).replaceAll('"','')));
                }
            }
        }
        body.appendChild(tbl);
    }
}
</script>
</head>
</html>
