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
    "# Customer auth lives on client — never serve these paths from the marketing site",
    "RewriteRule ^customer-login/?$ https://client.wwt.com.py/customer-login [R=302,L]",
    "RewriteRule ^customer-register/?$ https://client.wwt.com.py/customer-register [R=302,L]",
    "RewriteRule ^customer-mylogin/?$ https://client.wwt.com.py/customer-mylogin [R=302,L]",
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
