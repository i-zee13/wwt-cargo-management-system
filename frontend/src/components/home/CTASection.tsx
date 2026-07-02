"use client";

import { useTranslations } from "next-intl";
import { Container } from "@/components/shared/Container";
import { CTAButton } from "@/components/shared/CTAButton";
import { FadeIn } from "@/components/animations/FadeIn";
import { PORTAL_LINKS, whatsappLink } from "@/lib/constants";

export function CTASection() {
  const t = useTranslations("ctaSection");

  return (
    <section className="bg-slate-950 py-20 text-center sm:py-24">
      <Container>
        <FadeIn>
          <h2 className="text-3xl font-bold text-white sm:text-4xl">{t("title")}</h2>
          <p className="mx-auto mt-4 max-w-xl text-slate-300">{t("description")}</p>
          <div className="mt-8 flex flex-wrap justify-center gap-4">
            <CTAButton href={PORTAL_LINKS.register}>{t("ctaPrimary")}</CTAButton>
            <CTAButton
              href={whatsappLink()}
              variant="ghost"
              target="_blank"
              rel="noopener noreferrer"
            >
              {t("ctaSecondary")}
            </CTAButton>
          </div>
        </FadeIn>
      </Container>
    </section>
  );
}
