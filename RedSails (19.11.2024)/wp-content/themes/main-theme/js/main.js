document.addEventListener('DOMContentLoaded', function () {
  const burger = document.querySelector('.burger');
  const header = document.querySelector('.header');
  const body = document.body;

  burger.addEventListener('click', function (e) {
    e.preventDefault();
    burger.classList.toggle('active');
    header.classList.toggle('active');
    body.classList.toggle('no-scroll');
  });

  document.querySelectorAll('.main-nav a').forEach((link) => {
    link.addEventListener('click', function () {
      burger.classList.remove('active');
      header.classList.remove('active');
      body.classList.remove('no-scroll');
    });
  });

  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener('click', function (e) {
      e.preventDefault();
      const targetId = this.getAttribute('href');
      document.querySelector(targetId).scrollIntoView({
        behavior: 'smooth',
      });
    });
  });

  const swiper = new Swiper('.presentation-slider', {
    autoHeight: true,
    navigation: {
      nextEl: '.presentation-slider__nav-arrow-right',
      prevEl: '.presentation-slider__nav-arrow-left',
    },
    breakpoints: {
      360: {
        slidesPerView: 1,
        spaceBetween: 0,
      },
      768: {
        slidesPerView: 2,
        spaceBetween: 10,
      },
    },
  });

  const chars = document.querySelectorAll(
    '.presentation-grid__nav-list-char, .presentation-grid__cabin-list-char, .presentation-grid__entertainment-list-char'
  );

  chars.forEach((char) => {
    if (char.textContent.includes(' (no)')) {
      char.textContent = char.textContent.replace(' (no)', '');
      char.style.color = '#9EA3B0';
      char.style.textDecoration = 'line-through';
    }
  });

  const swiperExplore = new Swiper('.explore-slider', {
    autoHeight: true,
    slidesPerView: 1,
    spaceBetween: 0,
    navigation: {
      nextEl: '.explore-grid__content-nav-right',
      prevEl: '.explore-grid__content-nav-left',
    },
  });

  const swiperTestimonials = new Swiper('.testimonials-slider', {
    spaceBetween: 32,
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
    navigation: {
      nextEl: '.testimonials-slider__nav-box-link-right',
      prevEl: '.testimonials-slider__nav-box-link-left',
    },
    breakpoints: {
      360: {
        slidesPerView: 1,
      },
      768: {
        slidesPerView: 2,
      },
      992: {
        slidesPerView: 3,
      },
    },
  });

  document.addEventListener('DOMContentLoaded', function () {
    let submitButtons = document.querySelectorAll('.wpcf7-submit');

    submitButtons.forEach(function (submitButton) {
      submitButton.addEventListener('click', function (event) {
        event.preventDefault();
        let form = submitButton.closest('form');
        if (form) {
          form.submit();
        }
      });
    });
  });

  try {
    function openModal(modalId) {
      const modal = document.getElementById(modalId);
      if (modal) {
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
      } else {
        console.warn(`Модальное окно с ID '${modalId}' не найдено.`);
      }
    }

    function closeModal(modalId) {
      const modal = document.getElementById(modalId);
      if (modal) {
        modal.classList.remove('show');
        document.body.style.overflow = '';
      } else {
        console.warn(`Модальное окно с ID '${modalId}' не найдено.`);
      }
    }

    function checkFormStatus() {
      const forms = document.querySelectorAll('form.wpcf7-form');

      forms.forEach((form) => {
        const status = form.getAttribute('data-status');
        if (status === 'sent') {
          const action = form.getAttribute('action');
          if (action.includes('wpcf7-f153')) {
            openModal('successModal1');
          } else if (action.includes('wpcf7-f161')) {
            openModal('successModal2');
          }
          form.setAttribute('data-status', '');
        }
      });
    }
    setInterval(checkFormStatus, 500);

    document.addEventListener('click', function (event) {
      if (event.target.classList.contains('modal-close')) {
        const modal = event.target.closest('.modal');
        if (modal) {
          closeModal(modal.id);
        }
      }
    });

    window.onclick = function (event) {
      const modal1 = document.getElementById('successModal1');
      const modal2 = document.getElementById('successModal2');

      if (modal1 && event.target === modal1) {
        closeModal('successModal1');
      }
      if (modal2 && event.target === modal2) {
        closeModal('successModal2');
      }
    };
  } catch (error) {
    console.error('Ошибка в работе модальных окон:', error);
  }
});
