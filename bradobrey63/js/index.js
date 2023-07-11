//===(1st slider)===//

const slider = document.querySelector(".swiper-container");

var zed = (2.392 + (3 - 2.392) * ((window.outerWidth - 1024) / (1919 - 1024)));
var newZed = zed;

var gap = (34 + (47 - 34) * ((window.outerWidth - 1024) / (1919 - 1024)));
var newGap = gap;

var zed768 = 2.392;
var newZed768 = zed768;

var gap768 = (34 * 0.75 + (34 - 34 * 0.75) * ((window.outerWidth - 768) / (1023 - 768)));
var newGap768 = gap768;

var zed320 = (1.10835 + (1.1073 - 1.10835) * ((window.outerWidth - 320) / (767 - 320)));
var newZed320 = zed768;

var gap320 = (23.86 + (23.86 * 2.4 - 23.86) * ((window.outerWidth - 320) / (767 - 320)));
var newGap320 = gap320;

window.addEventListener('resize', function() {
    newZed = (2.392 + (3 - 2.392) * ((window.outerWidth - 1024) / (1919 - 1024)));
    return newZed;
}, true);

window.addEventListener('resize', function() {
    newGap = (34 + (47 - 34) * ((window.outerWidth - 1024) / (1919 - 1024)));
    return newGap;
}, true);

window.addEventListener('resize', function() {
    newGap768 = (34 * 0.75 + (34 - 34 * 0.75) * ((window.outerWidth - 768) / (1023 - 768)));
    return newGap768;
}, true);

window.addEventListener('resize', function() {
    newZed320 = (1.10835 + (1.1073 - 1.10835) * ((window.outerWidth - 320) / (767 - 320)));
    return newZed320;
}, true);

window.addEventListener('resize', function() {
    newGap320 = (23.86 + (23.86 * 2.4 - 23.86) * ((window.outerWidth - 320) / (767 - 320)));
    return newGap320;
}, true);

let mySwiper = new Swiper(slider, {
	navigation: {
		nextEl: '.swiper-button-next1',
		prevEl: '.swiper-button-prev1'
	},
	slidesPerView: 3,
	spaceBetween: 47,
	centeredSlides: false,
	pagination: {
		el: '.swiper-pagination1',
		type: 'fraction',
		renderFraction: function (currentClass, totalClass) {
			return '<span class="' + currentClass + '" id="x1"></span> / <span class="' + totalClass + '" id="y1"></span>';
		},
	},
	breakpoints: {
		320: {
			centeredSlides: true,
			slidesPerView: zed320,
			spaceBetween: gap320,
		},
		768: {
			centeredSlides: true,
			slidesPerView: zed768,
			spaceBetween: gap768,
		},
		1024: {
			centeredSlides: true,
			slidesPerView: zed,
			spaceBetween: gap,
		},
		1025: {
			slidesPerView: zed,
			spaceBetween: gap,
			centeredSlides: false,
		},
		1920: {
			slidesPerView: 3,
			spaceBetween: 47,
		},
	}
});

window.addEventListener('resize', function() {
	mySwiper.destroy();
	zed = (2.392 + (3 - 2.392) * ((window.innerWidth - 1024) / (1919 - 1024)));
    return zed;
}, true);

window.addEventListener('resize', function() {
    let mySwiper = new Swiper(slider, {
		navigation: {
			nextEl: '.swiper-button-next1',
			prevEl: '.swiper-button-prev1'
		},
		slidesPerView: 3,
		spaceBetween: 47,
		centeredSlides: false,
		pagination: {
			el: '.swiper-pagination1',
			type: 'fraction',
			renderFraction: function (currentClass, totalClass) {
				return '<span class="' + currentClass + '" id="x1"></span> / <span class="' + totalClass + '" id="y1"></span>';
			},
		},
		breakpoints: {
			320: {
				centeredSlides: true,
				slidesPerView: newZed320,
				spaceBetween: newGap320,
			},
			768: {
				centeredSlides: true,
				slidesPerView: newZed768,
				spaceBetween: newGap768,
			},
			1024: {
				centeredSlides: true,
				slidesPerView: newZed,
				spaceBetween: newGap,
			},
			1025: {
				slidesPerView: newZed,
				spaceBetween: newGap,
				centeredSlides: false,
			},
			1920: {
				slidesPerView: 3,
				spaceBetween: 47,
			},
		}
	});

	window.addEventListener('resize', function() {
		mySwiper.destroy();
	}, true);
}, true);

var prev1 = document.getElementById("prev1");
var next1 = document.getElementById("next1");

var pagcurrent = document.getElementById("x1");
var pagtotal = document.getElementById("y1");
var x1 = pagcurrent.innerHTML;
var y1 = pagtotal.innerHTML;
if (x1 != '1') {
	prev1.style.background = "url('../img/Icons-PNGx3/Button-2.png')";
	prev1.style.backgroundSize = "contain";
	prev1.style.backgroundRepeat = "no-repeat";
};
if (x1 == '1') {
	prev1.style.background = "url('../img/Icons-PNGx3/Button.png')";
	prev1.style.backgroundSize = "contain";
	prev1.style.backgroundRepeat = "no-repeat";
};
if (x1 == y1) {
	next1.style.background = "url('../img/Icons-PNGx3/Button-1.png')";
	next1.style.backgroundSize = "contain";
	next1.style.backgroundRepeat = "no-repeat";
};
if (x1 != y1) {
	next1.style.background = "url('../img/Icons-PNGx3/Button-3.png')";
	next1.style.backgroundSize = "contain";
	next1.style.backgroundRepeat = "no-repeat";
};

