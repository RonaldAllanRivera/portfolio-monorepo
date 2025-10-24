import React from 'react';
import Image from 'next/image';
import type { Setting } from '@/types/content';

export default function About({ setting }: { setting?: Setting | null }) {
  if (!setting) return null;

  const title = 'About';
  const headline = setting.headline ?? '';
  const about = setting.about_me ?? '';
  const profile = setting.profile_picture_url ?? undefined;

  return (
    <section aria-labelledby="about-title" className="space-y-6">
      <h2 id="about-title" className="text-2xl font-bold tracking-tight">{title}</h2>
      <div className="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
        <div className="md:col-span-1">
          {profile ? (
            <div className="overflow-hidden rounded-xl ring-1 ring-black/5">
              <Image
                src={profile}
                alt="Profile picture"
                width={600}
                height={600}
                className="h-auto w-full object-cover"
                priority
              />
            </div>
          ) : null}
        </div>

        <div className="md:col-span-2 space-y-4">
          {headline ? (
            <h3 className="text-xl font-semibold">{headline}</h3>
          ) : null}
          {about ? (
            <div className="text-muted-foreground leading-relaxed" dangerouslySetInnerHTML={{ __html: about }} />
          ) : null}

          <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
            {setting.contact_email ? (
              <div><span className="font-medium">Email:</span> {setting.contact_email}</div>
            ) : null}
            {setting.contact_phone ? (
              <div><span className="font-medium">Phone:</span> {setting.contact_phone}</div>
            ) : null}
            {setting.address_city || setting.address_country ? (
              <div><span className="font-medium">City:</span> {[setting.address_city, setting.address_country].filter(Boolean).join(', ')}</div>
            ) : null}
            {setting.website_url ? (
              <div><span className="font-medium">Website:</span> <a className="text-primary underline" href={setting.website_url} target="_blank" rel="noreferrer">{setting.website_url}</a></div>
            ) : null}
          </div>
        </div>
      </div>
    </section>
  );
}
