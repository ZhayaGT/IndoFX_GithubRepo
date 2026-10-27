<?php

use IndoFX\Helpers\Storage;

/**
 * Satu kartu aset.
 *
 * @var array $item baris dari IndoFX\Models\Asset::search()
 * @var string $colClass kelas kolom Bootstrap, default 4 kartu per baris
 */

$colClass = $colClass ?? 'col-12 col-sm-6 col-lg-3';
$price = (int) $item['price'];
?>
<div class="<?= $colClass ?>">
    <a href="/asset/detail?slug=<?= urlencode($item['slug']) ?>" class="text-decoration-none d-block h-100">
        <div class="asset-card-figma h-100 d-flex flex-column">
            <div class="asset-thumb-wrapper">
                <img class="asset-thumb-img"
                     src="<?= htmlspecialchars(Storage::image($item['image_path'] ?? null), ENT_QUOTES, 'UTF-8') ?>"
                     alt="<?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?>" loading="lazy">
            </div>
            <div class="asset-card-body d-flex flex-column justify-content-between flex-grow-1">
                <div>
                    <span class="asset-studio"><?= htmlspecialchars($item['seller_name'], ENT_QUOTES, 'UTF-8') ?></span>
                    <h6 class="asset-name"><?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?></h6>
                    <div class="d-flex gap-1 mb-2">
                        <span class="badge-pipeline <?= pipeline_badge_class((string) $item['render_pipeline']) ?>">
                            <?= htmlspecialchars($item['render_pipeline'], ENT_QUOTES, 'UTF-8') ?>
                        </span>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center pt-1">
                    <span class="asset-price"><?= htmlspecialchars(format_rupiah($price), ENT_QUOTES, 'UTF-8') ?></span>
                    <i class="bi bi-download asset-dl-icon"></i>
                </div>
            </div>
        </div>
    </a>
</div>