addEventListener("mousemove", function() {
	var pagcurrent = document.getElementById("x1");
	var pagtotal = document.getElementById("y1");
	var x1 = pagcurrent.innerHTML;
	var y1 = pagtotal.innerHTML;
	if (x1 != '1') {
		prev1.style.background = "url('../img/Icons-PNGx3/Button-2.png')";
		prev1.style.backgroundSize = "contain";
		prev1.style.backgroundRepeat = "no-repeat";
	};
	if (x1 == y1) {
		next1.style.background = "url('../img/Icons-PNGx3/Button-1.png')";
		next1.style.backgroundSize = "contain";
		next1.style.backgroundRepeat = "no-repeat";
	};
})

addEventListener("mousemove", function() {
	var pagcurrent = document.getElementById("x1");
	var pagtotal = document.getElementById("y1");
	var x1 = pagcurrent.innerHTML;
	var y1 = pagtotal.innerHTML;
	if (x1 == '1') {
		prev1.style.background = "url('../img/Icons-PNGx3/Button.png')";
		prev1.style.backgroundSize = "contain";
		prev1.style.backgroundRepeat = "no-repeat";
	};
	if (x1 != y1) {
		next1.style.background = "url('../img/Icons-PNGx3/Button-3.png')";
		next1.style.backgroundSize = "contain";
		next1.style.backgroundRepeat = "no-repeat";
	};
})

addEventListener("touchend", function() {
	var pagcurrent = document.getElementById("x1");
	var pagtotal = document.getElementById("y1");
	var x1 = pagcurrent.innerHTML;
	var y1 = pagtotal.innerHTML;
	if (x1 != 1) {
		prev1.style.background = "url('../img/Icons-PNGx3/Button-2.png')";
		prev1.style.backgroundSize = "contain";
		prev1.style.backgroundRepeat = "no-repeat";
	};
	if (x1 == y1) {
		next1.style.background = "url('../img/Icons-PNGx3/Button-1.png')";
		next1.style.backgroundSize = "contain";
		next1.style.backgroundRepeat = "no-repeat";
	};
})

addEventListener("touchend", function() {
	var pagcurrent = document.getElementById("x1");
	var pagtotal = document.getElementById("y1");
	var x1 = pagcurrent.innerHTML;
	var y1 = pagtotal.innerHTML;
	if (x1 == 1) {
		prev1.style.background = "url('../img/Icons-PNGx3/Button.png')";
		prev1.style.backgroundSize = "contain";
		prev1.style.backgroundRepeat = "no-repeat";
	};
	if (x1 != y1) {
		next1.style.background = "url('../img/Icons-PNGx3/Button-3.png')";
		next1.style.backgroundSize = "contain";
		next1.style.backgroundRepeat = "no-repeat";
	};
})

next1.addEventListener("click", function() {
	var pagcurrent = document.getElementById("x1");
	var pagtotal = document.getElementById("y1");
	var x1 = pagcurrent.innerHTML;
	var y1 = pagtotal.innerHTML;
	if (x1 != '1') {
		prev1.style.background = "url('../img/Icons-PNGx3/Button-2.png')";
		prev1.style.backgroundSize = "contain";
		prev1.style.backgroundRepeat = "no-repeat";
	};
	if (x1 == y1) {
		next1.style.background = "url('../img/Icons-PNGx3/Button-1.png')";
		next1.style.backgroundSize = "contain";
		next1.style.backgroundRepeat = "no-repeat";
	};
})

prev1.addEventListener("click", function() {
	var pagcurrent = document.getElementById("x1");
	var pagtotal = document.getElementById("y1");
	var x1 = pagcurrent.innerHTML;
	var y1 = pagtotal.innerHTML;
	if (x1 == '1') {
		prev1.style.background = "url('../img/Icons-PNGx3/Button.png')";
		prev1.style.backgroundSize = "contain";
		prev1.style.backgroundRepeat = "no-repeat";
	};
	if (x1 != y1) {
		next1.style.background = "url('../img/Icons-PNGx3/Button-3.png')";
		next1.style.backgroundSize = "contain";
		next1.style.backgroundRepeat = "no-repeat";
	};
})

