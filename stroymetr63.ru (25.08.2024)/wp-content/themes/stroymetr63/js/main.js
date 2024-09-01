const swiper = new Swiper('.summary-info-slider-container', {
  loop: false,
  navigation: {
    nextEl: '.summary-info-slider-next',
    prevEl: '.summary-info-slider-prev',
  },
  scrollbar: {
    el: '.summary-info-slider-progressbar',
    draggable: true,
  },
});

const swiperObjects = new Swiper('.objects-slider-container', {
  slidesPerView: 'auto',
  spaceBetween: 20,
  loop: false,
  navigation: {
    nextEl: '.objects-slider-slider-next',
    prevEl: '.objects-slider-slider-prev',
  },
  scrollbar: {
    el: '.objects-slider-progressbar',
    draggable: true,
  },
});

const swiperGallery = new Swiper('.obj-gallery-slider', {
  slidesPerView: 'auto',
  spaceBetween: 20,
  loop: false,
  navigation: {
    nextEl: '.obj-gallery-slider-next',
    prevEl: '.obj-gallery-slider-prev',
  },
  scrollbar: {
    el: '.obj-gallery-slider-progressbar',
    draggable: true,
  },
});

const swiperReviews = new Swiper('.reviews-slider', {
  slidesPerView: 'auto',
  autoHeight: true,
  loop: false,
  scrollbar: {
    el: '.reviews-slider-progressbar',
    draggable: true,
  },
});

Fancybox.bind('[data-fancybox]', {});

