$(document).ready(function() {
	$('.slider').slick({
		dots: true,
	});
})

document.getElementById("open-modal-btn").addEventListener("click", function() {
    document.getElementById("my-modal").classList.add("open");
    document.body.classList.add("lock");
});

document.getElementById("open-modal-btn2").addEventListener("click", function() {
    document.getElementById("my-modal").classList.add("open");
    document.body.classList.add("lock");
});

document.getElementById("open-modal-btn3").addEventListener("click", function() {
    document.getElementById("my-modal").classList.add("open");
    document.body.classList.add("lock");
});

document.getElementById("open-modal-btn4").addEventListener("click", function() {
    document.getElementById("my-modal").classList.add("open");
    document.body.classList.add("lock");
});

document.getElementById("close-my-modal-btn").addEventListener("click", function() {
    document.getElementById("my-modal").classList.remove("open");
    document.body.classList.remove("lock");
});

window.addEventListener('keydown', (e) => {
    if (e.key === "Escape") {
        document.getElementById("my-modal").classList.remove("open");
        document.body.classList.remove("lock");
    }
});

document.querySelector("#my-modal .modal__box").addEventListener('click', event => {
    event._isClickWithInModal = true;
});
document.getElementById("my-modal").addEventListener('click', event => {
    if (event._isClickWithInModal) return;
    event.currentTarget.classList.remove('open');
    document.body.classList.remove("lock");
});

document.getElementById("close-my-modal-btn2").addEventListener("click", function() {
    document.getElementById("my-modal2").classList.remove("open");
    document.body.classList.remove("lock");
});

window.addEventListener('keydown', (e) => {
    if (e.key === "Escape") {
        document.getElementById("my-modal2").classList.remove("open");
        document.body.classList.remove("lock");
    }
});

document.querySelector("#my-modal2 .modal__box").addEventListener('click', event => {
    event._isClickWithInModal = true;
});
document.getElementById("my-modal2").addEventListener('click', event => {
    if (event._isClickWithInModal) return;
    event.currentTarget.classList.remove('open');
    document.body.classList.remove("lock");
});

document.getElementById("input1").addEventListener("click", function() {
    document.getElementById('input1').style.background = "#fff";
});

document.getElementById("custom-checkbox").addEventListener("click", function() {
    document.getElementById('custom-checkbox').style.background = "inherit";
});

document.getElementById("input2").addEventListener("click", function() {
    document.getElementById('input2').style.background = "#fff";
});

$(document).ready(function() {
	$('#form1').submit(function() { 
		if (document.form1.input1.value == '') {
			valid = false;
			document.getElementById('input1').style.background = "#fcd9d9";
			return valid;
		}
		if (document.getElementById('checkbox').checked == false) {
            valid = false;
            document.getElementById('custom-checkbox').style.background = "#fcd9d9";
			return valid;
        }
		$.ajax({
			type: "POST",
			url: "",
			data: $(this).serialize()
		}).done(function() {
			$(this).find('input').val('');
			$('#form1').trigger('reset');
			$('body').toggleClass("lock");
			$('#my-modal2').toggleClass("open");
		});
		return false;
	});
});

$(function($){
	$('[name="input1"]').mask("+7(999) 999-9999");
});

$(document).ready(function() {
	$('#form2').submit(function() { 
		if (document.form2.input2.value == '') {
			valid = false;
			document.getElementById('input2').style.background = "#fcd9d9";
			return valid;
		}
		$.ajax({
			type: "POST",
			url: "",
			data: $(this).serialize()
		}).done(function() {
			$(this).find('input').val('');
			$('#form2').trigger('reset');
			$('#my-modal').removeClass("open");
			$('#my-modal2').toggleClass("open");
		});
		return false;
	});
});

$(function($){
	$('[name="input2"]').mask("+7(999) 999-9999");
});

document.getElementById("mob-btn").addEventListener("click", function() {
    document.getElementById("nav-menu").classList.toggle("open-mob");
    document.body.classList.toggle("lock");
});