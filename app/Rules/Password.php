<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Password implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $result = preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/", $value, $matches);

        if (in_array($result, [0, false])) {
            $fail('Password must contain: upper and lower case letters, numbers');
        }
    }
}
