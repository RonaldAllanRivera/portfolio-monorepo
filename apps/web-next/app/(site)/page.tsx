import { fetchAppearance } from '@/lib/api';
import { fetchSiteContent } from '@/lib/api';
import { resolveTemplate } from '@/templates/registry';
import type { Appearance } from '@/types/appearance';
import { compareExperienceDesc } from '@/lib/present';

type SearchParams = { template?: string };

export const revalidate = 60; // seconds

export default async function Page({ searchParams }: { searchParams: Promise<SearchParams> }) {
  const [sp, appearance, contentRaw]: [SearchParams, Appearance, Awaited<ReturnType<typeof fetchSiteContent>>] = await Promise.all([
    searchParams,
    fetchAppearance(),
    fetchSiteContent(),
  ]);
  const content = {
    ...contentRaw,
    experiences: (contentRaw.experiences || []).slice().sort(compareExperienceDesc),
  };
  const previewSlug = sp?.template;
  const slug = previewSlug || appearance.active_public_template || process.env.NEXT_PUBLIC_ACTIVE_TEMPLATE || 'classic';
  const Template = await resolveTemplate(slug);
  return <Template appearance={appearance} content={content} />;
}
