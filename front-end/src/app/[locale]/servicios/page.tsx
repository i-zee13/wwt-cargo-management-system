import type { Metadata } from "next";
import { getTranslations, setRequestLocale } from "next-intl/server";
import { Container } from "@/components/shared/Container";
import { PageCover } from "@/components/shared/PageCover";
import { ServiceCard } from "@/components/shared/ServiceCard";
import { FadeIn } from "@/components/animations/FadeIn";
import { CTASection } from "@/components/home/CTASection";
import { ImageTextSections } from "@/components/shared/ImageTextSections";
import { parseImageBlocks, parsePageCover } from "@/lib/image-blocks";

type Item = {
  title: string;
  description: string;
  icon: string;
  image?: string;
  imageLabel?: string;
};

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
  const tBlocks = await getTranslations({ locale, namespace: "imageBlocks" });
  const items = tServices.raw("items") as Item[];
  const spotlight = parseImageBlocks(tBlocks.raw("servicesPage"));
  const cover = parsePageCover(t.raw("cover"));

  return (
    <>
      {cover && (
        <PageCover
          title={t("hero.title")}
          subtitle={t("hero.subtitle")}
          image={cover.image}
          imageAlt={cover.imageAlt}
        />
      )}
      <ImageTextSections items={spotlight} alternateBg />
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
