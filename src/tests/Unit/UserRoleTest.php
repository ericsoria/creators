<?php

namespace Tests\Unit;

use App\Models\User;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class UserRoleTest extends TestCase
{
    public function test_user_rejects_unsupported_roles(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new User(['role' => 'unsupported']);
    }
}
