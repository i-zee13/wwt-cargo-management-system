"use client";

import { useRef } from "react";
import { useInView } from "framer-motion";
import { FadeIn } from "@/components/animations/FadeIn";
import { TypewriterText } from "@/components/animations/TypewriterText";
import { Container } from "@/components/shared/Container";
import { BlockMedia, type MediaLayout } from "@/components/shared/BlockMedia";
import { CTAButton } from "@/components/shared/CTAButton";
import type { VisualTheme } from "@/components/shared/VisualPanel";
import {
  BODY_SPEED_MS,
  TITLE_SPEED_MS,
  chainDelays,
  typewriterMs,
} from "@/lib/typewriter-timing";
import clsx from "clsx";

export type BlockLayout = "split" | "mosaic" | "collage";

export type ImageTextItem = {
  title: string;
  description: string;
  bullets?: string[];
  visual?: VisualTheme;
  visualLabel: string;
  image?: string;
  images?: string[];
  imageGrid?: boolean;
  layout?: BlockLayout;
  reversed?: boolean;
  ctaLabel?: string;
  ctaHref?: string;
};

export function ImageTextBlock({
  item,
  className,
}: {
  item: ImageTextItem;
  className?: string;
}) {
  const reversed = item.reversed ?? false;
  const layout = item.layout ?? (item.images && item.images.length >= 3 ? "mosaic" : "split");
  const ref = useRef<HTMLDivElement>(null);
  const inView = useInView(ref, { once: true, margin: "-80px" });

  const bulletDelays = item.bullets
    ? (() => {
        const descDelay =
          typewriterMs(item.title, TITLE_SPEED_MS) +
          280 +
          typewriterMs(item.description, BODY_SPEED_MS) +
          250;
        return chainDelays(
          descDelay,
          item.bullets.map((bullet) => ({
            text: bullet,
            speedMs: BODY_SPEED_MS,
            pauseAfter: 300,
          }))
        );
      })()
    : [];

  const descriptionDelay = typewriterMs(item.title, TITLE_SPEED_MS) + 280;
  const mediaDelay =
    (item.bullets?.length
      ? bulletDelays[bulletDelays.length - 1] +
        typewriterMs(item.bullets[item.bullets.length - 1], BODY_SPEED_MS) +
        400
      : descriptionDelay +
        typewriterMs(item.description, BODY_SPEED_MS) +
        400) / 1000;

  const mediaLayout: MediaLayout =
    layout === "collage"
      ? "collage"
      : layout === "mosaic"
        ? "mosaic"
        : "single";

  const textContent = (
    <div className="max-w-xl lg:max-w-none">
      <TypewriterText
        text={item.title}
        as="h2"
        startWhenInView
        inView={inView}
        speedMs={TITLE_SPEED_MS}
        className="font-heading text-2xl font-bold tracking-tight text-navy sm:text-3xl"
      />
      <TypewriterText
        text={item.description}
        as="p"
        delay={descriptionDelay}
        startWhenInView
        inView={inView}
        speedMs={BODY_SPEED_MS}
        className="mt-4 text-base leading-relaxed text-slate-600"
      />
      {item.bullets && item.bullets.length > 0 && (
        <ul className="mt-6 space-y-2">
          {item.bullets.map((bullet, i) => (
            <li
              key={bullet}
              className="flex items-start gap-2 text-sm text-slate-600"
            >
              <span className="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-accent-500" />
              <TypewriterText
                text={bullet}
                delay={bulletDelays[i]}
                startWhenInView
                inView={inView}
                speedMs={BODY_SPEED_MS}
              />
            </li>
          ))}
        </ul>
      )}
      {item.ctaLabel && item.ctaHref && (
        <div className="mt-8">
          <CTAButton href={item.ctaHref}>{item.ctaLabel}</CTAButton>
        </div>
      )}
    </div>
  );

  const mediaContent = (
    <FadeIn delay={mediaDelay}>
      <BlockMedia
        image={item.image}
        images={item.images}
        imageGrid={item.imageGrid}
        layout={mediaLayout}
        visual={item.visual}
        visualLabel={item.visualLabel}
      />
    </FadeIn>
  );

  return (
    <section className={clsx("py-16 sm:py-20", className)}>
      <Container>
        <div ref={ref}>
          {layout === "split" && !item.images?.length ? (
            <div
              className={clsx(
                "grid items-center gap-10 lg:grid-cols-2 lg:gap-14",
                reversed && "lg:[&>*:first-child]:order-2"
              )}
            >
              {textContent}
              {mediaContent}
            </div>
          ) : (
            <div
              className={clsx(
                "grid items-center gap-10 lg:gap-14",
                layout === "collage"
                  ? "lg:grid-cols-5"
                  : "lg:grid-cols-2",
                reversed && "lg:[&>*:first-child]:order-2"
              )}
            >
              <div
                className={clsx(
                  layout === "collage" ? "lg:col-span-2" : undefined
                )}
              >
                {textContent}
              </div>
              <div
                className={clsx(
                  layout === "collage" ? "lg:col-span-3" : undefined
                )}
              >
                {mediaContent}
              </div>
            </div>
          )}
        </div>
      </Container>
    </section>
  );
}
