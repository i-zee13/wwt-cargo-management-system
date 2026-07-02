import clsx from "clsx";

const HEIGHT = {
  sm: "h-9 w-auto",
  md: "h-12 w-auto",
  lg: "h-16 w-auto",
  xl: "h-24 w-auto sm:h-28 md:h-32",
} as const;

type LogoSize = keyof typeof HEIGHT;
type LogoVariant = "light" | "dark";

type Props = {
  size?: LogoSize;
  className?: string;
  variant?: LogoVariant;
  ocrLine1?: string;
  ocrLine2?: string;
};

export function Logo({
  size = "md",
  className,
  variant = "light",
  ocrLine1 = "WWT",
  ocrLine2 = "Group",
}: Props) {
  const isDark = variant === "dark";
  const wwtColor = isDark ? "#ffffff" : "#040725";
  const groupColor = "#ebb30a";
  const hexColor = "#ebb30a";

  return (
    <span className={clsx("inline-flex shrink-0", className)}>
      <svg
        viewBox="0 0 220 64"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
        className={HEIGHT[size]}
        role="img"
        aria-label={`${ocrLine1} ${ocrLine2} — World Wide Trading Group`}
      >
        <polygon
          points="32,4 56,4 68,32 56,60 32,60 20,32"
          fill={hexColor}
        />
        <text
          x="74"
          y="42"
          fill={wwtColor}
          fontFamily="var(--font-lato), Lato, sans-serif"
          fontSize="34"
          fontWeight="900"
          letterSpacing="-0.5"
        >
          {ocrLine1}
        </text>
        <text
          x="74"
          y="58"
          fill={groupColor}
          fontFamily="var(--font-cinzel), Cinzel, serif"
          fontSize="14"
          fontWeight="600"
        >
          {ocrLine2}
        </text>
      </svg>
    </span>
  );
}
