import { useState, useEffect, useRef } from "react";

export const useViewportWidth = () => {
  const [width, setWidth] = useState(null);

  const widthRef = useRef(width);

  useEffect(() => {
    widthRef.current = width;
  }, [width]);

  useEffect(() => {
    const changeVersion = () => {
      const newValue = window.innerWidth > 767 ? "desktop" : "mobile";
      setWidth(newValue);
      widthRef.current = newValue;
    };
    changeVersion();

    const handleResize = () => {
      if (window.innerWidth > 767 && widthRef.current === "mobile") {
        setWidth("desktop");
      }
      if (window.innerWidth < 768 && widthRef.current === "desktop") {
        setWidth("mobile");
      }
    };

    window.addEventListener("resize", handleResize);
    return () => window.removeEventListener("resize", changeVersion);
  }, []);

  return { width };
};
