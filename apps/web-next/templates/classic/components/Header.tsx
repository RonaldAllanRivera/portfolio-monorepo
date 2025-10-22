import React from 'react';
import Image from 'next/image';

export default function Header({ logo }: { logo: string | null }) {
  return (
    <header className="flex items-center gap-4">
      {logo ? (
        <div className="w-14 h-14 relative">
          <Image src={logo} alt="Logo" fill className="rounded-lg object-contain" sizes="56px" priority />
        </div>
      ) : (
        <div className="w-14 h-14 rounded-lg" style={{ backgroundColor: 'var(--brand-primary)' }} />
      )}
      <div>
        <h1 className="m-0 text-3xl font-semibold" style={{ color: 'var(--brand-primary)' }}>Portfolio</h1>
        <p className="m-0 text-gray-600">Classic template. Configure branding via Admin Appearance.</p>
      </div>
    </header>
  );
}
