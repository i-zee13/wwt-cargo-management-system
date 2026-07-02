"use client";

import { motion } from "framer-motion";
import { ServiceIcon } from "@/components/shared/ServiceIcon";

export function ServiceCard({
  title,
  description,
  icon,
}: {
  title: string;
  description: string;
  icon: string;
}) {
  return (
    <motion.div
      whileHover={{ y: -6 }}
      transition={{ duration: 0.2 }}
      className="h-full rounded-2xl border border-slate-100 bg-white p-6 shadow-sm hover:shadow-md"
    >
      <span className="flex h-11 w-11 items-center justify-center rounded-xl bg-brand-50 text-brand-600">
        <ServiceIcon name={icon} />
      </span>
      <h3 className="mt-4 text-base font-semibold text-slate-900">{title}</h3>
      <p className="mt-2 text-sm leading-relaxed text-slate-600">{description}</p>
    </motion.div>
  );
}
