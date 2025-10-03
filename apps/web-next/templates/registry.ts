import type { ComponentType } from 'react';
import type { Appearance } from '@/types/appearance';

export type TemplateComponent = ComponentType<{ appearance: Appearance }>;

export const registry: Record<string, () => Promise<{ default: TemplateComponent }>> = {
  classic: () => import('./classic'),
  modern: () => import('./modern'),
};

export async function resolveTemplate(slug?: string): Promise<TemplateComponent> {
  const key = (slug && registry[slug]) ? slug : 'classic';
  const mod = await registry[key]();
  return mod.default;
}

