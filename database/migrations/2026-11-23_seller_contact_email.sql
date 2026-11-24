BEGIN;

ALTER TABLE public.seller_profiles ADD COLUMN IF NOT EXISTS contact_email varchar(254);

-- PHP connects as postgres; direct Data API clients must not edit seller or package records.
ALTER TABLE public.seller_profiles ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.asset_files ENABLE ROW LEVEL SECURITY;

COMMIT;
