<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Users;

class EmailNotExists implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user = Users::where('email', '=', $value)->first();
        if ($user != null || $value == 'allisprogramming12789@gmail.com') {
            $fail('Email already exists');
        }
    }
}
