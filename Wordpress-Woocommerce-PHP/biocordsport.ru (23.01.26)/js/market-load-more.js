// СКРИПТ КНОПКИ "ПОКАЗАТЬ БОЛЬШЕ"

document.addEventListener("DOMContentLoaded", initLoadMore);
document.addEventListener("marketLoadMoreReinit", initLoadMore);

function initLoadMore() {
  const button = document.querySelector(".market-load-more");
  if (!button || button.dataset.bound === "true") return;

  button.dataset.bound = "true";

  const container = document.getElementById("products-container");
  let loaded = parseInt(button.dataset.loaded) || 0;
  const total = parseInt(button.dataset.total) || 0;
  const category = button.dataset.category || "all"; // ← ЧИТАЕМ КАТЕГОРИЮ С КНОПКИ

  const getPerPage = () => (window.innerWidth <= 767 ? 3 : 3);

  button.addEventListener("click", () => {
    if (loaded >= total) {
      button.remove();
      return;
    }

    button.textContent = "Загружаем...";
    button.disabled = true;

    fetch(marketLoadMore.ajax_url, {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: new URLSearchParams({
        action: "market_loadmore",
        nonce: marketLoadMore.nonce,
        offset: loaded,
        limit: getPerPage(),
        category: category, // ← ПЕРЕДАЁМ КАТЕГОРИЮ НА СЕРВЕР
      }),
    })
      .then((r) => r.text())
      .then((html) => {
        if (html.trim()) {
          container.insertAdjacentHTML("beforeend", html);

          // Считаем точно сколько добавили
          const added = (html.match(/<li/g) || []).length;
          loaded += added;
          button.dataset.loaded = loaded;

          if (loaded >= total) {
            button.remove();
          } else {
            button.textContent = "Показать ещё";
            button.disabled = false;
          }
        } else {
          button.remove();
        }
      })
      .catch(() => {
        button.textContent = "Ошибка";
        button.disabled = false;
      });
  });
}

// *******************************
