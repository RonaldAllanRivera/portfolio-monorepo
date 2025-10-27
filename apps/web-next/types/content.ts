export type Experience = {
  id?: number | string;
  company?: string;
  role?: string;
  location?: string | null;
  start_date?: string | null; // ISO
  end_date?: string | null;   // ISO or null for current
  current?: boolean;
  description?: string | null;
  highlights?: string[];
  logo_url?: string | null;
};

export type Education = {
  id?: number | string;
  school?: string;
  degree?: string | null;
  field?: string | null;
  start_date?: string | null;
  end_date?: string | null;
  current?: boolean;
  location?: string | null;
  description?: string | null;
  logo_url?: string | null;
};

export type Project = {
  id?: number | string;
  name?: string;
  description?: string | null;
  url?: string | null;
  repo_url?: string | null;
  image_url?: string | null;
  tags?: string[];
};

export type Certification = {
  id?: number | string;
  name?: string;
  issuer?: string | { id?: number | string; name?: string; website?: string | null } | null;
  issue_date?: string | null;
  expiry_date?: string | null;
  credential_id?: string | null;
  credential_url?: string | null;
  image_url?: string | null;
  total_minutes?: number | null;
  duration?: { hours?: number | null; minutes?: number | null; label?: string | null } | null;
  skills?: string[];
  media?: { path?: string; url?: string; full_url?: string }[];
};

export type Setting = {
  id?: number | string;
  headline?: string | null;
  about_me?: string | null;
  profile_picture_url?: string | null;
  contact_email?: string | null;
  contact_phone?: string | null;
  address_city?: string | null;
  address_country?: string | null;
  date_of_birth?: string | null;
  github_url?: string | null;
  linkedin_url?: string | null;
  website_url?: string | null;
};

export type SiteContent = {
  experiences: Experience[];
  educations: Education[];
  projects: Project[];
  certifications: Certification[];
  settings?: Setting | null;
};
