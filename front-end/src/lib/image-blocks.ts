import type { ImageTextItem } from "@/components/shared/ImageTextBlock";
import type { VisualTheme } from "@/components/shared/VisualPanel";

export function parseImageBlocks(raw: unknown): ImageTextItem[] {
  if (!Array.isArray(raw)) return [];
  return raw.map((item) => {
    const row = item as ImageTextItem;
    return {
      ...row,
      visual: row.visual as VisualTheme | undefined,
    };
  });
}

export type PageCoverData = {
  image: string;
  imageAlt?: string;
  align?: "left" | "center";
};

export function parsePageCover(raw: unknown): PageCoverData | null {
  if (!raw || typeof raw !== "object") return null;
  const row = raw as PageCoverData;
  if (!row.image) return null;
  return row;
}
