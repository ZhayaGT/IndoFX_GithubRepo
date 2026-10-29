<?php

declare(strict_types=1);

// Kumpulan fungsi helper untuk konversi markdown

if (!function_exists('parse_markdown')) {
    /**
     * Konversi teks Markdown dasar ke HTML yang aman (XSS safe).
     */
    function parse_markdown(string $text): string
    {
        // 1. Amankan karakter khusus HTML terlebih dahulu
        $safeText = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

        // 2. Format Bold: **teks** -> <strong>teks</strong>
        $safeText = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $safeText);

        // 3. Format Italic: *teks* -> <em>teks</em>
        $safeText = preg_replace('/\*([^\*]+)\*/', '<em>$1</em>', $safeText);

        // 4. Konversi baris baru menjadi tag <br>
        return nl2br($safeText);
    }
}

if (!function_exists('parseMarkdown')) {
    /**
     * Alias camelCase untuk parse_markdown
     */
    function parseMarkdown(string $text): string
    {
        return parse_markdown($text);
    }
}
