"use client";

import { useEffect, useState } from "react";
import { useTranslations } from "next-intl";
import { Menu, X } from "lucide-react";
import { Link, usePathname } from "@/i18n/navigation";
import { Container } from "@/components/shared/Container";
import { LanguageToggle } from "@/components/layout/LanguageToggle";
import { CTAButton } from "@/components/shared/CTAButton";
import { Logo } from "@/components/shared/Logo";
import { PORTAL_LINKS } from "@/lib/constants";
import clsx from "clsx";

const SCROLL_THRESHOLD = 56;

export function Header() {
  const t = useTranslations("nav");
  const tLogo = useTranslations("logo");
  const pathname = usePathname();
  const [open, setOpen] = useState(false);
  const [solid, setSolid] = useState(false);

  useEffect(() => {
    const onScroll = () => setSolid(window.scrollY > SCROLL_THRESHOLD);
    onScroll();
    window.addEventListener("scroll", onScroll, { passive: true });
    return () => window.removeEventListener("scroll", onScroll);
  }, []);

  const transparent = !solid;

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
    <header
      className={clsx(
        "fixed inset-x-0 top-0 z-50 transition-all duration-300",
        solid
          ? "border-b border-slate-100 bg-white/95 shadow-sm backdrop-blur-md"
          : "border-b border-transparent bg-transparent"
      )}
    >
      <Container className="flex h-[4.25rem] items-center justify-between sm:h-[4.5rem]">
        <Link href="/" className="shrink-0">
          <Logo
            size="sm"
            variant={transparent ? "dark" : "light"}
            ocrLine1={tLogo("ocrLine1")}
            ocrLine2={tLogo("ocrLine2")}
          />
        </Link>

        <nav className="hidden items-center gap-6 lg:flex">
          {links.map((link) => (
            <Link
              key={link.href}
              href={link.href}
              className={clsx(
                "text-sm font-medium transition-colors",
                transparent
                  ? pathname === link.href
                    ? "text-accent-400"
                    : "text-white/90 hover:text-accent-400"
                  : pathname === link.href
                    ? "text-brand-600"
                    : "text-slate-600 hover:text-brand-600"
              )}
            >
              {link.label}
            </Link>
          ))}
        </nav>

        <div className="hidden items-center gap-3 lg:flex">
          <LanguageToggle inverted={transparent} />
          <a
            href={PORTAL_LINKS.login}
            className={clsx(
              "text-sm font-semibold transition-colors",
              transparent
                ? "text-white/90 hover:text-accent-400"
                : "text-slate-600 hover:text-brand-600"
            )}
          >
            {t("ingresar")}
          </a>
          <CTAButton href={PORTAL_LINKS.register} className="!px-5 !py-2">
            {t("registro")}
          </CTAButton>
        </div>

        <button
          type="button"
          className={clsx(
            "inline-flex items-center justify-center rounded-lg p-2 lg:hidden",
            transparent ? "text-white" : "text-slate-600"
          )}
          aria-label="Menu"
          aria-expanded={open}
          onClick={() => setOpen((v) => !v)}
        >
          {open ? <X size={22} /> : <Menu size={22} />}
        </button>
      </Container>

      {open && (
        <div
          className={clsx(
            "border-t lg:hidden",
            solid ? "border-slate-100 bg-white" : "border-white/10 bg-brand-600/95 backdrop-blur-md"
          )}
        >
          <Container className="flex flex-col gap-1 py-4">
            {links.map((link) => (
              <Link
                key={link.href}
                href={link.href}
                onClick={() => setOpen(false)}
                className={clsx(
                  "rounded-lg px-3 py-2.5 text-sm font-medium",
                  solid
                    ? "text-slate-700 hover:bg-brand-50 hover:text-brand-600"
                    : "text-white hover:bg-white/10"
                )}
              >
                {link.label}
              </Link>
            ))}
            <div className="mt-3 flex items-center justify-between gap-3 px-3">
              <LanguageToggle inverted={!solid} />
              <a
                href={PORTAL_LINKS.login}
                className={clsx(
                  "text-sm font-semibold",
                  solid ? "text-slate-600" : "text-white"
                )}
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
