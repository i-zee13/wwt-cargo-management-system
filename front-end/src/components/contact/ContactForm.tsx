"use client";

import { FormEvent, useState } from "react";
import { useTranslations } from "next-intl";
import { Send } from "lucide-react";
import { CONTACT } from "@/lib/constants";

export function ContactForm() {
  const t = useTranslations("contactPage.form");
  const [name, setName] = useState("");
  const [email, setEmail] = useState("");
  const [message, setMessage] = useState("");
  const [sent, setSent] = useState(false);

  function handleSubmit(e: FormEvent) {
    e.preventDefault();
    const subject = encodeURIComponent(`Consulta de ${name || "cliente"} - WWT`);
    const body = encodeURIComponent(`${message}\n\n${name} <${email}>`);
    window.location.href = `mailto:${CONTACT.email}?subject=${subject}&body=${body}`;
    setSent(true);
  }

  return (
    <form onSubmit={handleSubmit} className="space-y-4">
      <div>
        <label className="mb-1.5 block text-sm font-medium text-slate-700">
          {t("name")}
        </label>
        <input
          required
          value={name}
          onChange={(e) => setName(e.target.value)}
          className="w-full rounded-xl border border-slate-200 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-accent-500"
        />
      </div>

      <div>
        <label className="mb-1.5 block text-sm font-medium text-slate-700">
          {t("email")}
        </label>
        <input
          required
          type="email"
          value={email}
          onChange={(e) => setEmail(e.target.value)}
          className="w-full rounded-xl border border-slate-200 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-accent-500"
        />
      </div>

      <div>
        <label className="mb-1.5 block text-sm font-medium text-slate-700">
          {t("message")}
        </label>
        <textarea
          required
          rows={4}
          value={message}
          onChange={(e) => setMessage(e.target.value)}
          className="w-full rounded-xl border border-slate-200 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-accent-500"
        />
      </div>

      <button
        type="submit"
        className="inline-flex items-center gap-2 rounded-full bg-accent-500 px-6 py-3 text-sm font-bold text-navy transition-colors hover:bg-accent-400"
      >
        <Send size={16} />
        {t("submit")}
      </button>

      {sent && <p className="text-sm text-emerald-600">{t("success")}</p>}
    </form>
  );
}
