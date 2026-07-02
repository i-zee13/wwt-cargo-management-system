import { setRequestLocale, getTranslations } from "next-intl/server";
import { Hero } from "@/components/home/Hero";
import { HowItWorks } from "@/components/home/HowItWorks";
import { ServicesPreview } from "@/components/home/ServicesPreview";
import { StatsCounter } from "@/components/home/StatsCounter";
import { TrackingTeaser } from "@/components/home/TrackingTeaser";
import { TrustedPartner } from "@/components/home/TrustedPartner";
import { ServiceCategoryGrid } from "@/components/home/ServiceCategoryGrid";
import { ConsultationSection } from "@/components/home/ConsultationSection";
import { WhyChooseUs } from "@/components/home/WhyChooseUs";
import { CTASection } from "@/components/home/CTASection";
import { ImageTextSections } from "@/components/shared/ImageTextSections";
import { parseImageBlocks } from "@/lib/image-blocks";
import { SITE_URL } from "@/lib/constants";

export default async function HomePage({
  params,
}: {
  params: Promise<{ locale: string }>;
}) {
  const { locale } = await params;
  setRequestLocale(locale);

  const tBlocks = await getTranslations({ locale, namespace: "imageBlocks" });
  const homeBlocks = parseImageBlocks(tBlocks.raw("home"));

  const jsonLd = {
    "@context": "https://schema.org",
    "@type": "Organization",
    name: "World Wide Trading Group (WWT)",
    alternateName: "WWT",
    url: SITE_URL,
    email: "consultas@wwt.com.py",
    areaServed: ["PY", "AR"],
  };

  return (
    <>
      <script
        type="application/ld+json"
        dangerouslySetInnerHTML={{ __html: JSON.stringify(jsonLd) }}
      />
      <Hero />
      <TrustedPartner />
      <ServiceCategoryGrid />
      <HowItWorks />
      <ImageTextSections items={homeBlocks} alternateBg />
      <ServicesPreview />
      <WhyChooseUs />
      <StatsCounter />
      <TrackingTeaser />
      <ConsultationSection />
      <CTASection />
    </>
  );
}
