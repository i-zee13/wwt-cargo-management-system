import sharp from "sharp";
import path from "path";
import fs from "fs";
import { fileURLToPath } from "url";

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const root = path.join(__dirname, "..");

const src = process.argv[2] ?? "public/images/logistics-grid-sprite.png";
const outDir = path.join(root, "public", "images", "logistics");

const names = [
  "packing-tape",
  "warehouse-forklift",
  "van-loading",
  "truck-highway",
  "container-ship",
  "door-delivery",
  "tracking-phone",
  "pallet-jack",
  "support-agent-f",
  "support-agent-m",
  "barcode-scan",
  "delivery-fleet",
];

fs.mkdirSync(outDir, { recursive: true });

const meta = await sharp(path.join(root, src)).metadata();
const cols = 4;
const rows = 3;
const cellW = Math.floor(meta.width / cols);
const cellH = Math.floor(meta.height / rows);

let i = 0;
for (let row = 0; row < rows; row++) {
  for (let col = 0; col < cols; col++) {
    const out = path.join(outDir, `${names[i]}.png`);
    await sharp(path.join(root, src))
      .extract({ left: col * cellW, top: row * cellH, width: cellW, height: cellH })
      .png()
      .toFile(out);
    console.log("Wrote", out);
    i++;
  }
}
