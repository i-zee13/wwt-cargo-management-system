"use client";

import { useLocale } from "next-intl";
import { usePathname, useRouter } from "@/i18n/navigation";
import { useParams } from "next/navigation";

const LOCALES: { code: "es" | "en"; label: string }[] = [
  { code: "es", label: "ES" },
  { code: "en", label: "EN" },
];

export function LanguageToggle({ className }: { className?: string }) {
  const locale = useLocale();
  const router = useRouter();
  const pathname = usePathname();
  const params = useParams();

  return (
    <div
      className={`inline-flex items-center rounded-full border border-slate-200 bg-white p-1 text-sm font-semibold ${className ?? ""}`}
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
          className={`rounded-full px-3 py-1 transition-colors ${
            locale === code
              ? "bg-brand-600 text-white"
              : "text-slate-500 hover:text-brand-600"
          }`}
        >
          {label}
        </button>
      ))}
    </div>
  );
}
