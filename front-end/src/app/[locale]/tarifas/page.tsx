import type { Metadata } from "next";
import { getTranslations, setRequestLocale } from "next-intl/server";
import { Container } from "@/components/shared/Container";
import { PageCover } from "@/components/shared/PageCover";
import { CTAButton } from "@/components/shared/CTAButton";
import { FadeIn } from "@/components/animations/FadeIn";
import { PORTAL_LINKS } from "@/lib/constants";
import { ImageTextSections } from "@/components/shared/ImageTextSections";
import { parseImageBlocks, parsePageCover } from "@/lib/image-blocks";

type Factor = { title: string; description: string };

export async function generateMetadata({
  params,
}: {
  params: Promise<{ locale: string }>;
}): Promise<Metadata> {
  const { locale } = await params;
  const t = await getTranslations({ locale, namespace: "ratesPage" });
  return {
    title: t("hero.title"),
    description: t("hero.subtitle"),
  };
}

export default async function TarifasPage({
  params,
}: {
  params: Promise<{ locale: string }>;
}) {
  const { locale } = await params;
  setRequestLocale(locale);

  const t = await getTranslations({ locale, namespace: "ratesPage" });
  const tBlocks = await getTranslations({ locale, namespace: "imageBlocks" });
  const factors = t.raw("factors") as Factor[];
  const spotlight = parseImageBlocks(tBlocks.raw("ratesPage"));
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
        <Container className="max-w-4xl">
          <h2 className="font-heading text-2xl font-bold text-navy">
            {t("factorsTitle")}
          </h2>
          <div className="mt-8 grid gap-6 sm:grid-cols-2">
            {factors.map((factor, i) => (
              <FadeIn key={factor.title} delay={i * 0.06}>
                <div className="h-full rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                  <h3 className="text-base font-semibold text-slate-900">
                    {factor.title}
                  </h3>
                  <p className="mt-2 text-sm leading-relaxed text-slate-600">
                    {factor.description}
                  </p>
                </div>
              </FadeIn>
            ))}
          </div>

          <div className="mt-12 rounded-2xl bg-brand-50 p-8 text-center">
            <p className="text-slate-700">{t("ctaNote")}</p>
            <CTAButton href={PORTAL_LINKS.login} className="mt-5">
              {t("cta")}
            </CTAButton>
          </div>
        </Container>
      </section>
    </>
  );
}