window.addEventListener('resize', function() {
    var prev1 = document.getElementById("prev1");
	var next1 = document.getElementById("next1");

	var pagcurrent = document.getElementById("x1");
	var pagtotal = document.getElementById("y1");
	var x1 = pagcurrent.innerHTML;
	var y1 = pagtotal.innerHTML;
	if (x1 != '1') {
		prev1.style.background = "url('../img/Icons-PNGx3/Button-2.png')";
		prev1.style.backgroundSize = "contain";
		prev1.style.backgroundRepeat = "no-repeat";
	};
	if (x1 == '1') {
		prev1.style.background = "url('../img/Icons-PNGx3/Button.png')";
		prev1.style.backgroundSize = "contain";
		prev1.style.backgroundRepeat = "no-repeat";
	};
	if (x1 == y1) {
		next1.style.background = "url('../img/Icons-PNGx3/Button-1.png')";
		next1.style.backgroundSize = "contain";
		next1.style.backgroundRepeat = "no-repeat";
	};
	if (x1 != y1) {
		next1.style.background = "url('../img/Icons-PNGx3/Button-3.png')";
		next1.style.backgroundSize = "contain";
		next1.style.backgroundRepeat = "no-repeat";
	};

	addEventListener("mousemove", function() {
		var pagcurrent = document.getElementById("x1");
		var pagtotal = document.getElementById("y1");
		var x1 = pagcurrent.innerHTML;
		var y1 = pagtotal.innerHTML;
		if (x1 != '1') {
			prev1.style.background = "url('../img/Icons-PNGx3/Button-2.png')";
			prev1.style.backgroundSize = "contain";
			prev1.style.backgroundRepeat = "no-repeat";
		};
		if (x1 == y1) {
			next1.style.background = "url('../img/Icons-PNGx3/Button-1.png')";
			next1.style.backgroundSize = "contain";
			next1.style.backgroundRepeat = "no-repeat";
		};
	})

	addEventListener("mousemove", function() {
		var pagcurrent = document.getElementById("x1");
		var pagtotal = document.getElementById("y1");
		var x1 = pagcurrent.innerHTML;
		var y1 = pagtotal.innerHTML;
		if (x1 == '1') {
			prev1.style.background = "url('../img/Icons-PNGx3/Button.png')";
			prev1.style.backgroundSize = "contain";
			prev1.style.backgroundRepeat = "no-repeat";
		};
		if (x1 != y1) {
			next1.style.background = "url('../img/Icons-PNGx3/Button-3.png')";
			next1.style.backgroundSize = "contain";
			next1.style.backgroundRepeat = "no-repeat";
		};
	})

	addEventListener("touchend", function() {
		var pagcurrent = document.getElementById("x1");
		var pagtotal = document.getElementById("y1");
		var x1 = pagcurrent.innerHTML;
		var y1 = pagtotal.innerHTML;
		if (x1 != 1) {
			prev1.style.background = "url('../img/Icons-PNGx3/Button-2.png')";
			prev1.style.backgroundSize = "contain";
			prev1.style.backgroundRepeat = "no-repeat";
		};
		if (x1 == y1) {
			next1.style.background = "url('../img/Icons-PNGx3/Button-1.png')";
			next1.style.backgroundSize = "contain";
			next1.style.backgroundRepeat = "no-repeat";
		};
	})

	addEventListener("touchend", function() {
		var pagcurrent = document.getElementById("x1");
		var pagtotal = document.getElementById("y1");
		var x1 = pagcurrent.innerHTML;
		var y1 = pagtotal.innerHTML;
		if (x1 == 1) {
			prev1.style.background = "url('../img/Icons-PNGx3/Button.png')";
			prev1.style.backgroundSize = "contain";
			prev1.style.backgroundRepeat = "no-repeat";
		};
		if (x1 != y1) {
			next1.style.background = "url('../img/Icons-PNGx3/Button-3.png')";
			next1.style.backgroundSize = "contain";
			next1.style.backgroundRepeat = "no-repeat";
		};
	})

	next1.addEventListener("click", function() {
		var pagcurrent = document.getElementById("x1");
		var pagtotal = document.getElementById("y1");
		var x1 = pagcurrent.innerHTML;
		var y1 = pagtotal.innerHTML;
		if (x1 != '1') {
			prev1.style.background = "url('../img/Icons-PNGx3/Button-2.png')";
			prev1.style.backgroundSize = "contain";
			prev1.style.backgroundRepeat = "no-repeat";
		};
		if (x1 == y1) {
			next1.style.background = "url('../img/Icons-PNGx3/Button-1.png')";
			next1.style.backgroundSize = "contain";
			next1.style.backgroundRepeat = "no-repeat";
		};
	})

	prev1.addEventListener("click", function() {
		var pagcurrent = document.getElementById("x1");
		var pagtotal = document.getElementById("y1");
		var x1 = pagcurrent.innerHTML;
		var y1 = pagtotal.innerHTML;
		if (x1 == '1') {
			prev1.style.background = "url('../img/Icons-PNGx3/Button.png')";
			prev1.style.backgroundSize = "contain";
			prev1.style.backgroundRepeat = "no-repeat";
		};
		if (x1 != y1) {
			next1.style.background = "url('../img/Icons-PNGx3/Button-3.png')";
			next1.style.backgroundSize = "contain";
			next1.style.backgroundRepeat = "no-repeat";
		};
	})
}, true);

//=======================================================================================//
//=======================================================================================//
//=======================================================================================//

//===(2nd slider)===//

var slider1 = null;

