import type { ComponentType } from 'react';
import type { Appearance } from '@/types/appearance';
import type { SiteContent } from '@/types/content';

export type TemplateComponent = ComponentType<{ appearance: Appearance; content: SiteContent; ui?: { q?: string | null; sec?: string | null } }>;

export const registry: Record<string, () => Promise<{ default: TemplateComponent }>> = {
  classic: () => import('./classic'),
  modern: () => import('./modern'),
};

export async function resolveTemplate(slug?: string): Promise<TemplateComponent> {
  const key = (slug && registry[slug]) ? slug : 'classic';
  const mod = await registry[key]();
  return mod.default;
}

