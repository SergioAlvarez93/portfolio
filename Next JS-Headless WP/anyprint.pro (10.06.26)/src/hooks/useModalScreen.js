import { useState, useEffect } from "react";

export const useModalScreen = () => {
  const [modalId, setModalId] = useState(null);

  const changeModalId = (id) => setModalId(id);

  const closeModal = () => setModalId(null);

  useEffect(() => {
    if (modalId) {
      document.body.style.overflow = "hidden";
    } else {
      document.body.style.overflow = "";
    }

    return () => {
      document.body.style.overflow = "";
    };
  }, [modalId]);

  useEffect(() => {
    const handleOutsideClick = (e) => {
      if (e.target.dataset.modalOverlay !== undefined) {
        setModalId(null);
      }
    };

    if (modalId) {
      document.addEventListener("click", handleOutsideClick);
    }

    return () => document.removeEventListener("click", handleOutsideClick);
  }, [modalId]);

  useEffect(() => {
    const handleEscape = (e) => {
      if (e.key === "Escape") setModalId(null);
    };

    if (modalId) {
      document.addEventListener("keydown", handleEscape);
    }

    return () => document.removeEventListener("keydown", handleEscape);
  }, [modalId]);

  return { modalId, changeModalId, closeModal };
};
