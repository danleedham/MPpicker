
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
	
function twitter(handle){
	var twitter = document.getElementById("twitter");
	twitter.style.display = twitter.style.display === 'none' ? '' : 'none';
   $("#twitter").load('template/twitter.php?handle='+handle);
}
function loadextras(){
	var yourUl = document.getElementById("extras");
	yourUl.style.display = yourUl.style.display === 'none' ? '' : 'none';
}

function wholoadresults() {
	document.getElementById('loader').style.display = 'inline';
	var house = document.getElementById("house-input").value;
	if (house) {
		var houseurl = '&house=' + house;
	} else {
		var houseurl = "";
	}
	var sex = document.getElementById("sex-input").value;
	if (sex) {
		var sexurl = '&sex=' + sex;
	} else {
		var sexurl = "";
	}
	var party = document.getElementById("party-input").value;
	if (party) {
		var partyurl = '&party=' + encodeURI(party);
	} else {
		var partyurl = "";
	}
	var position = document.getElementById("position-input").value;
	if (position) {
		var positionurl = '&position=' + encodeURI(position);
	} else {
		var positionurl = "";
	}
	var committee = document.getElementById("committee-input").value;
	if (committee) {
		var committeeurl = '&committee=' + encodeURI(committee);
	} else {
		var committeeurl = "";
	}
	var department = document.getElementById("department-input").value;
	if (department) {
		var departmenturl = '&department=' + encodeURI(department);
	} else {
		var departmenturl = "";
	}
	var topic = document.getElementById("topic-input").value;
	if (topic) {
		var topicurl = '&topic=' + encodeURI(topic);
	} else {
		var topicurl = "";
	}
	var photos = document.getElementById("photos-input").value;
	if (photos) {
		var photosurl = '&photos=' + encodeURI(photos);
	} else {
		var photosurl = "";
	}
	var joined = document.getElementById("joined-input").value;
	if (joined) {
		var joinedurl = '&joined=' + String(joined);
	} else {
		var joinedurl = "";
	}
	var sortby = document.getElementById("sortby-input").value;
	if (sortby) {
		var sortbyurl = '&sortby=' + String(sortby);
	} else {
		var sortbyurl = "";
	}
	console.log('Loading List: ' + houseurl + sexurl + partyurl + positionurl + committeeurl + topicurl + departmenturl + photosurl + joinedurl);
	$("#whoresults").load('template/wholist.php?' + houseurl + sexurl + partyurl + positionurl + committeeurl + topicurl + departmenturl + photosurl + joinedurl + sortbyurl, function() {
		document.getElementById('loader').style.display = 'none';
	});
}
		
function whohideparty() {
	elements = document.getElementsByClassName("party");
	for (var i = 0; i < elements.length; i++) {
		elements[i].style.display = elements[i].style.display == 'none' ? 'block' : 'none';
	}
}	
	
function whohidejobs() {
	elements = document.getElementsByClassName("joblist");
	for (var i = 0; i < elements.length; i++) {
		elements[i].style.display = elements[i].style.display == 'none' ? 'block' : 'none';
	}
}

function whohideconst() {
	elements = document.getElementsByClassName("constituency");
	for (var i = 0; i < elements.length; i++) {
		elements[i].style.display = elements[i].style.display == 'none' ? 'block' : 'none';
	}
}

function whohidemenu() {
	var menu = document.getElementById("list");
	menu.style.display = menu.style.display === 'none' ? '' : 'none';
	var results = document.getElementById("whoresults");
	results.classList.add("col-sm-12");
	results.classList.remove("col-sm-9");
}

function wholoadsex() {
	var house = document.getElementById("house-input").value;
	$("#sex-input").load('template/whosex.php?&house=' + house);
}

function wholoadparties() {
	var house = document.getElementById("house-input").value;
	var sex = document.getElementById("sex-input").value;
	$("#party-input").load('template/whoparty.php?house=' + house + '&sex=' + sex);
}

