import { fetchAppearance } from '@/lib/api';
import { resolveTemplate } from '@/templates/registry';
import type { Appearance } from '@/types/appearance';

type SearchParams = { template?: string };

export const revalidate = 60; // seconds

export default async function Page({ searchParams }: { searchParams: SearchParams }) {
  const appearance: Appearance = await fetchAppearance();
  const previewSlug = searchParams?.template;
  const slug = previewSlug || appearance.active_public_template || process.env.NEXT_PUBLIC_ACTIVE_TEMPLATE || 'classic';
  const Template = await resolveTemplate(slug);
  return <Template appearance={appearance} />;
}
