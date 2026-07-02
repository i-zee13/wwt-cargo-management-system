"use client";

import Image from "next/image";
import { useTranslations } from "next-intl";
import { Container } from "@/components/shared/Container";
import { SectionHeading } from "@/components/shared/SectionHeading";
import { SectionTypewriter, useSectionTypewriter } from "@/components/animations/SectionTypewriter";
import { TypewriterText } from "@/components/animations/TypewriterText";
import { FadeIn } from "@/components/animations/FadeIn";
import {
  BODY_SPEED_MS,
  itemTypeDelays,
} from "@/lib/typewriter-timing";

type Step = {
  title: string;
  description: string;
  image?: string;
  imageLabel?: string;
};

function HowItWorksSteps({ steps }: { steps: Step[] }) {
  const section = useSectionTypewriter();
  const contentBaseDelay = section?.contentBaseDelay ?? 0;
  const inView = section?.inView;

  return (
    <div className="mt-14 grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
      {steps.map((step, i) => {
        const { titleDelay, descriptionDelay } = itemTypeDelays(
          contentBaseDelay,
          i,
          step.title
        );

        return (
          <FadeIn key={step.title} delay={titleDelay / 1000}>
            <div className="relative h-full overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
              {step.image && (
                <div className="relative aspect-[4/3] w-full">
                  <Image
                    src={step.image}
                    alt={step.imageLabel ?? step.title}
                    fill
                    className="object-cover"
                    sizes="(max-width: 1024px) 50vw, 25vw"
                  />
                </div>
              )}
              <div className="p-6">
                <span className="flex h-10 w-10 items-center justify-center rounded-xl bg-accent-500 text-sm font-bold text-navy">
                  {i + 1}
                </span>
                <TypewriterText
                  text={step.title}
                  as="h3"
                  startWhenInView
                  inView={inView}
                  delay={titleDelay}
                  className="mt-4 text-lg font-semibold text-slate-900"
                />
                <TypewriterText
                  text={step.description}
                  as="p"
                  startWhenInView
                  inView={inView}
                  delay={descriptionDelay}
                  speedMs={BODY_SPEED_MS}
                  className="mt-2 text-sm leading-relaxed text-slate-600"
                />
              </div>
            </div>
          </FadeIn>
        );
      })}
    </div>
  );
}

export function HowItWorks() {
  const t = useTranslations("howItWorks");
  const steps = t.raw("steps") as Step[];

  return (
    <section id="como-funciona" className="py-20 sm:py-28">
      <Container>
        <SectionTypewriter title={t("title")} subtitle={t("subtitle")}>
          <SectionHeading title={t("title")} subtitle={t("subtitle")} />
          <HowItWorksSteps steps={steps} />
        </SectionTypewriter>
      </Container>
    </section>
  );
}
