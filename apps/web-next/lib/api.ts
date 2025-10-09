import type { Appearance, TemplateMeta } from '@/types/appearance';
import type { Experience, Education, Project, Certification, SiteContent } from '@/types/content';

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

