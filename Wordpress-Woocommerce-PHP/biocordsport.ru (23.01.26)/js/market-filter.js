// СКРИПТ ФИЛЬТРА МАГАЗИНА

class ProductFilter {
  constructor() {
    this.buttons = document.querySelectorAll(".filter-btn");
    this.container = document.getElementById("products-container");
    this.loading = document.getElementById("loading-indicator");
    this.init();
  }

  init() {
    this.buttons.forEach((btn) => {
      btn.addEventListener("click", this.handleFilter.bind(this));
    });
  }

  async handleFilter(e) {
    const button = e.currentTarget;
    const category = button.dataset.category || "all";

    // Активируем кнопку
    this.buttons.forEach((b) => b.classList.remove("filter-active"));
    button.classList.add("filter-active");

    if (this.loading) this.loading.style.display = "block";

    try {
      const data = await this.fetchProducts(category);

      // Заменяем товары
      this.container.innerHTML = data.html;

      // Удаляем старую кнопку
      const oldButton = document.querySelector(".market-load-more");
      if (oldButton) oldButton.remove();

      // Добавляем новую кнопку с сохранением категории
      if (data.button) {
        const wrapper = document.createElement("div");
        wrapper.innerHTML = data.button;
        const newButton = wrapper.firstElementChild;

        // ← КРИТИЧНО: сохраняем текущую категорию
        newButton.dataset.category = category;

        // Вставляем после контейнера
        this.container.insertAdjacentElement("afterend", newButton);

        // Перезапускаем load-more
        this.reinitLoadMore();
      }
    } catch (err) {
      console.error("Ошибка фильтра:", err);
      this.container.innerHTML = "<p>Ошибка загрузки товаров</p>";
    } finally {
      if (this.loading) this.loading.style.display = "none";
    }
  }

  async fetchProducts(category) {
    const formData = new FormData();
    formData.append("action", "filter_products");
    formData.append("category", category);
    formData.append("nonce", marketFilter.nonce);
    formData.append("per_page", window.innerWidth <= 767 ? "3" : "3");

    const res = await fetch(marketFilter.ajax_url, {
      method: "POST",
      body: formData,
    });

    if (!res.ok) throw new Error("Network error");

    const json = await res.json();
    if (!json.success) throw new Error(json.data || "Server error");

    return json.data;
  }

  reinitLoadMore() {
    document.dispatchEvent(new CustomEvent("marketLoadMoreReinit"));
  }
}

// Запуск
new ProductFilter();

// ***********************
