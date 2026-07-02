"use client";

import Image from "next/image";
import { useTranslations } from "next-intl";
import { Link } from "@/i18n/navigation";
import { ChevronRight } from "lucide-react";
import { Container } from "@/components/shared/Container";
import { SectionHeading } from "@/components/shared/SectionHeading";
import { SectionTypewriter, useSectionTypewriter } from "@/components/animations/SectionTypewriter";
import { TypewriterText } from "@/components/animations/TypewriterText";
import { VisualPanel, type VisualTheme } from "@/components/shared/VisualPanel";
import { FadeIn } from "@/components/animations/FadeIn";
import { CARD_STAGGER_MS } from "@/lib/typewriter-timing";

type CategoryLink = { label: string; href: string };
type Category = {
  title: string;
  visual: VisualTheme;
  image?: string;
  visualLabel: string;
  links: CategoryLink[];
};

function CategoryCards({ categories }: { categories: Category[] }) {
  const section = useSectionTypewriter();
  const contentBaseDelay = section?.contentBaseDelay ?? 0;
  const inView = section?.inView;

  return (
    <div className="mt-14 grid gap-8 sm:grid-cols-2 xl:grid-cols-3">
      {categories.map((cat, i) => {
        const cardDelay = (contentBaseDelay + i * CARD_STAGGER_MS) / 1000;

        return (
          <FadeIn key={cat.title} delay={cardDelay}>
            <article className="group flex h-full flex-col overflow-hidden rounded-2xl border border-brand-100 bg-white shadow-sm transition-shadow hover:shadow-lg">
              {cat.image ? (
                <div className="relative aspect-[16/10] w-full overflow-hidden bg-brand-600">
                  <Image
                    src={cat.image}
                    alt={cat.visualLabel}
                    fill
                    className="object-cover transition-transform duration-300 group-hover:scale-105"
                    sizes="(max-width: 1280px) 50vw, 33vw"
                  />
                </div>
              ) : (
                <VisualPanel
                  theme={cat.visual}
                  label={cat.visualLabel}
                  className="!aspect-[16/10] !rounded-none !shadow-none"
                />
              )}
              <div className="flex flex-1 flex-col p-6">
                <TypewriterText
                  text={cat.title}
                  as="h3"
                  delay={contentBaseDelay + i * CARD_STAGGER_MS}
                  startWhenInView
                  inView={inView}
                  className="font-heading text-xl font-bold text-navy"
                />
                <ul className="mt-4 flex-1 space-y-2">
                  {cat.links.map((link) => (
                    <li key={link.label}>
                      <Link
                        href={link.href}
                        className="flex items-center gap-1.5 text-sm text-slate-600 transition-colors hover:text-accent-600"
                      >
                        <ChevronRight
                          size={14}
                          className="shrink-0 text-accent-500"
                        />
                        {link.label}
                      </Link>
                    </li>
                  ))}
                </ul>
              </div>
            </article>
          </FadeIn>
        );
      })}
    </div>
  );
}

export function ServiceCategoryGrid() {
  const t = useTranslations("serviceCategories");
  const categories = t.raw("categories") as Category[];

  return (
    <section className="bg-white py-16 sm:py-24">
      <Container>
        <SectionTypewriter title={t("title")} subtitle={t("subtitle")}>
          <SectionHeading title={t("title")} subtitle={t("subtitle")} />
          <CategoryCards categories={categories} />
        </SectionTypewriter>
      </Container>
    </section>
  );
}
