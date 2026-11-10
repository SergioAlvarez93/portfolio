import { useState, useEffect, useRef } from "react";

export default function toggleBurgerMenu() {
  const [state, toggleState] = useState(false);
  const gridRef = useRef(null);

  const initToggleState = () => toggleState((prev) => !prev);

  useEffect(() => {
    if (state) {
      document.body.style.overflow = "hidden";
    } else {
      document.body.style.overflow = "";
    }

    return () => {
      document.body.style.overflow = "";
    };
  }, [state]);

  useEffect(() => {
    const handleEscape = (e) => {
      if (e.key === "Escape") toggleState(false);
    };

    if (state) {
      document.addEventListener("keydown", handleEscape);
    }

    return () => document.removeEventListener("keydown", handleEscape);
  }, [state]);

  return { state, initToggleState, gridRef };
}
