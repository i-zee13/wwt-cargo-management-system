export const SITE_URL =
  process.env.NEXT_PUBLIC_SITE_URL?.replace(/\/$/, "") ?? "https://wwt.com.py";

/** Customer portal host (login / register / tracking API). */
export const CLIENT_URL =
  process.env.NEXT_PUBLIC_CLIENT_URL?.replace(/\/$/, "") ??
  "https://client.wwt.com.py";

/** @deprecated Prefer CLIENT_URL — kept for tracking helpers. */
export const LARAVEL_URL =
  process.env.NEXT_PUBLIC_LARAVEL_URL?.replace(/\/$/, "") ?? CLIENT_URL;

/** Admin panel host (not linked on the public marketing site). */
export const ADMIN_URL =
  process.env.NEXT_PUBLIC_ADMIN_URL?.replace(/\/$/, "") ??
  "https://portal.wwt.com.py";

export const CONTACT = {
  email: "consultas@wwt.com.py",
  whatsappNumber: "595986747236",
  whatsappDisplay: "+595 986 747 236",
};

export const whatsappLink = (message?: string) =>
  `https://wa.me/${CONTACT.whatsappNumber}${
    message ? `?text=${encodeURIComponent(message)}` : ""
  }`;

/** Customer auth on client.*; admin URL for private sharing only. */
export const PORTAL_LINKS = {
  login: `${CLIENT_URL}/customer-login`,
  register: `${CLIENT_URL}/customer-register`,
  admin: `${ADMIN_URL}/admin/login`,
};
