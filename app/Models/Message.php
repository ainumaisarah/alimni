<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'is_read',
    ];

    /* ======================
       ENCRYPT / DECRYPT
    ====================== */

    // Encrypt before saving to database
    public function setMessageAttribute($value)
    {
        $this->attributes['message'] = Crypt::encryptString($value);
    }

    // Decrypt when reading from database
    public function getMessageAttribute($value)
    {
        try {
            return Crypt::decryptString($value);
        } catch (DecryptException $e) {
            // Old unencrypted message
            return $value;
        }
    }

    /* ======================
       RELATIONSHIPS
    ====================== */

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
