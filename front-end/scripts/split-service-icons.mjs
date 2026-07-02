import sharp from "sharp";
import path from "path";
import { fileURLToPath } from "url";
import fs from "fs";

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const root = path.join(__dirname, "..");

const src = process.argv[2];
if (!src) {
  console.error("Usage: node scripts/split-service-icons.mjs <source-image>");
  process.exit(1);
}

const outDir = path.join(root, "public", "images", "services");
fs.mkdirSync(outDir, { recursive: true });

const names = [
  "service-parcel-shipping",
  "service-mailbox-shopping",
  "service-warehouse",
  "service-tracking",
  "service-rates",
  "service-business",
];

const meta = await sharp(src).metadata();
const cols = 3;
const rows = 2;
const cellW = Math.floor(meta.width / cols);
const cellH = Math.floor(meta.height / rows);

for (let row = 0; row < rows; row++) {
  for (let col = 0; col < cols; col++) {
    const index = row * cols + col;
    const out = path.join(outDir, `${names[index]}.png`);
    await sharp(src)
      .extract({
        left: col * cellW,
        top: row * cellH,
        width: cellW,
        height: cellH,
      })
      .png()
      .toFile(out);
    console.log("Wrote", out);
  }
}