function initSwiper1() {
	var changeSwiper = document.getElementById('change');
	
	if (window.innerWidth > 767) {
		if (document.getElementById('change').classList.contains('swiper-container11')) {
		    document.getElementById('change').classList.remove('swiper-container11');
		}

		if (document.getElementById('change').classList.contains('swiper-container1') === false) {
		    document.getElementById('change').classList.add('swiper-container1');
		}

		var sc1Gap1024 = (82 + (120 - 82) * ((window.innerWidth - 1024) / (1474 - 1024)));
		var sc1Gap768 = (82 * 0.75 + (82 - 82 * 0.75) * ((window.innerWidth - 768) / (1023 - 768)));

		slider1 = new Swiper('.swiper-container1', {
			navigation: {
				nextEl: '.swiper-button-next11',
			},
			direction: "vertical",
			initialSlide: 0,
			spaceBetween: 120,
			slidesPerView: 2,
			loop: true,
			breakpoints: {
				768: {
					spaceBetween: sc1Gap768,
				},
				1024: {
					spaceBetween: sc1Gap1024,
				},
				1475: {
					spaceBetween: 120,
				},
			}
		});

		var mCol = document.querySelectorAll('.masters__main-column');
		var mColLength = mCol.length;

		if (mColLength === 3) {
			var mRow = document.querySelector('.masters__main-row.swiper-wrapper');
			mRow.classList.add('dis');

			var sc1 = document.querySelector('.swiper-container1');
			var marginSC1 = getComputedStyle(document.querySelector('.masters__main-column.swiper-slide.swiper-slide-active')).marginBottom;
			var heightSC1 = document.querySelectorAll('.main-column-itembox');
			var heightSCol1 = document.querySelectorAll('.sub-column');
			var heightSCol2 = document.querySelectorAll('.sub-column2');
			var heightSCol3 = document.querySelectorAll('.sub-column3');
			var arr2 = [];

			for (var iii2=0; iii2<heightSCol1.length; iii2++) {
				arr2.push(heightSCol1[iii2].offsetHeight);
			}

			for (var iii21=0; iii21<heightSCol2.length; iii21++) {
				arr2.push(heightSCol2[iii21].offsetHeight);
			}

			for (var iii22=0; iii22<heightSCol3.length; iii22++) {
				arr2.push(heightSCol3[iii22].offsetHeight);
			}

			var maxarr2 = Math.max.apply(null, arr2);

			for (var iii3=0; iii3<heightSC1.length; iii3++) {
				heightSC1[iii3].style.height = maxarr2 + 'px';
			}

			var newHeightSC1 = maxarr2;
			var numMarginSC1 = marginSC1.replace('px', '');
			var newMarginSC1 = Number(numMarginSC1);
			var sumHeightSC1 = newHeightSC1;
			var dis = -1 * (newHeightSC1 + newMarginSC1);

			sc1.style.maxHeight = sumHeightSC1 + 'px';

			document.documentElement.style.setProperty("--mH", dis + 'px');

		}
		else {
			var sc1 = document.querySelector('.swiper-container1');
			var marginSC1 = getComputedStyle(document.querySelector('.masters__main-column.swiper-slide.swiper-slide-active')).marginBottom;
			var heightSC1 = document.querySelectorAll('.main-column-itembox');
			var heightSCol1 = document.querySelectorAll('.sub-column');
			var heightSCol2 = document.querySelectorAll('.sub-column2');
			var heightSCol3 = document.querySelectorAll('.sub-column3');
			var arr2 = [];

			for (var iii2=0; iii2<heightSCol1.length; iii2++) {
				arr2.push(heightSCol1[iii2].offsetHeight);
			}

			for (var iii21=0; iii21<heightSCol2.length; iii21++) {
				arr2.push(heightSCol2[iii21].offsetHeight);
			}

			for (var iii22=0; iii22<heightSCol3.length; iii22++) {
				arr2.push(heightSCol3[iii22].offsetHeight);
			}

			var maxarr2 = Math.max.apply(null, arr2);

			for (var iii3=0; iii3<heightSC1.length; iii3++) {
				heightSC1[iii3].style.height = maxarr2 + 'px';
			}

			var newHeightSC1 = maxarr2;
			var numMarginSC1 = marginSC1.replace('px', '');
			var newMarginSC1 = Number(numMarginSC1);
			var sumHeightSC1 = newHeightSC1 * 2 + newMarginSC1;

			sc1.style.maxHeight = sumHeightSC1 + 'px';
		}

		var pag11  = document.getElementsByClassName('masters__main-column');

		for(var i11=0;i11<pag11.length; i11++) {
		    var z11 = pag11[i11].getAttribute('data-swiper-slide-index');
		    var zz11 = Number(z11);
		    zzz11 = zz11 + 1;
		    var y11 = document.getElementsByClassName('swiper-pagination11');
		    if (zzz11<10){
		    	y11[i11].innerText = '0' + zzz11;
		    }
		    else {
		    	y11[i11].innerText = zzz11;
		    } 
		}
	}
	else {

		var mRowMob = document.querySelector('.masters__main-row.swiper-wrapper');
		
		if (mRowMob.classList.contains('dis')) {
			mRowMob.classList.remove('dis');
		}

		if (document.getElementById('change').classList.contains('swiper-container1')) {
		    document.getElementById('change').classList.remove('swiper-container1');
		}

		if (document.getElementById('change').classList.contains('swiper-container11') === false) {
		    document.getElementById('change').classList.add('swiper-container11');
		}

		var slider11Gap320 = (20 + (40 - 20) * ((window.innerWidth - 320) / (767 - 320)));

		slider1 = new Swiper('.swiper-container11', {
			navigation: {
				nextEl: '.swiper-button-next1-mob',
				prevEl: '.swiper-button-prev1-mob'
			},
			spaceBetween: slider11Gap320,
			slidesPerView: 1,
			pagination: {
				el: '.swiper-pagination1-mob',
				type: 'fraction',
				renderFraction: function (currentClass, totalClass) {
					return '<span class="' + currentClass + '" id="x11"></span> / <span class="' + totalClass + '" id="y11"></span>';
				},
			},

		});

		var prev1mob = document.getElementById("prev1mob");
		var next1mob = document.getElementById("next1mob");

		var pagcurrent11 = document.getElementById("x11");
		var pagtotal11 = document.getElementById("y11");
		var x11 = pagcurrent11.innerHTML;
		var y11 = pagtotal11.innerHTML;
		if (x11 != '1') {
			prev1mob.style.background = "url('../img/Icons-PNGx3/Button-2.png')";
			prev1mob.style.backgroundSize = "contain";
			prev1mob.style.backgroundRepeat = "no-repeat";
		};
		if (x11 == '1') {
			prev1mob.style.background = "url('../img/Icons-PNGx3/Button.png')";
			prev1mob.style.backgroundSize = "contain";
			prev1mob.style.backgroundRepeat = "no-repeat";
		};
		if (x11 == y11) {
			next1mob.style.background = "url('../img/Icons-PNGx3/Button-1.png')";
			next1mob.style.backgroundSize = "contain";
			next1mob.style.backgroundRepeat = "no-repeat";
		};
		if (x11 != y11) {
			next1mob.style.background = "url('../img/Icons-PNGx3/Button-3.png')";
			next1mob.style.backgroundSize = "contain";
			next1mob.style.backgroundRepeat = "no-repeat";
		};

		addEventListener("mousemove", function() {
			var pagcurrent11 = document.getElementById("x11");
			var pagtotal11 = document.getElementById("y11");
			var x11 = pagcurrent11.innerHTML;
			var y11 = pagtotal11.innerHTML;
			if (x11 != '1') {
				prev1mob.style.background = "url('../img/Icons-PNGx3/Button-2.png')";
				prev1mob.style.backgroundSize = "contain";
				prev1mob.style.backgroundRepeat = "no-repeat";
			};
			if (x11 == y11) {
				next1mob.style.background = "url('../img/Icons-PNGx3/Button-1.png')";
				next1mob.style.backgroundSize = "contain";
				next1mob.style.backgroundRepeat = "no-repeat";
			};
		})

		addEventListener("mousemove", function() {
			var pagcurrent11 = document.getElementById("x11");
			var pagtotal11 = document.getElementById("y11");
			var x11 = pagcurrent11.innerHTML;
			var y11 = pagtotal11.innerHTML;
			if (x11 == '1') {
				prev1mob.style.background = "url('../img/Icons-PNGx3/Button.png')";
				prev1mob.style.backgroundSize = "contain";
				prev1mob.style.backgroundRepeat = "no-repeat";
			};
			if (x11 != y11) {
				next1mob.style.background = "url('../img/Icons-PNGx3/Button-3.png')";
				next1mob.style.backgroundSize = "contain";
				next1mob.style.backgroundRepeat = "no-repeat";
			};
		})

		addEventListener("touchend", function() {
			var pagcurrent11 = document.getElementById("x11");
			var pagtotal11 = document.getElementById("y11");
			var x11 = pagcurrent11.innerHTML;
			var y11 = pagtotal11.innerHTML;
			if (x11 != 1) {
				prev1mob.style.background = "url('../img/Icons-PNGx3/Button-2.png')";
				prev1mob.style.backgroundSize = "contain";
				prev1mob.style.backgroundRepeat = "no-repeat";
			};
			if (x11 == y11) {
				next1mob.style.background = "url('../img/Icons-PNGx3/Button-1.png')";
				next1mob.style.backgroundSize = "contain";
				next1mob.style.backgroundRepeat = "no-repeat";
			};
		})

		addEventListener("touchend", function() {
			var pagcurrent11 = document.getElementById("x11");
			var pagtotal11 = document.getElementById("y11");
			var x11 = pagcurrent11.innerHTML;
			var y11 = pagtotal11.innerHTML;
			if (x11 == 1) {
				prev1mob.style.background = "url('../img/Icons-PNGx3/Button.png')";
				prev1mob.style.backgroundSize = "contain";
				prev1mob.style.backgroundRepeat = "no-repeat";
			};
			if (x11 != y11) {
				next1mob.style.background = "url('../img/Icons-PNGx3/Button-3.png')";
				next1mob.style.backgroundSize = "contain";
				next1mob.style.backgroundRepeat = "no-repeat";
			};
		})

		next1mob.addEventListener("click", function() {
			var pagcurrent11 = document.getElementById("x11");
			var pagtotal11 = document.getElementById("y11");
			var x11 = pagcurrent11.innerHTML;
			var y11 = pagtotal11.innerHTML;
			if (x11 != '1') {
				prev1mob.style.background = "url('../img/Icons-PNGx3/Button-2.png')";
				prev1mob.style.backgroundSize = "contain";
				prev1mob.style.backgroundRepeat = "no-repeat";
			};
			if (x11 == y11) {
				next1mob.style.background = "url('../img/Icons-PNGx3/Button-1.png')";
				next1mob.style.backgroundSize = "contain";
				next1mob.style.backgroundRepeat = "no-repeat";
			};
		})

		prev1mob.addEventListener("click", function() {
			var pagcurrent11 = document.getElementById("x11");
			var pagtotal11 = document.getElementById("y11");
			var x11 = pagcurrent11.innerHTML;
			var y11 = pagtotal11.innerHTML;
			if (x11 == '1') {
				prev1mob.style.background = "url('../img/Icons-PNGx3/Button.png')";
				prev1mob.style.backgroundSize = "contain";
				prev1mob.style.backgroundRepeat = "no-repeat";
			};
			if (x11 != y11) {
				next1mob.style.background = "url('../img/Icons-PNGx3/Button-3.png')";
				next1mob.style.backgroundSize = "contain";
				next1mob.style.backgroundRepeat = "no-repeat";
			};
		})
	}
}

