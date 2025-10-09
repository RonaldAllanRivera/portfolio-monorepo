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
  issuer?: string | null;
  issue_date?: string | null;
  expiry_date?: string | null;
  credential_id?: string | null;
  credential_url?: string | null;
  image_url?: string | null;
};

export type SiteContent = {
  experiences: Experience[];
  educations: Education[];
  projects: Project[];
  certifications: Certification[];
};
