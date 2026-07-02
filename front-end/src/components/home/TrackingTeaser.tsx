"use client";

import { FormEvent, useState } from "react";
import { useTranslations } from "next-intl";
import { useRouter } from "@/i18n/navigation";
import { Container } from "@/components/shared/Container";
import { WaybillSearchBar } from "@/components/shared/WaybillSearchBar";
import { FadeIn } from "@/components/animations/FadeIn";

export function TrackingTeaser() {
  const t = useTranslations("trackingTeaser");
  const router = useRouter();
  const [waybill, setWaybill] = useState("");

  function handleSubmit(e: FormEvent) {
    e.preventDefault();
    const trimmed = waybill.trim();
    router.push(trimmed ? `/rastreo?w=${encodeURIComponent(trimmed)}` : "/rastreo");
  }

  return (
    <section className="py-20 sm:py-24">
      <Container>
        <FadeIn>
          <div className="rounded-3xl bg-brand-600 px-6 py-14 text-center text-white sm:px-14">
            <h2 className="font-heading text-2xl font-bold uppercase tracking-wider sm:text-3xl">
              {t("title")}
            </h2>
            <p className="mx-auto mt-3 max-w-xl text-brand-200">{t("description")}</p>

            <div className="mt-8">
              <WaybillSearchBar
                value={waybill}
                onChange={setWaybill}
                onSubmit={handleSubmit}
                placeholder={t("placeholder")}
                submitLabel={t("cta")}
                variant="dark"
              />
            </div>
          </div>
        </FadeIn>
      </Container>
    </section>
  );
}
