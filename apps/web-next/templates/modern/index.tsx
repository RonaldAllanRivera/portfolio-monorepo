import type { Appearance } from '@/types/appearance';

export default function Modern({ appearance }: { appearance: Appearance }) {
  const primary = appearance.brand_primary_color || '#0ea5e9';
  const secondary = appearance.brand_secondary_color || '#64748b';

  return (
    <main style={{ padding: 24 }}>
      <section
        style={{
          padding: 24,
          borderRadius: 16,
          background: `linear-gradient(135deg, ${primary} 0%, ${secondary} 100%)`,
          color: '#fff',
        }}
      >
        <h1 style={{ margin: 0 }}>Modern</h1>
        <p style={{ marginTop: 8 }}>Sleek gradient hero. Configure colors in Admin Appearance.</p>
      </section>
    </main>
  );
}
