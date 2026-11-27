CREATE TABLE public.categories (
  id bigint NOT NULL DEFAULT nextval('categories_id_seq'::regclass),
  public_id uuid NOT NULL DEFAULT gen_random_uuid() UNIQUE,
  name character varying NOT NULL,
  slug character varying NOT NULL UNIQUE,
  created_at timestamp with time zone DEFAULT CURRENT_TIMESTAMP,
  updated_at timestamp with time zone DEFAULT CURRENT_TIMESTAMP,
  description text NOT NULL DEFAULT ''::text,
  icon character varying NOT NULL DEFAULT 'bi-tags'::character varying,
  CONSTRAINT categories_pkey PRIMARY KEY (id)
);
CREATE TABLE public.users (
  id bigint NOT NULL DEFAULT nextval('users_id_seq'::regclass),
  public_id uuid NOT NULL DEFAULT gen_random_uuid() UNIQUE,
  email character varying NOT NULL UNIQUE,
  password character varying NOT NULL,
  remember_token character varying DEFAULT NULL::character varying,
  created_at timestamp with time zone DEFAULT CURRENT_TIMESTAMP,
  updated_at timestamp with time zone DEFAULT CURRENT_TIMESTAMP,
  role USER-DEFINED DEFAULT 'buyer'::user_role,
  is_active boolean NOT NULL DEFAULT true,
  CONSTRAINT users_pkey PRIMARY KEY (id)
);
CREATE TABLE public.assets (
  id bigint NOT NULL DEFAULT nextval('assets_id_seq'::regclass),
  public_id uuid NOT NULL DEFAULT gen_random_uuid() UNIQUE,
  seller_id bigint NOT NULL,
  category_id bigint NOT NULL,
  title character varying NOT NULL,
  slug character varying NOT NULL UNIQUE,
  description character varying NOT NULL,
  how_to_use character varying DEFAULT NULL::character varying,
  price integer NOT NULL,
  render_pipeline character varying NOT NULL,
  unity_version character varying NOT NULL,
  status character varying NOT NULL,
  preview_video_url character varying DEFAULT NULL::character varying,
  created_at timestamp with time zone DEFAULT CURRENT_TIMESTAMP,
  updated_at timestamp with time zone DEFAULT CURRENT_TIMESTAMP,
  deleted_at timestamp with time zone,
  download_count integer NOT NULL DEFAULT 0,
  lisensi character varying DEFAULT 'MIT'::character varying,
  reject_reason text,
  reviewed_by bigint,
  reviewed_at timestamp with time zone,
  CONSTRAINT assets_pkey PRIMARY KEY (id),
  CONSTRAINT fk_assets_seller FOREIGN KEY (seller_id) REFERENCES public.users(id),
  CONSTRAINT fk_assets_category FOREIGN KEY (category_id) REFERENCES public.categories(id),
  CONSTRAINT assets_reviewed_by_fkey FOREIGN KEY (reviewed_by) REFERENCES public.users(id)
);
CREATE TABLE public.asset_files (
  id bigint NOT NULL DEFAULT nextval('asset_files_id_seq'::regclass),
  public_id uuid NOT NULL DEFAULT gen_random_uuid() UNIQUE,
  asset_id bigint NOT NULL,
  file_path character varying NOT NULL,
  original_name character varying NOT NULL,
  mime_type character varying NOT NULL,
  extension character varying NOT NULL,
  file_size integer NOT NULL,
  version character varying NOT NULL,
  created_at timestamp with time zone DEFAULT CURRENT_TIMESTAMP,
  updated_at timestamp with time zone DEFAULT CURRENT_TIMESTAMP,
  changelog text,
  CONSTRAINT asset_files_pkey PRIMARY KEY (id),
  CONSTRAINT fk_asset_files_asset FOREIGN KEY (asset_id) REFERENCES public.assets(id)
);
CREATE TABLE public.asset_images (
  id bigint NOT NULL DEFAULT nextval('asset_images_id_seq'::regclass),
  public_id uuid NOT NULL DEFAULT gen_random_uuid() UNIQUE,
  asset_id bigint NOT NULL,
  image_path character varying NOT NULL,
  sort_order integer NOT NULL DEFAULT 1,
  created_at timestamp with time zone DEFAULT CURRENT_TIMESTAMP,
  updated_at timestamp with time zone DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT asset_images_pkey PRIMARY KEY (id),
  CONSTRAINT fk_asset_images_asset FOREIGN KEY (asset_id) REFERENCES public.assets(id)
);
CREATE TABLE public.transactions (
  id bigint NOT NULL DEFAULT nextval('transactions_id_seq'::regclass),
  public_id uuid NOT NULL DEFAULT gen_random_uuid() UNIQUE,
  buyer_id bigint NOT NULL,
  invoice_number character varying NOT NULL UNIQUE,
  amount integer NOT NULL,
  payment_type character varying DEFAULT NULL::character varying,
  status character varying NOT NULL,
  midtrans_transaction_id character varying DEFAULT NULL::character varying,
  paid_at timestamp with time zone,
  created_at timestamp with time zone DEFAULT CURRENT_TIMESTAMP,
  updated_at timestamp with time zone DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT transactions_pkey PRIMARY KEY (id),
  CONSTRAINT fk_transactions_buyer FOREIGN KEY (buyer_id) REFERENCES public.users(id)
);
CREATE TABLE public.entitlements (
  id bigint NOT NULL DEFAULT nextval('entitlements_id_seq'::regclass),
  public_id uuid NOT NULL DEFAULT gen_random_uuid() UNIQUE,
  user_id bigint NOT NULL,
  asset_id bigint NOT NULL,
  transaction_id bigint,
  source character varying NOT NULL,
  granted_at timestamp with time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
  created_at timestamp with time zone DEFAULT CURRENT_TIMESTAMP,
  updated_at timestamp with time zone DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT entitlements_pkey PRIMARY KEY (id),
  CONSTRAINT fk_entitlements_user FOREIGN KEY (user_id) REFERENCES public.users(id),
  CONSTRAINT fk_entitlements_asset FOREIGN KEY (asset_id) REFERENCES public.assets(id),
  CONSTRAINT fk_entitlements_transaction FOREIGN KEY (transaction_id) REFERENCES public.transactions(id)
);
CREATE TABLE public.download_logs (
  id bigint NOT NULL DEFAULT nextval('download_logs_id_seq'::regclass),
  public_id uuid NOT NULL DEFAULT gen_random_uuid() UNIQUE,
  entitlement_id bigint NOT NULL,
  ip_address character varying NOT NULL,
  downloaded_at timestamp with time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT download_logs_pkey PRIMARY KEY (id),
  CONSTRAINT fk_download_logs_entitlement FOREIGN KEY (entitlement_id) REFERENCES public.entitlements(id)
);
CREATE TABLE public.seller_profiles (
  user_id bigint NOT NULL,
  store_name character varying NOT NULL,
  address character varying,
  reason character varying,
  website_url character varying,
  payout_number character varying,
  payout_account_name character varying,
  created_at timestamp with time zone NOT NULL DEFAULT now(),
  updated_at timestamp with time zone NOT NULL DEFAULT now(),
  portfolio_url character varying,
  sample_asset_path character varying,
  sample_asset_desc text,
  status character varying DEFAULT 'pending'::character varying,
  reject_reason text,
  reviewed_by bigint,
  reviewed_at timestamp with time zone,
  contact_email character varying,
  CONSTRAINT seller_profiles_pkey PRIMARY KEY (user_id),
  CONSTRAINT seller_profiles_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.users(id),
  CONSTRAINT seller_profiles_reviewed_by_fkey FOREIGN KEY (reviewed_by) REFERENCES public.users(id)
);
CREATE TABLE public.user_profiles (
  user_id bigint NOT NULL,
  name character varying NOT NULL,
  bio character varying DEFAULT NULL::character varying,
  created_at timestamp with time zone DEFAULT now(),
  updated_at timestamp with time zone DEFAULT now(),
  CONSTRAINT user_profiles_pkey PRIMARY KEY (user_id),
  CONSTRAINT user_profiles_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.users(id)
);
CREATE TABLE public.cart_items (
  id integer NOT NULL DEFAULT nextval('cart_items_id_seq'::regclass),
  user_id bigint NOT NULL,
  asset_id bigint NOT NULL,
  added_at timestamp with time zone DEFAULT now(),
  CONSTRAINT cart_items_pkey PRIMARY KEY (id),
  CONSTRAINT cart_items_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.users(id),
  CONSTRAINT cart_items_asset_id_fkey FOREIGN KEY (asset_id) REFERENCES public.assets(id)
);
CREATE TABLE public.transaction_details (
  id bigint GENERATED ALWAYS AS IDENTITY NOT NULL,
  transaction_id bigint NOT NULL,
  asset_id bigint NOT NULL,
  price integer NOT NULL,
  created_at timestamp with time zone DEFAULT now(),
  updated_at timestamp with time zone DEFAULT now(),
  CONSTRAINT transaction_details_pkey PRIMARY KEY (id),
  CONSTRAINT fk_transaction FOREIGN KEY (transaction_id) REFERENCES public.transactions(id),
  CONSTRAINT fk_asset FOREIGN KEY (asset_id) REFERENCES public.assets(id)
);
CREATE TABLE public.reviews (
  id bigint GENERATED ALWAYS AS IDENTITY NOT NULL,
  public_id uuid NOT NULL DEFAULT gen_random_uuid(),
  user_id bigint NOT NULL,
  asset_id bigint NOT NULL,
  rating smallint NOT NULL CHECK (rating >= 1 AND rating <= 5),
  title character varying,
  comment text NOT NULL,
  created_at timestamp with time zone DEFAULT now(),
  updated_at timestamp with time zone DEFAULT now(),
  CONSTRAINT reviews_pkey PRIMARY KEY (id),
  CONSTRAINT reviews_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.users(id),
  CONSTRAINT reviews_asset_id_fkey FOREIGN KEY (asset_id) REFERENCES public.assets(id)
);