<!-- Here's the script that *should* get the relevant MPs from the search -->		
		<script>
			function showResult(str) {
			  if (str.length==0) { 
				document.getElementById("livesearch").innerHTML="";
				document.getElementById("livesearch").style.border="0px";
				return;
			  }
			  if (window.XMLHttpRequest) {
				// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			  } else {  // code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			  }
			  xmlhttp.onreadystatechange=function() {
				if (this.readyState==4 && this.status==200) {
				  document.getElementById("livesearch").innerHTML=this.responseText;
				}
			  }
			  xmlhttp.open("GET","livesearch.php?q="+str,true);
			  xmlhttp.send();
			}
			</script>