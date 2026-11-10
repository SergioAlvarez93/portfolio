addEventListener("DOMContentLoaded", (event) => {
  // Мобильная версия навигационной панели

  const burgerBtn = document.querySelector(".burger-btn");

  burgerBtn.addEventListener("click", function () {
    document.querySelector(".header").classList.toggle("header-mob");
    document.body.classList.toggle("body-blocked");
  });

  document
    .querySelectorAll(".header-menu .navigation__item a")
    .forEach(function (e) {
      e.addEventListener("click", function () {
        if (window.outerWidth < 768) {
          document.querySelector(".header").classList.toggle("header-mob");
          document.body.classList.toggle("body-blocked");
        }
      });
    });

  window.addEventListener("resize", () => {
    if (
      window.outerWidth > 767 &&
      document.body.classList.contains("body-blocked") &&
      document.querySelector(".header").classList.contains("header-mob")
    ) {
      document.body.classList.remove("body-blocked");
    }
    if (
      window.outerWidth < 768 &&
      !document.body.classList.contains("body-blocked") &&
      document.querySelector(".header").classList.contains("header-mob")
    ) {
      document.body.classList.add("body-blocked");
    }
  });

  // *************************************

  // Отступ от хэдера первого раздела main

  function mainMarginTop() {
    document.querySelector(".main").style.marginTop =
      document.querySelector(".header").offsetHeight + "px";
  }

  mainMarginTop();

  window.addEventListener("resize", () => {
    mainMarginTop();
  });

  // Слайдер для целей

  const purposeSlider = new Swiper(".purpose-swiper", {
    loop: true,
    autoplay: {
      delay: 3000,
    },
  });

  // *****************

  // *************************************

  // Слайдер для витрины

  const showcaseSlider = new Swiper(".showcase-list", {
    pagination: {
      el: ".swiper-pagination",
    },
    navigation: {
      nextEl: ".showcase-controls__next",
      prevEl: ".showcase-controls__prev",
    },
    allowTouchMove: true,
    simulateTouch: true,
    speed: 300,
    slidesPerView: 1,
    breakpoints: {
      768: {
        allowTouchMove: false,
        simulateTouch: false,
        speed: 0,
      },
    },
  });

  // *******************

  // Слайдер для фильтра товаров

  const filterSwiper = new Swiper(".filter-swiper", {
    freeMode: true,
    slidesPerView: "auto",
    spaceBetween: 0,
    navigation: {
      nextEl: ".market-button-next",
      prevEl: ".market-button-prev",
    },
    breakpoints: {
      768: {
        spaceBetween: 0,
      },
    },
  });

  // ***************************

  // Выстраиваем грид-сетку в блоке "Спортсмены выбирают"

  const installGrid = (
    element,
    col,
    colMob,
    row,
    rowMob,
    align,
    displayMob,
  ) => {
    if (window.outerWidth < 768) {
      console.log(displayMob);
      if (displayMob == "none") {
        element.style.display = displayMob;
      } else {
        element.style.display = "block";
        element.style.gridColumn = colMob;
        element.style.gridRow = rowMob;
        element.style.alignSelf = align;
      }
    } else {
      element.style.display = "block";
      element.style.gridColumn = col;
      element.style.gridRow = row;
      element.style.alignSelf = align;
    }
  };

  const buildGrid = () => {
    const gridArr = document.querySelectorAll(".choice-grid__item");
    gridArr.forEach((el) => {
      let element = el;
      let col = el.getAttribute("data-col");
      let colMob = el.getAttribute("data-col-mob");
      let row = el.getAttribute("data-row");
      let rowMob = el.getAttribute("data-row-mob");
      let align = el.getAttribute("data-align");
      let displayMob = el.getAttribute("data-display-mob");
      installGrid(element, col, colMob, row, rowMob, align, displayMob);
    });
  };

  buildGrid();

  window.addEventListener("resize", buildGrid);

  // ****************************************************

  // Слайдер для блоггеров

  const bloggerSwiper = new Swiper(".bloggers-gallery-slider", {
    freeMode: true,
    slidesPerView: 1.05,
    spaceBetween: 8,
    breakpoints: {
      1366: {
        slidesPerView: 4.5185,
        spaceBetween: 24,
      },
      1024: {
        slidesPerView: 3.05,
        spaceBetween: 12,
      },
      768: {
        slidesPerView: 2.05,
        spaceBetween: 12,
      },
      576: {
        slidesPerView: 2,
      },
    },
  });

  // ***********************

  // Модальное окно для видео от блоггеров

  Fancybox.bind("[data-fancybox]", {
    // Кастомизировать тут
  });

  // ***********************

  // Слайдер для отзывов с маркетплейсов

  const reviewsSwiper = new Swiper(".reviews-gallery-slider", {
    freeMode: true,
    slidesPerView: 1.0,
    spaceBetween: 8,
    breakpoints: {
      1275: {
        slidesPerView: 3.3541,
        spaceBetween: 24,
      },
      1024: {
        slidesPerView: 3.0,
        spaceBetween: 12,
      },
      768: {
        slidesPerView: 2.0,
        spaceBetween: 12,
      },
      576: {
        slidesPerView: 2,
      },
    },
  });

  // ***********************

  // Кастомизируем плэйсхолдеры для формы обратной связи

  // Инициализация при загрузке страницы: проверяем все поля и скрываем/показываем плейсхолдеры
  const placeholderBoxes = document.querySelectorAll(".placeholder-box");

  placeholderBoxes.forEach(function (box) {
    const input = box.querySelector("input, textarea"); // Находим поле ввода внутри блока
    const placeholder = box.querySelector(".placeholder-text"); // Находим наш кастомный плейсхолдер

    if (input && placeholder) {
      // Проверяем, есть ли уже текст в поле при загрузке
      if (input.value.trim() !== "") {
        placeholder.style.display = "none";
      } else {
        placeholder.style.display = "block";
      }
    }
  });

  // ***************************************************

  // Реализуем плавный скролл для навигационных ссылок с любой страницы

  const hash = window.location.hash.slice(1);
  if (hash && window.location.pathname === "/") {
    setTimeout(() => {
      const target = document.getElementById(hash);
      if (target) {
        target.scrollIntoView({
          behavior: "smooth",
          block: "start",
          inline: "nearest",
        });
      }
    }, 100);
  }

  const pendingHash = sessionStorage.getItem("pendingHash");
  if (pendingHash && window.location.pathname === "/") {
    setTimeout(() => {
      const target = document.getElementById(pendingHash);
      if (target) {
        target.scrollIntoView({
          behavior: "smooth",
          block: "start",
          inline: "nearest",
        });
        history.replaceState(null, null, "#" + pendingHash);
      }
    }, 100);
    sessionStorage.removeItem("pendingHash");
  }

  document
    .querySelectorAll(
      '.navigation__item a[href^="' +
        window.location.origin +
        '"], .presentation-btn[href^="' +
        window.location.origin +
        '"]',
    )
    .forEach((link) => {
      link.addEventListener("click", (e) => {
        const href = link.href;
        const url = new URL(href);

        const isHomeLink = url.pathname === "/" && !url.hash;
        if (isHomeLink) {
          e.preventDefault();
          window.location.href = href;
          return;
        }

        const isExternalAnchor =
          url.pathname !== window.location.pathname &&
          url.pathname !== "/" &&
          url.hash;
        const isRegularLink = !url.hash;

        if (isExternalAnchor || isRegularLink) {
          return;
        }

        const isSamePageAnchor =
          window.location.pathname === url.pathname && url.hash;
        const isHomeAnchor = url.pathname === "/" && url.hash;
        const hash = url.hash.slice(1);

        if (isHomeAnchor && window.location.pathname !== "/") {
          e.preventDefault();
          sessionStorage.setItem("pendingHash", hash);
          window.location.href = url.origin + "/";
          return;
        }

        if (isSamePageAnchor || isHomeAnchor) {
          e.preventDefault();

          let targetElement = null;

          if (hash) {
            targetElement = document.getElementById(hash);
          }

          if (targetElement) {
            targetElement.scrollIntoView({
              behavior: "smooth",
              block: "start",
              inline: "nearest",
            });

            setTimeout(() => {
              if (isHomeAnchor) {
                history.pushState(null, null, url.hash);
              } else {
                history.pushState(null, null, url.pathname + url.hash);
              }
            }, 500);
          } else {
            window.location.href = href;
          }
        }
      });
    });

  // ******************************************************************

  // Модальное окно конфиденциальности и оферты

  const modalConf = document.getElementById("confidential");
  const modalLegalOffer = document.getElementById("legalOffer");
  const closeBtn = document.querySelectorAll(".modal-close");
  const bodyCondition = document.body;

  document
    .querySelectorAll(".footer-menu .navigation__item")
    .forEach(function (e) {
      e.addEventListener("click", function (event) {
        event.preventDefault();
        if (e.firstChild.innerText === "Конфиденциальность") {
          modalConf.style.display = "block";
          bodyCondition.classList.add("body-blocked");
        }
        if (e.firstChild.innerText === "Оферта") {
          modalLegalOffer.style.display = "block";
          bodyCondition.classList.add("body-blocked");
        }
      });
    });

  closeBtn.forEach(function (e) {
    e.addEventListener("click", () => {
      modalConf.style.display = "none";
      modalLegalOffer.style.display = "none";
      bodyCondition.classList.remove("body-blocked");
    });
  });

  window.addEventListener("click", (e) => {
    if (e.target === modalConf) {
      modalConf.style.display = "none";
      bodyCondition.classList.remove("body-blocked");
    }
    if (e.target === modalLegalOffer) {
      modalLegalOffer.style.display = "none";
      bodyCondition.classList.remove("body-blocked");
    }
  });

  // ******************************************

  document.addEventListener("click", async function (e) {
    const variationOption = e.target.closest(
      ".showcase-product .variation-option",
    );
    if (!variationOption) return;

    e.preventDefault();

    const variationIndex = variationOption.dataset.variationIndex;
    const showcase = variationOption.closest(".showcase-product");
    const productId = showcase.dataset.productId;

    const variationsDataScript = showcase.querySelector(".variations-data");
    if (!variationsDataScript) return;

    const variationsData = JSON.parse(variationsDataScript.textContent);
    const selectedVariation = variationsData.find(
      (v) => v.index == variationIndex,
    );
    if (!selectedVariation) return;

    // Меняем активный класс
    showcase.querySelectorAll(".variation-option__active").forEach((el) => {
      el.classList.remove("variation-option__active");
    });

    const newActiveOption = showcase.querySelector(
      `.variation-option[data-variation-index="${variationIndex}"]`,
    );
    if (newActiveOption) {
      newActiveOption.classList.add("variation-option__active");
    }

    // Меняем изображение
    if (selectedVariation.image) {
      const imgBox = showcase.querySelector(".showcase-product-img-box__wrap");
      imgBox.innerHTML = `<img src="${selectedVariation.image}" class="showcase-product-img-box__img" alt="">`;
    }

    // Меняем ссылки
    const linksBox = showcase.querySelector(
      ".showcase-product-content-box-btn-box",
    );
    if (linksBox) {
      const showcaseColor = linksBox.querySelector("a")?.style.color || "";
      let linksHTML = "";
      if (selectedVariation.ozon) {
        linksHTML += `<a href="${selectedVariation.ozon}" class="showcase-product-content-box-btn-box__link" style="color: ${showcaseColor};" target="_blank">OZON</a>`;
      }
      if (selectedVariation.wildberries) {
        linksHTML += `<a href="${selectedVariation.wildberries}" class="showcase-product-content-box-btn-box__link" style="color: ${showcaseColor};" target="_blank"><span>WILDBERRIES</span></a>`;
      }
      linksBox.innerHTML = linksHTML;
    }
  });
});

// Обработчик ввода данных для динамического скрытия/показа плейсхолдера

document.body.addEventListener("input", function (event) {
  // Проверяем, что событие произошло на нужном нам поле ввода
  const target = event.target;
  if (target.matches(".placeholder-box input, .placeholder-box textarea")) {
    const placeholder = target
      .closest(".placeholder-box")
      .querySelector(".placeholder-text");

    if (placeholder) {
      if (target.value.trim() !== "") {
        placeholder.style.display = "none";
      } else {
        placeholder.style.display = "block";
      }
    }
  }

  // ***********************

  // Маска для ввода номера телефона
  const maskOptions = {
    mask: "+{7}(000)000-00-00",
  };

  try {
    const tel = document.querySelectorAll(".request-form__tel");
    const telArr = [...tel];
    telArr.forEach((el) => {
      IMask(el, maskOptions);
    });
  } catch (error) {}

  // ***********************
});
