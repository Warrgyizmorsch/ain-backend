<?php

namespace Tests\Feature;

use Tests\TestCase;

class ContactApiTest extends TestCase
{
    public function test_app_contact_us_endpoint_returns_exact_details(): void
    {
        $response = $this->getJson('/api/app/contact-us');

        $response->assertStatus(200)
            ->assertExactJson([
                'success' => true,
                'data' => [
                    'call_number' => '+44 7917481696',
                    'email' => 'order@assignnmentinneed.com',
                    'whatsapp_number' => '+44 78262 33106',
                ]
            ]);
    }

    public function test_app_contact_details_alias_works(): void
    {
        $response = $this->getJson('/api/app/contact-details');

        $response->assertStatus(200)
            ->assertExactJson([
                'success' => true,
                'data' => [
                    'call_number' => '+44 7917481696',
                    'email' => 'order@assignnmentinneed.com',
                    'whatsapp_number' => '+44 78262 33106',
                ]
            ]);
    }

    public function test_public_contact_us_endpoint_works(): void
    {
        $response = $this->getJson('/api/contact-us');

        $response->assertStatus(200)
            ->assertExactJson([
                'success' => true,
                'data' => [
                    'call_number' => '+44 7917481696',
                    'email' => 'order@assignnmentinneed.com',
                    'whatsapp_number' => '+44 78262 33106',
                ]
            ]);
    }
}
