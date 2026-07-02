"use client";

import clsx from "clsx";
import { TypewriterText } from "@/components/animations/TypewriterText";
import { useSectionTypewriter } from "@/components/animations/SectionTypewriter";
import {
  SUBTITLE_SPEED_MS,
  TITLE_SPEED_MS,
} from "@/lib/typewriter-timing";

export function SectionHeading({
  eyebrow,
  title,
  subtitle,
  align = "center",
  light = false,
  typeDelay = 0,
}: {
  eyebrow?: string;
  title: string;
  subtitle?: string;
  align?: "center" | "left";
  light?: boolean;
  typeDelay?: number;
}) {
  const section = useSectionTypewriter();

  const titleDelay = section?.titleDelay ?? typeDelay;
  const subtitleDelay =
    section?.subtitleDelay ?? typeDelay + 500;
  const titleSpeed = section?.titleSpeed ?? TITLE_SPEED_MS;
  const subtitleSpeed = section?.subtitleSpeed ?? SUBTITLE_SPEED_MS;
  const inView = section?.inView;

  return (
    <div
      className={clsx(
        "max-w-3xl",
        align === "center" ? "mx-auto text-center" : "text-left"
      )}
    >
      {eyebrow && (
        <TypewriterText
          text={eyebrow}
          as="p"
          delay={titleDelay}
          speedMs={titleSpeed}
          startWhenInView
          inView={inView}
          className={clsx(
            "mb-2 text-sm font-semibold uppercase tracking-widest",
            light ? "text-accent-400" : "text-accent-600"
          )}
        />
      )}
      <TypewriterText
        text={title}
        as="h2"
        delay={titleDelay}
        speedMs={titleSpeed}
        startWhenInView
        inView={inView}
        className={clsx(
          "font-heading text-3xl font-bold tracking-tight sm:text-4xl",
          light ? "text-white" : "text-navy"
        )}
      />
      {subtitle && (
        <TypewriterText
          text={subtitle}
          as="p"
          delay={subtitleDelay}
          speedMs={subtitleSpeed}
          startWhenInView
          inView={inView}
          className={clsx(
            "mt-4 text-lg leading-relaxed",
            light ? "text-brand-100" : "text-slate-600"
          )}
        />
      )}
    </div>
  );
}
