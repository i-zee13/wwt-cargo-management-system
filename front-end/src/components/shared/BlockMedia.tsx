import Image from "next/image";
import { VisualPanel, type VisualTheme } from "@/components/shared/VisualPanel";
import clsx from "clsx";

export type MediaLayout = "single" | "grid" | "mosaic" | "collage";

type Props = {
  image?: string;
  images?: string[];
  imageGrid?: boolean;
  layout?: MediaLayout;
  visual?: VisualTheme;
  visualLabel: string;
  className?: string;
};

const GRID_POSITIONS = [
  "0% 0%",
  "100% 0%",
  "0% 100%",
  "100% 100%",
] as const;

function MosaicGallery({
  images,
  visualLabel,
  className,
}: {
  images: string[];
  visualLabel: string;
  className?: string;
}) {
  const [hero, ...rest] = images;
  const secondary = rest.slice(0, 2);

  return (
    <div className={clsx("grid gap-3", className)}>
      <div className="relative aspect-[16/10] overflow-hidden rounded-2xl shadow-lg sm:aspect-[2/1]">
        <Image
          src={hero}
          alt={`${visualLabel} — main`}
          fill
          className="object-cover"
          sizes="(max-width: 1024px) 100vw, 40vw"
        />
      </div>
      {secondary.length > 0 && (
        <div className="grid grid-cols-2 gap-3">
          {secondary.map((src, index) => (
            <div
              key={src}
              className="relative aspect-[4/3] overflow-hidden rounded-2xl shadow-md"
            >
              <Image
                src={src}
                alt={`${visualLabel} — ${index + 2}`}
                fill
                className="object-cover"
                sizes="(max-width: 1024px) 50vw, 20vw"
              />
            </div>
          ))}
        </div>
      )}
    </div>
  );
}

function CollageGallery({
  images,
  visualLabel,
  className,
}: {
  images: string[];
  visualLabel: string;
  className?: string;
}) {
  const imgs = images.slice(0, 3);
  if (imgs.length < 3) {
    return <MosaicGallery images={imgs} visualLabel={visualLabel} className={className} />;
  }

  return (
    <div
      className={clsx(
        "grid h-[420px] grid-cols-12 grid-rows-6 gap-3 sm:h-[480px]",
        className
      )}
    >
      <div className="relative col-span-7 row-span-6 overflow-hidden rounded-2xl shadow-lg">
        <Image
          src={imgs[0]}
          alt={`${visualLabel} — 1`}
          fill
          className="object-cover"
          sizes="(max-width: 1024px) 60vw, 35vw"
        />
      </div>
      <div className="relative col-span-5 row-span-3 overflow-hidden rounded-2xl shadow-md">
        <Image
          src={imgs[1]}
          alt={`${visualLabel} — 2`}
          fill
          className="object-cover"
          sizes="(max-width: 1024px) 40vw, 25vw"
        />
      </div>
      <div className="relative col-span-5 row-span-3 overflow-hidden rounded-2xl shadow-md">
        <Image
          src={imgs[2]}
          alt={`${visualLabel} — 3`}
          fill
          className="object-cover"
          sizes="(max-width: 1024px) 40vw, 25vw"
        />
      </div>
    </div>
  );
}

export function BlockMedia({
  image,
  images,
  imageGrid = false,
  layout = "single",
  visual,
  visualLabel,
  className,
}: Props) {
  if (images && images.length >= 3 && (layout === "mosaic" || layout === "collage")) {
    if (layout === "collage") {
      return (
        <CollageGallery
          images={images}
          visualLabel={visualLabel}
          className={className}
        />
      );
    }
    return (
      <MosaicGallery
        images={images}
        visualLabel={visualLabel}
        className={className}
      />
    );
  }

  if (image && imageGrid) {
    return (
      <div className={clsx("grid grid-cols-2 gap-3", className)}>
        {GRID_POSITIONS.map((position) => (
          <div
            key={position}
            className="relative aspect-square overflow-hidden rounded-2xl shadow-md"
            role="img"
            aria-label={visualLabel}
            style={{
              backgroundImage: `url(${image})`,
              backgroundSize: "200% 200%",
              backgroundPosition: position,
            }}
          />
        ))}
      </div>
    );
  }

  if (images && images.length > 0) {
    return (
      <div
        className={clsx(
          "grid gap-3",
          images.length === 1 ? "grid-cols-1" : "grid-cols-2",
          className
        )}
      >
        {images.map((src, index) => (
          <div
            key={src}
            className="relative aspect-[4/3] overflow-hidden rounded-2xl shadow-lg"
          >
            <Image
              src={src}
              alt={`${visualLabel} — ${index + 1}`}
              fill
              className="object-cover"
              sizes="(max-width: 1024px) 50vw, 28vw"
            />
          </div>
        ))}
      </div>
    );
  }

  if (image) {
    return (
      <div
        className={clsx(
          "relative aspect-[4/3] overflow-hidden rounded-3xl shadow-xl sm:aspect-[3/2]",
          className
        )}
      >
        <Image
          src={image}
          alt={visualLabel}
          fill
          className="object-cover"
          sizes="(max-width: 1024px) 100vw, 50vw"
        />
      </div>
    );
  }

  if (visual) {
    return <VisualPanel theme={visual} label={visualLabel} className={className} />;
  }

  return null;
}
