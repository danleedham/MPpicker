
// Global function to get another photo
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
	// Load Members from the ID and Section
	function windloadmembers(eventid,section){
		document.getElementById('togglemenu').style.display = 'none';
		document.getElementById('loader').style.display = 'inline';
		console.log('Loading list for program '+location+' and section: '+section);
		if (!document.getElementById("removedupes-input").checked){
			var dupes = 'remove';
		} else {
			var dupes = 'keep';
		}
		$("#wrapups").load('template/wind-list.php?&event='+eventid+'&section='+section+'&keepdupes='+dupes,function() {
   			document.getElementById('loader').style.display = 'none';
   			document.getElementById('togglemenu').style.display = 'inline';
			$("#currentspeakersdiv").load('template/wind-checknew.php?event='+eventid+'&section='+section+'&keepdupes='+dupes);
			console.log('template/wind-list.php?&event='+eventid+'&section='+section+'&keepdupes='+dupes);
   		});
	}
	function windloadevents(date){
	   $("#event-input").load('template/wind-events.php?date='+date);
	   $("#sect-input").load('template/wind-chapters.php?date='+date);
	   console.log('Loading events for: '+date);
	}
	function windloadsections(){
	   var date = document.getElementById("date-input").value;
	   var event = encodeURI(document.getElementById("event-input").value);
	   $("#sect-input").load('template/wind-chapters.php?date='+date+'&event='+event);
	   console.log('Loading events for: '+date+' to '+event);
	}
	
	function windcheckformembers(){
		var eventid = document.getElementById('event-input').value;
		var section = document.getElementById('sect-input').value;
		if (!document.getElementById("removedupes-input").checked){
			var dupes = 'remove';
		} else {
			var dupes = 'keep';
		}
		var currentcount = document.getElementById("currentspeakers").value;
		$("#countspeakersdiv").load('template/wind-checknew.php?event='+eventid+'&section='+section+'&keepdupes='+dupes+'&id=countspeakers', function() {
			var newcount = document.getElementById("countspeakers").value;
			console.log('New Count = '+newcount+' Old Count = '+currentcount);
			if(newcount > currentcount) {
				windloadmembers(eventid,section);
				console.log('New member logged... reloading list');
			} else {
				// console.log('No new members logged...');
			}	
		});	
		setTimeout(windcheckformembers, 10000);
	}
	
	function togglemenu(){
		var menu = document.getElementById("menu");
		menu.style.display = menu.style.display === 'none' ? '' : 'none';
		var h = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
		var listsize = h - 196;
		console.log('Removing Menu and Resizing list to '+listsize);
	}
	function togglemobilelist(){
		var list = document.getElementById("list");
		list.style.display = list.style.display === 'none' ? 'block' : 'none';
	}