<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class YoutubeUrl implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
        // Regex checks for YouTube watch links, short youtu.be, or embed
        if (!preg_match('/(youtu\.be\/|youtube\.com\/(watch\?v=|embed\/))([^\&\?]+)/', $value)) {
            $fail('The :attribute must be a valid YouTube link.');
        }

        $response = Http::get('https://www.youtube.com/oembed', [
            'url' => $value,
            'format' => 'json',
        ]);

        if ($response->failed()) {
            $fail('The :attribute must be a valid and existing YouTube video.');
        }


    }
}
