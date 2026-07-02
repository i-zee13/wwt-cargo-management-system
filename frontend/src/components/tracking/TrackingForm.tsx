"use client";

import { FormEvent, useEffect, useState } from "react";
import { useTranslations } from "next-intl";
import { Search, PackageSearch, AlertCircle } from "lucide-react";
import { motion, AnimatePresence } from "framer-motion";
import { trackPackage } from "@/lib/tracking";
import type { TrackingResult } from "@/types/tracking";
import { TrackingTimeline } from "@/components/tracking/TrackingTimeline";

type State =
  | { phase: "idle" }
  | { phase: "loading" }
  | { phase: "success"; data: TrackingResult }
  | { phase: "not_found" }
  | { phase: "error" };

export function TrackingForm({ initialWaybill }: { initialWaybill?: string }) {
  const t = useTranslations("trackingPage");
  const [waybill, setWaybill] = useState(initialWaybill ?? "");
  const [state, setState] = useState<State>({ phase: "idle" });

  async function runSearch(value: string) {
    if (!value.trim()) return;
    setState({ phase: "loading" });
    const result = await trackPackage(value);

    if (result.status === "success") {
      setState({ phase: "success", data: result.data });
    } else if (result.status === "not_found") {
      setState({ phase: "not_found" });
    } else {
      setState({ phase: "error" });
    }
  }

  useEffect(() => {
    if (initialWaybill) {
      runSearch(initialWaybill);
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [initialWaybill]);

  function handleSubmit(e: FormEvent) {
    e.preventDefault();
    runSearch(waybill);
  }

  return (
    <div className="mx-auto max-w-2xl">
      <form onSubmit={handleSubmit} className="flex flex-col gap-3 sm:flex-row">
        <input
          type="text"
          value={waybill}
          onChange={(e) => setWaybill(e.target.value)}
          placeholder={t("placeholder")}
          className="w-full rounded-full border border-slate-200 px-5 py-3 text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500"
        />
        <button
          type="submit"
          disabled={state.phase === "loading"}
          className="inline-flex items-center justify-center gap-2 rounded-full bg-brand-600 px-6 py-3 text-sm font-semibold text-white transition-colors hover:bg-brand-700 disabled:opacity-60"
        >
          <Search size={16} />
          {t("submit")}
        </button>
      </form>

      <AnimatePresence mode="wait">
        {state.phase === "loading" && (
          <motion.p
            key="loading"
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            className="mt-8 text-center text-slate-500"
          >
            {t("loading")}
          </motion.p>
        )}

        {state.phase === "not_found" && (
          <motion.div
            key="not_found"
            initial={{ opacity: 0, y: 8 }}
            animate={{ opacity: 1, y: 0 }}
            exit={{ opacity: 0 }}
            className="mt-8 flex items-start gap-3 rounded-2xl bg-amber-50 p-5 text-amber-800"
          >
            <PackageSearch size={20} className="mt-0.5 shrink-0" />
            <p className="text-sm">{t("notFound")}</p>
          </motion.div>
        )}

        {state.phase === "error" && (
          <motion.div
            key="error"
            initial={{ opacity: 0, y: 8 }}
            animate={{ opacity: 1, y: 0 }}
            exit={{ opacity: 0 }}
            className="mt-8 flex items-start gap-3 rounded-2xl bg-red-50 p-5 text-red-700"
          >
            <AlertCircle size={20} className="mt-0.5 shrink-0" />
            <p className="text-sm">{t("error")}</p>
          </motion.div>
        )}

        {state.phase === "success" && (
          <motion.div
            key="success"
            initial={{ opacity: 0, y: 8 }}
            animate={{ opacity: 1, y: 0 }}
            exit={{ opacity: 0 }}
            className="mt-10 rounded-2xl border border-slate-100 bg-white p-6 shadow-sm sm:p-8"
          >
            <h2 className="text-lg font-semibold text-slate-900">
              {t("resultTitle")}
            </h2>
            <dl className="mt-4 grid grid-cols-2 gap-4 text-sm sm:grid-cols-3">
              <div>
                <dt className="text-slate-500">{t("waybillLabel")}</dt>
                <dd className="font-semibold text-slate-900">{state.data.waybill}</dd>
              </div>
              {state.data.kg !== null && (
                <div>
                  <dt className="text-slate-500">{t("weightLabel")}</dt>
                  <dd className="font-semibold text-slate-900">{state.data.kg} kg</dd>
                </div>
              )}
            </dl>
            <TrackingTimeline status={state.data.status} />
          </motion.div>
        )}
      </AnimatePresence>
    </div>
  );
}
