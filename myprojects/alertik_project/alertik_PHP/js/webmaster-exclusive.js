if(typeof mx_run == "undefined") {
	function mx_run(){
		var g=function(i){ //get element
			return document.getElementById(i);
		}
		var a=function(i){ //add comma
			if (i<100) return i;
			return i.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		}
		if (document.location.hostname!="www.1gheroon.net"
			&&document.location.hostname!="www.2zaar.ir"
			&&document.location.hostname!="www.10shahi.ir"
			&&document.location.hostname!="www.2gheroon.ir") return;
		g("arzlive").style.display="block";
		g("mx_update").innerHTML=update;
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
mx_run();