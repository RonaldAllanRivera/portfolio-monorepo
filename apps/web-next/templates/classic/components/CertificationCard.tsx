"use client";
import React from 'react';
import { Icon } from '@iconify/react';
import type { Certification } from '@/types/content';
import { iconNameForCertification } from '@/templates/shared/certifications-icons';
import { textOf } from '@/lib/present';

export default function CertificationCard({ c, onOpen }: { c: Certification; onOpen: (c: Certification) => void }) {
  const icon = iconNameForCertification(c);
  const title = textOf(c.name) || 'Certification';
  return (
    <button
      type="button"
      onClick={() => onOpen(c)}
      className="group relative w-44 sm:w-48 lg:w-56 h-64 rounded-2xl bg-gradient-to-b from-gray-800/50 to-gray-900/70 ring-1 ring-white/10 shadow-lg overflow-hidden flex items-center justify-center hover:scale-[1.02] transition-transform focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500"
      aria-label={`Open ${title}`}
    >
      <div className="absolute inset-0 pointer-events-none bg-[radial-gradient(ellipse_at_top,rgba(255,255,255,0.08),transparent_60%)]" />
      <Icon icon={icon} className="text-white/90 drop-shadow w-20 h-20 sm:w-24 sm:h-24" />
      <div className="absolute inset-x-0 bottom-0 p-3 bg-gradient-to-t from-black/70 to-transparent">
        <p className="m-0 text-left text-white/95 text-sm sm:text-base font-medium line-clamp-2">{title}</p>
      </div>
    </button>
  );
}
