<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MaxLines implements ValidationRule
{
    /**
     * The maximum number of lines allowed.
     *
     * @var int
     */
    private int $maxLines;

    /**
     * Indicates whether the rule should be implicit.
     *
     * @var bool
     */
    public $implicit = true;

    /**
     * Create a new rule instance.
     *
     * @param  int  $maxLines
     * @return void
     */
    public function __construct($maxLines)
    {
        $this->maxLines = $maxLines;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        preg_match_all('/(\r\n|\r|\n)/', $value, $matches);
        $num_lines = count($matches[0]) + 1;

        if ($num_lines > $this->maxLines) {
            $fail("Maksimal {$this->maxLines} baris.");
        }
    }
}
