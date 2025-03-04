<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class FileNameMaxLength implements ValidationRule
{
     /**
     * Create a new rule instance.
     *
     * @param  string  $attribute
     * @return void
     */

     private $attribute, $maxLength;

     public function __construct($attribute, $maxLength)
     {
         $this->attribute = $attribute;
         $this->maxLength = $maxLength;
     }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $fileName = $value->getClientOriginalName();
        if (strlen($fileName) > $this->maxLength) {
            $fail($this->attribute . ' name must be at most ' . $this->maxLength . ' characters long');
        }
    }
}
