import type { Metadata } from "next";
import { getTranslations, setRequestLocale } from "next-intl/server";
import { MessageCircle, Mail } from "lucide-react";
import { Container } from "@/components/shared/Container";
import { PageHero } from "@/components/shared/PageHero";
import { FadeIn } from "@/components/animations/FadeIn";
import { ContactForm } from "@/components/contact/ContactForm";
import { CONTACT, whatsappLink } from "@/lib/constants";

export async function generateMetadata({
  params,
}: {
  params: Promise<{ locale: string }>;
}): Promise<Metadata> {
  const { locale } = await params;
  const t = await getTranslations({ locale, namespace: "contactPage" });
  return {
    title: t("title"),
    description: t("subtitle"),
  };
}

export default async function ContactoPage({
  params,
}: {
  params: Promise<{ locale: string }>;
}) {
  const { locale } = await params;
  setRequestLocale(locale);

  const t = await getTranslations({ locale, namespace: "contactPage" });

  return (
    <>
      <PageHero title={t("title")} subtitle={t("subtitle")} />

      <section className="py-16 sm:py-20">
        <Container className="grid gap-10 lg:grid-cols-2 lg:items-start">
          <FadeIn className="grid gap-5 sm:grid-cols-2 lg:grid-cols-1">
            <a
              href={whatsappLink()}
              target="_blank"
              rel="noopener noreferrer"
              className="flex items-center gap-4 rounded-2xl border border-slate-100 bg-white p-6 shadow-sm transition-shadow hover:shadow-md"
            >
              <span className="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                <MessageCircle size={22} />
              </span>
              <div>
                <p className="font-semibold text-slate-900">{t("whatsappTitle")}</p>
                <p className="text-sm text-slate-500">{CONTACT.whatsappDisplay}</p>
              </div>
            </a>

            <a
              href={`mailto:${CONTACT.email}`}
              className="flex items-center gap-4 rounded-2xl border border-slate-100 bg-white p-6 shadow-sm transition-shadow hover:shadow-md"
            >
              <span className="flex h-12 w-12 items-center justify-center rounded-xl bg-brand-50 text-brand-600">
                <Mail size={22} />
              </span>
              <div>
                <p className="font-semibold text-slate-900">{t("emailTitle")}</p>
                <p className="text-sm text-slate-500">{CONTACT.email}</p>
              </div>
            </a>
          </FadeIn>

          <FadeIn delay={0.1}>
            <div className="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm sm:p-8">
              <h2 className="text-lg font-semibold text-slate-900">
                {t("formTitle")}
              </h2>
              <div className="mt-5">
                <ContactForm />
              </div>
            </div>
          </FadeIn>
        </Container>
      </section>
    </>
  );
}
