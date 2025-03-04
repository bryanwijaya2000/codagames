<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CodeMatch implements ValidationRule
{
     /**
     * Create a new rule instance.
     *
     * @param  string  $code
     * @return void
     */

     private $code;

     public function __construct($code)
     {
         $this->code = $code;
     }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value != $this->code) {
            $fail('Code does not match');
        }
    }
}
