import type { Metadata } from "next";
import { getTranslations, setRequestLocale } from "next-intl/server";
import { PageHero } from "@/components/shared/PageHero";
import { Container } from "@/components/shared/Container";
import { TrackingForm } from "@/components/tracking/TrackingForm";

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

  return (
    <>
      <PageHero title={t("title")} subtitle={t("subtitle")} />
      <section className="py-16 sm:py-20">
        <Container>
          <TrackingForm initialWaybill={w} />
        </Container>
      </section>
    </>
  );
}
