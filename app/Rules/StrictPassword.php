<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StrictPassword implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = (string) $value;

        if (strlen($value) < 8) {
            $fail('Kata sandi gagal divalidasi! Pastikan kata sandi minimal berisi 8 karakter dan wajib mengombinasikan huruf, angka, serta karakter spesial (simbol).');
            return;
        }

        if (!preg_match('/[A-Z]/', $value)) {
            $fail('Kata sandi gagal divalidasi! Pastikan kata sandi minimal berisi 8 karakter dan wajib mengombinasikan huruf, angka, serta karakter spesial (simbol).');
            return;
        }

        if (!preg_match('/[a-z]/', $value)) {
            $fail('Kata sandi gagal divalidasi! Pastikan kata sandi minimal berisi 8 karakter dan wajib mengombinasikan huruf, angka, serta karakter spesial (simbol).');
            return;
        }

        if (!preg_match('/[0-9]/', $value)) {
            $fail('Kata sandi gagal divalidasi! Pastikan kata sandi minimal berisi 8 karakter dan wajib mengombinasikan huruf, angka, serta karakter spesial (simbol).');
            return;
        }

        if (!preg_match('/[!@#$%^&*()_\-+=<>?\/{}~\[\]|:;".]/', $value)) {
            $fail('Kata sandi gagal divalidasi! Pastikan kata sandi minimal berisi 8 karakter dan wajib mengombinasikan huruf, angka, serta karakter spesial (simbol).');
        }
    }
}
