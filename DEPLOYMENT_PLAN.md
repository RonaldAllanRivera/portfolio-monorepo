# Deployment Plan

## Overview
- Admin (Laravel) runs on Hostinger at `https://allanwebdesign.com`.
- Public site (Next.js) runs on Vercel at `https://ronaldallanrivera.com`.

## Registrar & Cost Decision
- Prefer buying/renewing domains on Hostinger.
  - Typically includes a free domain for the first year with hosting plans.
  - Renewal for .com is usually ~$10–15/year.
- Avoid buying domains on Vercel (convenient but generally ~$15–20/year, no free year).

## Domains
- `allanwebdesign.com`
  - Registrar: Hostinger
  - Hosting: Hostinger (Laravel admin/API)
- `ronaldallanrivera.com`
  - Registrar: Hostinger (DNS also managed on Hostinger)
  - Hosting: Vercel (Next.js)

## DNS Records (configure in Hostinger DNS)

### allanwebdesign.com → Hostinger (Laravel)
- A @ → <HOSTINGER_IP>
- CNAME www → @  (or A www → <HOSTINGER_IP>)

### ronaldallanrivera.com → Vercel (Next.js)
- A @ → 76.76.21.21  (Vercel apex IP)
- CNAME www → cname.vercel-dns.com
- TXT _vercel-verification → <TOKEN_FROM_VERCEL>  (added by Vercel after domain is connected)

Notes:
- Keep DNS on Hostinger for centralized management and lower cost.
- Alternatively, you can switch nameservers to Vercel DNS, but this plan assumes Hostinger DNS.

## Vercel Project Setup (apps/web-next)
1. In Vercel, open the project for `apps/web-next`.
2. Go to Settings → Domains → Add `ronaldallanrivera.com`.
3. Choose “Configure DNS records manually”.
4. Add A, CNAME, and TXT records in Hostinger as listed above.
5. Wait for DNS propagation, then click “Verify”.
6. Optionally add `www.ronaldallanrivera.com` as a redirect to apex.

## Laravel (Hostinger)
- APP_URL: `https://allanwebdesign.com`
- Storage: `public` disk with symlink.
- CORS (`config/cors.php`): allow `https://ronaldallanrivera.com`.
- Ensure document root is set to Laravel `public/`.

## Next.js (Vercel)
- Environment: `NEXT_PUBLIC_API_BASE_URL=https://allanwebdesign.com`
- Use caching/revalidation to reduce API calls.

## Checklist Before Go-Live
- [ ] Domains purchased on Hostinger and set to auto-renew.
- [ ] DNS records match the above tables and have propagated.
- [ ] Laravel CORS configured for `ronaldallanrivera.com`.
- [ ] Vercel domain verified and set as primary.
- [ ] Health-check both sites over HTTPS.
