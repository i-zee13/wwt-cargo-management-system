import { setRequestLocale } from "next-intl/server";
import { Hero } from "@/components/home/Hero";
import { HowItWorks } from "@/components/home/HowItWorks";
import { ServicesPreview } from "@/components/home/ServicesPreview";
import { StatsCounter } from "@/components/home/StatsCounter";
import { TrackingTeaser } from "@/components/home/TrackingTeaser";
import { CTASection } from "@/components/home/CTASection";
import { SITE_URL } from "@/lib/constants";

export default async function HomePage({
  params,
}: {
  params: Promise<{ locale: string }>;
}) {
  const { locale } = await params;
  setRequestLocale(locale);

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
      <HowItWorks />
      <ServicesPreview />
      <StatsCounter />
      <TrackingTeaser />
      <CTASection />
    </>
  );
}
