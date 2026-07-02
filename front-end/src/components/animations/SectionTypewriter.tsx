"use client";

import {
  createContext,
  useContext,
  useRef,
  type ReactNode,
} from "react";
import { useInView } from "framer-motion";
import { sectionHeadingDelays } from "@/lib/typewriter-timing";

type SectionTypewriterContextValue = {
  inView: boolean;
  contentBaseDelay: number;
  titleDelay: number;
  subtitleDelay: number;
  titleSpeed: number;
  subtitleSpeed: number;
};

const SectionTypewriterContext =
  createContext<SectionTypewriterContextValue | null>(null);

export function useSectionTypewriter() {
  return useContext(SectionTypewriterContext);
}

export function SectionTypewriter({
  title,
  subtitle,
  baseDelay = 0,
  children,
}: {
  title: string;
  subtitle?: string;
  baseDelay?: number;
  children: ReactNode;
}) {
  const ref = useRef<HTMLDivElement>(null);
  const inView = useInView(ref, { once: true, margin: "-80px" });
  const delays = sectionHeadingDelays(title, subtitle, baseDelay);

  return (
    <SectionTypewriterContext.Provider value={{ inView, ...delays }}>
      <div ref={ref}>{children}</div>
    </SectionTypewriterContext.Provider>
  );
}
