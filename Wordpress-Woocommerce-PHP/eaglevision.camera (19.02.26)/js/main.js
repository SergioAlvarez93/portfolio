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

  // Реализация плавного скролла

  function getHeaderHeight() {
    if (window.innerWidth <= 768) return 52;
    const header = document.querySelector(
      "header, .header, #header, [data-header], .site-header",
    );
    return header ? header.offsetHeight : 0;
  }

  function smoothScrollTo(element) {
    if (!element) return;
    const offset = getHeaderHeight();
    const y = element.getBoundingClientRect().top + window.pageYOffset - offset;
    window.scrollTo({ top: y, behavior: "smooth" });
  }

  const hash = window.location.hash.slice(1);
  if (hash && window.location.pathname === "/") {
    setTimeout(() => smoothScrollTo(document.getElementById(hash)), 150);
  }

  const pendingHash = sessionStorage.getItem("pendingHash");
  if (pendingHash && window.location.pathname === "/") {
    setTimeout(() => {
      const target = document.getElementById(pendingHash);
      if (target) {
        smoothScrollTo(target);
        history.replaceState(null, "", "#" + pendingHash);
      }
      sessionStorage.removeItem("pendingHash");
    }, 150);
  }

  document.querySelectorAll('a[href*="#"]').forEach((link) => {
    link.addEventListener("click", (e) => {
      const href = link.getAttribute("href");
      if (!href || href.endsWith("#")) return;

      let targetHash = href.split("#")[1];
      if (!targetHash) return;

      const fullUrl = new URL(href, window.location.origin);
      if (
        fullUrl.pathname !== window.location.pathname &&
        fullUrl.pathname !== "/"
      ) {
        return;
      }

      e.preventDefault();

      const target = document.getElementById(targetHash);
      if (target) {
        smoothScrollTo(target);
        setTimeout(() => {
          history.pushState(null, "", "#" + targetHash);
        }, 400);
      } else if (href.startsWith("#")) {
        window.location.href = href;
      }
    });
  });

  // ******************************************************************

  // Определение динамической высоты header

  let headerHeight = document.querySelector(".header").offsetHeight;

  const presentationSection = document.querySelector(".presentation");

  presentationSection.style.marginTop = headerHeight + "px";

  window.addEventListener(
    "resize",
    function (event) {
      headerHeight = document.querySelector(".header").offsetHeight;
      presentationSection.style.marginTop = headerHeight + "px";
    },
    true,
  );

  // **************************************

  // Слайдер "О нас"

  const aboutSlider = new Swiper(".about-slider", {
    slidesPerView: "auto",
    spaceBetween: 8,
    breakpoints: {
      768: {
        spaceBetween: 28,
      },
    },
  });

  // ***************

  // Слайдер "Услуги компаниям"

  const servicesStSlider = new Swiper(".services-st-slider", {
    slidesPerView: "auto",
    spaceBetween: 8,
    breakpoints: {
      768: {
        spaceBetween: 28,
      },
    },
  });

  // **************************

  // Слайдер "Услуги частникам"

  const servicesNdSlider = new Swiper(".services-nd-slider", {
    slidesPerView: "auto",
    spaceBetween: 8,
    breakpoints: {
      768: {
        spaceBetween: 28,
      },
    },
  });

  // **************************

  // Слайдер "Как мы работаем"

  const rulesSlider = new Swiper(".rules-slider", {
    slidesPerView: "auto",
    spaceBetween: 8,
    breakpoints: {
      768: {
        spaceBetween: 28,
      },
    },
  });

  // **************************

  // Fancybox "О нас"

  Fancybox.bind('[data-fancybox="about-slider"]', {});

  // ****************

  // Fancybox "Первый слайдер услуг"

  Fancybox.bind('[data-fancybox="services-st-slider"]', {});

  // ****************

  // Fancybox "Второй слайдер услуг"

  Fancybox.bind('[data-fancybox="services-nd-slider"]', {});

  // ****************

  // Fancybox "Как мы работаем"

  Fancybox.bind('[data-fancybox="rules-slider"]', {});

  // ****************

  // Функция управляющая динамических шрифтом из атрибутов (раздел "Партнёры")

  function setFluidVariables() {
    document.querySelectorAll(".partners-list__item-name").forEach((el) => {
      const desktop = el.dataset.desctopFont;
      const mobile = el.dataset.mobileFont;

      if (desktop && mobile) {
        const slope = (desktop - mobile) / (1248 - 360);
        const intercept = mobile - slope * 360;

        el.style.setProperty("--font-slope", slope);
        el.style.setProperty("--font-intercept", intercept + "px");
        el.style.setProperty("--font-min", mobile + "px");
        el.style.setProperty("--font-max", desktop + "px");
      }
    });
  }
  setFluidVariables();

  // *************************************************************************

  // Функция выравнивания высоты элементов для раздела "Партнеры"

  function setHeight() {
    const heightArr = [
      ...document.querySelectorAll(".partners-list__item-name"),
    ].map((el) => {
      return el.offsetHeight;
    });

    let maxHeight = -Infinity;
    for (let num of heightArr) {
      if (num > maxHeight) {
        maxHeight = num;
      }
    }
    document.querySelectorAll(".partners-list__item-name").forEach((el) => {
      el.style.height = maxHeight + "px";
    });
  }

  setHeight();

  window.addEventListener("resize", () => {
    setHeight();
  });

  // ************************************************************

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

  // ******************************

  // Стартовая модалка Cookie

  if (!localStorage.getItem("cookie-consent")) {
    document.getElementById("cookie-consent").style.display = "flex";
  }

  function acceptCookies(type = "all") {
    localStorage.setItem("cookie-consent", type);
    document.getElementById("cookie-consent").remove();
  }

  document.getElementById("cookie-accept-essential").onclick = () =>
    acceptCookies("essential");
  document.getElementById("cookie-accept-all").onclick = () =>
    acceptCookies("all");

  // ************************

  // Модалки

  function modalFn(openBtnClass, modalOverlayLink) {
    const openBtn = document.querySelectorAll(`.${openBtnClass}`);
    const modalOverlay = document.getElementById(modalOverlayLink);
    const closeBtn = modalOverlay.querySelector("#closeModalBtn");
    const closeFooterBtn = modalOverlay.querySelector("#closeModalFooterBtn");

    function openModal() {
      modalOverlay.style.display = "flex";
      document.body.classList.add("body-blocked");
    }

    function closeModal() {
      modalOverlay.style.display = "none";
      document.body.classList.remove("body-blocked");
    }

    openBtn.forEach(function (e) {
      e.addEventListener("click", openModal);
    });

    closeBtn.addEventListener("click", closeModal);

    if (closeFooterBtn) {
      closeFooterBtn.addEventListener("click", closeModal);
    }

    modalOverlay.addEventListener("click", function (event) {
      if (event.target === modalOverlay) {
        closeModal();
      }
    });

    document.addEventListener("keydown", function (event) {
      if (event.key === "Escape" && modalOverlay.style.display === "flex") {
        closeModal();
      }
    });
  }

  modalFn("open-privacy", "privacyModal");
  modalFn("footer-bottom__security_privacy", "privacyModal");
  modalFn("open-personal", "personalModal");
  modalFn("footer-bottom__security_cookie", "cookieModal");
  modalFn("cookie-consent-link", "cookieModal");

  // *******
});
