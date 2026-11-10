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
    if (window.innerWidth <= 768) return 60;
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

  // Вращение карточки на витрине

  document.querySelectorAll("li.novelty-products__item").forEach((li) => {
    let timeout;
    const card = li.querySelector(".cardFlip");

    li.addEventListener("mouseenter", () => {
      clearTimeout(timeout);
      timeout = setTimeout(() => card?.classList.add("is-flipped"), 150);
    });

    li.addEventListener("mouseleave", () => {
      clearTimeout(timeout);
      timeout = setTimeout(() => card?.classList.remove("is-flipped"), 150);
    });
  });

  // ************************

  // Функция определения максимальной высоты элементов в сетке

  function generalHeight(elements, container, backside) {
    const elementsArr = document.querySelectorAll(`.${elements}`);
    const heightsArr = [...elementsArr].map((el) => el.offsetHeight);
    const maxHeight = Math.max(...heightsArr);
    document
      .querySelectorAll(`.${container}`)
      .forEach((el) => (el.style.minHeight = maxHeight + "px"));
    document
      .querySelectorAll(`.${backside}`)
      .forEach((el) => (el.style.minHeight = maxHeight + "px"));
  }

  generalHeight(
    "novelty-products__item .product-preview-grid",
    "novelty-products__item .cardFlip",
    "novelty-products__item .product-backside-img",
  );
  window.addEventListener("resize", () => {
    generalHeight(
      "novelty-products__item .product-preview-grid",
      "novelty-products__item .cardFlip",
      "novelty-products__item .product-backside-img",
    );
  });

  // *********************************************************

  // Слайдер для фильтра

  const fliterSlider = new Swiper(".catalog-flex-filter", {
    freeMode: true,
    slidesPerView: "auto",
    spaceBetween: 8,
    navigation: {
      nextEl: ".catalog-flex-filter-next",
      prevEl: ".catalog-flex-filter-prev",
    },
    breakpoints: {
      768: {
        spaceBetween: 14,
      },
    },
  });

  // *******************

  // Вариации

  function initFirstVariation(elementClass) {
    document.querySelectorAll(`${elementClass}`).forEach((li) => {
      const firstBtn = li.querySelector(".quantity-btn");
      if (firstBtn) {
        const data = JSON.parse(firstBtn.dataset.variation || "{}");
        if (data.id) {
          updateVariation(li, data);
        }
      }
    });
  }

  initFirstVariation("li.novelty-products__item");

  function clickBtnVariation(elementClass) {
    document
      .querySelectorAll(`${elementClass} .quantity-btn`)
      .forEach((btn) => {
        btn.addEventListener("click", () => {
          const data = JSON.parse(btn.dataset.variation || "{}");
          if (!data.id) return;

          const li = btn.closest(`${elementClass}`);
          if (!li) return;

          updateVariation(li, data);
        });
      });
  }

  clickBtnVariation("li.novelty-products__item");

  function updateVariation(li, data) {
    const img = li.querySelector(".product-preview-img img");
    if (img && data.image) {
      img.src = data.image;
      img.srcset = data.srcset || "";
    }

    if (data.acf) {
      Object.entries(data.acf).forEach(([key, value]) => {
        const els = li.querySelectorAll(`[data-acf="${key}"]`);
        els.forEach((el) => {
          if (!value || value === "" || (value.url && !value.url.trim())) {
            el.style.display = "none";
          } else {
            if (value.url) {
              el.innerHTML = `<img src="${value.url}" alt="${value.alt || ""}">`;
            } else if (el.tagName === "A") {
              el.href = value;
            } else {
              el.textContent = value;
            }
            el.style.display = "";
          }
        });
      });
    }
  }

  // ********

  // Стилизация кнопок для вариаций в карточке

  function activateFirstBtn(container, btnFindClass, btnAddClass) {
    document.querySelectorAll(`.${container}`).forEach((el) => {
      el.querySelector(`.${btnFindClass}`).classList.add(`${btnAddClass}`);
    });
  }

  activateFirstBtn("type-product", "quantity-btn", "quantity-btn__active");

  function activeAnotherBtn(container, btnFindClass, btnAddClass) {
    document.querySelectorAll(`.${container}`).forEach((el) => {
      el.querySelectorAll(`.${btnFindClass}`).forEach((btn) => {
        btn.addEventListener("click", function () {
          el.querySelectorAll(`.${btnFindClass}`).forEach((removeClassItem) => {
            if (removeClassItem.classList.contains(`${btnAddClass}`)) {
              removeClassItem.classList.remove(`${btnAddClass}`);
            }
          });
          btn.classList.add(`${btnAddClass}`);
        });
      });
    });
  }

  activeAnotherBtn("type-product", "quantity-btn", "quantity-btn__active");

  // *****************************************

  // Функция для фильтра

  let currentCategory = "";
  let currentPage = 1;
  let hasMore = true;

  async function filterProducts(slug = "", page = 1, append = false) {
    const response = await fetch("/wp-admin/admin-ajax.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: new URLSearchParams({
        action: "load_products_section",
        category: slug,
        paged: page,
        nonce: filter_products,
      }),
    });

    const data = await response.json();

    const container = document.querySelector("#category-products");

    if (append) {
      container.insertAdjacentHTML("beforeend", data.html);
    } else {
      container.innerHTML = data.html;
    }

    if (document.querySelector("#category-products").style.display === "none") {
      document.querySelector("#category-products").style.display = "flex";
    }

    if (document.querySelector("#search-results").style.display === "flex") {
      document.querySelector("#search-results").style.display = "none";
    }

    document.querySelector("#product-search").value = "";

    initFirstVariation("li.products__item");
    activateFirstBtn("type-product", "quantity-btn", "quantity-btn__active");
    clickBtnVariation("li.products__item");
    activeAnotherBtn("type-product", "quantity-btn", "quantity-btn__active");

    hasMore = data.has_more;
    currentPage = data.next_page;

    const btn = document.getElementById("load-more-btn");
    if (btn) {
      btn.style.display = hasMore ? "block" : "none";
    }
  }

  const firstItem = document.querySelector(".catalog-flex-filter__item");
  if (firstItem) {
    currentCategory = firstItem.getAttribute("data-filter");
    firstItem.classList.add("catalog-flex-filter__item-active");
    filterProducts(currentCategory, 1, false);
  }

  document.querySelectorAll(".catalog-flex-filter__item").forEach((el) => {
    el.addEventListener("click", async function (e) {
      e.preventDefault();

      document
        .querySelectorAll(".catalog-flex-filter__item")
        .forEach((item) =>
          item.classList.remove("catalog-flex-filter__item-active"),
        );

      this.classList.add("catalog-flex-filter__item-active");

      currentCategory = this.getAttribute("data-filter");
      currentPage = 1;
      hasMore = true;

      await filterProducts(currentCategory, 1, false);
    });
  });

  document.addEventListener("click", async function (e) {
    if (!e.target.matches("#load-more-btn")) return;

    e.preventDefault();
    if (!hasMore) return;

    await filterProducts(currentCategory, currentPage, true);
  });

  // *******************

  // Поиск товара

  const searchInput = document.getElementById("product-search");
  const resultsContainer = document.getElementById("search-results");

  let searchTimeout;

  searchInput.addEventListener("input", function () {
    clearTimeout(searchTimeout);

    const query = this.value.trim();

    if (query.length < 2) {
      resultsContainer.innerHTML = "";
      resultsContainer.style.display = "none";
      return;
    }

    searchTimeout = setTimeout(async () => {
      const response = await fetch("/wp-admin/admin-ajax.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({
          action: "live_product_search",
          search: query,
          nonce: filter_products,
        }),
      });

      const html = await response.text();

      resultsContainer.innerHTML =
        html ||
        `<div class="no-products">${window.location.hostname.startsWith("en.") ? "Nothing found" : "Ничего не найдено"}</div>`;
      resultsContainer.style.display = "flex";

      initFirstVariation("li.products__item");
      activateFirstBtn("type-product", "quantity-btn", "quantity-btn__active");
      clickBtnVariation("li.products__item");
      activeAnotherBtn("type-product", "quantity-btn", "quantity-btn__active");

      if (
        document.querySelector("#category-products").style.display !== "none"
      ) {
        document.querySelector("#category-products").style.display = "none";
      }

      if (document.querySelector("#load-more-btn").style.display !== "none") {
        document.querySelector("#load-more-btn").style.display = "none";
      }

      try {
        document
          .querySelectorAll(".catalog-flex-filter__item")
          .forEach((el) => {
            if (el.classList.contains("catalog-flex-filter__item-active")) {
              el.classList.remove("catalog-flex-filter__item-active");
            }
          });
      } catch (error) {}
    }, 400);
  });

  // ************

  // Раскрывающийся список вопрос-ответ

  document.querySelectorAll(".faq-repeater-item").forEach((el) => {
    el.addEventListener("click", () => {
      document
        .querySelectorAll(".faq-repeater-item-active")
        .forEach((active) => {
          active.classList.remove("faq-repeater-item-active");
        });
      el.classList.add("faq-repeater-item-active");
    });
  });

  // **********************************
});
