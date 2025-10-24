import type { Appearance, TemplateMeta } from '@/types/appearance';
import type { Experience, Education, Project, Certification, SiteContent } from '@/types/content';
import { cache } from 'react';

const API_BASE = String(process.env.NEXT_PUBLIC_API_BASE_URL || '');

export const revalidate = 60; // seconds

const DEFAULT_APPEARANCE: Appearance = {
  active_public_template: 'classic',
  brand_logo_url: undefined,
  favicon_url: undefined,
  brand_primary_color: undefined,
  brand_secondary_color: undefined,
  seo_meta: {
    title: 'My Portfolio',
    description: 'Professional portfolio and experience',
    image_url: undefined,
  },
};

const isDev = process.env.NODE_ENV !== 'production';
let warnedAppearanceOnce = false;

export async function fetchAppearance(signal?: AbortSignal): Promise<Appearance> {
  try {
    const url = `${API_BASE.replace(/\/$/, '')}/api/v1/appearance`;
    
    const res = await fetch(url, { 
      next: { revalidate },
      signal,
      headers: {
        'Accept': 'application/json',
      }
    });
    
    if (!res.ok) {
      if (isDev && !warnedAppearanceOnce) {
        console.warn(`Appearance API returned ${res.status} ${res.statusText} for ${url}`);
        warnedAppearanceOnce = true;
      }
      return DEFAULT_APPEARANCE;
    }
    
    const json = await res.json();
    // API may wrap payload: { success, data } or direct; normalize
    const data = json?.data ?? json ?? {};
    
    return {
      active_public_template: data.active_public_template ?? 'classic',
      brand_logo_url: data.brand_logo_url ?? data.logo_url ?? null,
      favicon_url: data.favicon_url ?? null,
      brand_primary_color: data.brand_primary_color ?? null,
      brand_secondary_color: data.brand_secondary_color ?? null,
      seo_meta: data.seo_meta ?? {
        title: data.seo_title,
        description: data.seo_description,
        image_url: data.seo_image_url,
      }
    };
  } catch (error) {
    if (isDev && !warnedAppearanceOnce) {
      console.error('Error fetching appearance:', error);
      warnedAppearanceOnce = true;
    }
    return DEFAULT_APPEARANCE;
  }
}

// Ensure single fetch per request/render by memoizing the server call
export const getAppearance = cache(() => fetchAppearance());

export async function fetchTemplates(signal?: AbortSignal): Promise<TemplateMeta[]> {
  const url = `${API_BASE.replace(/\/$/, '')}/api/v1/templates`;
  const res = await fetch(url, { next: { revalidate }, signal });
  if (!res.ok) throw new Error(`Templates fetch failed: ${res.status}`);
  const json = await res.json();
  const list: TemplateMeta[] = (json?.data ?? json ?? []) as TemplateMeta[];
  return Array.isArray(list) ? list : [];
}

function normalizeList<T>(json: any): T[] {
  const payload = json?.data ?? json ?? [];
  return Array.isArray(payload) ? (payload as T[]) : [];
}

export async function fetchExperiences(signal?: AbortSignal): Promise<Experience[]> {
  const url = `${API_BASE.replace(/\/$/, '')}/api/v1/experiences`;
  const res = await fetch(url, { next: { revalidate }, signal });
  if (!res.ok) throw new Error(`Experiences fetch failed: ${res.status}`);
  return normalizeList<Experience>(await res.json());
}

export async function fetchEducations(signal?: AbortSignal): Promise<Education[]> {
  const url = `${API_BASE.replace(/\/$/, '')}/api/v1/educations`;
  const res = await fetch(url, { next: { revalidate }, signal });
  if (!res.ok) throw new Error(`Educations fetch failed: ${res.status}`);
  return normalizeList<Education>(await res.json());
}

export async function fetchProjects(signal?: AbortSignal): Promise<Project[]> {
  const url = `${API_BASE.replace(/\/$/, '')}/api/v1/projects`;
  const res = await fetch(url, { next: { revalidate }, signal });
  if (!res.ok) throw new Error(`Projects fetch failed: ${res.status}`);
  return normalizeList<Project>(await res.json());
}

export async function fetchCertifications(signal?: AbortSignal): Promise<Certification[]> {
  const url = `${API_BASE.replace(/\/$/, '')}/api/v1/certifications`;
  const res = await fetch(url, { next: { revalidate }, signal });
  if (!res.ok) throw new Error(`Certifications fetch failed: ${res.status}`);
  return normalizeList<Certification>(await res.json());
}

export async function fetchSiteContent(): Promise<SiteContent> {
  const [experiences, educations, projects, certifications] = await Promise.all([
    fetchExperiences().catch(() => []),
    fetchEducations().catch(() => []),
    fetchProjects().catch(() => []),
    fetchCertifications().catch(() => []),
  ]);
  return { experiences, educations, projects, certifications };
}

