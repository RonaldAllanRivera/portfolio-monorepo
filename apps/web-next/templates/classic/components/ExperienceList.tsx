import React from 'react';
import Image from 'next/image';
import DateRange from './DateRange';
import { asHtml, companyNameOf, companyUrlOf, roleOfExperience, textOf, urlOf } from '@/lib/present';
import type { Experience } from '@/types/content';

export default function ExperienceList({ items }: { items: Experience[] }) {
  if (!items?.length) return null;
  return (
    <section>
      <h2 className="text-xl font-semibold mb-3">Experience</h2>
      <ul className="space-y-4">
        {items.map((exp, i) => (
          <li key={exp.id ?? i} className="flex gap-3">
            {urlOf(exp.logo_url) ? (
              <div className="w-9 h-9 relative flex-shrink-0">
                <Image src={urlOf(exp.logo_url)!} alt="" fill className="rounded object-contain" sizes="36px" />
              </div>
            ) : (
              <div className="w-9 h-9 rounded bg-gray-200" />
            )}
            <div>
              <div className="font-medium">
                {roleOfExperience(exp) || 'Role'}
                {companyNameOf(exp) ? (
                  <>
                    {' '}·{' '}
                    {companyUrlOf(exp) ? (
                      <a href={companyUrlOf(exp)!} target="_blank" rel="noreferrer" className="text-blue-600 hover:underline">
                        {companyNameOf(exp)}
                      </a>
                    ) : (
                      <span>{companyNameOf(exp)}</span>
                    )}
                  </>
                ) : null}
              </div>
              <div className="flex items-center gap-2 text-sm text-gray-500">
                <DateRange start={exp.start_date} end={exp.end_date} />
                {textOf((exp as any).location) ? <span>• {textOf((exp as any).location)}</span> : null}
              </div>
              {asHtml(exp.description) ? (
                <div className="m-0 mt-1 text-gray-700 text-sm space-y-2" dangerouslySetInnerHTML={asHtml(exp.description)!} />
              ) : null}
              {Array.isArray(exp.highlights) && exp.highlights.length ? (
                <ul className="mt-2 list-disc list-inside text-sm text-gray-700 space-y-1">
                  {exp.highlights.map((h, idx) => (
                    <li key={idx}>{textOf(h)}</li>
                  ))}
                </ul>
              ) : null}
            </div>
          </li>
        ))}
      </ul>
    </section>
  );
}
