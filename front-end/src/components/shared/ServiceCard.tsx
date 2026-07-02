"use client";

import Image from "next/image";
import { motion } from "framer-motion";
import { ServiceIcon } from "@/components/shared/ServiceIcon";
import clsx from "clsx";

export function ServiceCard({
  title,
  description,
  icon,
  image,
  imageLabel,
  variant = "light",
}: {
  title: string;
  description: string;
  icon: string;
  image?: string;
  imageLabel?: string;
  variant?: "light" | "dark";
}) {
  const isDark = variant === "dark";

  return (
    <motion.div
      whileHover={{ y: -6 }}
      transition={{ duration: 0.2 }}
      className={clsx(
        "group h-full overflow-hidden rounded-2xl border shadow-sm transition-colors",
        isDark
          ? "border-brand-500/40 bg-brand-600 hover:border-accent-500/50 hover:shadow-lg hover:shadow-brand-600/30"
          : "border-brand-100 bg-white hover:border-accent-300 hover:shadow-md"
      )}
    >
      {image && (
        <div className="relative aspect-[16/10] w-full overflow-hidden">
          <Image
            src={image}
            alt={imageLabel ?? title}
            fill
            className="object-cover transition-transform duration-300 group-hover:scale-105"
            sizes="(max-width: 1024px) 50vw, 33vw"
          />
        </div>
      )}
      <div className="p-6">
        {!image && (
          <span
            className={clsx(
              "flex h-11 w-11 items-center justify-center rounded-xl transition-colors",
              isDark
                ? "bg-brand-700 text-accent-500 group-hover:bg-accent-500 group-hover:text-navy"
                : "bg-brand-600 text-accent-500 group-hover:bg-accent-500 group-hover:text-navy"
            )}
          >
            <ServiceIcon name={icon} />
          </span>
        )}
        <h3
          className={clsx(
            "font-heading text-base font-semibold uppercase tracking-wide",
            image ? "mt-0" : "mt-4",
            isDark ? "text-white" : "text-navy"
          )}
        >
          {title}
        </h3>
        <p
          className={clsx(
            "mt-2 text-sm leading-relaxed",
            isDark ? "text-brand-200" : "text-slate-600"
          )}
        >
          {description}
        </p>
      </div>
    </motion.div>
  );
}
