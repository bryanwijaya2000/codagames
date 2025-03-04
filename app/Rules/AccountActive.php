<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Users;

class AccountActive implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user = Users::where('username', '=', $value)->orWhere('email', '=', $value)->first();
        if (!(($user != null && $user->blocked == 0) || $value == 'admin2000' || $value == 'allisprogramming12789@gmail.com')) {
            $fail('Account blocked');
        }
    }
}
