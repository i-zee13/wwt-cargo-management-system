export const TITLE_SPEED_MS = 11;
export const SUBTITLE_SPEED_MS = 9;
export const BODY_SPEED_MS = 7;
export const PAUSE_AFTER_TITLE_MS = 280;
export const PAUSE_AFTER_SUBTITLE_MS = 320;
export const PAUSE_AFTER_ITEM_TITLE_MS = 120;
export const CARD_STAGGER_MS = 80;

export function typewriterMs(text: string, speedMs: number) {
  return text.length * speedMs;
}

export function sectionHeadingDelays(
  title: string,
  subtitle?: string,
  baseDelay = 0
) {
  const titleDelay = baseDelay;
  const subtitleDelay =
    titleDelay + typewriterMs(title, TITLE_SPEED_MS) + PAUSE_AFTER_TITLE_MS;
  const contentBaseDelay = subtitle
    ? subtitleDelay +
      typewriterMs(subtitle, SUBTITLE_SPEED_MS) +
      PAUSE_AFTER_SUBTITLE_MS
    : titleDelay + typewriterMs(title, TITLE_SPEED_MS) + PAUSE_AFTER_SUBTITLE_MS;

  return {
    titleDelay,
    subtitleDelay,
    contentBaseDelay,
    titleSpeed: TITLE_SPEED_MS,
    subtitleSpeed: SUBTITLE_SPEED_MS,
  };
}

export function itemTypeDelays(
  contentBaseDelay: number,
  index: number,
  titleText: string,
  staggerMs = CARD_STAGGER_MS
) {
  const titleDelay = contentBaseDelay + index * staggerMs;
  const descriptionDelay =
    titleDelay + typewriterMs(titleText, TITLE_SPEED_MS) + PAUSE_AFTER_ITEM_TITLE_MS;

  return { titleDelay, descriptionDelay };
}

export function chainDelays(
  baseDelay: number,
  parts: { text: string; speedMs: number; pauseAfter?: number }[]
) {
  let cursor = baseDelay;
  return parts.map(({ text, speedMs, pauseAfter = 220 }) => {
    const delay = cursor;
    cursor += typewriterMs(text, speedMs) + pauseAfter;
    return delay;
  });
}
