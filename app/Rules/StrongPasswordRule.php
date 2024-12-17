<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StrongPasswordRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check if the value meets the strong password criteria
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', $value)) {
            // If it doesn't, use the $fail closure to report the validation failure
            $fail('The :attribute must be a strong password.');
        }
    }

    public function message()
    {
        return 'The :attribute must be a strong password.';
    }
}
