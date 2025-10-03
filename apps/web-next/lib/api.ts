import type { Appearance, TemplateMeta } from '@/types/appearance';

const API_BASE = process.env.NEXT_PUBLIC_API_BASE_URL || 'http://admin.allanwebdesign.com.2025.test';

export const revalidate = 60; // seconds

export async function fetchAppearance(signal?: AbortSignal): Promise<Appearance> {
  const url = `${API_BASE.replace(/\/$/, '')}/api/v1/appearance`;
  const res = await fetch(url, { next: { revalidate }, signal });
  if (!res.ok) throw new Error(`Appearance fetch failed: ${res.status}`);
  const json = await res.json();
  // API may wrap payload: { success, data } or be direct; normalize
  const data = json?.data ?? json ?? {};
  const appearance: Appearance = {
    active_public_template: data.active_public_template,
    brand_logo_url: data.brand_logo_url ?? data.logo_url ?? null,
    favicon_url: data.favicon_url ?? null,
    brand_primary_color: data.brand_primary_color ?? null,
    brand_secondary_color: data.brand_secondary_color ?? null,
    seo_meta: data.seo_meta ?? {
      title: data.seo_title,
      description: data.seo_description,
      image_url: data.seo_image_url,
    },
  };
  return appearance;
}

export async function fetchTemplates(signal?: AbortSignal): Promise<TemplateMeta[]> {
  const url = `${API_BASE.replace(/\/$/, '')}/api/v1/templates`;
  const res = await fetch(url, { next: { revalidate }, signal });
  if (!res.ok) throw new Error(`Templates fetch failed: ${res.status}`);
  const json = await res.json();
  const list: TemplateMeta[] = (json?.data ?? json ?? []) as TemplateMeta[];
  return Array.isArray(list) ? list : [];
}

