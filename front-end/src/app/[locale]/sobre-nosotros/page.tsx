import type { Metadata } from "next";
import { getTranslations, setRequestLocale } from "next-intl/server";
import { Container } from "@/components/shared/Container";
import { PageCover } from "@/components/shared/PageCover";
import { FadeIn } from "@/components/animations/FadeIn";
import { CTASection } from "@/components/home/CTASection";
import { ImageTextSections } from "@/components/shared/ImageTextSections";
import { parseImageBlocks, parsePageCover } from "@/lib/image-blocks";

type ValueItem = { title: string; description: string };

export async function generateMetadata({
  params,
}: {
  params: Promise<{ locale: string }>;
}): Promise<Metadata> {
  const { locale } = await params;
  const t = await getTranslations({ locale, namespace: "aboutPage" });
  return {
    title: t("title"),
    description: t("intro"),
  };
}

export default async function SobreNosotrosPage({
  params,
}: {
  params: Promise<{ locale: string }>;
}) {
  const { locale } = await params;
  setRequestLocale(locale);

  const t = await getTranslations({ locale, namespace: "aboutPage" });
  const tBlocks = await getTranslations({ locale, namespace: "imageBlocks" });
  const values = t.raw("values.items") as ValueItem[];
  const spotlight = parseImageBlocks(tBlocks.raw("aboutPage"));
  const cover = parsePageCover(t.raw("cover"));

  return (
    <>
      {cover && (
        <PageCover
          title={t("title")}
          subtitle={t("intro")}
          image={cover.image}
          imageAlt={cover.imageAlt}
        />
      )}
      <ImageTextSections items={spotlight} alternateBg />

      <section className="py-16 sm:py-20">
        <Container className="max-w-4xl">
          <FadeIn>
            <h2 className="font-heading text-2xl font-bold text-navy">
              {t("mission.title")}
            </h2>
            <p className="mt-3 text-slate-600">{t("mission.description")}</p>
          </FadeIn>

          <h2 className="mt-14 font-heading text-2xl font-bold text-navy">
            {t("values.title")}
          </h2>
          <div className="mt-6 grid gap-6 sm:grid-cols-3">
            {values.map((value, i) => (
              <FadeIn key={value.title} delay={i * 0.08}>
                <div className="h-full rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                  <h3 className="text-base font-semibold text-slate-900">
                    {value.title}
                  </h3>
                  <p className="mt-2 text-sm leading-relaxed text-slate-600">
                    {value.description}
                  </p>
                </div>
              </FadeIn>
            ))}
          </div>
        </Container>
      </section>

      <CTASection />
    </>
  );
}
