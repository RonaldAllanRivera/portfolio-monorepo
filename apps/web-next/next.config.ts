import type { NextConfig } from "next";

const cdnHost = process.env.NEXT_PUBLIC_CDN_HOST ?? "cdn.allanwebdesign.com";

const nextConfig: NextConfig = {
  /* config options here */
  reactStrictMode: true,
  images: {
    // CDN-only images (e.g., Cloudflare R2 via custom domain)
    remotePatterns: [
      {
        protocol: "https",
        hostname: cdnHost,
        pathname: "/**",
      },
    ],
  },
};

export default nextConfig;
