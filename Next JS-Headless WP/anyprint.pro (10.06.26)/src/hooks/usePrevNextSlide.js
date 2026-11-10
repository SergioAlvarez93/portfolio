import { useRef, useState, useCallback, useEffect } from "react";

export const usePrevNextSlide = (quantity) => {
  const [index, setIndex] = useState(0);
  const prev = useRef(null);
  const next = useRef(null);
  const currentSlider = useRef(null);
  const statXCoordinate = useRef(0);
  const finishXCoordinate = useRef(0);
  const isDraggable = useRef(false);
  const hasMoved = useRef(false);

  const changeIndex = useCallback(
    (eventOrIndex) => {
      if (typeof eventOrIndex === "number") {
        setIndex(eventOrIndex);
        return;
      }

      const direction = eventOrIndex.currentTarget.getAttribute("act");
      if (direction === prev.current?.getAttribute("act") && index > 0) {
        setIndex((prev) => prev - 1);
      }
      if (
        direction === next.current?.getAttribute("act") &&
        index < quantity - 1
      ) {
        setIndex((prev) => prev + 1);
      }
    },
    [index, quantity],
  );

  const startSlideMoving = useCallback((event) => {
    const clientX = event.touches ? event.touches[0].clientX : event.clientX;
    statXCoordinate.current = clientX;
    isDraggable.current = true;
    hasMoved.current = false;
  }, []);

  const duringSlideMoving = useCallback((event) => {
    if (!isDraggable.current) return;
    const clientX = event.touches ? event.touches[0].clientX : event.clientX;
    finishXCoordinate.current = clientX;
    hasMoved.current = true;
  }, []);

  const finishSlideMoving = useCallback(() => {
    if (!isDraggable.current) return;
    isDraggable.current = false;
    if (!hasMoved.current) return;

    const movingWidth = statXCoordinate.current - finishXCoordinate.current;
    const minimalCoordinateWidth = 25;

    if (Math.abs(movingWidth) > minimalCoordinateWidth) {
      if (movingWidth > 0 && index < quantity - 1) {
        setIndex((prev) => prev + 1);
      }
      if (movingWidth < 0 && index > 0) {
        setIndex((prev) => prev - 1);
      }
    }
  }, [quantity, index]);

  const initPossibilityToMoveSides = useCallback(
    (element) => {
      if (!element) return;

      element.addEventListener("touchstart", startSlideMoving, {
        passive: true,
      });
      element.addEventListener("touchmove", duringSlideMoving, {
        passive: true,
      });
      element.addEventListener("touchend", finishSlideMoving);
      element.addEventListener("mousedown", startSlideMoving);
      element.addEventListener("mousemove", duringSlideMoving);
      element.addEventListener("mouseup", finishSlideMoving);

      currentSlider.current = element;
    },
    [startSlideMoving, duringSlideMoving, finishSlideMoving],
  );

  useEffect(() => {
    const element = currentSlider.current;
    return () => {
      if (element) {
        element.removeEventListener("touchstart", startSlideMoving);
        element.removeEventListener("touchmove", duringSlideMoving);
        element.removeEventListener("touchend", finishSlideMoving);
        element.removeEventListener("mousedown", startSlideMoving);
        element.removeEventListener("mousemove", duringSlideMoving);
        element.removeEventListener("mouseup", finishSlideMoving);
      }
    };
  }, [startSlideMoving, duringSlideMoving, finishSlideMoving]);

  return { changeIndex, initPossibilityToMoveSides, index, prev, next };
};
