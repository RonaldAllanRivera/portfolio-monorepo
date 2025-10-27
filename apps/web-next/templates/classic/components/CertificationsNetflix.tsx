"use client";
import React, { useState, useCallback } from 'react';
import type { Certification } from '@/types/content';
import CertificationsRail from './CertificationsRail';
import CertificationModal from './CertificationModal';

export default function CertificationsNetflix({ items }: { items: Certification[] }) {
  const [open, setOpen] = useState(false);
  const [current, setCurrent] = useState<Certification | null>(null);

  const onOpen = useCallback((c: Certification) => {
    setCurrent(c);
    setOpen(true);
  }, []);

  const onClose = useCallback(() => {
    setOpen(false);
    setCurrent(null);
  }, []);

  if (!items?.length) return null;

  return (
    <section className="space-y-4">
      <h2 className="text-xl font-semibold">Certifications</h2>
      <CertificationsRail items={items} onOpen={onOpen} />
      <CertificationModal open={open} onClose={onClose} c={current} />
    </section>
  );
}
