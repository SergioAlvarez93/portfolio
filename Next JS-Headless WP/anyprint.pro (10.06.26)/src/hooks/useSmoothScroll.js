import { useEffect } from "react";

export const useSmoothScroll = () => {
  useEffect(() => {
    const handleInitialScroll = () => {
      const hash = window.location.hash.slice(1);
      const pendingHash = sessionStorage.getItem("pendingHash");

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
        }, 120);
      }

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
          sessionStorage.removeItem("pendingHash");
        }, 150);
      }
    };

    handleInitialScroll();

    const handleLinkClick = (e) => {
      const link = e.currentTarget;
      const href = link.href;
      if (!href) return;

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

        const targetElement = hash ? document.getElementById(hash) : null;

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
          }, 600);
        } else {
          window.location.href = href;
        }
      }
    };

    const selector =
      '.navigation__item a[href^="' +
      window.location.origin +
      '"], .header-btn[href^="' +
      window.location.origin +
      '"]';

    const links = document.querySelectorAll(selector);

    links.forEach((link) => {
      link.addEventListener("click", handleLinkClick);
    });

    // Cleanup
    return () => {
      const currentLinks = document.querySelectorAll(selector);
      currentLinks.forEach((link) => {
        link.removeEventListener("click", handleLinkClick);
      });
    };
  }, []);
};
