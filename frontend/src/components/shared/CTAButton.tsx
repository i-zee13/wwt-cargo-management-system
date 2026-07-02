import { ReactNode } from "react";
import clsx from "clsx";

type Props = {
  href: string;
  children: ReactNode;
  variant?: "primary" | "secondary" | "ghost";
  className?: string;
  target?: string;
  rel?: string;
};

const variants = {
  primary:
    "bg-brand-600 text-white hover:bg-brand-700 shadow-lg shadow-brand-600/20",
  secondary:
    "bg-white text-brand-700 border border-brand-200 hover:bg-brand-50",
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
        "inline-flex items-center justify-center gap-2 rounded-full px-6 py-3 text-sm font-semibold transition-colors duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500 focus-visible:ring-offset-2",
        variants[variant],
        className
      )}
    >
      {children}
    </a>
  );
}
