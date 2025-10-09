import type { NextConfig } from "next";

const localImageHost = process.env.NEXT_PUBLIC_IMAGE_HOST_LOCAL ?? "admin.allanwebdesign.com.2025.test";
const prodImageHost = process.env.NEXT_PUBLIC_IMAGE_HOST_PROD ?? "allanwebdesign.com";
const cdnHost = process.env.NEXT_PUBLIC_CDN_HOST ?? "cdn.allanwebdesign.com";

const nextConfig: NextConfig = {
  /* config options here */
  reactStrictMode: true,
  images: {
    // Allow remote images from Laravel admin in local and production
    remotePatterns: [
      {
        protocol: "http",
        hostname: localImageHost,
        pathname: "/storage/**",
      },
      {
        protocol: "https",
        hostname: prodImageHost,
        pathname: "/storage/**",
      },
      // Allow images served from Cloudflare R2 via custom CDN domain
      {
        protocol: "https",
        hostname: cdnHost,
        pathname: "/**",
      },
    ],
  },
};

export default nextConfig;
