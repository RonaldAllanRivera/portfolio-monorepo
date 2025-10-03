import Image from 'next/image';
import type { Appearance } from '@/types/appearance';

export default function Classic({ appearance }: { appearance: Appearance }) {
  const logo = appearance.brand_logo_url || null;

  return (
    <main className="p-6">
      <header className="flex items-center gap-3">
        {logo ? (
          <Image src={logo} alt="Logo" width={48} height={48} className="rounded-lg" />
        ) : (
          <div className="w-12 h-12 rounded-lg" style={{ backgroundColor: 'var(--brand-primary)' }} />
        )}
        <h1 className="m-0 text-2xl font-semibold" style={{ color: 'var(--brand-primary)' }}>Portfolio</h1>
      </header>

      <section className="mt-4">
        <p className="m-0" style={{ color: 'var(--brand-secondary)' }}>
          Classic template. Configure branding via Admin Appearance.
        </p>
      </section>
    </main>
  );
}
