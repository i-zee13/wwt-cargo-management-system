"use client";

import { useTranslations } from "next-intl";
import { motion } from "framer-motion";
import { Container } from "@/components/shared/Container";
import { CTAButton } from "@/components/shared/CTAButton";
import { TypingText } from "@/components/animations/TypingText";
import { TypewriterText } from "@/components/animations/TypewriterText";
import { HeroVisual } from "@/components/home/HeroVisual";
import {
  BODY_SPEED_MS,
  TITLE_SPEED_MS,
  typewriterMs,
} from "@/lib/typewriter-timing";

export function Hero() {
  const t = useTranslations("hero");
  const words = t.raw("typingWords") as string[];

  const eyebrow = t("eyebrow");
  const headlinePrefix = t("headlinePrefix");
  const headlineSuffix = `${t("headlineSuffix")} `;
  const subheadline = t("subheadline");
  const trustNote = t("trustNote");

  const headlinePrefixDelay = typewriterMs(eyebrow, 22) + 400;
  const headlineSuffixDelay =
    headlinePrefixDelay + typewriterMs(headlinePrefix, 18) + 300;
  const subheadlineDelay =
    headlineSuffixDelay + typewriterMs(headlineSuffix, 18) + 500;
  const ctaDelay = (subheadlineDelay + typewriterMs(subheadline, BODY_SPEED_MS) + 600) / 1000;
  const trustNoteDelay =
    subheadlineDelay + typewriterMs(subheadline, BODY_SPEED_MS) + 800;

  return (
    <section className="relative overflow-hidden bg-gradient-to-b from-brand-600 via-brand-600 to-brand-700 pb-20 pt-[5.5rem] sm:pb-28 sm:pt-28">
      <div className="absolute inset-0 bg-[radial-gradient(circle_at_20%_80%,rgba(235,179,10,0.12),transparent_50%)]" />
      <div className="absolute inset-0 bg-[radial-gradient(circle_at_80%_20%,rgba(255,255,255,0.06),transparent_40%)]" />

      <Container className="relative grid items-center gap-12 lg:grid-cols-2">
        <motion.div
          initial={{ opacity: 0 }}
          animate={{ opacity: 1 }}
          transition={{ duration: 0.4 }}
        >
          <TypewriterText
            text={eyebrow}
            delay={200}
            speedMs={22}
            inline
            className="inline-flex items-center rounded-full border border-white/20 bg-white/10 px-4 py-1.5 text-sm font-semibold text-accent-400"
          />

          <h1 className="mt-5 font-heading text-4xl font-bold leading-tight tracking-tight text-white sm:text-5xl">
            <TypewriterText
              text={headlinePrefix}
              delay={headlinePrefixDelay}
              speedMs={18}
            />
            <br />
            <TypewriterText
              text={headlineSuffix}
              delay={headlineSuffixDelay}
              speedMs={18}
              as="span"
            />
            <span className="text-accent-500">
              <TypingText words={words} />
            </span>
          </h1>

          <TypewriterText
            text={subheadline}
            as="p"
            delay={subheadlineDelay}
            speedMs={BODY_SPEED_MS}
            className="mt-6 max-w-xl text-lg text-brand-100"
          />

          <motion.div
            initial={{ opacity: 0, y: 8 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: ctaDelay, duration: 0.5 }}
            className="mt-8 flex flex-wrap gap-3"
          >
            <CTAButton href="/rastreo">{t("ctaTrack")}</CTAButton>
            <CTAButton href="/contacto" variant="ghost">
              {t("ctaQuote")}
            </CTAButton>
            <CTAButton
              href="#servicios"
              variant="ghost"
              className="!border-white/40 !text-white hover:!bg-white/10"
            >
              {t("ctaServices")}
            </CTAButton>
          </motion.div>

          <TypewriterText
            text={trustNote}
            as="p"
            delay={trustNoteDelay}
            speedMs={BODY_SPEED_MS}
            className="mt-6 text-sm text-brand-200"
          />
        </motion.div>

        <motion.div
          initial={{ opacity: 0, scale: 0.92 }}
          animate={{ opacity: 1, scale: 1 }}
          transition={{ duration: 0.7, ease: "easeOut", delay: 0.8 }}
        >
          <HeroVisual />
        </motion.div>
      </Container>
    </section>
  );
}
