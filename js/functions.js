/*

Global Functions 

*/

function togglemenu(){
	var menu = document.getElementById("menu");
	menu.style.display = menu.style.display === 'none' ? '' : 'none';
	var h = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
	var listsize = h - 196;
	console.log('Removing Menu and Resizing list to '+listsize);
}

function qstogglemenu(){
    var menu = document.getElementById("menu");
    menu.style.display = menu.style.display === 'none' ? '' : 'none';
    var h = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
    var listsize = h - 154;
    console.log('Removing Menu and Resizing list to '+listsize);
    document.getElementById("livesearch").setAttribute("style","height:"+listsize+"px");
    
}
function togglemobilelist(){
	var list = document.getElementById("list");
	list.style.display = list.style.display === 'none' ? 'block' : 'none';
}

function twitter(handle){
	var twitter = document.getElementById("twitter");
	twitter.style.display = twitter.style.display === 'none' ? '' : 'none';
   $("#twitter").load('template/fixed-queries/twitter.php?handle='+handle);
}
function loadextras(){
	var yourUl = document.getElementById("extras");
	yourUl.style.display = yourUl.style.display === 'none' ? '' : 'none';
}

// Function to get another photo
function anotherphoto(current,m){
	var toget = parseInt(current)+1;
	var url = "template/latestscreenshot.php?imagenumber="+toget+"&m="+m;
	console.log('URL requested: ' + url);		
	$.get(url, function(response) {
	  var imgs = response.replace('<div id="data" style="display: none;">',"");
	  var imgs = imgs.replace('</div>',"");
	  var imgarray = imgs.split(",");
	  $("#current-photo").val(imgarray[1]);
	  $("#questioner-img").attr("src",imgarray[0]);
	});	
}


/*

Functions for Windups

*/

// Load Members from the ID and Section
function windloadmembers(eventid,section) {
	document.getElementById('togglemenu').style.display = 'none';
	document.getElementById('loader').style.display = 'inline';
	console.log('Loading list for program '+location+' and section: '+section);
	if (!document.getElementById("removedupes-input").checked){
		var dupes = 'remove';
	} else {
		var dupes = 'keep';
	}
	if (!document.getElementById("sort-input").checked){
		var sort = 'alpha';
	} else {
		var sort = 'time';
	}
	$("#wrapups").load('template/wind-list.php?&event='+eventid+'&section='+section+'&keepdupes='+dupes+'&sort='+sort,function() {
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
   $("#sect-input").load('template/wind-chapters.php?date='+date+'&event='+event, function(){
        var eventid = document.getElementById('event-input').value;
        var section = document.getElementById('sect-input').value;
        windloadmembers(eventid,section);
   });
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
			windloadsections();
			console.log('Reloading Sections');
		} else {
			// console.log('No new members logged...');
		}	
	});	
	setTimeout(windcheckformembers, 10000);
}

/* 

Functions for Who 

*/

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
	$("#whoresults").load('template/who-returnresults.php?' + houseurl + sexurl + partyurl + positionurl + committeeurl + topicurl + departmenturl + photosurl + joinedurl + sortbyurl, function() {
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
	$("#party-input").load('template/who-listparty.php?house=' + house + '&sex=' + sex);
}

function wholoadpartiesjusthouse() {
	var house = document.getElementById("house-input").value;
	$("#party-input").load('template/who-listparty.php?house=' + house);
}	

function wholoadcommitteelists() {
	var house = document.getElementById("house-input").value;
	console.log('Loading Committees');
	$("#whocommittee").load('template/who-listcommittees.php?house='+house,function() {
		 $('#committee-input').chosen();
		console.log('Loaded list of Committees');
   	});
}
function wholoaddepartmentlists() {
	console.log('Loading Departments');
	$("#whodepartment").load('template/who-listdepartments.php',function() {
		$("#department-input").chosen();
		console.log('Loaded list of Departments');
   	});
}
function whotopics() {
	var house = document.getElementById("house-input").value;
	console.log('Loading Interest Topics');
	$("#whotopics").load('template/who-listinterests.php?house='+house,function() {
		$("#topic-input").chosen();
		console.log('Loaded list of Topics');
   	});
}
function updatelists() {
	var house = document.getElementById("house-input").value;
	console.log('Trying to update committees & topics');
	$("#whocommittee").load('template/who-listcommittees.php?house='+house,function() {
		 $('#committee-input').chosen();
		console.log('Updated list of Committees');
   	});
   	$("#whotopics").load('template/who-listinterests.php?house='+house,function() {
		$("#topic-input").chosen();
		console.log('Updated list of Topics');
   	});
}

/* 

Functions for the Search Page 

*/

// Main Ajax search function where str is the query 
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
        var url = "search-queryresults.php";
    } else if (searchby == "constituency") {
        reqdchars = 3;
        var url = "search-queryresults.php";
    } else {
        reqdchars = 4;
        var url = "search-queryresults.php";
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
    };
    xmlhttp.open("GET", "template/" + url + "?house=" + house + "&searchby=" + searchby + "&q=" + str + side, true);
    xmlhttp.send();
    
    var h = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
	var listsize = h - 200;
	//console.log('Resizing list to '+listsize+' px');
	document.getElementById("livesearchmember").setAttribute("style","height:"+listsize+"px");
}