var timer;

window.addEventListener('resize', function () {	
  slider1.destroy();
  if (timer) {
    clearTimeout(timer);
  }

  	timer = setTimeout(initSwiper1, 200);

});

initSwiper1();

//=======================================================================================//
//=======================================================================================//
//=======================================================================================//

//===(3rd slider)===//

var slider2 = null;

function initSwiper2() {

	var sc2Slides1024 = (2.4 + (3 - 2.4) * ((window.innerWidth - 1024) / (1919 - 1024)));
	var sc2Gap1024 = (21.1 + (40 - 21.1) * ((window.innerWidth - 1024) / (1919 - 1024)));
	var sc2Slides768 = (2.4 + (2.4 - 2.4) * ((window.innerWidth - 768) / (1023 - 768)));
	var sc2Gap768 = (21.1 * 0.75 + (21.1 - 21.1 * 0.75) * ((window.innerWidth - 768) / (1023 - 768)));
	var sc2Gap320 = (20 + (43 - 20) * ((window.innerWidth - 320) / (768 - 320)));

	slider2 = new Swiper('.swiper-container2', {
		navigation: {
			nextEl: '.swiper-button-next2',
			prevEl: '.swiper-button-prev2'
		},
		slidesPerView: 3,
		spaceBetween: 40,
		pagination: {
			el: '.swiper-pagination2',
			type: 'fraction',
			renderFraction: function (currentClass, totalClass) {
				return '<span class="' + currentClass + '" id="x2"></span> / <span class="' + totalClass + '" id="y2"></span>';
			},
		},
		breakpoints: {
			320: {
				centeredSlides: true,
				slidesPerView: 'auto',
				spaceBetween: sc2Gap320,
			},
			768: {
				centeredSlides: true,
				slidesPerView: sc2Slides768,
				spaceBetween: sc2Gap768,
			},
			1024: {
				centeredSlides: true,
				slidesPerView: sc2Slides1024,
				spaceBetween: sc2Gap1024,
			},
			1025: {
				centeredSlides: false,
				slidesPerView: sc2Slides1024,
				spaceBetween: sc2Gap1024,
			},
			1920: {
				slidesPerView: 3,
				spaceBetween: 40,
			},
		}
	});

	var prev2 = document.getElementById("prev2");
	var next2 = document.getElementById("next2");

	var pagcurrent2 = document.getElementById("x2");
	var pagtotal2 = document.getElementById("y2");
	var x2 = pagcurrent2.innerHTML;
	var y2 = pagtotal2.innerHTML;
	if (x2 != '1') {
		prev2.style.background = "url('../img/Icons-PNGx3/Button-2.png')";
		prev2.style.backgroundSize = "contain";
		prev2.style.backgroundRepeat = "no-repeat";
	};
	if (x2 == '1') {
		prev2.style.background = "url('../img/Icons-PNGx3/Button.png')";
		prev2.style.backgroundSize = "contain";
		prev2.style.backgroundRepeat = "no-repeat";
	};
	if (x2 == y2) {
		next2.style.background = "url('../img/Icons-PNGx3/Button-1.png')";
		next2.style.backgroundSize = "contain";
		next2.style.backgroundRepeat = "no-repeat";
	};
	if (x2 != y2) {
		next2.style.background = "url('../img/Icons-PNGx3/Button-3.png')";
		next2.style.backgroundSize = "contain";
		next2.style.backgroundRepeat = "no-repeat";
	};

	addEventListener("mousemove", function() {
		var pagcurrent2 = document.getElementById("x2");
		var pagtotal2 = document.getElementById("y2");
		var x2 = pagcurrent2.innerHTML;
		var y2 = pagtotal2.innerHTML;
		if (x2 != '1') {
			prev2.style.background = "url('../img/Icons-PNGx3/Button-2.png')";
			prev2.style.backgroundSize = "contain";
			prev2.style.backgroundRepeat = "no-repeat";
		};
		if (x2 == y2) {
			next2.style.background = "url('../img/Icons-PNGx3/Button-1.png')";
			next2.style.backgroundSize = "contain";
			next2.style.backgroundRepeat = "no-repeat";
		};
	})

	addEventListener("mousemove", function() {
		var pagcurrent2 = document.getElementById("x2");
		var pagtotal2 = document.getElementById("y2");
		var x2 = pagcurrent2.innerHTML;
		var y2 = pagtotal2.innerHTML;
		if (x2 == '1') {
			prev2.style.background = "url('../img/Icons-PNGx3/Button.png')";
			prev2.style.backgroundSize = "contain";
			prev2.style.backgroundRepeat = "no-repeat";
		};
		if (x2 != y2) {
			next2.style.background = "url('../img/Icons-PNGx3/Button-3.png')";
			next2.style.backgroundSize = "contain";
			next2.style.backgroundRepeat = "no-repeat";
		};
	})

	addEventListener("touchend", function() {
		var pagcurrent2 = document.getElementById("x2");
		var pagtotal2 = document.getElementById("y2");
		var x2 = pagcurrent2.innerHTML;
		var y2 = pagtotal2.innerHTML;
		if (x2 != 1) {
			prev2.style.background = "url('../img/Icons-PNGx3/Button-2.png')";
			prev2.style.backgroundSize = "contain";
			prev2.style.backgroundRepeat = "no-repeat";
		};
		if (x2 == y2) {
			next2.style.background = "url('../img/Icons-PNGx3/Button-1.png')";
			next2.style.backgroundSize = "contain";
			next2.style.backgroundRepeat = "no-repeat";
		};
	})

	addEventListener("touchend", function() {
		var pagcurrent2 = document.getElementById("x2");
		var pagtotal2 = document.getElementById("y2");
		var x2 = pagcurrent2.innerHTML;
		var y2 = pagtotal2.innerHTML;
		if (x2 == 1) {
			prev2.style.background = "url('../img/Icons-PNGx3/Button.png')";
			prev2.style.backgroundSize = "contain";
			prev2.style.backgroundRepeat = "no-repeat";
		};
		if (x2 != y2) {
			next2.style.background = "url('../img/Icons-PNGx3/Button-3.png')";
			next2.style.backgroundSize = "contain";
			next2.style.backgroundRepeat = "no-repeat";
		};
	})

	next2.addEventListener("click", function() {
		var pagcurrent2 = document.getElementById("x2");
		var pagtotal2 = document.getElementById("y2");
		var x2 = pagcurrent2.innerHTML;
		var y2 = pagtotal2.innerHTML;
		if (x2 != '1') {
			prev2.style.background = "url('../img/Icons-PNGx3/Button-2.png')";
			prev2.style.backgroundSize = "contain";
			prev2.style.backgroundRepeat = "no-repeat";
		};
		if (x2 == y2) {
			next2.style.background = "url('../img/Icons-PNGx3/Button-1.png')";
			next2.style.backgroundSize = "contain";
			next2.style.backgroundRepeat = "no-repeat";
		};
	})

	prev2.addEventListener("click", function() {
		var pagcurrent2 = document.getElementById("x2");
		var pagtotal2 = document.getElementById("y2");
		var x2 = pagcurrent2.innerHTML;
		var y2 = pagtotal2.innerHTML;
		if (x2 == '1') {
			prev2.style.background = "url('../img/Icons-PNGx3/Button.png')";
			prev2.style.backgroundSize = "contain";
			prev2.style.backgroundRepeat = "no-repeat";
		};
		if (x2 != y2) {
			next2.style.background = "url('../img/Icons-PNGx3/Button-3.png')";
			next2.style.backgroundSize = "contain";
			next2.style.backgroundRepeat = "no-repeat";
		};
	})
}

