import React from 'react';
import Link from 'next/link';

export type SecKey = 'ab' | 'xp' | 'ed' | 'pr' | 'cf' | undefined;

const links: Array<{ key: SecKey; href: string; label: string }> = [
  { key: undefined, href: '/', label: 'All' },
  { key: 'ab', href: '/about', label: 'About' },
  { key: 'xp', href: '/experience', label: 'Experience' },
  { key: 'ed', href: '/education', label: 'Education' },
  { key: 'pr', href: '/projects', label: 'Projects' },
  { key: 'cf', href: '/certifications', label: 'Certifications' },
];

export default function SectionNav({ activeSec }: { activeSec?: SecKey }) {
  return (
    <nav className="flex flex-wrap items-center gap-2 text-sm" aria-label="Sections">
      {links.map((l) => {
        const active = l.key === activeSec || (!activeSec && l.key === undefined);
        return (
          <Link
            key={l.href}
            href={l.href}
            aria-current={active ? 'page' : undefined}
            className={`rounded-full border px-3 py-1 ${active ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-300'}`}
          >
            {l.label}
          </Link>
        );
      })}
    </nav>
  );
}
