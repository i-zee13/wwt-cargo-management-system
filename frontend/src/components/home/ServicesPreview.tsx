"use client";

import { useTranslations } from "next-intl";
import { Container } from "@/components/shared/Container";
import { SectionHeading } from "@/components/shared/SectionHeading";
import { ServiceCard } from "@/components/shared/ServiceCard";
import { FadeIn } from "@/components/animations/FadeIn";

type Item = { title: string; description: string; icon: string };

export function ServicesPreview() {
  const t = useTranslations("services");
  const items = (t.raw("items") as Item[]).slice(0, 4);

  return (
    <section className="bg-slate-50 py-20 sm:py-28">
      <Container>
        <SectionHeading title={t("title")} subtitle={t("subtitle")} />

        <div className="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
          {items.map((item, i) => (
            <FadeIn key={item.title} delay={i * 0.06}>
              <ServiceCard {...item} />
            </FadeIn>
          ))}
        </div>
      </Container>
    </section>
  );
}
