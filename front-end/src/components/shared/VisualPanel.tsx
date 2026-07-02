import {
  Package,
  Plane,
  Ship,
  Warehouse,
  MapPin,
  Globe2,
  BadgeDollarSign,
  Boxes,
  type LucideIcon,
} from "lucide-react";
import clsx from "clsx";

export type VisualTheme =
  | "shipping"
  | "warehouse"
  | "tracking"
  | "global"
  | "packages"
  | "rates";

const THEMES: Record<
  VisualTheme,
  { gradient: string; icon: LucideIcon; accent: string; secondary: LucideIcon }
> = {
  shipping: {
    gradient: "from-accent-400 via-accent-500 to-orange-600",
    icon: Plane,
    accent: "bg-white/20",
    secondary: Ship,
  },
  warehouse: {
    gradient: "from-brand-500 via-brand-600 to-brand-800",
    icon: Warehouse,
    accent: "bg-accent-500/30",
    secondary: Boxes,
  },
  tracking: {
    gradient: "from-emerald-400 via-teal-500 to-brand-600",
    icon: MapPin,
    accent: "bg-white/20",
    secondary: Package,
  },
  global: {
    gradient: "from-violet-500 via-brand-600 to-indigo-700",
    icon: Globe2,
    accent: "bg-accent-500/25",
    secondary: Plane,
  },
  packages: {
    gradient: "from-amber-400 via-accent-500 to-yellow-600",
    icon: Package,
    accent: "bg-brand-600/30",
    secondary: Boxes,
  },
  rates: {
    gradient: "from-brand-600 via-slate-700 to-brand-800",
    icon: BadgeDollarSign,
    accent: "bg-accent-500/30",
    secondary: Ship,
  },
};

export function VisualPanel({
  theme,
  label,
  className,
}: {
  theme: VisualTheme;
  label: string;
  className?: string;
}) {
  const { gradient, icon: Icon, accent, secondary: Secondary } = THEMES[theme];

  return (
    <div
      className={clsx(
        "relative aspect-[4/3] overflow-hidden rounded-3xl bg-gradient-to-br shadow-xl sm:aspect-square lg:aspect-[4/3]",
        gradient,
        className
      )}
      role="img"
      aria-label={label}
    >
      <div className="absolute inset-0 bg-[radial-gradient(circle_at_30%_20%,rgba(255,255,255,0.25),transparent_50%)]" />
      <div className="absolute -right-8 -top-8 h-40 w-40 rounded-full bg-white/10 blur-2xl" />
      <div className="absolute -bottom-10 -left-10 h-48 w-48 rounded-full bg-black/10 blur-2xl" />

      <div className="relative flex h-full flex-col items-center justify-center p-8">
        <span
          className={clsx(
            "mb-6 flex h-24 w-24 items-center justify-center rounded-3xl backdrop-blur-sm",
            accent
          )}
        >
          <Icon size={48} className="text-white drop-shadow-md" strokeWidth={1.5} />
        </span>
        <span className="absolute bottom-8 right-8 flex h-14 w-14 items-center justify-center rounded-2xl bg-white/15 backdrop-blur-sm">
          <Secondary size={26} className="text-white/90" />
        </span>
        <div className="absolute left-8 top-8 flex gap-2">
          <span className="h-2 w-2 rounded-full bg-white/60" />
          <span className="h-2 w-2 rounded-full bg-white/40" />
          <span className="h-2 w-2 rounded-full bg-accent-300" />
        </div>
      </div>
    </div>
  );
}
