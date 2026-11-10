function zoom() {
  if (outerWidth > 1440) {
    document.body.style.zoom = (outerWidth * 100) / 1440 + "%";
  }
  if (outerWidth < 1441) {
    document.body.style.zoom = "unset";
  }
}

zoom();
window.addEventListener("resize", zoom);

document.addEventListener("DOMContentLoaded", () => {
  const prevSwiper = new Swiper(".preview-swiper", {
    autoplay: {
      delay: 10000,
    },
    loop: "auto",
    pagination: {
      el: ".preview-pagination",
      clickable: true,
    },
  });

  const gallerySwiper = new Swiper(".gallery-swiper", {
    spaceBetween: 40,
    slidesPerView: 4.4167,
    freeMode: true,
    pagination: {
      el: ".gallery-pagination",
      clickable: true,
    },
    breakpoints: {
      360: {
        slidesPerView: 1.5,
      },
      768: {
        slidesPerView: 2.5,
      },
      992: {
        slidesPerView: 4,
      },
    },
  });

  Fancybox.bind('[data-fancybox="gallery"]', {
    on: {
      reveal: (fancybox, slide) => {
        document.body.style.zoom = "unset";
      },
      close: (fancybox, slide) => {
        zoom();
      },
    },
    autoSize: true,
  });

  function callPhoneFn() {
    const callPhone = document.querySelector(".header-btn");
    if (innerWidth > 1023) {
      callPhone.innerHTML = 'Обратный звонок <span class="tel-icon"></span>';
    } else {
      callPhone.innerHTML = '<span class="tel-icon"></span>';
    }
  }

  callPhoneFn();

  window.addEventListener("resize", (event) => {
    callPhoneFn();
  });

  try {
    const productItems = document.querySelectorAll(".product-grid__item");
    const products = Array.from(productItems);
    const productsLegth = products.length;
    const loadMoreBtn = document.getElementById("load-more");
    const productsBtnBox = document.querySelector(".product-buttons");

    loadMoreBtn.addEventListener("click", (el) => {
      let count = 0;
      if (innerWidth > 1023) {
        products.forEach((element, index) => {
          if (index < 9) {
            element.style.display = "flex";
          }
          if (count < 9) {
            let styles = window.getComputedStyle(element);
            if (styles.display === "none") {
              element.style.display = "flex";
              count++;
              if (index === productsLegth - 1) {
                loadMoreBtn.style.display = "none";
                productsBtnBox.style.justifyContent = "center";
              }
            }
          }
        });
      }
      if (innerWidth < 1024 && innerWidth > 767) {
        products.forEach((element, index) => {
          if (index < 6) {
            element.style.display = "flex";
          }
          if (count < 6) {
            let styles = window.getComputedStyle(element);
            if (styles.display === "none") {
              element.style.display = "flex";
              count++;
              if (index === productsLegth - 1) {
                loadMoreBtn.style.display = "none";
                productsBtnBox.style.justifyContent = "center";
              }
            }
          }
        });
      }
      if (innerWidth < 768) {
        products.forEach((element, index) => {
          if (index < 3) {
            element.style.display = "flex";
          }
          if (count < 3) {
            let styles = window.getComputedStyle(element);
            if (styles.display === "none") {
              element.style.display = "flex";
              count++;
              if (index === productsLegth - 1) {
                loadMoreBtn.style.display = "none";
                productsBtnBox.style.justifyContent = "center";
              }
            }
          }
        });
      }
    });
  } catch (error) {}

  const maskOptions = {
    mask: "+{7}(000)000-00-00",
  };

  try {
    const tel = document.querySelectorAll(".contant-form__tel");
    const telArr = [...tel];
    telArr.forEach((el) => {
      IMask(el, maskOptions);
    });
  } catch (error) {}

  const navHeightEl = document.querySelector(".header");
  const firstScreen = document.querySelector(".preview");

  const navHeight = navHeightEl.offsetHeight;
  firstScreen.style.paddingTop = navHeight + "px";

  const orderButtons = document.querySelectorAll(
    ".product-grid__item-price-box-btn"
  );

  orderButtons.forEach((button) => {
    button.addEventListener("click", function (event) {
      console.log("Button clicked");
      const productTitleElement = this.closest(
        ".product-grid__item"
      ).querySelector(".product-grid__item-title");
      if (!productTitleElement) {
        console.error("Product title not found");
        return;
      }
      const productTitle = productTitleElement.innerText;
      console.log("Product title:", productTitle);

      const questionField = document.querySelector(".contant-form__question");
      if (!questionField) {
        console.error("Text field not found");
        return;
      }
      console.log("Text field found");

      questionField.value = `Здравствуйте, интересуют эти витамины: "${productTitle}".`;
      console.log("Text field value set");

      const contactsSection = document.getElementById("contacts");
      if (!contactsSection) {
        console.error("Contacts section not found");
        return;
      }
      console.log("Contacts section found, scrolling...");
      contactsSection.scrollIntoView({ behavior: "smooth" });
    });
  });

  const burger = document.querySelector(".burger-btn");
  const nav = document.querySelector(".navigation");

  burger.addEventListener("click", () => {
    nav.classList.toggle("active");
    burger.classList.toggle("active");
    document.querySelector("body").classList.toggle("lock");
  });

  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault();
      let target = document.querySelector(this.getAttribute("href"));
      let navbarHeight = document.querySelector(".header").offsetHeight;
      let offset =
        target.getBoundingClientRect().top + window.scrollY - navbarHeight;

      nav.classList.remove("active");
      burger.classList.remove("active");
      document.querySelector("body").classList.remove("lock");

      window.scrollTo({
        top: offset,
        behavior: "smooth",
      });
    });
  });

  const modal = document.getElementById("modal");
  const openBtn = document.querySelector(".header-btn");
  const closeBtn = document.querySelector(".modal-close");

  openBtn.addEventListener("click", function () {
    modal.style.display = "block";
    document.body.classList.toggle("lock-btn");
    document.body.style.zoom = "unset";
  });

  closeBtn.addEventListener("click", function () {
    modal.style.display = "none";
    document.body.classList.remove("lock-btn");
    zoom();
  });

  modal.addEventListener("click", function (e) {
    if (e.target === modal) {
      modal.style.display = "none";
      document.body.classList.remove("lock-btn");
      zoom();
    }
  });

  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
      modal.style.display = "none";
      document.body.classList.remove("lock-btn");
      zoom();
    }
  });
});