function wholoadpartiesjusthouse() {
	var house = document.getElementById("house-input").value;
	$("#party-input").load('template/whoparty.php?house=' + house);
}	

function wholoadcommitteelists() {
	var house = document.getElementById("house-input").value;
	console.log('Loading Committees');
	$("#whocommittee").load('template/whocommittee.php?house='+house,function() {
		 $('#committee-input').chosen();
		console.log('Loaded list of Committees');
   	});
}
function wholoaddepartmentlists() {
	console.log('Loading Departments');
	$("#whodepartment").load('template/whodepartment.php',function() {
		$("#department-input").chosen();
		console.log('Loaded list of Departments');
   	});
}
function whotopics() {
	var house = document.getElementById("house-input").value;
	console.log('Loading Interest Topics');
	$("#whotopics").load('template/whointerests.php?house='+house,function() {
		$("#topic-input").chosen();
		console.log('Loaded list of Topics');
   	});
}

function updatelists() {
	var house = document.getElementById("house-input").value;
	console.log('Trying to update committees & topics');
	$("#whocommittee").load('template/whocommittee.php?house='+house,function() {
		 $('#committee-input').chosen();
		console.log('Updated list of Committees');
   	});
   	$("#whotopics").load('template/whointerests.php?house='+house,function() {
		$("#topic-input").chosen();
		console.log('Updated list of Topics');
   	});
}


function searchshowResult(str) {
    // Check which house to search through  
    if (!document.getElementById("choosehouse").checked) {
        var house = "Commons";
    } else {
        var house = "Lords";
    }
    // Check if the user wants to search by name, constituency or opsition
    var searchby = document.getElementById("searchby").value;
    if (searchby == "name") {
        reqdchars = 2;
        var url = "livesearch.php";
    } else if (searchby == "constituency") {
        reqdchars = 3;
        var url = "livesearch.php";
    } else {
        reqdchars = 4;
        var url = "livesearch.php";
    }
    // If we want to search by position then 
    if (searchby == "position"){
        var positiontype = document.getElementById("positiontype").value;
        var side = "&side=" + positiontype;
    } else {
        var side = "";
    }
    // If the string is x characters or more then do a nice little search
    if (str.length <= reqdchars) {
        document.getElementById("livesearchmember").innerHTML = "";
        document.getElementById("livesearchmember").style.border = "0px";
        return;
    }
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else { // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("livesearchmember").innerHTML = this.responseText;
        }
    }
    xmlhttp.open("GET", "template/" + url + "?house=" + house + "&searchby=" + searchby + "&q=" + str + side, true);
    xmlhttp.send();
    
    var h = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
	var listsize = h - 200;
	console.log('Resizing list to '+listsize+' px');
	document.getElementById("livesearchmember").setAttribute("style","height:"+listsize+"px");
}

function searchload(id) {
    if (!document.getElementById("photos").checked) {
        var photos = 'Stock';
    } else {
        var photos = document.getElementById("photos").value;
    }
    if (!document.getElementById("searchby").checked) {
        var searchby = 'name';
    }
    $("#contactCard").load('template/member.php?m=' + id + '&photos=' + photos);
    $('.active').removeClass('active');
    $('#m' + id).addClass("active");
}

function searchtogglemobilelist() {
    var list = document.getElementById("list");
    list.style.display = list.style.display === 'none' ? 'block' : 'none';
}
function searchchangesearchby() {
    var searchby = document.getElementById("searchby").value;
    console.log('Searching by '+searchby);
    var positiontype = document.getElementById("positiontypediv");
    if (searchby == "position") {
        positiontype.style.display = 'block';
    } else {
         positiontype.style.display = 'none';
    }
}

function searchiflordsscreenshot() {
    console.log('Checking to see if Lords...')
    if (!document.getElementById("choosehouse").checked) {
        console.log('Nah, you\'re good');
    } else {
        console.log('Setting default image to Screenshot');
        $('#photos').bootstrapToggle('on')
    }
}