var timer2;

window.addEventListener('resize', function () {	
  slider2.destroy();
  if (timer2) {
    clearTimeout(timer2);
  }

  	timer2 = setTimeout(initSwiper2, 200);

});

initSwiper2();

//=======================================================================================//
//=======================================================================================//
//=======================================================================================//

//===(4th slider)===//

var slider3 = null;

function initSwiper3() {

	var sc3Slides1024 = (3.4109 + (4.3385 - 3.4109) * ((window.innerWidth - 1024) / (1919 - 1024)));
	var sc3Gap1024 = (10.2 + (15 - 10.2) * ((window.innerWidth - 1024) / (1919 - 1024)));
	var sc3Slides768 = (3.4109 + (3.4109 - 3.4109) * ((window.innerWidth - 768) / (1023 - 768)));
	var sc3Gap768 = (10.2 * 0.75 + (10.2 - 10.2 * 0.75) * ((window.innerWidth - 768) / (1023 - 768)));
	var sc3Slides320 = (1.11575 + (1.1143 - 1.11575) * ((window.innerWidth - 320) / (767 - 320)));
	var sc3Gap320 = (9.96 + (9.96 * 2.4 - 9.96) * ((window.innerWidth - 320) / (767 - 320)));
	
	slider3 = new Swiper('.swiper-container3', {
		slidesPerView: 4.3385,
		spaceBetween: 15,
		freeMode: true,
		breakpoints: {
			320: {
				slidesPerView: sc3Slides320,
				spaceBetween: sc3Gap320,
				touchRatio: 0.75,
			},
			768: {
				slidesPerView: sc3Slides768,
				spaceBetween: sc3Gap768,
				touchRatio: 1,
			},
			1024: {
				slidesPerView: sc3Slides1024,
				spaceBetween: sc3Gap1024,
			},
			1920: {
				slidesPerView: 4.3385,
				spaceBetween: 15,
			},
		}
	});
}

