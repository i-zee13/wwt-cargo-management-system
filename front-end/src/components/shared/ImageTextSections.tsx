import { ImageTextBlock, type ImageTextItem } from "@/components/shared/ImageTextBlock";

export function ImageTextSections({
  items,
  alternateBg = false,
}: {
  items: ImageTextItem[];
  alternateBg?: boolean;
}) {
  return (
    <>
      {items.map((item, i) => (
        <ImageTextBlock
          key={item.title}
          item={item}
          className={alternateBg && i % 2 === 1 ? "bg-brand-50/60" : undefined}
        />
      ))}
    </>
  );
}
