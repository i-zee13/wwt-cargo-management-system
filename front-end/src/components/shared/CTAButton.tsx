import { ReactNode } from "react";
import clsx from "clsx";

type Props = {
  href: string;
  children: ReactNode;
  variant?: "primary" | "secondary" | "brand" | "ghost";
  className?: string;
  target?: string;
  rel?: string;
};

const variants = {
  primary:
    "bg-accent-500 text-navy hover:bg-accent-400 shadow-lg shadow-accent-500/30 font-bold",
  brand:
    "bg-brand-600 text-white hover:bg-brand-700 shadow-lg shadow-brand-600/25",
  secondary:
    "bg-white text-brand-600 border-2 border-brand-600 hover:bg-brand-50",
  ghost: "text-white border border-white/40 hover:bg-white/10",
};

export function CTAButton({
  href,
  children,
  variant = "primary",
  className,
  target,
  rel,
}: Props) {
  return (
    <a
      href={href}
      target={target}
      rel={rel}
      className={clsx(
        "inline-flex items-center justify-center gap-2 rounded-full px-6 py-3 text-sm font-semibold transition-colors duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-accent-500 focus-visible:ring-offset-2",
        variants[variant],
        className
      )}
    >
      {children}
    </a>
  );
}