var timer3;

window.addEventListener('resize', function () {	
  slider3.destroy();
  if (timer3) {
    clearTimeout(timer3);
  }

  	timer3 = setTimeout(initSwiper3, 200);

});

initSwiper3();

//=======================================================================================//
//=======================================================================================//
//=======================================================================================//

function init() {
	var map = new ymaps.Map("map", {
		center: [53.199622150142254,50.12655447486877],
		zoom: 17,
	});

	let placemark = new ymaps.Placemark([53.199622150142254,50.12655447486877], {
		balloonContentHeader: 'Bradobrey63',
		balloonContentBody: 'Мужской барбершоп',
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'https://cdn-icons-png.flaticon.com/512/2776/2776067.png',
		iconImageSize: [40, 40],
		iconImageOffset: [-20, -35]
	});

	map.geoObjects.add(placemark);

	map.controls.remove('geolocationControl'); // удаляем геолокацию
	map.controls.remove('searchControl'); // удаляем поиск
	map.controls.remove('trafficControl'); // удаляем контроль трафика
	// map.controls.remove('typeSelector'); // удаляем тип
	map.controls.remove('fullscreenControl'); // удаляем кнопку перехода в полноэкранный режим
	// map.controls.remove('zoomControl'); // удаляем контрол зуммирования
	map.controls.remove('rulerControl'); // удаляем контрол правил
}

