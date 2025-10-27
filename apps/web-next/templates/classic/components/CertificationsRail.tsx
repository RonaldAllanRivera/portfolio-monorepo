"use client";
import React, { useEffect, useRef, useState } from 'react';
import type { Certification } from '@/types/content';
import CertificationCard from './CertificationCard';

export default function CertificationsRail({ items, onOpen }: { items: Certification[]; onOpen: (c: Certification) => void }) {
  const scrollerRef = useRef<HTMLDivElement>(null);
  const [canLeft, setCanLeft] = useState(false);
  const [canRight, setCanRight] = useState(false);
  const EDGE_EPS = 8; // px tolerance for start/end

  function scrollBy(delta: number) {
    const el = scrollerRef.current;
    if (!el) return;
    el.scrollBy({ left: delta, behavior: 'smooth' });
    // Recompute edges after the scroll kicks in
    requestAnimationFrame(() => {
      const x = el.scrollLeft;
      setCanLeft(x > EDGE_EPS);
      setCanRight(x + el.clientWidth < el.scrollWidth - EDGE_EPS);
    });
  }

  useEffect(() => {
    const el = scrollerRef.current;
    if (!el) return;
    const update = () => {
      const left = el.scrollLeft > EDGE_EPS;
      const right = el.scrollLeft + el.clientWidth < el.scrollWidth - EDGE_EPS;
      setCanLeft(left);
      setCanRight(right);
    };
    update();
    el.addEventListener('scroll', update, { passive: true });
    const onResize = () => update();
    window.addEventListener('resize', onResize);
    return () => {
      el.removeEventListener('scroll', update as any);
      window.removeEventListener('resize', onResize);
    };
  }, [items?.length]);

  return (
    <div className="relative">
      <button
        type="button"
        onClick={() => scrollBy(-((scrollerRef.current?.clientWidth || 800) * 0.9))}
        className={`hidden ${canLeft ? 'md:flex' : 'md:hidden'} absolute left-0 top-1/2 -translate-y-1/2 z-10 w-10 h-10 items-center justify-center rounded-full bg-black/50 hover:bg-black/70 text-white shadow`}
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
        className={`hidden ${canRight ? 'md:flex' : 'md:hidden'} absolute right-0 top-1/2 -translate-y-1/2 z-10 w-10 h-10 items-center justify-center rounded-full bg-black/50 hover:bg-black/70 text-white shadow`}
        aria-label="Scroll right"
      >
        ▶
      </button>
    </div>
  );
}
