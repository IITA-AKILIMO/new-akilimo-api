<?php

namespace App\Auth;

final class TokenAbility
{
    /** Grants all abilities — used for admin/superuser tokens. */
    const WILDCARD = '*';

    /** Read any resource. */
    const READ = 'read';

    /** Create or update resources. */
    const WRITE = 'write';

    /** Generate, revoke, and delete own API keys. */
    const API_KEYS_MANAGE = 'api-keys:manage';

    /** Manage users and perform privileged admin operations. */
    const ADMIN = 'admin';

    /** All abilities that can be assigned to a token or API key. */
    const ALL = [
        self::READ,
        self::WRITE,
        self::API_KEYS_MANAGE,
        self::ADMIN,
    ];
}
