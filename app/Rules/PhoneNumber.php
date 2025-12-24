<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $result = preg_match("/^\d{12,15}$/", $value, $matches);

        if (in_array($result, [0, false])) {
            $fail('Invalid phone number format, e.g. 380930000011');
        }
    }
}
