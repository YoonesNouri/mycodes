if(typeof mx_run == "undefined") {
	function link_removed(org_element){
		var remove=0;
		var element=org_element;
		if (element.offsetWidth<30||element.offsetHeight<8) return 1;
		while(element&&element.tagName.toLowerCase()!='body'){
			if (element.style.visibility=='hidden' || element.style.display=='none') return 1;
			element=element.parentNode;
		}
		element=org_element.getBoundingClientRect();
		if (element.top<-10||element.left<-10||element.right<-10||element.bottom<-10) return 1;
		return remove;
	}
	function mx_run(){
		var g=function(i){ //get element
			return document.getElementById(i);
		}
		var a=function(i){ //add comma			
			return i.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		}
		var link=g("mx_lnk");	
		if (link){
			if(link.href != "http://www.arzlive.com/") return;
			g("arzlive").style.display="block";		
			g("mx_update").innerHTML=update+" <a href=\"http://www.arzlive.com\">arzlive.com</a>";
			if (link_removed(link)) return;
			for (var i in last) { //i = item
				var e=g("v"+i);
				if (e) {
					e.innerHTML=a(last[i]);
					var n=change[i]; //n = number
					if (n==0) var s = "same"; //s = style
					else if (n<0) var s = "neg";
					else var s = "pos";
					var perc=Math.round(((n*100)/last[i])*100)/100;
					g("c"+i).className+=" "+s;
					g("c"+i).innerHTML=a(n)+" ("+perc+"%)";
				}
			}
		}
	}		
}
mx_run();
/*
setInterval('mx_reload();', 60000);
function mx_reload(){
	var localScript = document.createElement("script");
	localScript.type = "text/javascript";
	localScript.src = "http://www.mazanex.com/ext/p.js"; // Is it required? +new Date().getTime()
	document.body.appendChild(localScript);
	mx_run();
}
// Anonymous one
(function () {
    var callback = function() { 
		var localScript = document.createElement("script");
		localScript.type = "text/javascript";
		localScript.src = "http://www.mazanex.com/ext/p.js"; // Is it required? +new Date().getTime()
		document.body.appendChild(localScript);		
		mx_run();
    };
    window.setInterval(callback, 10);
})();

window.setInterval(function(){var a=document.createElement("script");a.type="text/javascript";a.src="http://www.mazanex.com/ext/p.js?"+new Date().getTime();document.body.appendChild(a);mx_run()},6E4);

// Anonymous with countdown counter
(function () {
    var callback = function() { 
		var counter=parseInt(document.getElementById("mx_counter").innerHTML);
		counter=counter-1;		
		if (counter==0){
			counter=60;
			var localScript = document.createElement("script");
			localScript.type = "text/javascript";
			localScript.src = "http://www.mazanex.com/ext/p.js"; // Is it required? +new Date().getTime()
			document.body.appendChild(localScript);		
			mx_run();
		}
		document.getElementById("mx_counter").innerHTML=counter;
    };
    window.setInterval(callback, 1000);
})();

<span id="mx_counter">60</span>

window.setInterval(function(){var a=parseInt(document.getElementById("mx_counter").innerHTML),a=a-1;if(0==a){var a=60,b=document.createElement("script");b.type="text/javascript";b.src="http://www.mazanex.com/ext/p.js";document.body.appendChild(b);mx_run()}document.getElementById("mx_counter").innerHTML=a},1E3);

*/