import type { Metadata } from "next";
import { getTranslations, setRequestLocale } from "next-intl/server";
import { Container } from "@/components/shared/Container";
import { PageCover } from "@/components/shared/PageCover";
import { TrackingForm } from "@/components/tracking/TrackingForm";
import { ImageTextSections } from "@/components/shared/ImageTextSections";
import { parseImageBlocks, parsePageCover } from "@/lib/image-blocks";

export async function generateMetadata({
  params,
}: {
  params: Promise<{ locale: string }>;
}): Promise<Metadata> {
  const { locale } = await params;
  const t = await getTranslations({ locale, namespace: "trackingPage" });
  return {
    title: t("title"),
    description: t("subtitle"),
  };
}

export default async function RastreoPage({
  params,
  searchParams,
}: {
  params: Promise<{ locale: string }>;
  searchParams: Promise<{ w?: string }>;
}) {
  const { locale } = await params;
  setRequestLocale(locale);

  const { w } = await searchParams;
  const t = await getTranslations({ locale, namespace: "trackingPage" });
  const tBlocks = await getTranslations({ locale, namespace: "imageBlocks" });
  const spotlight = parseImageBlocks(tBlocks.raw("trackingPage"));
  const cover = parsePageCover(t.raw("cover"));

  return (
    <>
      {cover && (
        <PageCover
          title={t("title")}
          subtitle={t("subtitle")}
          image={cover.image}
          imageAlt={cover.imageAlt}
        />
      )}
      <section className="py-16 sm:py-20">
        <Container>
          <TrackingForm initialWaybill={w} />
        </Container>
      </section>
      <ImageTextSections items={spotlight} alternateBg />
    </>
  );
}
