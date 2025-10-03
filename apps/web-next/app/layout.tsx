import type { ReactNode } from 'react';
import './globals.css';
import { fetchAppearance } from '@/lib/api';

export const metadata = {
  title: 'Portfolio',
  description: 'Public portfolio site',
};

export default async function RootLayout({ children }: { children: ReactNode }) {
  const appearance = await fetchAppearance().catch(() => null);
  const style = appearance
    ? ({
        ['--brand-primary' as any]: appearance.brand_primary_color || '#111827',
        ['--brand-secondary' as any]: appearance.brand_secondary_color || '#6b7280',
      } as React.CSSProperties)
    : undefined;

  return (
    <html lang="en">
      <body style={style}>{children}</body>
    </html>
  );
}
