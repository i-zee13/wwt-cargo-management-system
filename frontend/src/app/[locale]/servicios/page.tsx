import type { Metadata } from "next";
import { getTranslations, setRequestLocale } from "next-intl/server";
import { Container } from "@/components/shared/Container";
import { PageHero } from "@/components/shared/PageHero";
import { ServiceCard } from "@/components/shared/ServiceCard";
import { FadeIn } from "@/components/animations/FadeIn";
import { CTASection } from "@/components/home/CTASection";

type Item = { title: string; description: string; icon: string };

export async function generateMetadata({
  params,
}: {
  params: Promise<{ locale: string }>;
}): Promise<Metadata> {
  const { locale } = await params;
  const t = await getTranslations({ locale, namespace: "servicesPage" });
  return {
    title: t("hero.title"),
    description: t("hero.subtitle"),
  };
}

export default async function ServiciosPage({
  params,
}: {
  params: Promise<{ locale: string }>;
}) {
  const { locale } = await params;
  setRequestLocale(locale);

  const t = await getTranslations({ locale, namespace: "servicesPage" });
  const tServices = await getTranslations({ locale, namespace: "services" });
  const items = tServices.raw("items") as Item[];

  return (
    <>
      <PageHero title={t("hero.title")} subtitle={t("hero.subtitle")} />
      <section className="py-16 sm:py-20">
        <Container>
          <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            {items.map((item, i) => (
              <FadeIn key={item.title} delay={i * 0.05}>
                <ServiceCard {...item} />
              </FadeIn>
            ))}
          </div>
        </Container>
      </section>
      <CTASection />
    </>
  );
}
