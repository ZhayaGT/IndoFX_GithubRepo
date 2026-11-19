<?php

namespace IndoFX\Helpers;

/**
 * Pemeriksaan dan penyimpanan berkas unggahan aset.
 *
 * Nama berkas selalu acak; nama asli pengguna hanya disimpan sebagai metadata.
 */
class Upload {
    private const IMAGE_TYPES = [
        IMAGETYPE_JPEG => 'jpg',
        IMAGETYPE_PNG  => 'png',
    ];

    private const PACKAGE_EXTENSIONS = ['unitypackage', 'zip', 'rar', 'fbx', 'blend'];

    // Cek awal gambar tanpa memindahkan berkas, dipakai sebelum menyentuh DB.
    public static function validateImage(array $file, int $maxBytes): array {
        return self::checkCommon($file, $maxBytes, 'Gambar pratinjau belum dipilih.', 'Ukuran gambar melebihi batas server.', 'Ukuran gambar maksimal 5 MB.')
            ?? self::checkImageContent($file);
    }

    // Cek awal berkas paket tanpa memindahkan berkas.
    public static function validatePackage(array $file, int $maxBytes): array {
        return self::checkCommon($file, $maxBytes, 'Berkas paket belum dipilih.', 'Ukuran berkas melebihi batas server.', 'Ukuran berkas maksimal 200 MB.')
            ?? self::checkPackageExtension($file);
    }

    // Simpan gambar pratinjau ke public/uploads/previews dan kembalikan path relatifnya.
    public static function image(array $file, int $maxBytes): array {
        $check = self::validateImage($file, $maxBytes);

        if (!$check['ok']) {
            return $check;
        }

        $extension = self::IMAGE_TYPES[(int) getimagesize($file['tmp_name'])[2]];
        $name = bin2hex(random_bytes(16)) . '.' . $extension;
        $dir = BASE_PATH . '/public/uploads/previews';

        if (!self::move($file, $dir, $name)) {
            return ['ok' => false, 'error' => 'Berkas gagal disimpan.', 'name' => ''];
        }

        return ['ok' => true, 'error' => '', 'name' => 'uploads/previews/' . $name];
    }

    // Simpan berkas paket ke storage/uploads/assets/<public_id> dan kembalikan metadatanya.
    public static function package(array $file, int $maxBytes, string $assetPublicId): array {
        $check = self::validatePackage($file, $maxBytes);

        if (!$check['ok']) {
            return ['ok' => false, 'error' => $check['error']] + self::emptyPackageMeta();
        }

        $originalName = (string) ($file['name'] ?? 'berkas');
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $name = bin2hex(random_bytes(16)) . '.' . $extension;
        $dir = BASE_PATH . '/storage/uploads/assets/' . $assetPublicId;

        if (!self::move($file, $dir, $name)) {
            return ['ok' => false, 'error' => 'Berkas gagal disimpan.'] + self::emptyPackageMeta();
        }

        return [
            'ok'            => true,
            'error'         => '',
            'file_path'     => 'storage/uploads/assets/' . $assetPublicId . '/' . $name,
            'original_name' => basename($originalName),
            'mime_type'     => (string) ($file['type'] ?? 'application/octet-stream'),
            'extension'     => $extension,
            'file_size'     => (int) ($file['size'] ?? 0),
        ];
    }

    // Pemeriksaan yang sama untuk kedua jenis berkas; null berarti lanjut ke cek tipe.
    private static function checkCommon(array $file, int $maxBytes, string $missing, string $tooLargeServer, string $tooLargeLimit): ?array {
        $error = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);

        if ($error === UPLOAD_ERR_NO_FILE) {
            return ['ok' => false, 'error' => $missing];
        }
        if ($error === UPLOAD_ERR_INI_SIZE || $error === UPLOAD_ERR_FORM_SIZE) {
            return ['ok' => false, 'error' => $tooLargeServer];
        }
        if ($error !== UPLOAD_ERR_OK) {
            return ['ok' => false, 'error' => 'Berkas gagal diunggah.'];
        }
        if ((int) ($file['size'] ?? 0) > $maxBytes) {
            return ['ok' => false, 'error' => $tooLargeLimit];
        }

        return null;
    }

    private static function checkImageContent(array $file): array {
        $info = @getimagesize((string) $file['tmp_name']);

        if ($info === false || !isset(self::IMAGE_TYPES[(int) $info[2]])) {
            return ['ok' => false, 'error' => 'Format gambar harus PNG atau JPG.'];
        }

        return ['ok' => true, 'error' => ''];
    }

    private static function checkPackageExtension(array $file): array {
        $extension = strtolower(pathinfo((string) ($file['name'] ?? ''), PATHINFO_EXTENSION));

        if (!in_array($extension, self::PACKAGE_EXTENSIONS, true)) {
            return ['ok' => false, 'error' => 'Format berkas harus .unitypackage, .zip, .rar, .fbx, atau .blend.'];
        }

        return ['ok' => true, 'error' => ''];
    }

    private static function move(array $file, string $dir, string $name): bool {
        if (!is_dir($dir) && !mkdir($dir, 0777, true) && !is_dir($dir)) {
            return false;
        }

        $tmp = (string) ($file['tmp_name'] ?? '');

        if (!is_uploaded_file($tmp)) {
            return false;
        }

        return move_uploaded_file($tmp, $dir . '/' . $name);
    }

    private static function emptyPackageMeta(): array {
        return [
            'file_path'     => '',
            'original_name' => '',
            'mime_type'     => '',
            'extension'     => '',
            'file_size'     => 0,
        ];
    }
}