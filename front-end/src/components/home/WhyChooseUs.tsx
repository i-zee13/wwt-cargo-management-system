"use client";

import Image from "next/image";
import { useTranslations } from "next-intl";
import { Container } from "@/components/shared/Container";
import { SectionHeading } from "@/components/shared/SectionHeading";
import { SectionTypewriter, useSectionTypewriter } from "@/components/animations/SectionTypewriter";
import { FadeIn } from "@/components/animations/FadeIn";
import { TypewriterText } from "@/components/animations/TypewriterText";
import {
  BODY_SPEED_MS,
  itemTypeDelays,
} from "@/lib/typewriter-timing";

type Item = {
  title: string;
  description: string;
  image?: string;
  imageLabel?: string;
};

function WhyChooseCards({ items }: { items: Item[] }) {
  const section = useSectionTypewriter();
  const contentBaseDelay = section?.contentBaseDelay ?? 0;
  const inView = section?.inView;

  return (
    <div className="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
      {items.map((item, i) => {
        const { titleDelay, descriptionDelay } = itemTypeDelays(
          contentBaseDelay,
          i,
          item.title
        );

        return (
          <FadeIn key={item.title} delay={titleDelay / 1000}>
            <div className="h-full overflow-hidden rounded-2xl border border-brand-100 bg-white shadow-sm">
              {item.image && (
                <div className="relative aspect-[4/3] w-full">
                  <Image
                    src={item.image}
                    alt={item.imageLabel ?? item.title}
                    fill
                    className="object-cover"
                    sizes="(max-width: 1024px) 50vw, 25vw"
                  />
                </div>
              )}
              <div className="p-6">
                <TypewriterText
                  text={item.title}
                  as="h3"
                  startWhenInView
                  inView={inView}
                  delay={titleDelay}
                  className="text-base font-semibold text-navy"
                />
                <TypewriterText
                  text={item.description}
                  as="p"
                  startWhenInView
                  inView={inView}
                  delay={descriptionDelay}
                  speedMs={BODY_SPEED_MS}
                  className="mt-2 text-sm leading-relaxed text-slate-600"
                />
              </div>
            </div>
          </FadeIn>
        );
      })}
    </div>
  );
}

export function WhyChooseUs() {
  const t = useTranslations("whyChooseUs");
  const items = t.raw("items") as Item[];

  return (
    <section className="bg-white py-20 sm:py-28">
      <Container>
        <SectionTypewriter title={t("title")} subtitle={t("subtitle")}>
          <SectionHeading title={t("title")} subtitle={t("subtitle")} />
          <WhyChooseCards items={items} />
        </SectionTypewriter>
      </Container>
    </section>
  );
}
