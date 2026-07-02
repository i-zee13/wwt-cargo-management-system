"use client";

import { useState } from "react";
import { useTranslations } from "next-intl";
import { Menu, X, Package } from "lucide-react";
import { Link, usePathname } from "@/i18n/navigation";
import { Container } from "@/components/shared/Container";
import { LanguageToggle } from "@/components/layout/LanguageToggle";
import { CTAButton } from "@/components/shared/CTAButton";
import { PORTAL_LINKS } from "@/lib/constants";
import clsx from "clsx";

export function Header() {
  const t = useTranslations("nav");
  const pathname = usePathname();
  const [open, setOpen] = useState(false);

  const links = [
    { href: "/", label: t("inicio") },
    { href: "/servicios", label: t("servicios") },
    { href: "/tarifas", label: t("tarifas") },
    { href: "/rastreo", label: t("rastreo") },
    { href: "/sobre-nosotros", label: t("sobreNosotros") },
    { href: "/preguntas-frecuentes", label: t("faq") },
    { href: "/contacto", label: t("contacto") },
  ];

  return (
    <header className="sticky top-0 z-50 border-b border-slate-100 bg-white/90 backdrop-blur">
      <Container className="flex h-16 items-center justify-between">
        <Link href="/" className="flex items-center gap-2 font-bold text-slate-900">
          <span className="flex h-9 w-9 items-center justify-center rounded-xl bg-brand-600 text-white">
            <Package size={18} />
          </span>
          <span className="text-lg tracking-tight">WWT</span>
        </Link>

        <nav className="hidden items-center gap-6 lg:flex">
          {links.map((link) => (
            <Link
              key={link.href}
              href={link.href}
              className={clsx(
                "text-sm font-medium transition-colors hover:text-brand-600",
                pathname === link.href ? "text-brand-600" : "text-slate-600"
              )}
            >
              {link.label}
            </Link>
          ))}
        </nav>

        <div className="hidden items-center gap-3 lg:flex">
          <LanguageToggle />
          <a
            href={PORTAL_LINKS.login}
            className="text-sm font-semibold text-slate-600 hover:text-brand-600"
          >
            {t("ingresar")}
          </a>
          <CTAButton href={PORTAL_LINKS.register} className="!px-5 !py-2">
            {t("registro")}
          </CTAButton>
        </div>

        <button
          type="button"
          className="inline-flex items-center justify-center rounded-lg p-2 text-slate-600 lg:hidden"
          aria-label="Menu"
          aria-expanded={open}
          onClick={() => setOpen((v) => !v)}
        >
          {open ? <X size={22} /> : <Menu size={22} />}
        </button>
      </Container>

      {open && (
        <div className="border-t border-slate-100 bg-white lg:hidden">
          <Container className="flex flex-col gap-1 py-4">
            {links.map((link) => (
              <Link
                key={link.href}
                href={link.href}
                onClick={() => setOpen(false)}
                className="rounded-lg px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-brand-50 hover:text-brand-600"
              >
                {link.label}
              </Link>
            ))}
            <div className="mt-3 flex items-center justify-between gap-3 px-3">
              <LanguageToggle />
              <a
                href={PORTAL_LINKS.login}
                className="text-sm font-semibold text-slate-600 hover:text-brand-600"
              >
                {t("ingresar")}
              </a>
            </div>
            <CTAButton href={PORTAL_LINKS.register} className="mx-3 mt-2">
              {t("registro")}
            </CTAButton>
          </Container>
        </div>
      )}
    </header>
  );
}
