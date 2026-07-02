export const LARAVEL_URL =
  process.env.NEXT_PUBLIC_LARAVEL_URL?.replace(/\/$/, "") ??
  "https://portal.wwt.com.py";

export const SITE_URL =
  process.env.NEXT_PUBLIC_SITE_URL?.replace(/\/$/, "") ?? "https://wwt.com.py";

export const CONTACT = {
  email: "consultas@wwt.com.py",
  whatsappNumber: "595986747236",
  whatsappDisplay: "+595 986 747 236",
};

export const whatsappLink = (message?: string) =>
  `https://wa.me/${CONTACT.whatsappNumber}${
    message ? `?text=${encodeURIComponent(message)}` : ""
  }`;

export const PORTAL_LINKS = {
  login: `${LARAVEL_URL}/customer-login`,
  register: `${LARAVEL_URL}/customer-register`,
};
