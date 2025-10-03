export type SeoMeta = {
  title?: string;
  description?: string;
  image_url?: string;
};

export type Appearance = {
  active_public_template?: string;
  brand_logo_url?: string | null;
  favicon_url?: string | null;
  brand_primary_color?: string | null;
  brand_secondary_color?: string | null;
  seo_meta?: SeoMeta | null;
};

export type TemplateMeta = {
  slug: string;
  name: string;
  description?: string;
  preview_image_url?: string;
  assets_base_path?: string;
  notes?: string;
};

