<!DOCTYPE html>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<script>

fetch("conf.json")
  .then(response => response.json())
  .then(json => urlconnect(json));
  
function urlconnect(json){

	var url_string = window.location.search.substring(1);
    var value = url_string.replace("url=","");
	var theUrl = json.url+json.db_name+value;
	var myImage = document.createElement("img");
	
	if(json.authentication=="true"){
		fetch(theUrl,{
		method: 'GET', 
		headers: {'Authorization': 'Basic ' + btoa(json.username+":"+json.password)}
		})
		  .then((response) => {
			if (!response.ok) {
			  throw new Error('Network response was not OK');
			}
			return response.blob();
		  })
		  .then((myBlob) => {
			myImage.src = URL.createObjectURL(myBlob);
			document.body.appendChild(myImage);
		  })
		  .catch((error) => {
			console.error('There has been a problem with your fetch operation:', error);
			});
	}
	else{
		fetch(theUrl)
		  .then((response) => {
			if (!response.ok) {
			  throw new Error('Network response was not OK');
			}
			return response.blob();
		  })
		  .then((myBlob) => {
			myImage.src = URL.createObjectURL(myBlob);
			document.body.appendChild(myImage);
		  })
		  .catch((error) => {
			console.error('There has been a problem with your fetch operation:', error);
			});
	}
}

</script>