function pickconst() {
	console.log('Loading Constituencies');
	$("#pick-const").load('template/search-listconst.php',function() {
		$("#const-pick").chosen();
		console.log('Loaded list of Constituencies');
		var constpickdiv = document.getElementById("constpickdiv");
		constpickdiv.style.display = 'none';
		$("#const-pick").chosen().change( function() {
				var chosenconst = document.getElementById("const-pick").value;
				console.log(chosenconst);
				searchshowResult(chosenconst);
			}
		);
   	});
}

function pickpos() {
	console.log('Loading Positions');
	$("#pick-pos").load('template/search-listpos.php?house=Commons&side="government"',function() {
		$("#pos-pick").chosen();	
		console.log('Loaded list of Positions');
		var pospickdiv = document.getElementById("pospickdiv");
		pospickdiv.style.display = 'none';
		$("#pos-pick").chosen().change( function() {
				var chosenpos = document.getElementById("pos-pick").value;
				console.log(chosenpos);
				searchshowResult(chosenpos);
			}
		);
   	});
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
    $("#contactCard").load('template/search-member.php?m=' + id + '&photos=' + photos);
    $('.active').removeClass('active');
    $('#m' + id).addClass("active");
}

function searchtogglemobilelist() {
    var list = document.getElementById("list");
    list.style.display = list.style.display === 'none' ? 'block' : 'none';
}

function searchchangesearchby() {
    var searchby = document.getElementById("searchby").value;
    var pospickdiv = document.getElementById("pospickdiv");
	var constpickdiv = document.getElementById("constpickdiv");
	var typeinput = document.getElementById("typeinput");
	var positiontypediv = document.getElementById("positiontypediv");
    if (searchby == "position") {
        pospickdiv.style.display = 'block';
		constpickdiv.style.display = 'none';
		typeinput.style.display = 'none';
		positiontypediv.style.display = 'block';
		console.log("Only showing Pos input");
    } else if (searchby == "constituency"){
		pospickdiv.style.display = 'none';
		constpickdiv.style.display = 'block';
		typeinput.style.display = 'none';
		positiontypediv.style.display = 'none';
		console.log("Only showing Const input");
	} else {
         pospickdiv.style.display = 'none';
		 constpickdiv.style.display = 'none';
		 typeinput.style.display = 'block';
		 positiontypediv.style.display = 'none';
		 console.log("Only showing Name input");
    }
}

function searchselectpostyle(postype) {
	console.log('Updating Positions');
	if (!document.getElementById("choosehouse").checked) {
        var house = "Commons";
    } else {
        var house = "Lords";
    }
	$("#pick-pos").load('template/search-listpos.php?side='+postype+'&house='+house,function() {
		$("#pos-pick").chosen();	
		console.log('Updated list of '+postype+' positions');
		$("#pos-pick").chosen().change( function() {
				var chosenpos = document.getElementById("pos-pick").value;
				console.log(chosenpos);
				searchshowResult(chosenpos);
			}
		);
   	});
}

/* 

Functions for Questions

*/

function printQuestions(){
    $("<link/>", {
        rel: "stylesheet",
        type: "text/css",
        href: "css/print-overrides.css"
    }).appendTo("head");    
}

