export function textOf(val: unknown): string {
  if (val == null) return '';
  if (typeof val === 'string' || typeof val === 'number') return String(val);
  if (typeof val === 'object') {
    const v = val as Record<string, unknown>;
    return (
      (typeof v.name === 'string' && v.name) ||
      (typeof v.title === 'string' && v.title) ||
      (typeof v.label === 'string' && v.label) ||
      (typeof v.company === 'string' && v.company) ||
      (typeof v.school === 'string' && v.school) ||
      ''
    );
  }
  return '';
}

export function urlOf(val: unknown): string | null {
  if (!val) return null;
  if (typeof val === 'string') return val;
  if (typeof val === 'object') {
    const v = val as Record<string, unknown>;
    const url = v.url || v.image_url || v.logo_url || v.path;
    return typeof url === 'string' ? url : null;
  }
  return null;
}

export function tagsOf(val: unknown): string[] {
  if (!Array.isArray(val)) return [];
  return (val as unknown[])
    .map((t) => {
      if (typeof t === 'string' || typeof t === 'number') return String(t);
      if (t && typeof t === 'object') {
        const v = t as Record<string, unknown>;
        return (
          (typeof v.name === 'string' && v.name) ||
          (typeof v.slug === 'string' && v.slug) ||
          (typeof v.label === 'string' && v.label) ||
          ''
        );
      }
      return '';
    })
    .filter(Boolean);
}

// WARNING: This renders HTML from the CMS/Admin. Only use with trusted content.
export function asHtml(val: unknown): { __html: string } | null {
  const s = typeof val === 'string' ? val : textOf(val);
  if (!s) return null;
  return { __html: s };
}

// Domain-specific helpers for Experience-like shapes coming from the API
export function roleOfExperience(exp: any): string {
  if (!exp || typeof exp !== 'object') return '';
  const v = exp as Record<string, unknown>;
  return (
    textOf(v.role) ||
    textOf((v as any).job_title) ||
    textOf(v.title) ||
    textOf((v as any).position) ||
    ''
  );
}

export function companyNameOf(exp: any): string {
  if (!exp || typeof exp !== 'object') return '';
  const v = exp as Record<string, unknown>;
  return textOf(v.company) || textOf((v as any).company_name) || '';
}

export function companyUrlOf(exp: any): string | null {
  if (!exp || typeof exp !== 'object') return null;
  const v = exp as Record<string, any>;
  if (v.company && typeof v.company === 'object') {
    const site = (v.company as any).website || (v.company as any).url;
    const u = urlOf(site);
    if (u) return u;
  }
  return urlOf(v.company_website) || urlOf(v.website) || null;
}

export function toTime(value?: string | null): number | null {
  if (!value) return null;
  const t = Date.parse(value);
  return Number.isNaN(t) ? null : t;
}

export function compareExperienceDesc(a: any, b: any): number {
  const aEnd = toTime(a?.end_date) ?? Date.now();
  const bEnd = toTime(b?.end_date) ?? Date.now();
  if (aEnd !== bEnd) return bEnd - aEnd;
  const aStart = toTime(a?.start_date) ?? 0;
  const bStart = toTime(b?.start_date) ?? 0;
  if (aStart !== bStart) return bStart - aStart;
  return String(b?.id ?? '').localeCompare(String(a?.id ?? ''));
}
