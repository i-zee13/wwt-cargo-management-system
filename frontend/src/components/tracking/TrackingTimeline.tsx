"use client";

import { useTranslations } from "next-intl";
import { motion } from "framer-motion";
import { Check } from "lucide-react";
import clsx from "clsx";
import type { PackageStatus } from "@/types/tracking";

const STEP_ORDER: PackageStatus[] = [
  "received",
  "in-progress",
  "embarked",
  "arrived",
  "retired",
];

export function TrackingTimeline({ status }: { status: string }) {
  const t = useTranslations("trackingPage.statuses");
  const currentIndex = STEP_ORDER.indexOf(status as PackageStatus);

  return (
    <ol className="mt-8 space-y-6 sm:space-y-0 sm:flex sm:items-start sm:justify-between sm:gap-2">
      {STEP_ORDER.map((step, i) => {
        const done = currentIndex >= 0 && i <= currentIndex;
        const isCurrent = i === currentIndex;

        return (
          <li key={step} className="flex flex-1 flex-col items-center text-center">
            <div className="flex w-full items-center">
              {i > 0 && (
                <span
                  className={clsx(
                    "hidden h-0.5 flex-1 sm:block",
                    done ? "bg-brand-600" : "bg-slate-200"
                  )}
                />
              )}
              <motion.span
                initial={false}
                animate={{ scale: isCurrent ? 1.15 : 1 }}
                className={clsx(
                  "flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-sm font-semibold",
                  done ? "bg-brand-600 text-white" : "bg-slate-200 text-slate-500"
                )}
              >
                {done ? <Check size={16} /> : i + 1}
              </motion.span>
              {i < STEP_ORDER.length - 1 && (
                <span
                  className={clsx(
                    "hidden h-0.5 flex-1 sm:block",
                    currentIndex > i ? "bg-brand-600" : "bg-slate-200"
                  )}
                />
              )}
            </div>
            <span
              className={clsx(
                "mt-2 text-xs font-medium sm:max-w-[6.5rem]",
                isCurrent ? "text-brand-700" : "text-slate-500"
              )}
            >
              {t(step)}
            </span>
          </li>
        );
      })}
    </ol>
  );
}
