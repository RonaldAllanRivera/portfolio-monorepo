import { getAppearance } from '@/lib/api';
import { fetchSiteContent } from '@/lib/api';
import { resolveTemplate } from '@/templates/registry';
import type { Appearance } from '@/types/appearance';
import { compareExperienceDesc } from '@/lib/present';

export const revalidate = 60; // seconds

type Params = { section?: string };

type SearchParams = { template?: string };

function toSecKey(section?: string): 'xp' | 'ed' | 'pr' | 'cf' | undefined {
  switch ((section || '').toLowerCase()) {
    case 'experience':
      return 'xp';
    case 'education':
      return 'ed';
    case 'projects':
      return 'pr';
    case 'certifications':
      return 'cf';
    default:
      return undefined;
  }
}

export default async function Page({ params, searchParams }: { params: Promise<Params>; searchParams: Promise<SearchParams> }) {
  const [p, sp, appearance, contentRaw]: [Params, SearchParams, Appearance, Awaited<ReturnType<typeof fetchSiteContent>>] = await Promise.all([
    params,
    searchParams,
    getAppearance(),
    fetchSiteContent(),
  ]);
  const content = {
    ...contentRaw,
    experiences: (contentRaw.experiences || []).slice().sort(compareExperienceDesc),
  };
  const sec = toSecKey(p.section);
  const slug = sp?.template || appearance.active_public_template || process.env.NEXT_PUBLIC_ACTIVE_TEMPLATE || 'classic';
  const Template = await resolveTemplate(slug);
  return <Template appearance={appearance} content={content} ui={{ sec }} />;
}
