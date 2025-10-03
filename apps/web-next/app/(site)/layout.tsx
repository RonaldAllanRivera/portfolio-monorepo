import type { ReactNode } from 'react';
import type { Metadata } from 'next';
import { fetchAppearance } from '@/lib/api';

export async function generateMetadata(): Promise<Metadata> {
  try {
    const appearance = await fetchAppearance();
    const title = appearance.seo_meta?.title ?? 'Portfolio';
    const description = appearance.seo_meta?.description ?? 'Public portfolio site';
    const ogImages = appearance.seo_meta?.image_url
      ? [{ url: appearance.seo_meta.image_url }]
      : undefined;

    return {
      title,
      description,
      openGraph: {
        title,
        description,
        type: 'website',
        images: ogImages,
      },
      twitter: {
        card: 'summary_large_image',
        title,
        description,
        images: ogImages?.[0]?.url,
      },
      icons: appearance.favicon_url
        ? {
            icon: [{ url: appearance.favicon_url }],
          }
        : undefined,
    };
  } catch {
    return {
      title: 'Portfolio',
      description: 'Public portfolio site',
    };
  }
}

export default function SiteLayout({ children }: { children: ReactNode }) {
  return <>{children}</>;
}
