import { useTranslations } from "next-intl";
import { Package, Mail, MessageCircle } from "lucide-react";
import { Link } from "@/i18n/navigation";
import { Container } from "@/components/shared/Container";
import { CONTACT, whatsappLink } from "@/lib/constants";

export function Footer() {
  const t = useTranslations("footer");
  const tNav = useTranslations("nav");

  const year = new Date().getFullYear();

  return (
    <footer className="border-t border-slate-100 bg-slate-950 text-slate-300">
      <Container className="grid gap-10 py-14 sm:grid-cols-2 lg:grid-cols-4">
        <div>
          <div className="flex items-center gap-2 font-bold text-white">
            <span className="flex h-9 w-9 items-center justify-center rounded-xl bg-brand-600">
              <Package size={18} />
            </span>
            <span className="text-lg tracking-tight">WWT</span>
          </div>
          <p className="mt-4 text-sm leading-relaxed text-slate-400">{t("about")}</p>
        </div>

        <div>
          <h3 className="text-sm font-semibold uppercase tracking-wide text-white">
            {t("quickLinksTitle")}
          </h3>
          <ul className="mt-4 space-y-2 text-sm">
            <li><Link href="/" className="hover:text-brand-400">{tNav("inicio")}</Link></li>
            <li><Link href="/servicios" className="hover:text-brand-400">{tNav("servicios")}</Link></li>
            <li><Link href="/tarifas" className="hover:text-brand-400">{tNav("tarifas")}</Link></li>
            <li><Link href="/rastreo" className="hover:text-brand-400">{tNav("rastreo")}</Link></li>
          </ul>
        </div>

        <div>
          <h3 className="text-sm font-semibold uppercase tracking-wide text-white">
            {t("servicesTitle")}
          </h3>
          <ul className="mt-4 space-y-2 text-sm">
            <li><Link href="/sobre-nosotros" className="hover:text-brand-400">{tNav("sobreNosotros")}</Link></li>
            <li><Link href="/preguntas-frecuentes" className="hover:text-brand-400">{tNav("faq")}</Link></li>
            <li><Link href="/contacto" className="hover:text-brand-400">{tNav("contacto")}</Link></li>
          </ul>
        </div>

        <div>
          <h3 className="text-sm font-semibold uppercase tracking-wide text-white">
            {t("contactTitle")}
          </h3>
          <ul className="mt-4 space-y-3 text-sm">
            <li>
              <a
                href={whatsappLink()}
                target="_blank"
                rel="noopener noreferrer"
                className="flex items-center gap-2 hover:text-brand-400"
              >
                <MessageCircle size={16} /> {CONTACT.whatsappDisplay}
              </a>
            </li>
            <li>
              <a
                href={`mailto:${CONTACT.email}`}
                className="flex items-center gap-2 hover:text-brand-400"
              >
                <Mail size={16} /> {CONTACT.email}
              </a>
            </li>
          </ul>
        </div>
      </Container>

      <div className="border-t border-white/10 py-6 text-center text-xs text-slate-500">
        &copy; {year} World Wide Trading Group (WWT). {t("rights")}
      </div>
    </footer>
  );
}
