import React from 'react';

export default function DateRange({ start, end }: { start?: string | null; end?: string | null }) {
  const s = start ? new Date(start).toLocaleDateString(undefined, { year: 'numeric', month: 'short' }) : '';
  const e = end ? new Date(end).toLocaleDateString(undefined, { year: 'numeric', month: 'short' }) : 'Present';
  return <span className="text-sm text-gray-500">{[s, e].filter(Boolean).join(' – ')}</span>;
}
