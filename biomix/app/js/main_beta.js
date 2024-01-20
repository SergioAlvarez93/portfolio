document.addEventListener("DOMContentLoaded", () => {
    let sameHeight = (element) => {
        let proposalItems = document.querySelectorAll('.' + element);
        proposalItems.forEach(e => e.style.height = "auto");
        let proposalMassive = [];
        proposalItems.forEach(e => proposalMassive.push(e.offsetHeight));
        let currentProposalHeight = Math.max.apply(Math, proposalMassive) + 'px';
        proposalItems.forEach(e => e.style.height = currentProposalHeight);
    }
    let puro = () => {
        let biocidMrg = parseInt(window.getComputedStyle(document.querySelector(".proposal-fbox__inner-content-title"), null).getPropertyValue("margin-bottom").replace(/px/, ""));
        let txtHeight = document.querySelector('.proposal-fbox__inner-content-txt').offsetHeight;
        let txtMrg = parseInt(window.getComputedStyle(document.querySelector(".proposal-fbox__inner-content-txt"), null).getPropertyValue("margin-bottom").replace(/px/, ""));
        let btnHeight = document.querySelector('.standart-btn__proposal').offsetHeight;
        let geneHeight = document.querySelector('.proposal-fbox__item2').offsetHeight;
        let summMrg = biocidMrg + geneHeight + 'px';
        document.querySelector(".proposal-fbox__inner-content-title").style.paddingBottom = 'calc(1rem + ' + summMrg + ')';
        let newSummMrg = biocidMrg + txtHeight + txtMrg + btnHeight + geneHeight + 'px';
        document.querySelector(".proposal-fbox__item2").style.marginTop = 'calc(-14rem - ' + newSummMrg + ')';
    }
    sameHeight('quality-fbox__item');
    let gridLG = (element) => {
        let cardItems = document.querySelectorAll('.' + element);
        let cardArray = Array.from(cardItems);
        let chunkSize = 3;
        for (let i = 0; i < cardArray.length; i += chunkSize) {
            let chunk = cardArray.slice(i, i + chunkSize);
            chunk.forEach((e, index) => e.setAttribute('style', 'grid-column: ' + (index + 1) + '/' + (index + 2)));
        }
        chunkSize = 6;
        let itaration = 1;
        for (let i = 0; i < cardArray.length; i += chunkSize) {
            let chunk = cardArray.slice(i, i + chunkSize);
            chunk.forEach((e, index) => {
                let cardCurrAttr = e.getAttribute('style');
                if (index == 0 || index == 2) {
                    e.setAttribute('style', cardCurrAttr + ' ;' + 'grid-row: ' + itaration + '/' + (itaration + 2) + ';');
                } else if (index == 4) {
                    e.setAttribute('style', cardCurrAttr + ' ;' + 'grid-row: ' + (itaration + 1) + '/' + (itaration + 3) + ';');
                } else if (index == 3 || index == 5) {
                    e.setAttribute('style', cardCurrAttr + ' ;' + 'grid-row: ' + (itaration + 2) + '/' + (itaration + 3) + ';')
                } else {
                    e.setAttribute('style', cardCurrAttr + ' ;' + 'grid-row: ' + itaration + '/' + (itaration + 1) + ';')
                }
            })
            itaration += 3;
        }
    }
    let gridMD = (element) => {
        let cardItems = document.querySelectorAll('.' + element);
        let cardArray = Array.from(cardItems);
        let chunkSize = 2;
        let iter = 1;
        for (let i = 0; i < cardArray.length; i += chunkSize) {
            let chunk = cardArray.slice(i, i + chunkSize);
            chunk.forEach((e, index) => {
                if (iter % 2 == 0) {
                    if (index == 0) {
                        e.setAttribute('style', 'grid-column: ' + (index + 2) + '/' + (index + 3));
                    } else {
                        e.setAttribute('style', 'grid-column: ' + index + '/' + (index + 1));
                    }
                } else {
                    e.setAttribute('style', 'grid-column: ' + (index + 1) + '/' + (index + 2));
                }
            })
            iter++;
        }
        chunkSize = 4;
        let itaration = 1;
        for (let i = 0; i < cardArray.length; i += chunkSize) {
            let chunk = cardArray.slice(i, i + chunkSize);
            chunk.forEach((e, index) => {
                let cardCurrAttr = e.getAttribute('style');
                if (index == 0) {
                    e.setAttribute('style', cardCurrAttr + ' ;' + 'grid-row: ' + itaration + '/' + (itaration + 2) + ';');
                } else if (index == 3) {
                    e.setAttribute('style', cardCurrAttr + ' ;' + 'grid-row: ' + (itaration + 2) + '/' + (itaration + 3) + ';');
                } else if (index == 2) {
                    e.setAttribute('style', cardCurrAttr + ' ;' + 'grid-row: ' + (itaration + 1) + '/' + (itaration + 3) + ';')
                } else {
                    e.setAttribute('style', cardCurrAttr + ' ;' + 'grid-row: ' + itaration + '/' + (itaration + 1) + ';')
                }
            })
            itaration += 3;
        }
    }
    let gridSM = (element) => {
        let cardItems = document.querySelectorAll('.' + element);
        let cardArray = Array.from(cardItems);
        let chunkSize = 2;
        let iter = 1;
        for (let i = 0; i < cardArray.length; i += chunkSize) {
            let chunk = cardArray.slice(i, i + chunkSize);
            chunk.forEach((e, index) => {
                if (index == 0) {
                    e.setAttribute('style', 'grid-row: ' + iter + '/' + (iter + 2) + '; grid-column: 1/2;');
                } else {
                    e.setAttribute('style', 'grid-row: ' + (iter + 2) + '/' + (iter + 3) + '; grid-column: 1/2;');
                }
            })
            iter = iter + 3;
        }
    }
    let dioxide = () => {
        let paddingTop = window.getComputedStyle(document.querySelector(".bubbles__body"), null).getPropertyValue("padding-top");
        let boxHeight = document.querySelector('.bubbles-fbox').offsetHeight + 'px';
        let bubbleBg = document.querySelector('.bubbles__bg-box-inner-box').offsetHeight + 'px';
        document.querySelector('.bubbles__bg-outer-box').style.top = 'calc(2rem + ' + paddingTop + ' + ' + boxHeight + ')';
        document.querySelector('.bubbles-fbox').style.marginBottom = 'calc(4rem + ' + bubbleBg + ')';
    }
    let docsBgMD = () => {
        let getPad = window.getComputedStyle(document.querySelector(".docs__body"), null).getPropertyValue("padding-top");
        let getMrg = window.getComputedStyle(document.querySelector(".documents__item-txt"), null).getPropertyValue("margin-top");
        let getHeight = document.querySelector(".documents__item-txt").offsetHeight + 'px';
        document.querySelector(".docs__bg-outer-box").style.top = 'calc(2.5rem + ' + getPad + ' + ' + getMrg + ' + ' + getHeight + ')';
        let getDocHeight = document.querySelector('.documents__item-cat-box').offsetHeight + 'px';
        let getBgHeight = document.querySelector('.docs-bg-inner-box').offsetHeight + 'px';
        let getAdvHeight = document.querySelector('.advantages').offsetHeight + 'px';
        document.querySelector('.documents__item-cat-box').style.marginTop = 'calc(12.5rem + ' + getBgHeight + ' + ' + getAdvHeight + ')';
        document.querySelector('.advantages').style.marginTop = 'calc(-2.5rem - ' + getDocHeight + ' - ' + getAdvHeight + ')';
    }
    let changeArrowWidth = () => {
        let currentCommerseArrowWidth = document.querySelector('.commerce-fbox__item').offsetWidth * 0.26 + 'px';
        document.documentElement.style.setProperty("--commerseArrowWidth", currentCommerseArrowWidth);
    }
    changeArrowWidth();
    if (window.outerWidth < 992) {
        docsBgMD();
        gridMD('care-cards__item');
    } else {
        gridLG('care-cards__item');
    }
    if (window.outerWidth > 767) {
        sameHeight('proposal-fbox__inner-content');
    }
    if (window.outerWidth < 768) {
        puro();
        gridSM('care-cards__item');
        dioxide();
    }
    window.addEventListener('resize', function(event) {
        sameHeight('quality-fbox__item');
        changeArrowWidth();
        if (window.outerWidth < 992 && window.outerWidth > 767) {
            sameHeight('proposal-fbox__inner-content');
            gridMD('care-cards__item');
            docsBgMD();
            document.querySelector('.proposal-fbox__item2').style.marginTop = "";
            document.querySelectorAll('.proposal-fbox__inner-content-title')[0].style.paddingBottom = "";
            document.querySelector('.bubbles__bg-outer-box').style.top = "";
            document.querySelector('.bubbles-fbox').style.marginBottom = "";
        } else if (window.outerWidth > 991) {
            sameHeight('proposal-fbox__inner-content');
            gridLG('care-cards__item');
            document.querySelector(".docs__bg-outer-box").style.top = "";
            document.querySelector('.documents__item-cat-box').style.marginTop = "";
            document.querySelector('.advantages').style.marginTop = "";
            document.querySelector('.proposal-fbox__item2').style.marginTop = "";
            document.querySelectorAll('.proposal-fbox__inner-content-title')[0].style.paddingBottom = "";
            document.querySelector('.bubbles__bg-outer-box').style.top = "";
            document.querySelector('.bubbles-fbox').style.marginBottom = "";
        } else {
            let proposalItems = document.querySelectorAll('.proposal-fbox__inner-content');
            proposalItems.forEach(e => e.style.height = "");
            puro();
            gridSM('care-cards__item');
            docsBgMD();
            dioxide();
        }
    }, true);
    // <=== ANIMATIONS ===>
    new WOW().init();
    let biomixTxt = document.querySelector('.proposal-fbox__inner-content-title');
    biomixTxt.addEventListener('mouseenter', function(e) {
        e.target.classList.add('animate__animated');
        e.target.classList.add('animate__jello');
    });
    biomixTxt.addEventListener('mouseleave', function(e) {
        e.target.classList.remove('animate__animated');
        e.target.classList.remove('animate__jello');
    });
    let bubbleBox = document.querySelector('.bubbles__bg-outer-box');
    bubbleBox.addEventListener('mouseenter', function(e) {
        document.querySelector('.bubble-large__first').classList.add('animate__animated');
        document.querySelector('.bubble-large__first').classList.add('animate__rubberBand');
        document.querySelector('.bubble-large__second').classList.add('animate__animated');
        document.querySelector('.bubble-large__second').classList.add('animate__bounce');
        document.querySelector('.bubble-middle__first').classList.add('animate__animated');
        document.querySelector('.bubble-middle__first').classList.add('animate__slideInUp');
        document.querySelector('.bubble-middle__second').classList.add('animate__animated');
        document.querySelector('.bubble-middle__second').classList.add('animate__heartBeat');
        document.querySelector('.bubble-small__first').classList.add('animate__animated');
        document.querySelector('.bubble-small__first').classList.add('animate__shakeX');
        document.querySelector('.bubble-small__second').classList.add('animate__animated');
        document.querySelector('.bubble-small__second').classList.add('animate__tada');
    });
    bubbleBox.addEventListener('mouseleave', function(e) {
        document.querySelector('.bubble-large__first').classList.remove('animate__animated');
        document.querySelector('.bubble-large__first').classList.remove('animate__rubberBand');
        document.querySelector('.bubble-large__second').classList.remove('animate__animated');
        document.querySelector('.bubble-large__second').classList.remove('animate__bounce');
        document.querySelector('.bubble-middle__first').classList.remove('animate__animated');
        document.querySelector('.bubble-middle__first').classList.remove('animate__slideInUp');
        document.querySelector('.bubble-middle__second').classList.remove('animate__animated');
        document.querySelector('.bubble-middle__second').classList.remove('animate__heartBeat');
        document.querySelector('.bubble-small__first').classList.remove('animate__animated');
        document.querySelector('.bubble-small__first').classList.remove('animate__shakeX');
        document.querySelector('.bubble-small__second').classList.remove('animate__animated');
        document.querySelector('.bubble-small__second').classList.remove('animate__tada');
    });

    if (window.outerWidth > 991) {
        document.querySelectorAll('.quality-fbox__item')[0].classList.add('wow');
        document.querySelectorAll('.quality-fbox__item')[0].classList.add('animate__animated');
        document.querySelectorAll('.quality-fbox__item')[0].classList.add('animate__slideInRight');
        document.querySelectorAll('.quality-fbox__item')[1].classList.add('wow');
        document.querySelectorAll('.quality-fbox__item')[1].classList.add('animate__animated');
        document.querySelectorAll('.quality-fbox__item')[1].classList.add('animate__slideInRight');
        document.querySelectorAll('.quality-fbox__item')[2].classList.add('wow');
        document.querySelectorAll('.quality-fbox__item')[2].classList.add('animate__animated');
        document.querySelectorAll('.quality-fbox__item')[2].classList.add('animate__slideInLeft');
    }
});
$(document).ready(function() {
    $(function($) {
        $('[name="tel"]').mask("+7(999) 999-9999");
    });
});