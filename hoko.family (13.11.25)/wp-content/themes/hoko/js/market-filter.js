// Фильтр для товаров по категориям

class ProductFilter {
  constructor() {
    this.buttons = document.querySelectorAll(".filter-btn");
    this.container = document.getElementById("products-container");
    this.init();
  }

  init() {
    this.buttons.forEach((button) => {
      button.removeEventListener("click", this.handleFilter); // Удаляем старые обработчики
      button.addEventListener("click", (e) => this.handleFilter(e));
    });
  }

  async handleFilter(event) {
    const button = event.currentTarget;
    const category = button.dataset.category;

    this.buttons.forEach((btn) => btn.classList.remove("filter-active"));
    button.classList.add("filter-active");

    try {
      const response = await this.fetchProducts(category);
      this.container.innerHTML = response;
    } catch (error) {
      console.error("Filter error:", error);
      this.container.innerHTML = "<p>Ошибка загрузки товаров</p>";
    }
  }

  async fetchProducts(category) {
    const formData = new FormData();
    formData.append("action", "filter_products");
    formData.append("category", category);
    formData.append("nonce", marketFilter.nonce);

    const response = await fetch(marketFilter.ajax_url, {
      method: "POST",
      body: formData,
    });

    if (!response.ok) {
      throw new Error(`HTTP error: ${response.status}`);
    }

    const data = await response.json();
    if (data.success) {
      return data.data;
    }
    throw new Error(data.data || "AJAX error");
  }
}

document.addEventListener("DOMContentLoaded", () => {
  new ProductFilter();
});
