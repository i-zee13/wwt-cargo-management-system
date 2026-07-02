"use client";

import { useRef } from "react";
import { useInView } from "framer-motion";
import { useTranslations } from "next-intl";
import { Container } from "@/components/shared/Container";
import { TypewriterText } from "@/components/animations/TypewriterText";
import {
  BODY_SPEED_MS,
  TITLE_SPEED_MS,
  typewriterMs,
} from "@/lib/typewriter-timing";

export function TrustedPartner() {
  const t = useTranslations("trustedPartner");
  const ref = useRef<HTMLDivElement>(null);
  const inView = useInView(ref, { once: true, margin: "-80px" });

  const title = t("title");
  const description = t("description");
  const descriptionDelay = typewriterMs(title, TITLE_SPEED_MS) + 280;

  return (
    <section className="border-b border-brand-100 bg-white py-16 sm:py-20">
      <Container className="max-w-4xl text-center">
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
            className="mx-auto mt-6 text-lg leading-relaxed text-slate-600"
          />
        </div>
      </Container>
    </section>
  );
}