document.addEventListener('DOMContentLoaded', function () {
  const burgerMenu = document.querySelector('.burger-menu');
  const header = document.querySelector('.header');
  const navItemColl = document.querySelectorAll('.navigation__item ');
  const navItemCollArr = [...navItemColl];

  burgerMenu.addEventListener('click', function () {
    header.classList.toggle('active');
    document.body.classList.toggle('no-scroll');
  });
  navItemCollArr.forEach((e) => {
    e.addEventListener('click', () => {
      header.classList.remove('active');
      document.body.classList.remove('no-scroll');
    });
  });

  const htmlCollectionToArray = (className) => {
    const slides = document.querySelectorAll(className);
    return [...slides];
  };
  const summarySliderArray = htmlCollectionToArray(
    '.summary-info-slider-slide.swiper-slide'
  );

  const currSlide = (className, sliderArrName) => {
    const currSlider = document.querySelector(className);
    sliderArrName.forEach((e, index) => {
      if (e.classList.contains('swiper-slide-active')) {
        const currNum = index + 1;
        currSlider.innerHTML = `/ ${currNum <= 9 ? '0' + currNum : currNum}`;
      }
    });
  };
  try {
    currSlide('.summary-slider-curr', summarySliderArray);
  } catch (error) {}

  const currSlideName = (className, sliderArrName) => {
    const currSlideName = document.querySelector(className);
    sliderArrName.forEach((e) => {
      if (e.classList.contains('swiper-slide-active')) {
        const currSlideImgAlt = e.children[0].querySelector('img').alt;
        currSlideName.innerHTML = `${currSlideImgAlt}`;
      }
    });
  };

  currSlideName('.curr-house-name', summarySliderArray);

  const currSlideSize = (className, sliderArrName) => {
    const currSlideSize = document.querySelector(className);
    sliderArrName.forEach((e) => {
      if (e.classList.contains('swiper-slide-active')) {
        const currSlideImgData = e.children[0]
          .querySelector('img')
          .getAttribute('data-size');
        currSlideSize.innerHTML = `${currSlideImgData}`;
      }
    });
  };

  currSlideSize('.curr-house-size', summarySliderArray);

  swiper.on('slideChange', function () {
    setTimeout(() => {
      try {
        currSlide('.summary-slider-curr', summarySliderArray);
      } catch (error) {}
      currSlideName('.curr-house-name', summarySliderArray);
      currSlideSize('.curr-house-size', summarySliderArray);
    }, 100);
  });

  const sumSlides = (className, sliderArrName) => {
    const currSliderSum = document.querySelector(className);
    currSliderSum.innerHTML = `/ ${
      sliderArrName.length <= 9
        ? '0' + sliderArrName.length
        : sliderArrName.length
    }`;
  };
  try {
    sumSlides('.summary-slider-sum', summarySliderArray);
  } catch (error) {}

  const constructionItemsArr = htmlCollectionToArray(
    '.construction-list__item'
  );

  try {
    constructionItemsArr[0].classList.add('construction-list__item_active');
  } catch (error) {}

  const constructionText = document.querySelector('.construction-text');

  const firstConstructionImg = document.querySelector(
    '.constructions-photo__first'
  );

  const secondConstructionImg = document.querySelector(
    '.constructions-photo__second'
  );

  constructionItemsArr.forEach((e) => {
    if (e.classList.contains('construction-list__item_active')) {
      constructionText.innerHTML = e.getAttribute('data-text');
      firstConstructionImg.parentElement.setAttribute(
        'src',
        e.getAttribute('data-imgVert')
      );
      firstConstructionImg.setAttribute('src', e.getAttribute('data-imgVert'));
      secondConstructionImg.parentElement.setAttribute(
        'src',
        e.getAttribute('data-imgHor')
      );
      secondConstructionImg.setAttribute('src', e.getAttribute('data-imgHor'));
    }
    e.addEventListener('click', (event) => {
      constructionItemsArr.forEach((e) => {
        if (e.classList.contains('construction-list__item_active')) {
          e.classList.remove('construction-list__item_active');
        }
      });
      event.target.classList.add('construction-list__item_active');
      setTimeout(() => {
        constructionText.innerHTML = event.target.getAttribute('data-text');
        firstConstructionImg.parentElement.setAttribute(
          'src',
          event.target.getAttribute('data-imgVert')
        );
        firstConstructionImg.setAttribute(
          'src',
          event.target.getAttribute('data-imgVert')
        );
        secondConstructionImg.parentElement.setAttribute(
          'src',
          event.target.getAttribute('data-imgHor')
        );
        secondConstructionImg.setAttribute(
          'src',
          event.target.getAttribute('data-imgHor')
        );
      }, 100);
    });
  });

  const maskOptions = {
    mask: '+{7}(000)000-00-00',
  };

  try {
    const firstTel = document.getElementById('firstTel');
    IMask(firstTel, maskOptions);
  } catch (error) {}

  try {
    const secondTel = document.getElementById('secondTel');
    IMask(secondTel, maskOptions);
  } catch (error) {}

  try {
    const thirdTel = document.getElementById('thirdTel');
    IMask(thirdTel, maskOptions);
  } catch (error) {}

  try {
    const fouthTel = document.getElementById('fouthTel');
    IMask(fouthTel, maskOptions);
  } catch (error) {}

  const objectsSlidesArr = htmlCollectionToArray('.objects-slider-slide');

  objectsSlidesArr.forEach((e) => {
    if (e.querySelectorAll('.objects-slider-slide-inner').length < 2) {
      e.querySelector('.objects-slider-slide-inner img').style.aspectRatio =
        '600/800';
    }
  });

  try {
    currSlide('.objects-slider-curr', objectsSlidesArr);
  } catch (error) {}

  swiperObjects.on('slideChange', function () {
    setTimeout(() => {
      try {
        currSlide('.objects-slider-curr', objectsSlidesArr);
      } catch (error) {}
    }, 100);
  });

  const sumSlidesResizable = (className, sliderName) => {
    const currSliderSum = document.querySelector(className);
    const scrollingLength = sliderName.snapGrid.length;
    currSliderSum.innerHTML = `/ ${
      scrollingLength <= 9 ? '0' + scrollingLength : scrollingLength
    }`;
  };

  try {
    sumSlidesResizable('.objects-slider-sum', swiperObjects);
  } catch (error) {}

  const reviewsSlidesArr = htmlCollectionToArray('.reviews-slider-slide');

  try {
    currSlide('.reviews-slider-curr', reviewsSlidesArr);
  } catch (error) {}

  swiperReviews.on('slideChange', function () {
    setTimeout(() => {
      try {
        currSlide('.reviews-slider-curr', reviewsSlidesArr);
      } catch (error) {}
    }, 100);
  });

  try {
    sumSlides('.reviews-slider-sum', reviewsSlidesArr);
  } catch (error) {}

  const arrowWrapArr = htmlCollectionToArray(
    '.calc-body-parameters__universal-parameter'
  );

  const rotateArrow = (e) => e.classList.toggle('arrow-rotation');

  arrowWrapArr.forEach((e) => {
    e.addEventListener('click', (event) =>
      rotateArrow(event.target.parentElement)
    );
    e.addEventListener('blur', (event) => {
      if (event.target.parentElement.classList.contains('arrow-rotation')) {
        rotateArrow(event.target.parentElement);
      }
    });
  });

  const objGalleryArr = htmlCollectionToArray('.obj-gallery-slider-slide');
  try {
    currSlide('.obj-gallery-slider-curr', objGalleryArr);
  } catch (error) {}

  swiperGallery.on('slideChange', function () {
    setTimeout(() => {
      try {
        currSlide('.obj-gallery-slider-curr', objGalleryArr);
      } catch (error) {}
    }, 100);
  });

  try {
    sumSlidesResizable('.obj-gallery-slider-sum', swiperGallery);
  } catch (error) {}

  //Window Resize
  window.addEventListener(
    'resize',
    function () {
      try {
        sumSlidesResizable('.objects-slider-sum', swiperObjects);
      } catch (error) {}
      try {
        sumSlidesResizable('.obj-gallery-slider-sum', swiperGallery);
      } catch (error) {}
    },
    true
  );

  //Smooth Scrolling
  function scrollToElementSmoothly(element) {
    setTimeout(function () {
      window.scrollTo({
        behavior: 'smooth',
        top: element.offsetTop,
      });
    }, 100);
  }

  function handlePageLoad() {
    const hash = window.location.hash.substring(1);
    const targetElement = document.getElementById(hash);
    if (targetElement) {
      scrollToElementSmoothly(targetElement);
    }
  }

  document.addEventListener('DOMContentLoaded', handlePageLoad);

  function hasElementWithId(id) {
    return !!document.getElementById(id);
  }

  function handleLinkClick(e) {
    e.preventDefault();

    const targetId = this.getAttribute('href').split('#')[1];

    if (hasElementWithId(targetId)) {
      scrollToElementSmoothly(document.getElementById(targetId));
    } else {
      window.location.href = this.getAttribute('href');
    }
  }

  document.querySelectorAll('a.scroll').forEach((link) => {
    link.addEventListener('click', handleLinkClick);
  });

  const myHash = location.hash;
  location.hash = '';

  if (myHash[1] !== undefined) {
    const targetElement = document.querySelector(myHash);
    if (targetElement) {
      const offset = targetElement.offsetTop;
      window.scrollTo({
        top: offset,
        behavior: 'smooth',
      });
    }
  }

  //Modal
  const modal = document.getElementById('myModal');
  const modalCloseBtn = document.querySelector('.modal__close');
  const body = document.body;

  document.addEventListener(
    'wpcf7mailsent',
    function (event) {
      openModal();
    },
    false
  );
  modalCloseBtn.addEventListener('click', closeModal);
  window.addEventListener('click', closeModalOutside);

  function openModal() {
    modal.style.display = 'block';
    modal.style.opacity = '0';
    fadeIn(modal);
    body.classList.add('modal-open');
    body.classList.add('no-scroll');
  }

  function closeModal() {
    fadeOut(modal);
    body.classList.remove('modal-open');
    body.classList.remove('no-scroll');
  }

  function closeModalOutside(event) {
    if (event.target === modal) {
      closeModal();
    }
  }

  function fadeIn(element) {
    clearInterval(element.fadeEffect);
    let opacity = 0;
    element.fadeEffect = setInterval(() => {
      if (opacity < 1) {
        opacity += 0.3;
        element.style.opacity = opacity;
      } else {
        clearInterval(element.fadeEffect);
      }
    }, 50);
  }

  function fadeOut(element) {
    clearInterval(element.fadeEffect);
    let opacity = 1;
    element.fadeEffect = setInterval(() => {
      if (opacity > 0) {
        opacity -= 0.3;
        element.style.opacity = opacity;
      } else {
        clearInterval(element.fadeEffect);
        element.style.display = 'none';
      }
    }, 50);
  }

  //Copying Email

  const target = document.querySelector('.mail-link');
  const button = document.querySelector('.copy-link');

  button.addEventListener('click', (e) => {
    e.preventDefault();

    const linkWithoutMailto = target.href.replace(/^mailto:/, '');

    const tempTextArea = document.createElement('textarea');
    tempTextArea.value = linkWithoutMailto;
    tempTextArea.style.position = 'absolute';
    tempTextArea.style.left = '-9999px';
    document.body.appendChild(tempTextArea);

    tempTextArea.select();
    tempTextArea.setSelectionRange(0, 99999);

    try {
      const successful = document.execCommand('copy');
      if (successful) {
        console.log('Copying text command was successful');
      } else {
        console.error('Copying text command was unsuccessful');
      }
    } catch (err) {
      console.error('Unable to copy text: ', err);
    }

    document.body.removeChild(tempTextArea);
  });
});
