import type { Certification } from '@/types/content';

export function iconNameForCertification(c: Certification): string {
  const name = `${(c?.name ?? '').toString().toLowerCase()} ${(typeof c?.issuer === 'string' ? c.issuer : (c?.issuer as any)?.name ?? '').toString().toLowerCase()}`;
  if (/linkedin|learning/.test(name)) return 'mdi:linkedin';
  if (/aws|amazon web services/.test(name)) return 'simple-icons:amazonaws';
  if (/microsoft|azure/.test(name)) return 'simple-icons:microsoft';
  if (/google cloud|google\b/.test(name)) return 'simple-icons:googlecloud';
  if (/udemy/.test(name)) return 'simple-icons:udemy';
  if (/nvidia/.test(name)) return 'simple-icons:nvidia';
  if (/github/.test(name)) return 'mdi:github';
  if (/kubernetes/.test(name)) return 'simple-icons:kubernetes';
  if (/docker/.test(name)) return 'simple-icons:docker';
  if (/react|next\.js|nextjs/.test(name)) return 'simple-icons:react';
  if (/wordpress/.test(name)) return 'simple-icons:wordpress';
  if (/ai|machine learning|ml/.test(name)) return 'mdi:robot-happy-outline';
  return 'mdi:certificate-outline';
}
