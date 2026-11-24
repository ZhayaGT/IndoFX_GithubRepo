-- Install fixtures/DUMMY-QA-ASSET.zip in storage/uploads/assets before running this seed.
-- Only fill published assets without a package; never replace a seller upload.
INSERT INTO public.asset_files
    (asset_id, file_path, original_name, mime_type, extension, file_size, version, changelog)
SELECT a.id, 'storage/uploads/assets/DUMMY-QA-ASSET.zip', 'DUMMY-QA-ASSET.zip',
       'application/zip', 'zip', 275, '0.0.0-qa',
       'Paket dummy untuk pengujian QA. Bukan aset Unity asli; ganti dengan paket penjual sebelum penjualan nyata.'
FROM public.assets a
WHERE a.status = 'published' AND a.deleted_at IS NULL
  AND NOT EXISTS (SELECT 1 FROM public.asset_files f WHERE f.asset_id = a.id);
