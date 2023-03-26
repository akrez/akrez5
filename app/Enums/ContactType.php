<?php

namespace App\Enums;

use App\Support\Enum;

class ContactType extends Enum
{
    public const CONTACT_TYPE_ADDRESS = 'address';
    public const CONTACT_TYPE_TELEGRAM = 'telegram';
    public const CONTACT_TYPE_WHATSUP = 'whatsup';
    public const CONTACT_TYPE_PHONE = 'phone';
    public const CONTACT_TYPE_EMAIL = 'email';
    public const CONTACT_TYPE_INSTAGRAM = 'instagram';

    public static function getItems()
    {
        return [
            static::CONTACT_TYPE_ADDRESS => __('Address'),
            static::CONTACT_TYPE_TELEGRAM => __('Telegram'),
            static::CONTACT_TYPE_WHATSUP => __('Whatsup'),
            static::CONTACT_TYPE_PHONE => __('Phone'),
            static::CONTACT_TYPE_EMAIL => __('Email'),
            static::CONTACT_TYPE_INSTAGRAM => __('Instagram'),
        ];
    }
}
