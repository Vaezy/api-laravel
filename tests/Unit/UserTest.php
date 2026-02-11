<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_true()
    {
        $user = new User([
            'email' => 'john@entreprise.com'
        ]);

        $this->assertTrue($user->usesProfessionalEmail());
    }
    public function test_false()
    {
        $user = new User([
            'email' => 'john@gmail.com'
        ]);

        $this->assertFalse($user->usesProfessionalEmail());
    }
}
