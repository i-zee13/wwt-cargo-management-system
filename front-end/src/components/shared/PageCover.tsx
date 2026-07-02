"use client";

import Image from "next/image";
import { Container } from "@/components/shared/Container";
import { TypewriterText } from "@/components/animations/TypewriterText";
import {
  BODY_SPEED_MS,
  TITLE_SPEED_MS,
  typewriterMs,
} from "@/lib/typewriter-timing";
import clsx from "clsx";

type Props = {
  title: string;
  subtitle?: string;
  image: string;
  imageAlt?: string;
  align?: "left" | "center";
};

export function PageCover({
  title,
  subtitle,
  image,
  imageAlt,
  align = "left",
}: Props) {
  const titleDelay = 200;
  const subtitleDelay = titleDelay + typewriterMs(title, TITLE_SPEED_MS) + 450;

  return (
    <section className="relative min-h-[340px] overflow-hidden sm:min-h-[420px]">
      <Image
        src={image}
        alt={imageAlt ?? title}
        fill
        priority
        className="object-cover"
        sizes="100vw"
      />
      <div className="absolute inset-0 bg-gradient-to-r from-navy/92 via-brand-600/85 to-brand-600/55" />
      <div className="absolute inset-0 bg-[radial-gradient(circle_at_80%_20%,rgba(235,179,10,0.12),transparent_45%)]" />

      <Container
        className={clsx(
          "relative z-10 flex min-h-[340px] flex-col justify-end pb-12 pt-[5.5rem] sm:min-h-[420px] sm:pb-16 sm:pt-28",
          align === "center" && "items-center text-center"
        )}
      >
        <div className={clsx("max-w-3xl", align === "center" && "mx-auto")}>
          <TypewriterText
            text={title}
            as="h1"
            delay={titleDelay}
            speedMs={TITLE_SPEED_MS}
            className="font-heading text-3xl font-bold uppercase tracking-wider text-white sm:text-4xl lg:text-5xl"
          />
          {subtitle && (
            <TypewriterText
              text={subtitle}
              as="p"
              delay={subtitleDelay}
              speedMs={BODY_SPEED_MS}
              className="mt-4 max-w-2xl text-base leading-relaxed text-brand-100 sm:text-lg"
            />
          )}
        </div>
      </Container>
    </section>
  );
}
