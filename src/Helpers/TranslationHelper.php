<?php

namespace Adiartawibawa\LanguageGenerator\Helpers;

use Illuminate\Support\Facades\Http;

class TranslationHelper
{
    /**
     * Translate text using Google Translate.
     *
     * @param string $text The text to translate.
     * @param string $source The source language code.
     * @param string $target The target language code.
     * @return string The translated text.
     */
    public static function translate($text, $source, $target)
    {
        $response = Http::retry(config('language-generator.retry_attempts', 3), config('language-generator.retry_interval', 100))
            ->throw()
            ->get(config('language-generator.api_endpoint'), [
                'client' => 'gtx',
                'sl' => $source,
                'tl' => $target,
                'dt' => 't',
                'q' => $text,
            ]);

        $response = json_decode($response->body());
        $translatedText = '';

        foreach ($response[0] as $translation) {
            $translatedText .= $translation[0];
        }

        return !empty($translatedText) ? $translatedText : $text;
    }

    /**
     * Convert an array to a string representation using short array syntax.
     *
     * @param array $array The array to convert.
     * @param int $indentLevel The current indentation level (for formatting).
     * @return string The array as a string.
     */
    public static function arrayToString(array $array, $indentLevel = 1)
    {
        $indent = str_repeat('    ', $indentLevel); // 4 spaces for indentation
        $entries = [];

        foreach ($array as $key => $value) {
            $entryKey = is_string($key) ? "'$key'" : $key;
            if (is_array($value)) {
                $entryValue = self::arrayToString($value, $indentLevel + 1);
                $entries[] = "$indent$entryKey => $entryValue";
            } else {
                // Escape single quotes inside strings
                $entryValue = is_string($value) ? "'" . addcslashes($value, "'") . "'" : $value;
                $entries[] = "$indent$entryKey => $entryValue";
            }
        }

        $glue = ",\n";
        $body = implode($glue, $entries);
        if ($indentLevel > 1) {
            return "[\n$body,\n" . str_repeat('    ', $indentLevel - 1) . ']';
        } else {
            return "[\n$body\n$indent]";
        }
    }
}
