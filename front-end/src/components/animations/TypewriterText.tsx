"use client";

import { useEffect, useRef, useState } from "react";
import { useInView, useReducedMotion } from "framer-motion";

type Props = {
  text: string;
  className?: string;
  delay?: number;
  speedMs?: number;
  startWhenInView?: boolean;
  showCursor?: boolean;
  inView?: boolean;
  as?: "span" | "p" | "h1" | "h2" | "h3";
  inline?: boolean;
};

export function TypewriterText({
  text,
  className,
  delay = 0,
  speedMs = 14,
  startWhenInView = false,
  showCursor = false,
  inView: inViewProp,
  as: Tag = "span",
  inline = false,
}: Props) {
  const containerRef = useRef<HTMLDivElement>(null);
  const internalInView = useInView(containerRef, {
    once: true,
    margin: "-40px",
  });
  const inView = inViewProp ?? internalInView;
  const prefersReducedMotion = useReducedMotion();
  const [charCount, setCharCount] = useState(0);
  const [started, setStarted] = useState(false);

  const shouldAnimate =
    !prefersReducedMotion && (!startWhenInView || inView);

  useEffect(() => {
    setCharCount(0);
    setStarted(false);
  }, [text]);

  useEffect(() => {
    if (!shouldAnimate) {
      setCharCount(text.length);
      return;
    }

    if (!started) {
      if (startWhenInView && !inView) return;
      const t = setTimeout(() => setStarted(true), delay);
      return () => clearTimeout(t);
    }

    if (charCount >= text.length) return;

    const t = setTimeout(() => setCharCount((c) => c + 1), speedMs);
    return () => clearTimeout(t);
  }, [
    shouldAnimate,
    started,
    charCount,
    text,
    delay,
    speedMs,
    inView,
    startWhenInView,
  ]);

  const visibleText =
    prefersReducedMotion || !shouldAnimate ? text : text.slice(0, charCount);

  const cursor =
    showCursor && shouldAnimate && charCount < text.length ? (
      <span
        aria-hidden
        className="ml-px inline-block h-[1em] w-[2px] animate-pulse bg-current align-[-0.1em]"
      />
    ) : null;

  if (inline) {
    return (
      <span ref={containerRef} className="inline">
        <Tag className={className}>
          {visibleText}
          {cursor}
        </Tag>
      </span>
    );
  }

  return (
    <div ref={containerRef}>
      <Tag className={className}>
        {visibleText}
        {cursor}
      </Tag>
    </div>
  );
}
