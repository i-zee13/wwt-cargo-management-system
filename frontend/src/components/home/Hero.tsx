"use client";

import { useTranslations } from "next-intl";
import { motion } from "framer-motion";
import { PORTAL_LINKS } from "@/lib/constants";
import { Container } from "@/components/shared/Container";
import { CTAButton } from "@/components/shared/CTAButton";
import { TypingText } from "@/components/animations/TypingText";
import { HeroVisual } from "@/components/home/HeroVisual";

export function Hero() {
  const t = useTranslations("hero");
  const words = t.raw("typingWords") as string[];

  return (
    <section className="relative overflow-hidden bg-gradient-to-b from-white to-brand-50/60 pt-14 pb-20 sm:pt-20 sm:pb-28">
      <Container className="grid items-center gap-12 lg:grid-cols-2">
        <motion.div
          initial={{ opacity: 0, y: 16 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.6, ease: "easeOut" }}
        >
          <span className="inline-flex items-center rounded-full bg-brand-100 px-4 py-1.5 text-sm font-semibold text-brand-700">
            {t("eyebrow")}
          </span>

          <h1 className="mt-5 text-4xl font-bold leading-tight tracking-tight text-slate-900 sm:text-5xl">
            {t("headlinePrefix")}
            <br />
            {t("headlineSuffix")}{" "}
            <span className="text-brand-600">
              <TypingText words={words} />
            </span>
          </h1>

          <p className="mt-6 max-w-xl text-lg text-slate-600">{t("subheadline")}</p>

          <div className="mt-8 flex flex-wrap gap-4">
            <CTAButton href={PORTAL_LINKS.register}>{t("ctaPrimary")}</CTAButton>
            <CTAButton href="#como-funciona" variant="secondary">
              {t("ctaSecondary")}
            </CTAButton>
          </div>

          <p className="mt-6 text-sm text-slate-500">{t("trustNote")}</p>
        </motion.div>

        <motion.div
          initial={{ opacity: 0, scale: 0.92 }}
          animate={{ opacity: 1, scale: 1 }}
          transition={{ duration: 0.7, ease: "easeOut", delay: 0.15 }}
        >
          <HeroVisual />
        </motion.div>
      </Container>
    </section>
  );
}
