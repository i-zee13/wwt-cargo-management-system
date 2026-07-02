"use client";

import { useRef } from "react";
import { useInView } from "framer-motion";
import { useTranslations } from "next-intl";
import { Container } from "@/components/shared/Container";
import { CTAButton } from "@/components/shared/CTAButton";
import { TypewriterText } from "@/components/animations/TypewriterText";
import { FadeIn } from "@/components/animations/FadeIn";
import {
  BODY_SPEED_MS,
  TITLE_SPEED_MS,
  typewriterMs,
} from "@/lib/typewriter-timing";

export function ConsultationSection() {
  const t = useTranslations("consultationSection");
  const ref = useRef<HTMLDivElement>(null);
  const inView = useInView(ref, { once: true, margin: "-80px" });

  const title = t("title");
  const description = t("description");
  const note = t("note");

  const descriptionDelay = typewriterMs(title, TITLE_SPEED_MS) + 280;
  const noteDelay =
    descriptionDelay + typewriterMs(description, BODY_SPEED_MS) + 250;
  const ctaDelay = (noteDelay + typewriterMs(note, BODY_SPEED_MS) + 220) / 1000;

  return (
    <section className="border-t border-brand-100 bg-brand-50/50 py-16 sm:py-20">
      <Container className="max-w-3xl text-center">
        <div ref={ref}>
          <TypewriterText
            text={title}
            as="h2"
            startWhenInView
            inView={inView}
            speedMs={TITLE_SPEED_MS}
            className="font-heading text-3xl font-bold text-navy sm:text-4xl"
          />
          <TypewriterText
            text={description}
            as="p"
            delay={descriptionDelay}
            startWhenInView
            inView={inView}
            speedMs={BODY_SPEED_MS}
            className="mt-5 text-lg leading-relaxed text-slate-600"
          />
          <TypewriterText
            text={note}
            as="p"
            delay={noteDelay}
            startWhenInView
            inView={inView}
            speedMs={BODY_SPEED_MS}
            className="mt-3 text-sm text-slate-500"
          />
          <FadeIn delay={ctaDelay}>
            <div className="mt-8 flex flex-wrap justify-center gap-4">
              <CTAButton href="/contacto">{t("cta")}</CTAButton>
              <CTAButton href="/rastreo" variant="secondary">
                {t("ctaSecondary")}
              </CTAButton>
            </div>
          </FadeIn>
        </div>
      </Container>
    </section>
  );
}
