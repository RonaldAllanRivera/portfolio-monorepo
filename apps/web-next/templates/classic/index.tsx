import Image from 'next/image';
import type { Appearance } from '@/types/appearance';

export default function Classic({ appearance }: { appearance: Appearance }) {
  const primary = appearance.brand_primary_color || '#111827';
  const secondary = appearance.brand_secondary_color || '#6b7280';
  const logo = appearance.brand_logo_url || null;

  return (
    <main style={{ padding: 24 }}>
      <header style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
        {logo ? (
          <Image src={logo} alt="Logo" width={48} height={48} />
        ) : (
          <div
            style={{
              width: 48,
              height: 48,
              borderRadius: 8,
              background: primary,
            }}
          />
        )}
        <h1 style={{ color: primary, margin: 0 }}>Portfolio</h1>
      </header>

      <section style={{ marginTop: 16 }}>
        <p style={{ color: secondary, margin: 0 }}>
          Classic template. Configure branding via Admin Appearance.
        </p>
      </section>
    </main>
  );
}
