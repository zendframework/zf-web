<?php

/**
 * Entry Normalizer 
 *
 * - Create stubs if not present
 * - Change entry date to a DateTime object
 * - etc.
 * 
 * @todo Pass content through a markup renderer?
 * @todo Create a summary if not present?
 * @todo Normalize author (i.e., create link, etc.)
 * @todo Normalize tags (ensure an array is present)
 */
class EntryNormalizer
{
    public static function normalize($entry)
    {
        if (!is_array($entry) && !is_object($entry)) {
            return false;
        }

        $entry = (object) $entry;

        if (!isset($entry->stub)) {
            $entry->stub = self::stub($entry->title);
        }

        $date = new DateTime($entry->date, new DateTimezone('America/Los_Angeles'));
        $entry->date = $date;

        if (!isset($entry->tags)) {
            $entry->tags = array();
        }

        return $entry;
    }

    public static function stub($title)
    {
        $stub = str_replace(array(' ', '+', '.'), '-', $title);
        $stub = preg_replace('/[^a-z0-9_-]/i', '', $stub);
        return $stub;
    }
}
