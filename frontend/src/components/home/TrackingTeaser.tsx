"use client";

import { FormEvent, useState } from "react";
import { useTranslations } from "next-intl";
import { Search } from "lucide-react";
import { useRouter } from "@/i18n/navigation";
import { Container } from "@/components/shared/Container";
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
          <div className="rounded-3xl bg-gradient-to-r from-brand-600 to-brand-700 px-6 py-14 text-center text-white sm:px-14">
            <h2 className="text-2xl font-bold sm:text-3xl">{t("title")}</h2>
            <p className="mx-auto mt-3 max-w-xl text-brand-100">{t("description")}</p>

            <form
              onSubmit={handleSubmit}
              className="mx-auto mt-8 flex max-w-md flex-col gap-3 sm:flex-row"
            >
              <input
                type="text"
                value={waybill}
                onChange={(e) => setWaybill(e.target.value)}
                placeholder={t("placeholder")}
                className="w-full rounded-full border-0 px-5 py-3 text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-white"
              />
              <button
                type="submit"
                className="inline-flex items-center justify-center gap-2 rounded-full bg-white px-6 py-3 text-sm font-semibold text-brand-700 transition-colors hover:bg-brand-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-white"
              >
                <Search size={16} />
                {t("cta")}
              </button>
            </form>
          </div>
        </FadeIn>
      </Container>
    </section>
  );
}
