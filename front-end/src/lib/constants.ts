export const SITE_URL =
  process.env.NEXT_PUBLIC_SITE_URL?.replace(/\/$/, "") ?? "https://wwt.com.py";

/** Customer auth + API on portal host (admin "/" still redirects to /admin/login). */
export const ADMIN_URL =
  process.env.NEXT_PUBLIC_ADMIN_URL?.replace(/\/$/, "") ??
  "https://portal.wwt.com.py";

/** Optional client subdomain entry (Laravel CLIENT_URL). */
export const CLIENT_URL =
  process.env.NEXT_PUBLIC_CLIENT_URL?.replace(/\/$/, "") ??
  "https://client.wwt.com.py";

/** Laravel API base — portal hosts customer auth. */
export const LARAVEL_URL =
  process.env.NEXT_PUBLIC_LARAVEL_URL?.replace(/\/$/, "") ?? ADMIN_URL;

export const CONTACT = {
  email: "consultas@wwt.com.py",
  whatsappNumber: "595986747236",
  whatsappDisplay: "+595 986 747 236",
};

export const whatsappLink = (message?: string) =>
  `https://wa.me/${CONTACT.whatsappNumber}${
    message ? `?text=${encodeURIComponent(message)}` : ""
  }`;

/** Ingresar / Register → portal (not the marketing apex). */
export const PORTAL_LINKS = {
  login: `${ADMIN_URL}/customer-login`,
  register: `${ADMIN_URL}/customer-register`,
  admin: `${ADMIN_URL}/admin/login`,
};
