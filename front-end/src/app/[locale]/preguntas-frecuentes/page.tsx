import type { Metadata } from "next";
import { getTranslations, setRequestLocale } from "next-intl/server";
import { Container } from "@/components/shared/Container";
import { PageCover } from "@/components/shared/PageCover";
import { FaqAccordion } from "@/components/faq/FaqAccordion";
import { ImageTextSections } from "@/components/shared/ImageTextSections";
import { parseImageBlocks, parsePageCover } from "@/lib/image-blocks";

type FaqItem = { question: string; answer: string };

export async function generateMetadata({
  params,
}: {
  params: Promise<{ locale: string }>;
}): Promise<Metadata> {
  const { locale } = await params;
  const t = await getTranslations({ locale, namespace: "faqPage" });
  return {
    title: t("title"),
    description: t("subtitle"),
  };
}

export default async function FaqPage({
  params,
}: {
  params: Promise<{ locale: string }>;
}) {
  const { locale } = await params;
  setRequestLocale(locale);

  const t = await getTranslations({ locale, namespace: "faqPage" });
  const tBlocks = await getTranslations({ locale, namespace: "imageBlocks" });
  const items = t.raw("items") as FaqItem[];
  const spotlight = parseImageBlocks(tBlocks.raw("faqPage"));
  const cover = parsePageCover(t.raw("cover"));

  const jsonLd = {
    "@context": "https://schema.org",
    "@type": "FAQPage",
    mainEntity: items.map((item) => ({
      "@type": "Question",
      name: item.question,
      acceptedAnswer: { "@type": "Answer", text: item.answer },
    })),
  };

  return (
    <>
      <script
        type="application/ld+json"
        dangerouslySetInnerHTML={{ __html: JSON.stringify(jsonLd) }}
      />
      {cover && (
        <PageCover
          title={t("title")}
          subtitle={t("subtitle")}
          image={cover.image}
          imageAlt={cover.imageAlt}
        />
      )}
      <ImageTextSections items={spotlight} alternateBg />
      <section className="py-16 sm:py-20">
        <Container className="max-w-3xl">
          <FaqAccordion items={items} />
        </Container>
      </section>
    </>
  );
}
