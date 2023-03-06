<!DOCTYPE html>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<form action="/">
    <input type="submit" value="Cofnij" />
</form>
<script>

fetch("conf.json")
  .then(response => response.json())
  .then(json => urlconnect(json));
  
function urlconnect(json){

	var url_string = window.location.search.substring(1);
    var value = url_string.replace("id=","");
	var theUrl = json.url+json.db_name+value;
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
		var points = [];
		var rectangles = [];
		
        tbl.style.width = '100px';
        tbl.style.border = '1px solid black';
        Object.keys(data).forEach(function(key) {
            const tr = tbl.insertRow();
            var td = tr.insertCell();
            td.appendChild(document.createTextNode(JSON.stringify(key).replaceAll('"','')));
            td.style.border = '1px solid black';
            td = tr.insertCell();
            td.appendChild(document.createTextNode(JSON.stringify(data[key]).replaceAll('"','')));
            td.style.border = '1px solid black';
            td = tr.insertCell();
            td.appendChild(document.createTextNode(typeof data[key]));
            td.style.border = '1px solid black';
            td = tr.insertCell();
            //td.appendChild(document.createTextNode(data[key].length));
            //td.style.border = '1px solid black';
            //td.setAttribute('rowSpan', '2');
            if(data[key].length==2){
                var canvas = document.createElement("canvas");
                canvas.width = 300;
                canvas.height = 300;
                var ctx = canvas.getContext("2d");

                //ctx.fillStyle = "rgba(0, 0, 200, 0.5)";
                if(data[key][0]>2 && data[key][1]>2){
                    ctx.fillRect (data[key][0]-2, data[key][1]-2, 5, 5);
                }
                else{
                    ctx.fillRect (data[key][0], data[key][1], 5, 5);
                }
                td.appendChild(canvas);
				points.push([data[key][0], data[key][1], 5, 5]);
            }
            else if(data[key].length==4){
                var canvas = document.createElement("canvas");
                canvas.width = 300;
                canvas.height = 300;
                var ctx = canvas.getContext("2d");

                //ctx.fillStyle = "rgba(0, 0, 200, 0.5)";
                ctx.fillRect (data[key][0], data[key][1], data[key][2], data[key][3]);
                td.appendChild(canvas);
				rectangles.push([data[key][0], data[key][1], data[key][2], data[key][3]]);
            }
			else if(key=="_attachments"){
                Object.keys(data[key]).forEach(function(obj){

                    var a = document.createElement('a');
                    var linkText = document.createTextNode(obj+"\n");
                    a.appendChild(linkText);
                    a.title = obj;
                    a.href = "/attachview.php?url=" + data["_id"] + "/" + obj;

                    td.appendChild(a);
                });
            }
            else if(key=="views"){
                Object.keys(data[key]).forEach(function(obj){

                    var a = document.createElement('a');
                    var linkText = document.createTextNode(obj+"\n");
                    a.appendChild(linkText);
                    a.title = obj;
                    a.href = "/view.php?url=" + data["_id"] + "/_view/" + obj;

                    td.appendChild(a);
                });
            }
            else{
                td.appendChild(document.createTextNode(data[key].length));
            }
            td.style.border = '1px solid black';
        });
        body.appendChild(tbl);
		
		var canvas = document.createElement("canvas");
		canvas.width = 300;
		canvas.height = 300;
		var ctx = canvas.getContext("2d");
		rectangles.forEach(rectangle => {
			//ctx.fillStyle = String(Math.floor(Math.random()*16777215).toString(16));
			ctx.fillRect (rectangle[0],rectangle[1],rectangle[2],rectangle[3]);
		});
			ctx.fillStyle = "red";
		points.forEach(point => {
			ctx.fillRect (point[0],point[1],point[2],point[3]);
		});
		document.body.appendChild(canvas);
    }
}

</script>