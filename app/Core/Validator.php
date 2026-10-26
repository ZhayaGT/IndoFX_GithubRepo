<?php

declare(strict_types=1);

namespace IndoFX\Core;

/**
 * Validasi input form sederhana dengan pesan berbahasa Indonesia.
 * Aturan yang didukung: required, email, min:<n>, max:<n>.
 */
class Validator
{
    /** @var array<string, string> */
    private array $errors = [];

    /**
     * @param array<string, mixed>  $data
     * @param array<string, string> $rules  contoh: ['email' => 'required|email']
     * @param array<string, string> $labels nama field untuk pesan error
     */
    public function __construct(
        private array $data,
        private array $rules,
        private array $labels = []
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        foreach ($this->rules as $field => $ruleSet) {
            $value = trim((string) ($this->data[$field] ?? ''));
            $label = $this->labels[$field] ?? $field;

            foreach (explode('|', $ruleSet) as $rule) {
                [$name, $param] = array_pad(explode(':', $rule, 2), 2, null);

                $message = match ($name) {
                    'required' => $value === '' ? "{$label} wajib diisi." : null,
                    'email'    => $value !== '' && filter_var($value, FILTER_VALIDATE_EMAIL) === false
                        ? "Format {$label} tidak valid." : null,
                    'min'      => $value !== '' && mb_strlen($value) < (int) $param
                        ? "{$label} minimal {$param} karakter." : null,
                    'max'      => $value !== '' && mb_strlen($value) > (int) $param
                        ? "{$label} maksimal {$param} karakter." : null,
                    default    => null,
                };

                if ($message !== null) {
                    $this->errors[$field] = $message;
                    break;
                }
            }
        }
    }

    public function fails(): bool
    {
        return $this->errors !== [];
    }

    /** @return array<string, string> */
    public function errors(): array
    {
        return $this->errors;
    }
}
