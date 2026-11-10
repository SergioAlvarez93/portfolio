function initVKGroupsWidget() {
  try {
    const container = document.getElementById('vk_groups');
    const parentWidth = container.parentElement.offsetWidth;
    container.innerHTML = '';

    VK.Widgets.Group(
      'vk_groups',
      {
        mode: 4,
        wide: 1,
        width: parentWidth,
        height: 800,
        color1: 'FFFFFF',
        color2: '000000',
        color3: '5181B8',
      },
      151162106
    );
  } catch (error) {}
}

try {
  const swiper = new Swiper('.mission-slider', {
    loop: true,
    autoplay: true,
    speed: 1500,
  });
} catch (error) {}

document.addEventListener('DOMContentLoaded', (event) => {
  window.addEventListener('scroll', function () {
    const header = document.querySelector('.header');
    if (window.scrollY > 0) {
      header.classList.add('scrolled');
    } else {
      header.classList.remove('scrolled');
    }
  });

  const burgerTab = document.querySelector('.header__burger');
  const navbar = document.querySelector('.navbar');
  burgerTab.addEventListener('click', function (e) {
    burgerTab.classList.toggle('active');
    navbar.classList.toggle('active');
    document.querySelector('body').classList.toggle('lock');
  });

  [...document.querySelectorAll('.navbar-flexbox__item_link')].forEach((e) => {
    e.addEventListener('click', (event) => {
      burgerTab.classList.remove('active');
      navbar.classList.remove('active');
      document.querySelector('body').classList.remove('lock');
    });
  });

  try {
    function adjustContentMargin() {
      const header = document.querySelector('.header');
      const underHeader = document.querySelector('.under-header');
      const navbarHeight = header.offsetHeight;
      try {
        underHeader.style.marginTop = navbarHeight + 'px';
      } catch (error) {}
    }
    window.addEventListener('load', adjustContentMargin);
    window.addEventListener('resize', adjustContentMargin);
  } catch (error) {}

  function initVKWidget() {
    VK.Widgets.Group('vk_widget', { mode: 3, width: 'auto', height: '400' }, 1);
  }

  initVKGroupsWidget();

  function debounce(func, wait) {
    let timeout;
    return function (...args) {
      clearTimeout(timeout);
      timeout = setTimeout(() => func.apply(this, args), wait);
    };
  }

  window.addEventListener(
    'resize',
    debounce(function () {
      initVKGroupsWidget();
    }, 250)
  );

  try {
    const element = document.getElementById('tel');
    const maskOptions = {
      mask: '+{7}(000)000-00-00',
    };
    const mask = IMask(element, maskOptions);
  } catch (error) {}

  sal({
    threshold: 0.05,
  });

  const currHeaderHeight = () => document.querySelector('.header').offsetHeight;

  function scrollToElementSmoothly(element) {
    const position = element.offsetTop - currHeaderHeight();
    setTimeout(function () {
      window.scrollTo({
        behavior: 'smooth',
        top: position,
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
      const offset = targetElement.offsetTop - currHeaderHeight();
      window.scrollTo({
        top: offset,
        behavior: 'smooth',
      });
    }
  }

  try {
    document.addEventListener('scroll', function () {
      const scrollPosition = window.scrollY;
      const parallaxImage = document.querySelector('.parallax-img');

      const parallaxSpeed = 0.5;
      try {
        parallaxImage.style.transform = `translateX(-50%) translateY(${
          scrollPosition * parallaxSpeed
        }px)`;
      } catch (error) {}
    });
  } catch (error) {}

  try {
    const telegram = main_vars.telegram;

    if (telegram) {
      document.querySelector('.form__tg').setAttribute('href', `${telegram}`);
    } else {
      document.querySelector('.form__tg').style.display = 'none';
    }
  } catch (error) {}
});
