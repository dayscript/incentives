<?php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class RedemptionMaxValue implements Rule
{
    // Should return true or false depending on whether the attribute value is valid or not.
    public function passes($attribute, $value)
    {
        return $value > 10;
    }

    // This method should return the validation error message that should be used when validation fails
    public function message()
    {
        return 'The :attribute must be greater than 10.';
    }
}
