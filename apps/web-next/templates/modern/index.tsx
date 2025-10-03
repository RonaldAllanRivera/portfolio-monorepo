import type { Appearance } from '@/types/appearance';

export default function Modern({ appearance }: { appearance: Appearance }) {
  const primary = appearance.brand_primary_color || 'var(--brand-primary)';
  const secondary = appearance.brand_secondary_color || 'var(--brand-secondary)';

  return (
    <main className="p-6">
      <section
        className="p-6 rounded-2xl text-white"
        style={{
          background: `linear-gradient(135deg, ${primary} 0%, ${secondary} 100%)`,
        }}
      >
        <h1 className="m-0 text-3xl font-semibold">Modern</h1>
        <p className="mt-2 opacity-90">Sleek gradient hero. Configure colors in Admin Appearance.</p>
      </section>
    </main>
  );
}
