import { useRef, useState, useEffect } from "react";

export const useFirstSectionMargin = () => {
  const headerRef = useRef(null);
  const [height, setHeight] = useState(0);

  useEffect(() => {
    const updateHeight = () =>
      setHeight(headerRef.current.getBoundingClientRect().height);
    updateHeight();
    window.addEventListener("resize", updateHeight);
    return () => window.removeEventListener("resize", updateHeight);
  }, []);

  return { headerRef, height };
};
