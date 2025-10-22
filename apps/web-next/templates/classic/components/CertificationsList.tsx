import React from 'react';
import Image from 'next/image';
import { textOf, urlOf } from '@/lib/present';
import type { Certification } from '@/types/content';

export default function CertificationsList({ items }: { items: Certification[] }) {
  if (!items?.length) return null;
  return (
    <section>
      <h2 className="text-xl font-semibold mb-3">Certifications</h2>
      <ul className="space-y-3">
        {items.map((c, i) => (
          <li key={c.id ?? i} className="flex items-center gap-3">
            {urlOf(c.image_url) ? (
              <div className="w-9 h-9 relative flex-shrink-0">
                <Image src={urlOf(c.image_url)!} alt="" fill className="rounded object-contain" sizes="36px" />
              </div>
            ) : (
              <div className="w-9 h-9 rounded bg-gray-200" />
            )}
            <div>
              <div className="font-medium">{textOf(c.name)}</div>
              <div className="text-sm text-gray-500">{textOf(c.issuer)}</div>
            </div>
          </li>
        ))}
      </ul>
    </section>
  );
}
