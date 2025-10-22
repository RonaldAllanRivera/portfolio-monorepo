import React from 'react';
import { asHtml, textOf, urlOf } from '@/lib/present';
import type { Project } from '@/types/content';

export default function ProjectsGrid({ items }: { items: Project[] }) {
  if (!items?.length) return null;
  return (
    <section>
      <h2 className="text-xl font-semibold mb-3">Projects</h2>
      <ul className="grid sm:grid-cols-2 gap-4">
        {items.map((p, i) => (
          <li key={p.id ?? i} className="border rounded-lg p-4">
            <div className="font-semibold">{textOf(p.name)}</div>
            {asHtml(p.description) ? (
              <div className="mt-1 text-gray-700 text-sm space-y-2" dangerouslySetInnerHTML={asHtml(p.description)!} />
            ) : null}
            <div className="mt-2 flex gap-3 text-sm">
              {urlOf(p.url) ? (
                <a className="text-blue-600 hover:underline" href={urlOf(p.url)!} target="_blank" rel="noreferrer">Live</a>
              ) : null}
              {urlOf(p.repo_url) ? (
                <a className="text-blue-600 hover:underline" href={urlOf(p.repo_url)!} target="_blank" rel="noreferrer">Repo</a>
              ) : null}
            </div>
          </li>
        ))}
      </ul>
    </section>
  );
}
