<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WhatsappTemplate;
use App\Models\WhatsappSetting;
use Illuminate\Support\Facades\Schema;

class WhatsappTemplateSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Sync Active WhatsApp Setting for AiSensy
        WhatsappSetting::query()->updateOrCreate(
            ['provider' => 'ai-sense'],
            [
                'settings' => [
                    'project_id' => '67e109077c4b230bed2fb1ff',
                    'api_key' => '222488aa8678e32a9069d',
                    'webhook_url' => url('/api/webhooks/whatsapp'),
                ],
                'is_active' => true,
            ]
        );

        // 2. Sync Meta Approved Templates
        if (Schema::hasTable('whatsapp_templates')) {
            $realTemplates = [
                [
                    'name' => 'introduction',
                    'title' => 'Welcome to AIN (Introduction)',
                    'category' => 'MARKETING',
                    'language' => 'en_GB',
                    'body' => "Hello! Hope you're doing well today\n\nWelcome to Assignment In Need (AIN)✨\n\nI'm here to assist you with your academic needs.📚:\n\nPlease let me know how I can help you today with assignments, projects, or anything else!\n\nLooking forward to supporting you. 😊",
                    'footer_text' => 'Assignment In Need Team',
                    'variables' => [],
                    'status' => 'APPROVED',
                    'is_active' => true,
                ],
                [
                    'name' => 'offer_message',
                    'title' => 'Special Flat 10% Discount Offer',
                    'category' => 'MARKETING',
                    'language' => 'en_GB',
                    'body' => "Hello! Hope you're doing well today.\n*We are from Assignment In Need (AIN)*\n\nNow our company is providing *Flat 10% discount* on any assignment work,\n\nIf you have any assignment, Please let us know",
                    'footer_text' => 'Assignment In Need Team',
                    'variables' => [],
                    'status' => 'APPROVED',
                    'is_active' => true,
                ],
                [
                    'name' => 'after_orderconfirmation_welcome_messge',
                    'title' => 'Order Confirmation Welcome Message',
                    'category' => 'MARKETING',
                    'language' => 'en_GB',
                    'body' => "Hello! Hope you're doing well today\n\nI'm a representative from Assignment In Need (AIN).\n\nYou have recently confirmed the order with us.\n\nWe would like to communicate with you about your recent assignment on this primary number, which offers instant response and hassle-free services.\n\nI'll be waiting for your response to the discussion.\n\nThanks,\nTeam AIN",
                    'footer_text' => 'Assignment In Need Team',
                    'variables' => [],
                    'status' => 'APPROVED',
                    'is_active' => true,
                ],
            ];

            WhatsappTemplate::truncate();
            foreach ($realTemplates as $t) {
                WhatsappTemplate::create($t);
            }
        }
    }
}
