var show = document.querySelector(".navbar-collapse");
var nv = document.querySelector(".navbar-expand-lg");

document.addEventListener("DOMContentLoaded", function() {
	document.getElementById("nav").addEventListener("click", function() {
		nv.classList.toggle("navbar-open");
		document.body.classList.toggle("lock");
		setTimeout(function(){
			if (show.classList.contains("show") == true && nv.classList.contains("navbar-open") == false) {
				show.classList.remove("show");
				document.body.classList.remove("lock");
			}
			if (show.classList.contains("show") == false && nv.classList.contains("navbar-open") == true) {
				nv.classList.remove("navbar-open");
				document.body.classList.remove("lock");
			}
		}, 500); 
	})
})

if (window.outerWidth < 992) {
	document.querySelector(".navbar-expand-lg").classList.toggle("fixed-top");
} 

window.addEventListener('resize', function(event) {
	if (window.outerWidth < 992) {
		if (document.querySelector(".navbar-expand-lg").classList.contains("fixed-top") == false) {
			document.querySelector(".navbar-expand-lg").classList.toggle("fixed-top");
		}
	} 
    if (window.outerWidth > 991) {
    	document.querySelector(".navbar-expand-lg").classList.remove("fixed-top");
		if (show.classList.contains("show") == true || nv.classList.contains("navbar-open") == true || document.body.classList.contains("lock")) {
			show.classList.remove("show");
			nv.classList.remove("navbar-open");
			document.body.classList.remove("lock");
		}
	}  
}, true);

let cbox = document.querySelectorAll(".navl");

cbox.forEach(navl => {
  navl.addEventListener('click', () => show.classList.remove("show"));
});

cbox.forEach(navl => {
  navl.addEventListener('click', () => nv.classList.remove("navbar-open"));
});

cbox.forEach(navl => {
  navl.addEventListener('click', () => document.body.classList.remove("lock"));
});