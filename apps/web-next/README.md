This is a [Next.js](https://nextjs.org) project bootstrapped with [`create-next-app`](https://nextjs.org/docs/pages/api-reference/create-next-app).

## Getting Started

First, run the development server:

```bash
npm run dev
# or
yarn dev
# or
pnpm dev
# or
bun dev
```

Open [http://localhost:3000](http://localhost:3000) with your browser to see the result.

You can start editing the page by modifying `pages/index.tsx`. The page auto-updates as you edit the file.

[API routes](https://nextjs.org/docs/pages/building-your-application/routing/api-routes) can be accessed on [http://localhost:3000/api/hello](http://localhost:3000/api/hello). This endpoint can be edited in `pages/api/hello.ts`.

The `pages/api` directory is mapped to `/api/*`. Files in this directory are treated as [API routes](https://nextjs.org/docs/pages/building-your-application/routing/api-routes) instead of React pages.

This project uses [`next/font`](https://nextjs.org/docs/pages/building-your-application/optimizing/fonts) to automatically optimize and load [Geist](https://vercel.com/font), a new font family for Vercel.

## Learn More

To learn more about Next.js, take a look at the following resources:

- [Next.js Documentation](https://nextjs.org/docs) - learn about Next.js features and API.
- [Learn Next.js](https://nextjs.org/learn-pages-router) - an interactive Next.js tutorial.

You can check out [the Next.js GitHub repository](https://github.com/vercel/next.js) - your feedback and contributions are welcome!

## Deploy on Vercel

The easiest way to deploy your Next.js app is to use the [Vercel Platform](https://vercel.com/new?utm_medium=default-template&filter=next.js&utm_source=create-next-app&utm_campaign=create-next-app-readme) from the creators of Next.js.

Check out our [Next.js deployment documentation](https://nextjs.org/docs/pages/building-your-application/deploying) for more details.

---

## Project specifics (Monorepo: Allan Web Design – Portfolio)

- **App Router (used in this project)**
  - Entry files live under `app/`, e.g. `app/layout.tsx`, `app/(site)/page.tsx`.
  - Avoid using `src/pages` to prevent routing conflicts.

- **Local env**
  - File: `apps/web-next/.env.local`
  - Required variables:
    - `NEXT_PUBLIC_API_BASE_URL=http://admin.allanwebdesign.com.2025.test`
    - `NEXT_PUBLIC_IMAGE_HOST_LOCAL=admin.allanwebdesign.com.2025.test`
    - `NEXT_PUBLIC_IMAGE_HOST_PROD=allanwebdesign.com`

- **Remote images**
  - Configured in `next.config.ts` using env-driven hosts:
    - Local: `http://admin.allanwebdesign.com.2025.test/storage/**`
    - Prod: `https://allanwebdesign.com/storage/**`
  - CDN (Cloudflare R2): `https://cdn.allanwebdesign.com/**`
    - Set `NEXT_PUBLIC_CDN_HOST=cdn.allanwebdesign.com` in `.env.local` or project env.
    - `next.config.ts` reads this env and allows the CDN domain in `images.remotePatterns`.

- **Troubleshooting**
  - Error: “App Router and Pages Router both match path: /”
    - Remove `src/pages/index.tsx`, `_app.tsx`, `_document.tsx`, and migrate any API routes to `app/api/...`.
  - Error: “The default export is not a React Component in "/page"”
    - Ensure `app/layout.tsx` exists and `app/(site)/page.tsx` has a default React component export.

- **Dynamic templates & preview**
  - Templates live under `templates/<slug>/` and are registered in `templates/registry.ts`.
  - The page `app/(site)/page.tsx` chooses the template by:
    1. `?template=<slug>` (preview)
    2. `appearance.active_public_template` from the API
    3. `NEXT_PUBLIC_ACTIVE_TEMPLATE` (env fallback)

## Tailwind CSS

- **Global CSS**: `app/globals.css` imports Tailwind (`@import "tailwindcss";`).
- **Config**: `tailwind.config.ts` scans `app/**`, `components/**`, `templates/**`.
- **PostCSS**: `postcss.config.mjs` uses `@tailwindcss/postcss`.
- **Branding via CSS vars**: `app/layout.tsx` sets `--brand-primary` and `--brand-secondary` on `<body>` from API `appearance`.
  - Use in templates with utilities plus inline vars, e.g. `style={{ color: 'var(--brand-primary)' }}` or gradients.
  - Prefer Tailwind utilities for spacing, layout, and typography.

