<?php
declare(strict_types=1);

namespace App;

/**
 * Minimal validation helper
 */
class Validator
{
    public array $errors = [];

    public function required(string $field, $value): void
    {
        if ($value === null || $value === '') {
            $this->errors[$field] = 'Required';
        }
    }

    public function stringLen(string $field, ?string $value, int $min, int $max): void
    {
        if ($value !== null && $value !== '' && (mb_strlen($value) < $min || mb_strlen($value) > $max)) {
            $this->errors[$field] = "Must be between $min and $max characters";
        }
    }

    public function intRange(string $field, $value, int $min, int $max): void
    {
        if (!filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => $min, 'max_range' => $max]])) {
            $this->errors[$field] = "Must be between $min and $max";
        }
    }

    public function email(string $field, ?string $value): void
    {
        if ($value === null || !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = 'Invalid email';
        }
    }

    public function phoneE164(string $field, ?string $value, bool $required = true): void
    {
        if ($required && ($value === null || $value === '')) {
            $this->errors[$field] = 'Required';
            return;
        }
        if ($value !== null && $value !== '' && !preg_match('/^(\+?[1-9]\d{1,14}|0\d{2,15})$/', $value)) {
            $this->errors[$field] = 'Invalid phone number';
        }
    }
}
