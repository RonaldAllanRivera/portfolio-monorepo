import Image from 'next/image';
import type { Appearance } from '@/types/appearance';
import type { SiteContent } from '@/types/content';
import { textOf, urlOf, tagsOf, asHtml, roleOfExperience, companyNameOf, companyUrlOf } from '@/lib/present';

function DateRange({ start, end }: { start?: string | null; end?: string | null }) {
  const s = start ? new Date(start).toLocaleDateString(undefined, { year: 'numeric', month: 'short' }) : '';
  const e = end ? new Date(end).toLocaleDateString(undefined, { year: 'numeric', month: 'short' }) : 'Present';
  return <span className="text-sm text-gray-500">{[s, e].filter(Boolean).join(' – ')}</span>;
}

export default function Classic({ appearance, content }: { appearance: Appearance; content: SiteContent }) {
  const logo = appearance.brand_logo_url || null;

  return (
    <main className="mx-auto max-w-4xl p-6 space-y-10">
      <header className="flex items-center gap-4">
        {logo ? (
          <div className="w-14 h-14 relative">
            <Image 
              src={logo} 
              alt="Logo" 
              fill 
              className="rounded-lg object-contain" 
              sizes="56px"
              priority
            />
          </div>
        ) : (
          <div className="w-14 h-14 rounded-lg" style={{ backgroundColor: 'var(--brand-primary)' }} />
        )}
        <div>
          <h1 className="m-0 text-3xl font-semibold" style={{ color: 'var(--brand-primary)' }}>Portfolio</h1>
          <p className="m-0 text-gray-600">Classic template. Configure branding via Admin Appearance.</p>
        </div>
      </header>

      {/* Experience */}
      {content.experiences?.length ? (
        <section>
          <h2 className="text-xl font-semibold mb-3">Experience</h2>
          <ul className="space-y-4">
            {content.experiences.map((exp, i) => (
              <li key={exp.id ?? i} className="flex gap-3">
                {urlOf(exp.logo_url) ? (
                  <div className="w-9 h-9 relative flex-shrink-0">
                    <Image 
                      src={urlOf(exp.logo_url)!} 
                      alt="" 
                      fill 
                      className="rounded object-contain" 
                      sizes="36px"
                    />
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
                    <div
                      className="m-0 mt-1 text-gray-700 text-sm space-y-2"
                      dangerouslySetInnerHTML={asHtml(exp.description)!}
                    />
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
      ) : null}

      {/* Education */}
      {content.educations?.length ? (
        <section>
          <h2 className="text-xl font-semibold mb-3">Education</h2>
          <ul className="space-y-3">
            {content.educations.map((ed, i) => (
              <li key={ed.id ?? i}>
                <div className="font-medium">{textOf(ed.degree) || textOf(ed.field) || 'Education'} · {textOf(ed.school)}</div>
                <DateRange start={ed.start_date} end={ed.end_date} />
              </li>
            ))}
          </ul>
        </section>
      ) : null}

      {/* Projects */}
      {content.projects?.length ? (
        <section>
          <h2 className="text-xl font-semibold mb-3">Projects</h2>
          <ul className="grid sm:grid-cols-2 gap-4">
            {content.projects.map((p, i) => (
              <li key={p.id ?? i} className="border rounded-lg p-4">
                <div className="font-semibold">{textOf(p.name)}</div>
                {asHtml(p.description) ? (
                  <div
                    className="mt-1 text-gray-700 text-sm space-y-2"
                    dangerouslySetInnerHTML={asHtml(p.description)!}
                  />
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
      ) : null}

      {/* Certifications */}
      {content.certifications?.length ? (
        <section>
          <h2 className="text-xl font-semibold mb-3">Certifications</h2>
          <ul className="space-y-3">
            {content.certifications.map((c, i) => (
              <li key={c.id ?? i} className="flex items-center gap-3">
                {urlOf(c.image_url) ? (
                  <div className="w-9 h-9 relative flex-shrink-0">
                    <Image 
                      src={urlOf(c.image_url)!} 
                      alt="" 
                      fill 
                      className="rounded object-contain" 
                      sizes="36px"
                    />
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
      ) : null}
    </main>
  );
}
