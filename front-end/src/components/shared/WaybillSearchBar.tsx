"use client";

import { FormEvent } from "react";
import { Search } from "lucide-react";
import clsx from "clsx";

type Props = {
  value: string;
  onChange: (value: string) => void;
  onSubmit: (e: FormEvent) => void;
  placeholder: string;
  submitLabel: string;
  disabled?: boolean;
  variant?: "dark" | "light";
  className?: string;
};

export function WaybillSearchBar({
  value,
  onChange,
  onSubmit,
  placeholder,
  submitLabel,
  disabled = false,
  variant = "dark",
  className,
}: Props) {
  const isDark = variant === "dark";

  return (
    <form
      onSubmit={onSubmit}
      className={clsx(
        "mx-auto flex w-full max-w-lg flex-col gap-3 sm:max-w-xl sm:flex-row sm:items-stretch sm:gap-0",
        className
      )}
    >
      <label className="sr-only" htmlFor="waybill-input">
        {placeholder}
      </label>
      <input
        id="waybill-input"
        name="waybill"
        type="text"
        autoComplete="off"
        inputMode="text"
        value={value}
        onChange={(e) => onChange(e.target.value)}
        placeholder={placeholder}
        className={clsx(
          "min-h-[52px] w-full px-5 py-3.5 text-base text-slate-900 placeholder:text-slate-500",
          "rounded-full border-0 bg-white shadow-sm",
          "focus:outline-none focus:ring-2",
          isDark ? "focus:ring-white/80" : "focus:ring-accent-500",
          "sm:rounded-r-none sm:pr-4"
        )}
      />
      <button
        type="submit"
        disabled={disabled}
        className={clsx(
          "inline-flex min-h-[52px] shrink-0 items-center justify-center gap-2",
          "rounded-full px-8 py-3.5 text-sm font-bold transition-colors",
          "bg-accent-500 text-navy hover:bg-accent-400",
          "focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2",
          isDark
            ? "focus-visible:ring-white focus-visible:ring-offset-brand-600"
            : "focus-visible:ring-accent-500 focus-visible:ring-offset-white",
          "disabled:cursor-not-allowed disabled:opacity-60",
          "sm:rounded-l-none sm:rounded-r-full"
        )}
      >
        <Search size={18} strokeWidth={2.5} aria-hidden />
        {submitLabel}
      </button>
    </form>
  );
}
