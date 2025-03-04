<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NoSpaces implements ValidationRule
{
     /**
     * Create a new rule instance.
     *
     * @param  string  $attribute
     * @return void
     */

     private $attribute;

     public function __construct($attribute)
     {
         $this->attribute = $attribute;
     }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $arr = explode(' ', $value);
        if (count($arr) > 1) {
            $fail($this->attribute . ' cannot contain spaces');
        }
    }
}
