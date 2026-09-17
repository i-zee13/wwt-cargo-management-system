export const SITE_URL =
  process.env.NEXT_PUBLIC_SITE_URL?.replace(/\/$/, "") ?? "https://wwt.com.py";

/** Laravel app base — customer auth + API on the primary domain. */
export const LARAVEL_URL =
  process.env.NEXT_PUBLIC_LARAVEL_URL?.replace(/\/$/, "") ?? SITE_URL;

/** Admin panel host (not linked on the public site). */
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

/** Customer portal — primary domain (wwt.com.py), not the admin portal host. */
export const PORTAL_LINKS = {
  login: `${SITE_URL}/customer-login`,
  register: `${SITE_URL}/customer-register`,
  admin: `${ADMIN_URL}/admin/login`,
};
