import type { ReactNode, CSSProperties } from 'react';
import './globals.css';
import { getAppearance } from '@/lib/api';

export const metadata = {
  title: 'Portfolio',
  description: 'Public portfolio site',
};

export default async function RootLayout({ children }: { children: ReactNode }) {
  const appearance = await getAppearance().catch(() => null);
  let style: (CSSProperties & Record<string, string>) | undefined;
  if (appearance) {
    style = {
      ['--brand-primary']: appearance.brand_primary_color || '#111827',
      ['--brand-secondary']: appearance.brand_secondary_color || '#6b7280',
    } as CSSProperties & Record<string, string>;
  }

  return (
    <html lang="en">
      <body style={style}>{children}</body>
    </html>
  );
}
