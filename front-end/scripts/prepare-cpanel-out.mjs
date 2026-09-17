import { copyFileSync, writeFileSync, existsSync } from "fs";
import { join } from "path";

const outDir = join(process.cwd(), "out");
const esHtml = join(outDir, "es.html");
const indexHtml = join(outDir, "index.html");

if (!existsSync(esHtml)) {
  console.warn("[prepare-cpanel-out] out/es.html missing — skip");
  process.exit(0);
}

copyFileSync(esHtml, indexHtml);

writeFileSync(
  join(outDir, ".htaccess"),
  [
    "DirectoryIndex index.html es.html",
    "RewriteEngine On",
    "RewriteRule ^servicios/?$ /es/servicios.html [L]",
    "RewriteRule ^rastreo/?$ /es/rastreo.html [L]",
    "RewriteRule ^tarifas/?$ /es/tarifas.html [L]",
    "RewriteRule ^sobre-nosotros/?$ /es/sobre-nosotros.html [L]",
    "RewriteRule ^preguntas-frecuentes/?$ /es/preguntas-frecuentes.html [L]",
    "RewriteRule ^contacto/?$ /es/contacto.html [L]",
    "",
  ].join("\n")
);

console.log("[prepare-cpanel-out] Wrote out/index.html and out/.htaccess");
