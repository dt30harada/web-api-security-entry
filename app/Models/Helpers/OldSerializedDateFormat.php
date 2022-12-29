<?php

namespace App\Models\Helpers;

/**
 * @see https://stackoverflow.com/questions/63549021/wrong-timezone-with-eloquent-model-timestamps-on-model-get-but-correct-with
 */
trait OldSerializedDateFormat
{
    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
