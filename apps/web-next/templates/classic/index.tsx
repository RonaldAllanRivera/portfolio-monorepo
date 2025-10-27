import React from 'react';
import type { Appearance } from '@/types/appearance';
import type { SiteContent } from '@/types/content';
import { textOf, urlOf, roleOfExperience, companyNameOf, tagsOf } from '@/lib/present';
import Header from './components/Header';
import SectionNav from './components/SectionNav';
import ExperienceList from './components/ExperienceList';
import EducationList from './components/EducationList';
import ProjectsGrid from './components/ProjectsGrid';
import CertificationsList from './components/CertificationsList';
import CertificationsNetflix from './components/CertificationsNetflix';
import About from './components/About';

export default function Classic({ appearance, content, ui }: { appearance: Appearance; content: SiteContent; ui?: { q?: string | null; sec?: string | null } }) {
  function matches(hay: unknown, needle: string): boolean {
    if (!needle) return true;
    const s = `${textOf(hay)} ${urlOf(hay) ?? ''}`.toLowerCase();
    return s.includes(needle.toLowerCase());
  }
  const q = (ui?.q ?? '').trim();
  const secKeys = (ui?.sec ?? 'ab,xp,ed,pr,cf').split(',').filter(Boolean);
  const activeSec: 'ab' | 'xp' | 'ed' | 'pr' | 'cf' | undefined =
    ui?.sec === 'ab' || ui?.sec === 'xp' || ui?.sec === 'ed' || ui?.sec === 'pr' || ui?.sec === 'cf' ? (ui?.sec as any) : undefined;
  const sections = {
    ab: secKeys.includes('ab'),
    xp: secKeys.includes('xp'),
    ed: secKeys.includes('ed'),
    pr: secKeys.includes('pr'),
    cf: secKeys.includes('cf'),
  } as const;

  const experiences = (content.experiences || []).filter(
    (e) =>
      matches(roleOfExperience(e), q) ||
      matches(companyNameOf(e), q) ||
      matches((e as any).location, q) ||
      matches((e as any).description, q)
  );

  const educations = (content.educations || []).filter(
    (e) => matches(e.school, q) || matches(e.degree, q) || matches(e.field, q)
  );

  const projects = (content.projects || []).filter(
    (p) => matches(p.name, q) || matches(p.description, q) || tagsOf(p.tags).some((t) => matches(t, q))
  );

  const certifications = (content.certifications || []).filter(
    (c) => matches(c.name, q) || matches(c.issuer, q)
  );

  const logo = appearance.brand_logo_url || null;
  const setting = content.settings || null;

  return (
    <main className="mx-auto max-w-4xl p-6 space-y-10">
      <SectionNav activeSec={activeSec} />
      <Header logo={logo} />

      {/* About */}
      {sections.ab ? <About setting={setting || undefined} /> : null}

      {/* Experience */}
      {sections.xp ? <ExperienceList items={experiences} /> : null}

      {/* Education */}
      {sections.ed ? <EducationList items={educations} /> : null}

      {/* Projects */}
      {sections.pr ? <ProjectsGrid items={projects} /> : null}

      {/* Certifications */}
      {sections.cf ? (
        <CertificationsNetflix items={certifications} />
      ) : null}
    </main>
  );
}