function futuredayoralsloaddepts(date){
   $("#dept-input").load('template/qs-futuredayorals-returndepts.php?output=true&date='+date);
   var jsDate = new Date(date*1000);
   var humanDate = jsDate.toLocaleDateString();
   console.log('Loading departments for: '+humanDate);
}
function futuredayoralsloaddates() {
    console.log('Loading Dates');
    $("#date-div").load('template/futuredayorals-returndates.php?output=true',function() {
        console.log('Loaded list of Dates');
        var date = document.getElementById("date-input").value;
        futuredayoralsloaddepts(date)
    });
}
function futuredayoralsloadquestions(date,dept){
    document.getElementById('togglemenu').style.display = 'none';
    document.getElementById('loader').style.display = 'inline';
    if (!document.getElementById("together-input").checked){
        var together = "together";
    } else {
        var together = "dont";
    }
    if (!document.getElementById("topicals-together").checked){
        var topicalsbyparty = "byparty";
    } else {
        var topicalsbyparty = "dont";
    }
    var groups = document.getElementById("groups-input").value;
    var withdrawn = document.getElementById("withdrawn-input").value;
    var withoutnotice = document.getElementById("withoutnotice-input").value;
    console.log('Loading questions to '+dept+' on '+date+' using groups: '+groups+' and withdrawing (day): '+withdrawn+' withdrawing (before): '+withoutnotice+' grouped: '+together);
    groups = groups.replace(/[\r\n]+/g,",");
    groups = encodeURI(groups);
    withdrawn = encodeURI(withdrawn);
    withoutnotice = encodeURI(withoutnotice);
    $("#livesearch").load('template/qs-futuredayorals-returnlist.php?date='+date+'&dept='+dept+'&groups='+groups+'&withdrawn='+withdrawn+'&withoutnotice='+withoutnotice+'&together='+together+'&topicalsbyparty='+topicalsbyparty+'&outputList=true',function() {
        document.getElementById('loader').style.display = 'none';
        document.getElementById('togglemenu').style.display = 'inline';
        var h = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
        var listsize = h - 154;
        document.getElementById("livesearch").setAttribute("style","height:"+listsize+"px");
    });
}
	
function futuredayoralsload(num,date){
    document.getElementById('togglemenu').style.display = 'none';
    document.getElementById('loader').style.display = 'inline';
    if (!document.getElementById("photos-input").checked){
        var photos = 'Stock';
    } else {
        var photos = "screenshot";
    }
    var next = document.getElementById('next'+num).value;
    var prev = document.getElementById('prev'+num).value;
    console.log('Loading question: '+num+' next: '+next+' prev: '+prev);
    $("#contactCard").load('template/qs-futuredayorals-questioner.php?uin='+num+'&date='+date+'&photos='+photos+'&next='+next+'&prev='+prev,function() {
        document.getElementById('loader').style.display = 'none';
        document.getElementById('togglemenu').style.display = 'inline';
    });
    $('.active').removeClass('active');
    $('#q'+num).addClass("active");
}
	
