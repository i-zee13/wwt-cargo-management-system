"use client";

import { useTranslations } from "next-intl";
import { Container } from "@/components/shared/Container";
import { Logo } from "@/components/shared/Logo";
import { TypewriterText } from "@/components/animations/TypewriterText";
import {
  BODY_SPEED_MS,
  TITLE_SPEED_MS,
  typewriterMs,
} from "@/lib/typewriter-timing";

type Props = {
  title: string;
  subtitle?: string;
  size?: "large" | "compact";
};

export function PageHero({ title, subtitle, size = "compact" }: Props) {
  const t = useTranslations("logo");

  if (size === "large") {
    const tagline = t("tagline");
    const taglineDelay = 300;
    const titleDelay = taglineDelay + typewriterMs(tagline, TITLE_SPEED_MS) + 450;
    const subtitleDelay = subtitle
      ? titleDelay + typewriterMs(title, TITLE_SPEED_MS) + 500
      : undefined;

    return (
      <section className="relative overflow-hidden bg-brand-600 pb-16 pt-[5.5rem] sm:pb-24 sm:pt-28">
        <div className="absolute inset-0 bg-[radial-gradient(circle_at_70%_30%,rgba(235,179,10,0.15),transparent_55%)]" />
        <Container className="relative text-center">
          <div className="flex flex-col items-center">
            <Logo
              size="xl"
              variant="dark"
              ocrLine1={t("ocrLine1")}
              ocrLine2={t("ocrLine2")}
              className="justify-center"
            />
            <TypewriterText
              text={tagline}
              as="p"
              delay={taglineDelay}
              speedMs={TITLE_SPEED_MS}
              className="mt-4 font-heading text-lg text-accent-500"
            />
            <TypewriterText
              text={title}
              as="h1"
              delay={titleDelay}
              speedMs={TITLE_SPEED_MS}
              className="mt-6 max-w-3xl font-heading text-3xl font-bold tracking-tight text-white sm:text-4xl md:text-5xl"
            />
            {subtitle && subtitleDelay !== undefined && (
              <TypewriterText
                text={subtitle}
                as="p"
                delay={subtitleDelay}
                speedMs={BODY_SPEED_MS}
                className="mx-auto mt-5 max-w-2xl text-lg text-brand-200"
              />
            )}
          </div>
        </Container>
      </section>
    );
  }

  const brandLine = `${t("ocrLine1")} ${t("ocrLine2")}`;
  const brandDelay = 100;
  const titleDelay = brandDelay + typewriterMs(brandLine, 20) + 350;
  const subtitleDelay = subtitle
    ? titleDelay + typewriterMs(title, TITLE_SPEED_MS) + 500
    : undefined;

  return (
    <section className="border-b border-brand-700 bg-brand-600 pb-8 pt-[5.5rem] sm:pb-10 sm:pt-28">
      <Container>
        <div className="flex flex-col items-start gap-5 sm:flex-row sm:items-center sm:gap-8">
          <Logo
            size="sm"
            variant="dark"
            ocrLine1={t("ocrLine1")}
            ocrLine2={t("ocrLine2")}
          />
          <div className="min-w-0 flex-1">
            <TypewriterText
              text={brandLine}
              delay={brandDelay}
              speedMs={20}
              className="text-xs font-semibold uppercase tracking-widest text-accent-500"
            />
            <TypewriterText
              text={title}
              as="h1"
              delay={titleDelay}
              speedMs={TITLE_SPEED_MS}
              className="mt-1 font-heading text-2xl font-bold tracking-tight text-white sm:text-3xl"
            />
            {subtitle && subtitleDelay !== undefined && (
              <TypewriterText
                text={subtitle}
                as="p"
                delay={subtitleDelay}
                speedMs={BODY_SPEED_MS}
                className="mt-2 max-w-2xl text-sm text-brand-200 sm:text-base"
              />
            )}
          </div>
        </div>
      </Container>
    </section>
  );
}
