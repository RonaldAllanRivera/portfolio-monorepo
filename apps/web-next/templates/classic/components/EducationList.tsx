import React from 'react';
import DateRange from './DateRange';
import { textOf } from '@/lib/present';
import type { Education } from '@/types/content';

export default function EducationList({ items }: { items: Education[] }) {
  if (!items?.length) return null;
  return (
    <section>
      <h2 className="text-xl font-semibold mb-3">Education</h2>
      <ul className="space-y-3">
        {items.map((ed, i) => (
          <li key={ed.id ?? i}>
            <div className="font-medium">{textOf(ed.degree) || textOf(ed.field) || 'Education'} · {textOf(ed.school)}</div>
            <DateRange start={ed.start_date} end={ed.end_date} />
          </li>
        ))}
      </ul>
    </section>
  );
}