function qsload(num,date){
    document.getElementById('togglemenu').style.display = 'none';
    document.getElementById('loader').style.display = 'inline';
    if (!document.getElementById("photos-input").checked){
        var photos = 'Stock';
    } else {
        var photos = "screenshot";
    }
    var next = document.getElementById('next'+num).value;
    var prev = document.getElementById('prev'+num).value;
    console.log('Loading question: '+num+' next: '+next+' prev: '+prev);
    $("#contactCard").load('template/qs-questioner.php?uin='+num+'&date='+date+'&photos='+photos+'&next='+next+'&prev='+prev,function() {
        document.getElementById('loader').style.display = 'none';
        document.getElementById('togglemenu').style.display = 'inline';
    });
    $('.active').removeClass('active');
    $('#q'+num).addClass("active");
}
function qsloadlords(id){
    document.getElementById('togglemenu').style.display = 'none';
    document.getElementById('loader').style.display = 'inline';
    console.log('Loading Lords Member: '+id);
    $("#contactCard").load('template/search-member.php?m='+id,function() {
        document.getElementById('loader').style.display = 'none';
        document.getElementById('togglemenu').style.display = 'inline';
    });
    $('.active').removeClass('active');
    $('#q'+id).addClass("active");
}
function qsloadquestions(date,dept,type){
    document.getElementById('togglemenu').style.display = 'none';
    document.getElementById('loader').style.display = 'inline';
    if (!document.getElementById("together-input").checked){
        var together = "together";
    } else {
        var together = "dont";
    }
    if (!document.getElementById("topicals-together").checked){
        var topicalsbyparty = "byparty";
    } else {
        var topicalsbyparty = "dont";
    }
    var groups = document.getElementById("groups-input").value;
    var withdrawn = document.getElementById("withdrawn-input").value;
    var withoutnotice = document.getElementById("withoutnotice-input").value;
    console.log('Loading '+type+' questions to '+dept+' on '+date+' using groups: '+groups+' and withdrawing (day): '+withdrawn+' withdrawing (before): '+withoutnotice+' grouped: '+together);
    groups = groups.replace(/[\r\n]+/g,",");
    groups = encodeURI(groups);
    withdrawn = encodeURI(withdrawn);
    withoutnotice = encodeURI(withoutnotice);
    $("#livesearch").load('template/qs-commonslistquestions.php?date='+date+'&type='+type+'&dept='+dept+'&groups='+groups+'&withdrawn='+withdrawn+'&withoutnotice='+withoutnotice+'&together='+together+'&topicalsbyparty='+topicalsbyparty,function() {
        document.getElementById('loader').style.display = 'none';
        document.getElementById('togglemenu').style.display = 'inline';
    });
}
function qsloadlordsquestions(){
	document.getElementById('togglemenu').style.display = 'none';
	document.getElementById('loader').style.display = 'inline';
	var chosenBusiness = document.getElementById("sect-input").value;
	if(chosenBusiness == "questions") {
		var urlend = "qs-lordslistquestions.php";
	} else {
		var urlend = 'qs-lordsspeakers.php?chosenBusiness='+chosenBusiness;
	}
	$("#livesearch").load('template/'+urlend,function() {
		document.getElementById('loader').style.display = 'none';
		document.getElementById('togglemenu').style.display = 'inline';
	});		
}
function qsloaddepts(date){
   $("#dept-input").load('template/qs-deptslist.php?date='+date);
   $("#type-input").load('template/qs-typelist.php?date='+date);
   console.log('Loading departments for: '+date);
}
function qsloadtypes(){
   var date = document.getElementById("date-input").value;
   var dept = encodeURI(document.getElementById("dept-input").value);
   $("#type-input").load('template/qs-typelist.php?date='+date+'&dept='+dept);
   console.log('Loading Question Types for: '+date+' to '+dept);
}

function qsloadsuggestedgroups(date,dept){
   $("#suggested-groups").load('template/qs-commonsgroupsauto.php?output=true&date='+date+'&dept='+dept);
   console.log('Loading suggested groups for: '+date+' to '+dept);
}	

function qsloadinitialquestions(){
    var date = document.getElementById("date-input").value;
    var dept = encodeURI(document.getElementById("dept-input").value);
    var type = encodeURI(document.getElementById("type-input").value);
    qsloadquestions(date,dept,type);
}
	
function qscheckforadvance(){
	if (document.getElementById("uselive").checked){ 
		if(!document.getElementById("currentuin")){
			console.log('No question loaded... waiting for user input');
		} else {
		    var date = document.getElementById("date-input").value;
			var currenuin = document.getElementById("currentuin").value;
			$("#currentlivequestiondiv").load('template/qs-currentquestion.php?date='+date, function() {
				var currentlivelogged = document.getElementById("currentlivequestion").value;
				console.log('Current Logged Question = '+currentlivelogged);
				if(currenuin !== currentlivelogged) {
					var toload = document.getElementById('next'+currentlivelogged).value;
					var newuin = document.getElementById("currentuin").value;
					if(newuin !== toload) {
						if(!document.getElementById('q'+toload)) {
							console.log('No more questions left to log');
						} else {
							qsload(toload,date);
							console.log('Loaded from logs');
						}
					}
				} else {
					//console.log('No new members logged...');
				}
			});
		}
		setTimeout(qscheckforadvance, 2500);			
	} else {
		console.log('Not moving questions on');
	}
}

function qsuseliveadvance(){
    if (document.getElementById("uselive").checked){ 
        console.log('Turning auto advance on');
        qscheckforadvance();
    } else {
        console.log('Turning auto advance off');
    }
}

function qsturnoffliveadvance(){
    console.log('Manually turning off auto advance');
    $('#uselive').bootstrapToggle('off');
}


function qsgotopicals() {
     document.getElementById('type-input').value='Topical';
     var date = document.getElementById("date-input").value;
     var dept = encodeURI(document.getElementById("dept-input").value); 
     var type = "Topical"; 
     qsloadquestions(date,dept,type);
}

function qsgosubstantive() {
     document.getElementById('type-input').value='Substantive';
     var date = document.getElementById("date-input").value;
     var dept = encodeURI(document.getElementById("dept-input").value); 
     var type = "Substantive"; 
     qsloadquestions(date,dept,type);
     
}
