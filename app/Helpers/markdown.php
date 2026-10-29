<?php

// Kumpulan fungsi helper untuk konversi markdown
declare(strict_types=1);

if (!function_exists('parseMarkdown')) {
    /**
     * Konversi teks Markdown sederhana ke HTML
     */
    function parseMarkdown(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
}
