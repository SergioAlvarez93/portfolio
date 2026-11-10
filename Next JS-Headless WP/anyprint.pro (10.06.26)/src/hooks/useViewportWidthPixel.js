import { useState, useEffect, useRef } from "react";

export const useViewportWidthPixel = () => {
  const [width, setWidth] = useState(null);

  const widthRef = useRef(width);

  useEffect(() => {
    widthRef.current = width;
  }, [width]);

  useEffect(() => {
    const changeVersion = () => {
      const newValue = window.innerWidth;
      setWidth(newValue);
      widthRef.current = newValue;
    };
    changeVersion();

    const handleResize = () => {
      const newWidth = window.innerWidth;
      if (newWidth !== widthRef.current) {
        setWidth(newWidth);
        widthRef.current = newWidth;
      }
    };

    window.addEventListener("resize", handleResize);
    return () => window.removeEventListener("resize", handleResize);
  }, []);

  return { width };
};
