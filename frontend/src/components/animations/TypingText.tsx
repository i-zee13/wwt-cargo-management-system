"use client";

import { useEffect, useState } from "react";
import { useReducedMotion } from "framer-motion";

/**
 * Cycles through `words`, typing and deleting each one. Falls back to a
 * static render of the first word when the user prefers reduced motion.
 */
export function TypingText({
  words,
  className,
  typingSpeedMs = 55,
  deletingSpeedMs = 30,
  pauseMs = 1600,
}: {
  words: string[];
  className?: string;
  typingSpeedMs?: number;
  deletingSpeedMs?: number;
  pauseMs?: number;
}) {
  const prefersReducedMotion = useReducedMotion();
  const [wordIndex, setWordIndex] = useState(0);
  const [charCount, setCharCount] = useState(0);
  const [isDeleting, setIsDeleting] = useState(false);

  useEffect(() => {
    if (prefersReducedMotion || words.length === 0) return;

    const currentWord = words[wordIndex % words.length];

    if (!isDeleting && charCount === currentWord.length) {
      const pause = setTimeout(() => setIsDeleting(true), pauseMs);
      return () => clearTimeout(pause);
    }

    if (isDeleting && charCount === 0) {
      setIsDeleting(false);
      setWordIndex((i) => (i + 1) % words.length);
      return;
    }

    const timeout = setTimeout(
      () => setCharCount((c) => c + (isDeleting ? -1 : 1)),
      isDeleting ? deletingSpeedMs : typingSpeedMs
    );

    return () => clearTimeout(timeout);
  }, [
    charCount,
    isDeleting,
    wordIndex,
    words,
    prefersReducedMotion,
    typingSpeedMs,
    deletingSpeedMs,
    pauseMs,
  ]);

  if (prefersReducedMotion || words.length === 0) {
    return <span className={className}>{words[0]}</span>;
  }

  const currentWord = words[wordIndex % words.length];

  return (
    <span className={className}>
      {currentWord.slice(0, charCount)}
      <span aria-hidden className="border-r-2 border-current animate-pulse ml-0.5" />
    </span>
  );
}
