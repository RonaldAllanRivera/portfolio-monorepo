"use client";
import React, { useEffect, useRef } from 'react';
import Image from 'next/image';
import type { Certification } from '@/types/content';
import { Icon } from '@iconify/react';
import { iconNameForCertification } from '@/lib/certifications-icons';
import { textOf, urlOf } from '@/lib/present';

export default function CertificationModal({
  open,
  onClose,
  c,
}: {
  open: boolean;
  onClose: () => void;
  c: Certification | null;
}) {
  const closeBtnRef = useRef<HTMLButtonElement | null>(null);

  useEffect(() => {
    function onKey(e: KeyboardEvent) {
      if (e.key === 'Escape') onClose();
    }
    if (open) window.addEventListener('keydown', onKey);
    if (open) setTimeout(() => closeBtnRef.current?.focus(), 0);
    return () => window.removeEventListener('keydown', onKey);
  }, [open, onClose]);

  if (!open || !c) return null;

  const icon = iconNameForCertification(c);
  const title = textOf(c.name) || 'Certification';
  const issuerName = typeof c.issuer === 'string' ? c.issuer : (c.issuer as any)?.name ?? '';
  const issuerSite = typeof c.issuer === 'object' ? (c.issuer as any)?.website ?? null : null;
  const issued = (c as any)?.issue_date_formatted || (c.issue_date ? new Date(c.issue_date).toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: '2-digit' }) : null);
  const expires = (c as any)?.expiration_date_formatted || null;
  const isValid = (c as any)?.is_valid ?? null;
  const duration = (c as any)?.duration?.label || (c.total_minutes ? `${Math.floor((c.total_minutes||0)/60)}h ${(c.total_minutes||0)%60}m` : null);
  const totalMinutes = c.total_minutes ?? null;
  const credentialUrl = c.credential_url || null;
  const credentialId = c.credential_id || null;
  const skills = Array.isArray((c as any)?.skills_full) && (c as any)?.skills_full.length
    ? (c as any)?.skills_full as { id?: number|string; name?: string; category?: string|null }[]
    : (Array.isArray(c.skills) ? (c.skills as string[]).map((n) => ({ name: n })) : []);
  const media = Array.isArray(c.media) ? c.media : [];

  function isImage(u: string | null): boolean {
    if (!u) return false;
    return /\.(png|jpe?g|webp|gif|svg)$/i.test(u.split('?')[0]);
  }

  return (
    <div className="fixed inset-0 z-50" role="dialog" aria-modal="true" aria-labelledby="cert-modal-title">
      <div className="absolute inset-0 bg-black/70" onClick={onClose} />
      <div className="absolute inset-0 p-4 sm:p-6 flex items-center justify-center overflow-auto">
        <div className="w-full max-w-4xl rounded-2xl bg-neutral-950 text-white shadow-xl ring-1 ring-white/10 overflow-hidden">
          <div className="relative p-5 sm:p-6">
            {/* Header */}
            <div className="flex items-start gap-4">
              <div className="flex-shrink-0">
                <div className="w-14 h-14 rounded-xl bg-white/10 flex items-center justify-center">
                  <Icon icon={icon} className="w-8 h-8" />
                </div>
              </div>
              <div className="min-w-0 flex-1">
                <h3 id="cert-modal-title" className="m-0 text-xl font-semibold leading-tight">{title}</h3>
                <div className="mt-1 text-sm text-white/80 flex items-center gap-2 flex-wrap">
                  <span>{issuerName || '—'}</span>
                  {issuerSite ? (
                    <a href={issuerSite} target="_blank" rel="noreferrer" className="text-blue-300 hover:text-blue-200 inline-flex items-center gap-1">
                      <Icon icon="mdi:open-in-new" className="w-3.5 h-3.5" /> Website
                    </a>
                  ) : null}
                  {typeof isValid === 'boolean' ? (
                    <span className={`inline-flex items-center gap-1 rounded-md px-1.5 py-0.5 text-xs ${isValid ? 'bg-emerald-500/15 text-emerald-300' : 'bg-red-500/15 text-red-300'}`}>
                      <Icon icon={isValid ? 'mdi:check-circle' : 'mdi:alert-circle'} className="w-3.5 h-3.5" />
                      {isValid ? 'Valid' : 'Expired'}
                    </span>
                  ) : null}
                </div>
              </div>
              <button ref={closeBtnRef} onClick={onClose} className="absolute top-3 right-3 w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center" aria-label="Close">
                ✕
              </button>
            </div>

            {/* Grid content */}
            <div className="mt-5 grid grid-cols-1 lg:grid-cols-5 gap-6">
              {/* Left: details */}
              <div className="lg:col-span-3 space-y-4">
                <div className="grid grid-cols-2 sm:grid-cols-3 gap-3 text-sm">
                  <div className="rounded-lg bg-white/5 p-3">
                    <div className="text-white/60">Issued</div>
                    <div className="font-medium">{issued || '—'}</div>
                  </div>
                  <div className="rounded-lg bg-white/5 p-3">
                    <div className="text-white/60">Expires</div>
                    <div className="font-medium">{expires || '—'}</div>
                  </div>
                  <div className="rounded-lg bg-white/5 p-3">
                    <div className="text-white/60">Duration</div>
                    <div className="font-medium">{duration || '—'}{totalMinutes ? <span className="text-white/60"> ({totalMinutes} min)</span> : null}</div>
                  </div>
                  <div className="rounded-lg bg-white/5 p-3 col-span-2 sm:col-span-3">
                    <div className="text-white/60">Credential</div>
                    <div className="mt-1 flex items-center gap-3 flex-wrap">
                      {credentialId ? (
                        <span className="inline-flex items-center gap-1 rounded-md bg-white/10 px-2 py-1 text-xs">ID: {credentialId}</span>
                      ) : null}
                      {credentialUrl ? (
                        <a href={credentialUrl} target="_blank" rel="noreferrer" className="inline-flex items-center gap-1 rounded-md bg-blue-500/20 px-2 py-1 text-xs text-blue-300 hover:bg-blue-500/30">
                          <Icon icon="mdi:link-variant" className="w-3.5 h-3.5" /> View credential
                        </a>
                      ) : null}
                    </div>
                  </div>
                </div>

                {skills.length ? (
                  <div>
                    <div className="mb-2 text-sm text-white/70">Skills</div>
                    <ul className="flex flex-wrap gap-2">
                      {skills.map((s, i) => (
                        <li key={String((s as any)?.id ?? i)} className="px-2 py-1 rounded bg-white/10 text-xs">
                          {textOf((s as any)?.name || s)}
                        </li>
                      ))}
                    </ul>
                  </div>
                ) : null}
              </div>

              {/* Right: media */}
              <div className="lg:col-span-2">
                {media.length ? (
                  <div>
                    <div className="mb-2 text-sm text-white/70">Media</div>
                    <div className="grid grid-cols-2 gap-3">
                      {media.slice(0, 4).map((m, i) => {
                        const u = urlOf(m);
                        const img = isImage(u);
                        const isPdf = typeof u === 'string' && /\.pdf(\?.*)?$/i.test(u);
                        return (
                          <a key={i} href={u ?? '#'} target="_blank" rel="noreferrer" className="group block aspect-video rounded-lg overflow-hidden ring-1 ring-white/10 bg-white/5">
                            {img && u ? (
                              <Image src={u} alt="" fill sizes="(max-width:768px) 50vw, 33vw" className="object-cover" />
                            ) : (
                              <div className="w-full h-full flex flex-col items-center justify-center gap-1 text-white/70 text-xs">
                                <Icon icon={isPdf ? 'mdi:file-pdf-box' : 'mdi:open-in-new'} className="w-6 h-6" />
                                <span>{isPdf ? 'Open PDF' : 'Open'}</span>
                              </div>
                            )}
                          </a>
                        );
                      })}
                    </div>
                  </div>
                ) : (
                  <div className="rounded-lg bg-white/5 p-4 text-sm text-white/60">No media</div>
                )}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
