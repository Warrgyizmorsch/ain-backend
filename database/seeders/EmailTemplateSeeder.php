<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Schema;

class EmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('email_templates')) {
            return;
        }

        $templates = [
            [
                'name' => 'Order Confirmation & In Process',
                'subject' => 'Your Order is Confirmed & In Process',
                'body_html' => '<p>Dear Student,</p><p>Thank you for choosing Assignment In Need. Your assignment requirements have been verified and our subject expert has commenced work.</p><p>We will keep you updated on the progress.</p><p>Best regards,<br><strong>Assignment In Need Team</strong></p>',
                'body_plain' => "Dear Student,\n\nThank you for choosing Assignment In Need. Your assignment requirements have been verified and our subject expert has commenced work.\n\nWe will keep you updated on the progress.\n\nBest regards,\nAssignment In Need Team",
                'category' => 'client',
            ],
            [
                'name' => 'Clarification Required',
                'subject' => 'Clarification Required - Regarding Your Order',
                'body_html' => '<p>Dear Student,</p><p>Our academic writer is reviewing your order details. To ensure the highest standard of work, could you please provide clarification on the following:</p><ul><li>Additional guidelines or class notes</li><li>Preferred referencing style</li></ul><p>Looking forward to your prompt response.</p><p>Best regards,<br><strong>Assignment In Need Team</strong></p>',
                'body_plain' => "Dear Student,\n\nOur academic writer is reviewing your order details. To ensure the highest standard of work, could you please provide clarification on the following:\n- Additional guidelines or class notes\n- Preferred referencing style\n\nLooking forward to your prompt response.\n\nBest regards,\nAssignment In Need Team",
                'category' => 'client',
            ],
            [
                'name' => 'Work Completed & Delivered',
                'subject' => 'Your Completed Assignment is Ready',
                'body_html' => '<p>Dear Student,</p><p>We are pleased to inform you that your assignment has been completed by our expert writer and verified by our quality assurance team.</p><p>Please find the final solution attached. Kindly review it and let us know if you need any adjustments.</p><p>Best regards,<br><strong>Assignment In Need Team</strong></p>',
                'body_plain' => "Dear Student,\n\nWe are pleased to inform you that your assignment has been completed by our expert writer and verified by our quality assurance team.\n\nPlease find the final solution attached. Kindly review it and let us know if you need any adjustments.\n\nBest regards,\nAssignment In Need Team",
                'category' => 'client',
            ],
        ];

        foreach ($templates as $item) {
            EmailTemplate::updateOrCreate(
                ['name' => $item['name']],
                [
                    'subject' => $item['subject'],
                    'body_html' => $item['body_html'],
                    'body_plain' => $item['body_plain'],
                    'category' => $item['category'],
                ]
            );
        }
    }
}
