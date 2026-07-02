/** @type {import('next-sitemap').IConfig} */
module.exports = {
  siteUrl: process.env.NEXT_PUBLIC_SITE_URL || "https://wwt.com.py",
  generateRobotsTxt: true,
  generateIndexSitemap: false,
  exclude: ["/api/*"],
};
