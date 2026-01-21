// Запуск модального окна с деталями продукта
document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById("product-modal");
  const closeBtn = document.querySelector(".modal-close");
  const content = document.getElementById("modal-product-data");
  const bodyCondition = document.body;
  const productContainer = document.getElementById("products-container");
  const showcaseContainer = document.querySelector(".showcase-list");

  [productContainer, showcaseContainer].forEach((container) => {
    if (container) {
      container.addEventListener("click", (e) => {
        if (e.target.classList.contains("modal-trigger")) {
          e.preventDefault();
          const productId = e.target.dataset.productId;
          content.innerHTML = "<p>Загрузка...</p>";
          fetchProductData(productId);
          modal.style.display = "block";
          bodyCondition.classList.add("body-blocked");
        }
      });
    }
  });

  closeBtn.addEventListener("click", () => {
    modal.style.display = "none";
    content.innerHTML = "";
    bodyCondition.classList.remove("body-blocked");
  });

  window.addEventListener("click", (e) => {
    if (e.target === modal) {
      modal.style.display = "none";
      content.innerHTML = "";
      bodyCondition.classList.remove("body-blocked");
    }
  });

  async function fetchProductData(productId) {
    const formData = new FormData();
    formData.append("action", "get_product_modal_data");
    formData.append("product_id", productId);
    formData.append("nonce", market_ajax.nonce);

    try {
      const response = await fetch(market_ajax.ajax_url, {
        method: "POST",
        body: formData,
      });
      const data = await response.json();
      if (data.success) {
        content.innerHTML = data.data;

        const modalProductSwiper = new Swiper(".modal-product-slider", {
          autoHeight: true,
          loop: true,
          navigation: {
            nextEl: ".modal-product-slider__next-btn",
          },
          breakpoints: {
            768: {
              direction: "vertical",
              slidesPerView: "auto",
              spaceBetween: 13,
            },
          },
        });

        const allSlides = document.querySelectorAll(
          ".modal-product-slider__slide"
        );
        allSlides.forEach((slide) => {
          slide.addEventListener("click", () => {
            const srcAttr = slide.lastElementChild.getAttribute("src");
            const altAttr = slide.lastElementChild.getAttribute("alt");
            const mainImg = document.querySelector(
              ".modal-product-flexbox-gallery__main-item"
            );
            if (mainImg) {
              mainImg.setAttribute("src", srcAttr);
              mainImg.setAttribute("alt", altAttr);
            }
          });
        });
      }
    } catch (error) {
      console.error("Error:", error);
      content.innerHTML = "<p>Ошибка загрузки</p>";
    }
  }
});
