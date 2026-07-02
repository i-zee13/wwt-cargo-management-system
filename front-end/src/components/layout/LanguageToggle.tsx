"use client";

import { useLocale } from "next-intl";
import { usePathname, useRouter } from "@/i18n/navigation";
import { useParams } from "next/navigation";
import clsx from "clsx";

const LOCALES: { code: "es" | "en"; label: string }[] = [
  { code: "es", label: "ES" },
  { code: "en", label: "EN" },
];

export function LanguageToggle({
  className,
  inverted = false,
}: {
  className?: string;
  inverted?: boolean;
}) {
  const locale = useLocale();
  const router = useRouter();
  const pathname = usePathname();
  const params = useParams();

  return (
    <div
      className={clsx(
        "inline-flex items-center rounded-full border p-1 text-sm font-semibold",
        inverted
          ? "border-white/25 bg-white/10"
          : "border-slate-200 bg-white",
        className
      )}
      role="group"
      aria-label="Language switch / Cambiar idioma"
    >
      {LOCALES.map(({ code, label }) => (
        <button
          key={code}
          type="button"
          onClick={() =>
            router.replace(
              // @ts-expect-error dynamic pathname from current route
              { pathname, params },
              { locale: code }
            )
          }
          aria-current={locale === code}
          className={clsx(
            "rounded-full px-3 py-1 transition-colors",
            locale === code
              ? inverted
                ? "bg-accent-500 text-navy"
                : "bg-brand-600 text-accent-500"
              : inverted
                ? "text-white/80 hover:text-white"
                : "text-slate-500 hover:text-brand-600"
          )}
        >
          {label}
        </button>
      ))}
    </div>
  );
}
