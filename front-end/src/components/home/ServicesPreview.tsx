"use client";

import { useTranslations } from "next-intl";
import { Container } from "@/components/shared/Container";
import { SectionHeading } from "@/components/shared/SectionHeading";
import { SectionTypewriter, useSectionTypewriter } from "@/components/animations/SectionTypewriter";
import { ServiceCard } from "@/components/shared/ServiceCard";
import { FadeIn } from "@/components/animations/FadeIn";
import { CARD_STAGGER_MS } from "@/lib/typewriter-timing";

type Item = {
  title: string;
  description: string;
  icon: string;
  image?: string;
  imageLabel?: string;
};

function ServiceCards({ items }: { items: Item[] }) {
  const section = useSectionTypewriter();
  const contentBaseDelay = section?.contentBaseDelay ?? 0;

  return (
    <div className="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
      {items.map((item, i) => (
        <FadeIn
          key={item.title}
          delay={(contentBaseDelay + i * CARD_STAGGER_MS) / 1000}
        >
          <ServiceCard {...item} variant="dark" />
        </FadeIn>
      ))}
    </div>
  );
}

export function ServicesPreview() {
  const t = useTranslations("services");
  const items =
    (t.raw("homeItems") as Item[]) ??
    (t.raw("items") as Item[]).slice(0, 6);

  return (
    <section id="servicios" className="bg-slate-50 py-20 sm:py-28">
      <Container>
        <SectionTypewriter title={t("title")} subtitle={t("subtitle")}>
          <SectionHeading title={t("title")} subtitle={t("subtitle")} />
          <ServiceCards items={items} />
        </SectionTypewriter>
      </Container>
    </section>
  );
}
