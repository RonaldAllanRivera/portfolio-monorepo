"use client";
import React, { useRef } from 'react';
import type { Certification } from '@/types/content';
import CertificationCard from './CertificationCard';

export default function CertificationsRail({ items, onOpen }: { items: Certification[]; onOpen: (c: Certification) => void }) {
  const scrollerRef = useRef<HTMLDivElement>(null);

  function scrollBy(delta: number) {
    const el = scrollerRef.current;
    if (!el) return;
    el.scrollBy({ left: delta, behavior: 'smooth' });
  }

  return (
    <div className="relative">
      <button
        type="button"
        onClick={() => scrollBy(-((scrollerRef.current?.clientWidth || 800) * 0.9))}
        className="hidden md:flex absolute left-0 top-1/2 -translate-y-1/2 z-10 w-10 h-10 items-center justify-center rounded-full bg-black/50 hover:bg-black/70 text-white shadow"
        aria-label="Scroll left"
      >
        ◀
      </button>
      <div
        ref={scrollerRef}
        className="flex gap-4 overflow-x-auto scroll-smooth snap-x snap-mandatory px-1 pb-2 [mask-image:linear-gradient(to_right,transparent,black_8rem,black_calc(100%-8rem),transparent)]"
      >
        {items.map((c, i) => (
          <div key={c.id ?? i} className="snap-start">
            <CertificationCard c={c} onOpen={onOpen} />
          </div>
        ))}
      </div>
      <button
        type="button"
        onClick={() => scrollBy((scrollerRef.current?.clientWidth || 800) * 0.9)}
        className="hidden md:flex absolute right-0 top-1/2 -translate-y-1/2 z-10 w-10 h-10 items-center justify-center rounded-full bg-black/50 hover:bg-black/70 text-white shadow"
        aria-label="Scroll right"
      >
        ▶
      </button>
    </div>
  );
}
