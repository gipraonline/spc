<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

/**
 * Encrypted cast that fails safe.
 *
 * Behaves like Laravel's built-in `encrypted` cast, but returns null on the
 * read side when the stored value cannot be decrypted (e.g. the APP_KEY was
 * rotated after the row was written, or the payload is corrupt) instead of
 * letting DecryptException bubble up and 500 the page.
 */
class EncryptedNullable implements CastsAttributes
{
    /**
     * Decrypt the value; unreadable/legacy values become null.
     */
    public function get($model, string $key, $value, array $attributes)
    {
        if ($value === null || $value === '') {
            return null;
        }

        try {
            return Crypt::decryptString($value);
        } catch (DecryptException $e) {
            return null;
        }
    }

    /**
     * Encrypt on the way in, exactly like the built-in cast.
     */
    public function set($model, string $key, $value, array $attributes)
    {
        return $value === null ? $value : Crypt::encryptString($value);
    }
}
