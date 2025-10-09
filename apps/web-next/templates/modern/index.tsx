import Image from 'next/image';
import type { Appearance } from '@/types/appearance';
import type { SiteContent } from '@/types/content';
import { textOf, urlOf, tagsOf, asHtml, roleOfExperience, companyNameOf, companyUrlOf } from '@/lib/present';

function DateRange({ start, end }: { start?: string | null; end?: string | null }) {
  const s = start ? new Date(start).toLocaleDateString(undefined, { year: 'numeric', month: 'short' }) : '';
  const e = end ? new Date(end).toLocaleDateString(undefined, { year: 'numeric', month: 'short' }) : 'Present';
  return <span className="text-sm/5 text-white/80">{[s, e].filter(Boolean).join(' – ')}</span>;
}

export default function Modern({ appearance, content }: { appearance: Appearance; content: SiteContent }) {
  const primary = appearance.brand_primary_color || 'var(--brand-primary)';
  const secondary = appearance.brand_secondary_color || 'var(--brand-secondary)';
  const logo = appearance.brand_logo_url || null;

  return (
    <main className="mx-auto max-w-6xl p-6 space-y-12">
      {/* Hero */}
      <section
        className="relative overflow-hidden rounded-3xl text-white"
        style={{ background: `linear-gradient(135deg, ${primary} 0%, ${secondary} 100%)` }}
      >
        <div className="p-8 sm:p-12">
          <div className="flex items-center gap-4">
            {logo ? (
              <div className="w-16 h-16 relative flex-shrink-0">
                <Image 
                  src={logo} 
                  alt="Logo" 
                  fill 
                  className="rounded-xl ring-2 ring-white/20 object-cover"
                  sizes="64px"
                  priority
                />
              </div>
            ) : (
              <div className="w-16 h-16 rounded-xl bg-white/20" />
            )}
            <div>
              <h1 className="m-0 text-4xl font-semibold tracking-tight">Portfolio</h1>
              <p className="m-0 mt-1 text-white/80">Modern template with gradient hero and cards.</p>
            </div>
          </div>
        </div>
        <div className="absolute inset-0 -z-10 opacity-20 [background-image:radial-gradient(ellipse_at_top,white,transparent_40%)]" />
      </section>

      {/* Experience */}
      {content.experiences?.length ? (
        <section className="space-y-5">
          <div className="flex items-center justify-between">
          </div>
          <ul className="grid md:grid-cols-2 gap-5">
            {content.experiences.map((exp, i) => (
              <li key={exp.id ?? i} className="rounded-2xl border border-gray-100 shadow-sm p-5 bg-white">
                <div className="flex items-start gap-4">
                  {urlOf(exp.logo_url) ? (
                    <div className="w-11 h-11 relative flex-shrink-0">
                      <Image 
                        src={urlOf(exp.logo_url)!} 
                        alt="" 
                        fill 
                        className="rounded object-cover"
                        sizes="44px"
                      />
                    </div>
                  ) : (
                    <div className="w-11 h-11 rounded bg-gray-200" />
                  )}
                  <div className="min-w-0">
                    <div className="font-semibold truncate">
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
                        className="m-0 mt-2 text-gray-700 text-sm space-y-2"
                        dangerouslySetInnerHTML={asHtml(exp.description)!}
                      />
                    ) : null}
                    {Array.isArray(exp.highlights) && exp.highlights.length ? (
                      <ul className="mt-3 flex flex-wrap gap-2">
                        {exp.highlights.map((h, idx) => (
                          <li key={idx} className="px-2 py-1 rounded-md bg-gray-100 text-gray-700 text-xs">{textOf(h)}</li>
                        ))}
                      </ul>
                    ) : null}
                  </div>
                </div>
              </li>
            ))}
          </ul>
        </section>
      ) : null}

      {/* Education & Certifications side-by-side */}
      {(content.educations?.length || content.certifications?.length) ? (
        <section className="grid md:grid-cols-2 gap-5">
          {/* Education */}
          {content.educations?.length ? (
            <div className="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
              <h2 className="text-lg font-semibold mb-3" style={{ color: 'var(--brand-primary)' }}>Education</h2>
              <ul className="space-y-3">
                {content.educations.map((ed, i) => (
                  <li key={ed.id ?? i}>
                    <div className="font-medium">{textOf(ed.degree) || textOf(ed.field) || 'Education'} · {textOf(ed.school)}</div>
                    <div className="text-gray-500 text-sm"><DateRange start={ed.start_date} end={ed.end_date} /></div>
                  </li>
                ))}
              </ul>
            </div>
          ) : <div />}

          {/* Certifications */}
          {content.certifications?.length ? (
            <div className="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
              <h2 className="text-lg font-semibold mb-3" style={{ color: 'var(--brand-primary)' }}>Certifications</h2>
              <ul className="space-y-3">
                {content.certifications.map((c, i) => (
                  <li key={c.id ?? i} className="flex items-center gap-3">
                    {urlOf(c.image_url) ? (
                      <div className="w-9 h-9 relative flex-shrink-0">
                        <Image 
                          src={urlOf(c.image_url)!} 
                          alt="" 
                          fill 
                          className="rounded object-cover"
                          sizes="36px"
                        />
                      </div>
                    ) : (
                      <div className="w-9 h-9 rounded bg-gray-200" />
                    )}
                    <div className="min-w-0">
                      <div className="font-medium truncate">{textOf(c.name)}</div>
                      <div className="text-gray-500 text-sm truncate">{textOf(c.issuer)}</div>
                    </div>
                  </li>
                ))}
              </ul>
            </div>
          ) : <div />}
        </section>
      ) : null}

      {/* Projects */}
      {content.projects?.length ? (
        <section className="space-y-5">
          <div className="flex items-center justify-between">
            <h2 className="text-2xl font-semibold" style={{ color: 'var(--brand-primary)' }}>Projects</h2>
          </div>
          <ul className="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
            {content.projects.map((p, i) => (
              <li key={p.id ?? i} className="group relative rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden">
                <div className="p-5">
                  <div className="font-semibold truncate">{textOf(p.name)}</div>
                  {asHtml(p.description) ? (
                    <div
                      className="mt-2 text-gray-700 text-sm space-y-2 line-clamp-6"
                      dangerouslySetInnerHTML={asHtml(p.description)!}
                    />
                  ) : null}
                  {p.tags?.length ? (
                    <ul className="mt-3 flex flex-wrap gap-2">
                      {tagsOf(p.tags).map((t, idx) => (
                        <li key={idx} className="px-2 py-1 rounded-md bg-gray-100 text-gray-700 text-xs">{t}</li>
                      ))}
                    </ul>
                  ) : null}
                  <div className="mt-4 flex gap-3 text-sm">
                    {urlOf(p.url) ? (
                      <a className="text-blue-600 hover:underline" href={urlOf(p.url)!} target="_blank" rel="noreferrer">Live</a>
                    ) : null}
                    {urlOf(p.repo_url) ? (
                      <a className="text-blue-600 hover:underline" href={urlOf(p.repo_url)!} target="_blank" rel="noreferrer">Repo</a>
                    ) : null}
                  </div>
                </div>
                <div className="absolute inset-x-0 bottom-0 h-1" style={{ background: `linear-gradient(90deg, ${primary}, ${secondary})` }} />
              </li>
            ))}
          </ul>
        </section>
      ) : null}
    </main>
  );
}
