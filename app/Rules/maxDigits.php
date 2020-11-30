<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class maxDigits implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if($value[0]=='+')
            return strlen($value)<=16;
        return strlen($value)<=15;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'too_long';
    }
}
