	function anotherphoto(current,m){
		var toget = parseInt(current)+1;
		var url = "template/latestscreenshot.php?imagenumber="+toget+"&m="+m;
		console.log('URL requested: '+url);		
		$.get(url, function(response) {
		  var imgs = response.replace('<div id="data" style="display: none;">',"");
		  var imgs = imgs.replace('</div>',"");
		  var imgarray = imgs.split(",");
		  $("#current-photo").val(imgarray[1]);
		  $("#questioner-img").attr("src",imgarray[0]);
		});	
	}