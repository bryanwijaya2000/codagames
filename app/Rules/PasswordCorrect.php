<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Users;
use Illuminate\Support\Facades\Hash;

class PasswordCorrect implements ValidationRule
{
    /**
     * Create a new rule instance.
     *
     * @param  string  $identifier
     * @return void
     */

     private $identifier;

     public function __construct($identifier)
     {
         $this->identifier = $identifier;
     }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $identifier = $this->identifier;
        $user = Users::where('username', '=', $identifier)->orWhere('email', '=', $identifier)->first();
        if ($user != null) {
            if (!Hash::check($value, $user->password)) {
                $fail('Wrong password');
            }
        }
    }
}