ymaps.ready(init);

const slider4 = document.querySelector(".swiper-container4");

let mySwiper4 = new Swiper(slider4, {
	navigation: {
		nextEl: '.swiper-button-next4', 
		prevEl: '.swiper-button-prev4'
	},
	slidesPerView: 1,
	spaceBetween: 295,
	loop: true,
	autoplay: {
		delay: 10000,
		stopOnLastSlide: false,
		disableOnInteraction: false,
	},
	speed: 800,
	pagination: {
		el: '.swiper-pagination4',
		type: 'fraction',
		renderFraction: function (currentClass, totalClass) {
			return '<span class="' + currentClass + '" id="x4"></span> / <span class="' + totalClass + '" id="y4"></span>';
		},
	},
});

var pag44  = document.getElementsByClassName('actions__body-row');

for(var i44=0;i44<pag44.length; i44++) {
    var z44 = pag44[i44].getAttribute('aria-label');
    var y44 = document.getElementsByClassName('swiper-pagination4');
    var y55 = document.getElementsByClassName('swiper-pagination5');
   	y44[i44].innerText = z44;
   	y55[i44].innerText = z44;
}

const anchors = document.querySelectorAll('a[href*="#"]');

for (let anchor of anchors) {
	anchor.addEventListener("click", function(event) {
		event.preventDefault();
		const blockID = anchor.getAttribute('href');
		document.querySelector('' + blockID).scrollIntoView({
			behavior: "smooth",
			block: "start"
		});
	});
}

$(document).ready(function() {
	$('.header__burger').click(function(event) {
		$('.header__logo').toggleClass('visible-logo');
		$('.nav-column').toggleClass('visible-column');
		$('.header__nav-options-row').toggleClass('header__nav-options-row_wide');
		$('.header__burger').toggleClass('header__burger_change');
		$('body').toggleClass('lock');
	});
});

if (window.innerWidth < 768) {
	var element1 = document.querySelectorAll( '.actions-column-mob__item' );
	var arr1 = [];

	for (var iii=0; iii<element1.length; iii++) {
		arr1.push(element1[iii].offsetHeight);
	}

	var maxarr1 = Math.max.apply(null, arr1);

	for (var iii1=0; iii1<element1.length; iii1++) {
		element1[iii1].style.height = maxarr1 + 'px';
	}

	window.addEventListener('resize', function () {	
		var element1new = document.querySelectorAll( '.actions-column-mob__item' );

		for (var iii1new=0; iii1new<element1new.length; iii1new++) {
			element1new[iii1new].style.height = 'fit-content';
		}

	 	var element1 = document.querySelectorAll( '.actions-column-mob__item' );
		var arr1 = [];

		for (var iii=0; iii<element1.length; iii++) {
			arr1.push(element1[iii].offsetHeight);
		}

		var maxarr1 = Math.max.apply(null, arr1);

		for (var iii1=0; iii1<element1.length; iii1++) {
			element1[iii1].style.height = maxarr1 + 'px';
		}
	});

}

var yWidgetSettings = {
	buttonColor: '#F4D782',
};

$(".modal__open").click(function(e) {
	e.preventDefault();
	$(".modal__around").fadeIn(600);
});

$(".modal__close").click(function() {
	$(".modal__around").fadeOut(600);
});


$(document).ready(function() {
	$('#form').submit(function() {
		if (document.form.modalName.value == '' || document.form.modalTel.value == '' || document.form.modalComment.value == '') {
			valid = false;
			return valid;
		}
		$.ajax({
			type: "POST",
			url: "tg-bot.php",
			data: $(this).serialize()
		}).done(function() {
			$('.modal-ok__around').fadeIn(600);
			$(".modal__around").fadeOut();
			$(this).find('input').val('');
			$('#form').trigger('reset');
		});
		return false;
	});
});

$('.modal-ok__close').click(function() {
	$('.modal-ok__around').fadeOut(600);
});

$(document).mouseup(function(e) {
	var popup = $('.popup');
	if (e.target!=popup[0]&&popup.has(e.target).length === 0){
		$('.modal-ok__around').fadeOut(600);
	}
});

$(document).ready(function() {
	$("#modalPhone").mask("+7 (999) 999-99-99");
}); 