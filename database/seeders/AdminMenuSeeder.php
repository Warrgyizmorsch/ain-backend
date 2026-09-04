<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        DB::table('submenus')->truncate();
        DB::table('menu')->truncate();

        $menus = array (
  0 => 
  array (
    'id' => 1,
    'parent_id' => NULL,
    'menu_name' => 'Dashbord',
    'icon_class' => 'fas fa-tachometer-alt',
    'show_menu' => 'Y',
    'routes' => 'dashboard',
    'sort_order' => 1,
    'updated_at' => '2026-05-21 06:45:25',
    'created_at' => '2023-12-16 08:14:34',
  ),
  1 => 
  array (
    'id' => 2,
    'parent_id' => 44,
    'menu_name' => 'Setting',
    'icon_class' => 'fa fa-gear',
    'show_menu' => 'Y',
    'routes' => 'menus',
    'sort_order' => 12,
    'updated_at' => '2026-07-16 12:25:50',
    'created_at' => '2023-12-16 09:28:09',
  ),
  2 => 
  array (
    'id' => 3,
    'parent_id' => 44,
    'menu_name' => 'Masters',
    'icon_class' => 'fas fa-gem',
    'show_menu' => 'Y',
    'routes' => 'master',
    'sort_order' => 0,
    'updated_at' => '2026-07-16 12:25:50',
    'created_at' => '2023-12-19 07:40:28',
  ),
  3 => 
  array (
    'id' => 4,
    'parent_id' => NULL,
    'menu_name' => 'User Module',
    'icon_class' => 'fa fa-user',
    'show_menu' => 'Y',
    'routes' => 'user',
    'sort_order' => 1,
    'updated_at' => '2026-05-21 06:45:52',
    'created_at' => '2023-12-16 11:40:36',
  ),
  4 => 
  array (
    'id' => 5,
    'parent_id' => NULL,
    'menu_name' => 'My Orders',
    'icon_class' => 'fa fa-list',
    'show_menu' => 'Y',
    'routes' => 'orders',
    'sort_order' => 3,
    'updated_at' => '2026-05-21 06:46:08',
    'created_at' => '2025-07-25 10:33:07',
  ),
  5 => 
  array (
    'id' => 11,
    'parent_id' => NULL,
    'menu_name' => 'Leads',
    'icon_class' => 'fa fa-list',
    'show_menu' => 'Y',
    'routes' => 'lead',
    'sort_order' => 4,
    'updated_at' => '2026-05-21 06:46:26',
    'created_at' => '2023-12-27 11:40:26',
  ),
  6 => 
  array (
    'id' => 12,
    'parent_id' => 44,
    'menu_name' => 'Cancel Leads',
    'icon_class' => 'fa fa-close',
    'show_menu' => 'Y',
    'routes' => 'c-leads',
    'sort_order' => 0,
    'updated_at' => '2026-07-16 12:25:50',
    'created_at' => '2023-12-27 11:40:52',
  ),
  7 => 
  array (
    'id' => 13,
    'parent_id' => NULL,
    'menu_name' => 'Ticket Number Sheet',
    'icon_class' => 'fa fa-list',
    'show_menu' => 'Y',
    'routes' => 'feedback',
    'sort_order' => 5,
    'updated_at' => '2026-05-21 06:46:43',
    'created_at' => '2024-01-09 11:20:34',
  ),
  8 => 
  array (
    'id' => 14,
    'parent_id' => 44,
    'menu_name' => 'College',
    'icon_class' => 'fa fa-home',
    'show_menu' => 'Y',
    'routes' => 'college',
    'sort_order' => 0,
    'updated_at' => '2026-07-16 12:25:50',
    'created_at' => '2024-01-25 14:38:47',
  ),
  9 => 
  array (
    'id' => 15,
    'parent_id' => 44,
    'menu_name' => 'Blog And Sample',
    'icon_class' => 'fa fa-list',
    'show_menu' => 'Y',
    'routes' => 'blog-sample',
    'sort_order' => 0,
    'updated_at' => '2026-07-16 12:25:50',
    'created_at' => '2024-02-24 08:45:35',
  ),
  10 => 
  array (
    'id' => 16,
    'parent_id' => 44,
    'menu_name' => 'Samples',
    'icon_class' => 'fa fa-list',
    'show_menu' => 'Y',
    'routes' => 'sample',
    'sort_order' => 0,
    'updated_at' => '2026-07-16 12:25:50',
    'created_at' => '2024-02-24 08:45:57',
  ),
  11 => 
  array (
    'id' => 17,
    'parent_id' => 44,
    'menu_name' => 'Qc Sheet',
    'icon_class' => 'fa fa-list',
    'show_menu' => 'Y',
    'routes' => 'Qc-Sheets',
    'sort_order' => 0,
    'updated_at' => '2026-07-16 08:20:24',
    'created_at' => '2024-03-11 02:26:01',
  ),
  12 => 
  array (
    'id' => 18,
    'parent_id' => NULL,
    'menu_name' => 'Success Tracking and return',
    'icon_class' => 'fa fa-list',
    'show_menu' => 'Y',
    'routes' => 'follow-up',
    'sort_order' => 6,
    'updated_at' => '2026-05-21 06:49:43',
    'created_at' => '2024-03-16 05:49:56',
  ),
  13 => 
  array (
    'id' => 20,
    'parent_id' => 44,
    'menu_name' => 'writer order checker',
    'icon_class' => 'fa fa-list',
    'show_menu' => 'Y',
    'routes' => 'order-writer',
    'sort_order' => 0,
    'updated_at' => '2026-07-16 08:20:57',
    'created_at' => '2024-04-26 07:38:00',
  ),
  14 => 
  array (
    'id' => 21,
    'parent_id' => 44,
    'menu_name' => 'Order With Status Date',
    'icon_class' => 'fa fa-list',
    'show_menu' => 'Y',
    'routes' => 'status-details',
    'sort_order' => 0,
    'updated_at' => '2026-07-16 12:25:50',
    'created_at' => '2024-05-07 06:17:10',
  ),
  15 => 
  array (
    'id' => 22,
    'parent_id' => 44,
    'menu_name' => 'Writer Available',
    'icon_class' => 'fa fa-check',
    'show_menu' => 'Y',
    'routes' => 'writer-available',
    'sort_order' => 0,
    'updated_at' => '2026-07-16 08:21:13',
    'created_at' => '2024-06-01 10:07:59',
  ),
  16 => 
  array (
    'id' => 23,
    'parent_id' => 44,
    'menu_name' => 'Ticket Sheet',
    'icon_class' => 'fa fa-list',
    'show_menu' => 'Y',
    'routes' => 'ticket-sheet',
    'sort_order' => 0,
    'updated_at' => '2026-07-16 12:25:50',
    'created_at' => '2024-07-01 05:27:43',
  ),
  17 => 
  array (
    'id' => 24,
    'parent_id' => NULL,
    'menu_name' => 'Whatsapp',
    'icon_class' => 'fa fa-whatsapp',
    'show_menu' => 'N',
    'routes' => 'whatsapp',
    'sort_order' => 0,
    'updated_at' => '2025-06-09 10:51:38',
    'created_at' => '2024-10-16 13:14:57',
  ),
  18 => 
  array (
    'id' => 25,
    'parent_id' => 44,
    'menu_name' => 'Lets-Learn',
    'icon_class' => 'fa fa-list-alt',
    'show_menu' => 'Y',
    'routes' => 'll-leads',
    'sort_order' => 0,
    'updated_at' => '2026-07-16 12:25:50',
    'created_at' => '2024-11-07 05:33:39',
  ),
  19 => 
  array (
    'id' => 26,
    'parent_id' => NULL,
    'menu_name' => 'Sample',
    'icon_class' => 'fa fa-list',
    'show_menu' => 'Y',
    'routes' => 'samples',
    'sort_order' => 0,
    'updated_at' => '2024-11-29 09:33:10',
    'created_at' => '2024-11-29 09:33:10',
  ),
  20 => 
  array (
    'id' => 27,
    'parent_id' => 44,
    'menu_name' => 'Experts',
    'icon_class' => 'fa fa-user',
    'show_menu' => 'Y',
    'routes' => 'new-expert',
    'sort_order' => 0,
    'updated_at' => '2026-07-16 12:25:50',
    'created_at' => '2025-01-04 05:51:53',
  ),
  21 => 
  array (
    'id' => 28,
    'parent_id' => 44,
    'menu_name' => 'Faq',
    'icon_class' => 'fa fa-list',
    'show_menu' => 'Y',
    'routes' => 'faqurl',
    'sort_order' => 0,
    'updated_at' => '2026-07-16 12:25:50',
    'created_at' => '2025-01-04 06:09:33',
  ),
  22 => 
  array (
    'id' => 29,
    'parent_id' => NULL,
    'menu_name' => 'payment',
    'icon_class' => 'fa fa-money',
    'show_menu' => 'Y',
    'routes' => 'Payments',
    'sort_order' => 7,
    'updated_at' => '2026-05-21 06:53:01',
    'created_at' => '2025-03-24 06:51:38',
  ),
  23 => 
  array (
    'id' => 30,
    'parent_id' => 44,
    'menu_name' => 'Review',
    'icon_class' => 'fa fa-list',
    'show_menu' => 'Y',
    'routes' => 'Review',
    'sort_order' => 0,
    'updated_at' => '2026-07-16 12:25:50',
    'created_at' => '2025-04-23 12:34:37',
  ),
  24 => 
  array (
    'id' => 31,
    'parent_id' => 44,
    'menu_name' => 'Whatsapp',
    'icon_class' => 'chat',
    'show_menu' => 'Y',
    'routes' => 'fa fa-whatsapp',
    'sort_order' => 0,
    'updated_at' => '2026-07-16 08:29:17',
    'created_at' => '2025-05-24 07:37:55',
  ),
  25 => 
  array (
    'id' => 32,
    'parent_id' => 44,
    'menu_name' => 'Task Reports',
    'icon_class' => 'fa fa-address-book',
    'show_menu' => 'Y',
    'routes' => 'task-reports',
    'sort_order' => 0,
    'updated_at' => '2026-07-16 12:25:50',
    'created_at' => '2025-05-28 05:40:09',
  ),
  26 => 
  array (
    'id' => 33,
    'parent_id' => 44,
    'menu_name' => 'Rask Review',
    'icon_class' => 'fa fa-address-book',
    'show_menu' => 'Y',
    'routes' => 'task-review',
    'sort_order' => 0,
    'updated_at' => '2026-07-16 12:25:50',
    'created_at' => '2025-05-28 05:41:14',
  ),
  27 => 
  array (
    'id' => 34,
    'parent_id' => 44,
    'menu_name' => 'Admin Task Report',
    'icon_class' => 'fa fa-address-book',
    'show_menu' => 'Y',
    'routes' => 'admin-task-report',
    'sort_order' => 0,
    'updated_at' => '2026-07-16 12:25:50',
    'created_at' => '2025-05-28 05:41:37',
  ),
  28 => 
  array (
    'id' => 35,
    'parent_id' => 44,
    'menu_name' => 'Career Form',
    'icon_class' => 'fa fa-user',
    'show_menu' => 'Y',
    'routes' => 'career',
    'sort_order' => 0,
    'updated_at' => '2026-07-16 12:25:50',
    'created_at' => '2025-12-09 07:46:01',
  ),
  29 => 
  array (
    'id' => 36,
    'parent_id' => NULL,
    'menu_name' => 'Wallet',
    'icon_class' => 'fa fa-wallet',
    'show_menu' => 'Y',
    'routes' => 'admin/wallet/bulk-credit',
    'sort_order' => 0,
    'updated_at' => '2026-07-16 12:25:50',
    'created_at' => '2025-12-16 05:26:31',
  ),
  30 => 
  array (
    'id' => 37,
    'parent_id' => 44,
    'menu_name' => 'order',
    'icon_class' => 'fa fa-list',
    'show_menu' => 'Y',
    'routes' => 'order',
    'sort_order' => 0,
    'updated_at' => '2026-07-16 12:25:50',
    'created_at' => '2026-03-25 05:47:01',
  ),
  31 => 
  array (
    'id' => 38,
    'parent_id' => 44,
    'menu_name' => 'Prime Assignment',
    'icon_class' => 'fa fa-list',
    'show_menu' => 'Y',
    'routes' => 'prime',
    'sort_order' => 0,
    'updated_at' => '2026-07-16 12:25:50',
    'created_at' => '2026-04-15 13:57:31',
  ),
  32 => 
  array (
    'id' => 39,
    'parent_id' => 44,
    'menu_name' => 'Smart Assignment',
    'icon_class' => 'fa fa-list',
    'show_menu' => 'Y',
    'routes' => 'smart',
    'sort_order' => 0,
    'updated_at' => '2026-07-16 12:25:50',
    'created_at' => '2026-04-16 05:23:28',
  ),
  33 => 
  array (
    'id' => 40,
    'parent_id' => NULL,
    'menu_name' => 'Reports',
    'icon_class' => 'fa fa-list',
    'show_menu' => 'Y',
    'routes' => 'report',
    'sort_order' => 10,
    'updated_at' => '2026-05-21 07:00:04',
    'created_at' => '2026-05-12 12:52:20',
  ),
  34 => 
  array (
    'id' => 41,
    'parent_id' => 44,
    'menu_name' => 'Feedbacks',
    'icon_class' => 'fa fa-list',
    'show_menu' => 'Y',
    'routes' => 'feedback-list',
    'sort_order' => 9,
    'updated_at' => '2026-07-16 08:22:33',
    'created_at' => '2026-05-21 07:01:47',
  ),
  35 => 
  array (
    'id' => 42,
    'parent_id' => NULL,
    'menu_name' => 'Delevery Followups',
    'icon_class' => 'fa fa-list',
    'show_menu' => 'Y',
    'routes' => 'order-feedback',
    'sort_order' => 7,
    'updated_at' => '2026-05-21 07:38:46',
    'created_at' => '2026-05-21 07:24:18',
  ),
  36 => 
  array (
    'id' => 43,
    'parent_id' => NULL,
    'menu_name' => 'Writer',
    'icon_class' => 'fa fa-user',
    'show_menu' => 'Y',
    'routes' => 'writer',
    'sort_order' => 11,
    'updated_at' => '2026-05-21 07:28:44',
    'created_at' => '2026-05-21 07:28:44',
  ),
  37 => 
  array (
    'id' => 44,
    'parent_id' => NULL,
    'menu_name' => 'Other',
    'icon_class' => 'fa fa-bars',
    'show_menu' => 'Y',
    'routes' => 'other',
    'sort_order' => 100,
    'updated_at' => '2026-06-24 07:26:35',
    'created_at' => '2026-06-24 07:26:35',
  ),
  38 => 
  array (
    'id' => 45,
    'parent_id' => NULL,
    'menu_name' => 'Revoked Payments',
    'icon_class' => 'fa fa-list',
    'show_menu' => 'Y',
    'routes' => 'revoke-payments',
    'sort_order' => 8,
    'updated_at' => '2026-07-16 08:25:47',
    'created_at' => '2026-07-16 08:25:47',
  ),
  39 => 
  array (
    'id' => 46,
    'parent_id' => NULL,
    'menu_name' => 'Prefix',
    'icon_class' => 'fa fa-book',
    'show_menu' => 'Y',
    'routes' => 'subjects',
    'sort_order' => 10,
    'updated_at' => '2026-07-16 08:39:37',
    'created_at' => '2026-07-16 08:39:37',
  ),
  40 => 
  array (
    'id' => 47,
    'parent_id' => NULL,
    'menu_name' => 'Dynamic Pages',
    'icon_class' => 'fa fa-file-text',
    'show_menu' => 'Y',
    'routes' => 'service-pages',
    'sort_order' => 11,
    'updated_at' => '2026-07-16 08:40:28',
    'created_at' => '2026-07-16 08:40:28',
  ),
  41 => 
  array (
    'id' => 48,
    'parent_id' => NULL,
    'menu_name' => 'Subject Pages',
    'icon_class' => 'fa fa-graduation-cap',
    'show_menu' => 'Y',
    'routes' => 'subject-pages',
    'sort_order' => 12,
    'updated_at' => '2026-07-16 08:41:13',
    'created_at' => '2026-07-16 08:41:13',
  ),
);

        $submenus = array (
  0 => 
  array (
    'id' => 1,
    'sub_menu_name' => 'Menu',
    'menus_id' => 2,
    'routes' => 'menus',
    'sort_order' => 1,
    'show' => 'Y',
    'created_at' => '2023-12-16 09:40:27',
    'updated_at' => '2026-05-21 07:41:34',
  ),
  1 => 
  array (
    'id' => 2,
    'sub_menu_name' => 'submenus',
    'menus_id' => 2,
    'routes' => 'submenu',
    'sort_order' => 2,
    'show' => 'Y',
    'created_at' => '2023-12-16 10:09:26',
    'updated_at' => '2026-05-21 07:41:57',
  ),
  2 => 
  array (
    'id' => 3,
    'sub_menu_name' => 'User Right',
    'menus_id' => 2,
    'routes' => 'userright',
    'sort_order' => 3,
    'show' => 'Y',
    'created_at' => '2023-12-16 11:20:40',
    'updated_at' => '2026-05-21 07:42:13',
  ),
  3 => 
  array (
    'id' => 4,
    'sub_menu_name' => 'User List',
    'menus_id' => 4,
    'routes' => 'user',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2023-12-16 11:41:03',
    'updated_at' => '2023-12-16 11:41:03',
  ),
  4 => 
  array (
    'id' => 5,
    'sub_menu_name' => 'New User',
    'menus_id' => 4,
    'routes' => 'usercreate',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2023-12-18 08:56:47',
    'updated_at' => '2023-12-18 08:56:47',
  ),
  5 => 
  array (
    'id' => 6,
    'sub_menu_name' => 'Type Of Service',
    'menus_id' => 3,
    'routes' => 'typeOfSecvices',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2023-12-19 07:44:09',
    'updated_at' => '2023-12-19 07:45:32',
  ),
  6 => 
  array (
    'id' => 7,
    'sub_menu_name' => 'Formatting',
    'menus_id' => 3,
    'routes' => 'formatting',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2023-12-19 07:50:02',
    'updated_at' => '2023-12-19 07:50:02',
  ),
  7 => 
  array (
    'id' => 8,
    'sub_menu_name' => 'Categories',
    'menus_id' => 3,
    'routes' => 'Categories',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2023-12-19 07:52:57',
    'updated_at' => '2023-12-28 08:45:51',
  ),
  8 => 
  array (
    'id' => 9,
    'sub_menu_name' => 'Banks',
    'menus_id' => 3,
    'routes' => 'Banks',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2023-12-19 07:53:28',
    'updated_at' => '2023-12-28 08:46:05',
  ),
  9 => 
  array (
    'id' => 10,
    'sub_menu_name' => 'Payments',
    'menus_id' => 3,
    'routes' => 'Payments',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2023-12-19 07:54:02',
    'updated_at' => '2023-12-28 08:46:21',
  ),
  10 => 
  array (
    'id' => 11,
    'sub_menu_name' => 'failedJobs Orders',
    'menus_id' => 3,
    'routes' => 'failedJobs',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2023-12-19 07:55:23',
    'updated_at' => '2023-12-28 08:46:48',
  ),
  11 => 
  array (
    'id' => 13,
    'sub_menu_name' => 'Type of  Paper',
    'menus_id' => 3,
    'routes' => 'typeofpaper',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2023-12-20 04:47:39',
    'updated_at' => '2023-12-20 04:47:39',
  ),
  12 => 
  array (
    'id' => 14,
    'sub_menu_name' => 'writer Team Leader',
    'menus_id' => 3,
    'routes' => 'writerTL',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2024-01-19 10:49:59',
    'updated_at' => '2024-01-19 10:49:59',
  ),
  13 => 
  array (
    'id' => 15,
    'sub_menu_name' => 'Writer Admin',
    'menus_id' => 3,
    'routes' => 'writer',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2024-01-24 04:10:50',
    'updated_at' => '2024-01-24 04:10:50',
  ),
  14 => 
  array (
    'id' => 16,
    'sub_menu_name' => 'writer Team Leader',
    'menus_id' => 3,
    'routes' => 'writerTL',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2024-01-24 04:11:25',
    'updated_at' => '2024-01-24 04:11:25',
  ),
  15 => 
  array (
    'id' => 17,
    'sub_menu_name' => 'Sub Writer',
    'menus_id' => 3,
    'routes' => 'subwriter',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2024-01-24 04:11:47',
    'updated_at' => '2024-01-24 04:11:47',
  ),
  16 => 
  array (
    'id' => 18,
    'sub_menu_name' => 'College',
    'menus_id' => 3,
    'routes' => 'college',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2024-01-25 14:29:43',
    'updated_at' => '2024-01-25 14:29:43',
  ),
  17 => 
  array (
    'id' => 19,
    'sub_menu_name' => 'New Blog',
    'menus_id' => 15,
    'routes' => 'write_blog',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2024-02-24 08:47:31',
    'updated_at' => '2024-02-24 08:47:31',
  ),
  18 => 
  array (
    'id' => 20,
    'sub_menu_name' => 'Blog And Sample List',
    'menus_id' => 15,
    'routes' => 'blog_list',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2024-02-24 08:47:56',
    'updated_at' => '2024-09-04 10:45:44',
  ),
  19 => 
  array (
    'id' => 21,
    'sub_menu_name' => 'New Sample',
    'menus_id' => 15,
    'routes' => 'create_sample',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2024-02-24 08:48:27',
    'updated_at' => '2024-08-01 11:58:40',
  ),
  20 => 
  array (
    'id' => 22,
    'sub_menu_name' => 'Sample List',
    'menus_id' => 16,
    'routes' => 'sample_list',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2024-02-24 08:48:52',
    'updated_at' => '2024-02-24 08:48:52',
  ),
  21 => 
  array (
    'id' => 23,
    'sub_menu_name' => 'Leads',
    'menus_id' => 25,
    'routes' => 'll-leads',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2024-11-07 05:34:20',
    'updated_at' => '2024-11-07 05:34:20',
  ),
  22 => 
  array (
    'id' => 24,
    'sub_menu_name' => 'Cancel Leads',
    'menus_id' => 25,
    'routes' => 'll-c-leads',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2024-11-07 05:35:05',
    'updated_at' => '2024-11-07 05:35:05',
  ),
  23 => 
  array (
    'id' => 25,
    'sub_menu_name' => 'Orders',
    'menus_id' => 25,
    'routes' => 'll-orders',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2024-11-07 05:35:46',
    'updated_at' => '2024-11-07 05:35:46',
  ),
  24 => 
  array (
    'id' => 26,
    'sub_menu_name' => 'Sample Category',
    'menus_id' => 26,
    'routes' => 'free-sample',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2024-11-29 09:34:30',
    'updated_at' => '2024-11-29 09:34:30',
  ),
  25 => 
  array (
    'id' => 27,
    'sub_menu_name' => 'Sample Type',
    'menus_id' => 26,
    'routes' => 'free-samples-type',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2024-11-29 09:35:07',
    'updated_at' => '2024-11-29 09:35:07',
  ),
  26 => 
  array (
    'id' => 28,
    'sub_menu_name' => 'Sample List',
    'menus_id' => 26,
    'routes' => 'samples',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2024-11-29 09:35:28',
    'updated_at' => '2024-11-29 09:35:28',
  ),
  27 => 
  array (
    'id' => 29,
    'sub_menu_name' => 'New Sample',
    'menus_id' => 26,
    'routes' => 'free-sample-write',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2024-11-29 09:35:56',
    'updated_at' => '2024-11-29 09:35:56',
  ),
  28 => 
  array (
    'id' => 30,
    'sub_menu_name' => 'New Expert',
    'menus_id' => 27,
    'routes' => 'create-expert',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2025-01-04 05:52:25',
    'updated_at' => '2025-01-04 05:53:32',
  ),
  29 => 
  array (
    'id' => 31,
    'sub_menu_name' => 'Expert list',
    'menus_id' => 27,
    'routes' => 'new-expert',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2025-01-04 05:53:22',
    'updated_at' => '2025-01-04 05:53:22',
  ),
  30 => 
  array (
    'id' => 32,
    'sub_menu_name' => 'Faq',
    'menus_id' => 28,
    'routes' => 'faqurl',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2025-01-04 06:09:56',
    'updated_at' => '2025-01-04 06:09:56',
  ),
  31 => 
  array (
    'id' => 33,
    'sub_menu_name' => 'New Faq',
    'menus_id' => 28,
    'routes' => 'newfaq',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2025-01-04 06:11:54',
    'updated_at' => '2025-01-04 06:11:54',
  ),
  32 => 
  array (
    'id' => 34,
    'sub_menu_name' => 'New Review',
    'menus_id' => 30,
    'routes' => 'review-create',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2025-04-23 12:35:09',
    'updated_at' => '2025-04-23 12:35:09',
  ),
  33 => 
  array (
    'id' => 35,
    'sub_menu_name' => 'Review List',
    'menus_id' => 30,
    'routes' => 'review-list',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2025-04-23 12:35:29',
    'updated_at' => '2025-04-23 12:35:29',
  ),
  34 => 
  array (
    'id' => 36,
    'sub_menu_name' => 'Whatsapp',
    'menus_id' => 31,
    'routes' => 'chat',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2025-05-24 07:38:33',
    'updated_at' => '2025-06-09 10:32:11',
  ),
  35 => 
  array (
    'id' => 37,
    'sub_menu_name' => 'Campaigns',
    'menus_id' => 31,
    'routes' => 'campaigns',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2025-05-24 07:38:59',
    'updated_at' => '2025-05-24 07:38:59',
  ),
  36 => 
  array (
    'id' => 38,
    'sub_menu_name' => 'Template',
    'menus_id' => 31,
    'routes' => 'template-message',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2025-05-24 07:39:29',
    'updated_at' => '2025-05-24 07:39:29',
  ),
  37 => 
  array (
    'id' => 39,
    'sub_menu_name' => 'Chat',
    'menus_id' => 31,
    'routes' => 'chat-setting',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2025-05-24 07:40:04',
    'updated_at' => '2025-05-24 07:40:04',
  ),
  38 => 
  array (
    'id' => 40,
    'sub_menu_name' => 'User Attribute',
    'menus_id' => 31,
    'routes' => 'user-attributes',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2025-05-24 13:44:49',
    'updated_at' => '2025-05-24 13:44:49',
  ),
  39 => 
  array (
    'id' => 41,
    'sub_menu_name' => 'Canned Message',
    'menus_id' => 31,
    'routes' => 'canned-messages',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2025-05-24 13:45:17',
    'updated_at' => '2025-05-24 13:45:17',
  ),
  40 => 
  array (
    'id' => 42,
    'sub_menu_name' => 'Agents',
    'menus_id' => 31,
    'routes' => 'agents',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2025-05-24 13:45:36',
    'updated_at' => '2025-05-24 13:45:36',
  ),
  41 => 
  array (
    'id' => 43,
    'sub_menu_name' => 'Tags',
    'menus_id' => 31,
    'routes' => 'tags',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2025-05-24 13:46:02',
    'updated_at' => '2025-05-24 13:46:02',
  ),
  42 => 
  array (
    'id' => 44,
    'sub_menu_name' => 'Login History',
    'menus_id' => 4,
    'routes' => 'login-history',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2025-08-01 07:28:28',
    'updated_at' => '2025-08-01 07:28:28',
  ),
  43 => 
  array (
    'id' => 45,
    'sub_menu_name' => 'Leads',
    'menus_id' => 38,
    'routes' => 'prime-leads',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2026-04-16 05:26:12',
    'updated_at' => '2026-04-16 05:26:12',
  ),
  44 => 
  array (
    'id' => 46,
    'sub_menu_name' => 'Leads',
    'menus_id' => 39,
    'routes' => 'smart-leads',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2026-04-16 05:26:57',
    'updated_at' => '2026-04-16 05:26:57',
  ),
  45 => 
  array (
    'id' => 47,
    'sub_menu_name' => 'Cancel Leads',
    'menus_id' => 39,
    'routes' => 'smart-cancelled',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2026-04-16 05:28:10',
    'updated_at' => '2026-04-16 06:03:47',
  ),
  46 => 
  array (
    'id' => 48,
    'sub_menu_name' => 'Orders',
    'menus_id' => 39,
    'routes' => 'smart-orders',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2026-04-16 05:30:00',
    'updated_at' => '2026-04-16 06:04:21',
  ),
  47 => 
  array (
    'id' => 49,
    'sub_menu_name' => 'Cancel Leads',
    'menus_id' => 38,
    'routes' => 'prime-cancelled',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2026-04-16 05:33:06',
    'updated_at' => '2026-04-16 06:02:42',
  ),
  48 => 
  array (
    'id' => 50,
    'sub_menu_name' => 'Orders',
    'menus_id' => 38,
    'routes' => 'prime-orders',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2026-04-16 05:33:49',
    'updated_at' => '2026-04-16 06:03:16',
  ),
  49 => 
  array (
    'id' => 51,
    'sub_menu_name' => 'Group Master',
    'menus_id' => 3,
    'routes' => 'group-master',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2026-05-12 09:08:00',
    'updated_at' => '2026-05-12 09:10:03',
  ),
  50 => 
  array (
    'id' => 52,
    'sub_menu_name' => 'Revenue Report',
    'menus_id' => 40,
    'routes' => 'user/report-list',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2026-05-12 12:58:36',
    'updated_at' => '2026-05-12 12:58:36',
  ),
  51 => 
  array (
    'id' => 53,
    'sub_menu_name' => 'Ticket Report',
    'menus_id' => 40,
    'routes' => 'admin/ticket-report',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2026-05-12 13:01:52',
    'updated_at' => '2026-05-12 13:01:52',
  ),
  52 => 
  array (
    'id' => 54,
    'sub_menu_name' => 'Delevery Followups',
    'menus_id' => 40,
    'routes' => 'user-report',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2026-05-12 13:03:02',
    'updated_at' => '2026-05-12 13:03:02',
  ),
  53 => 
  array (
    'id' => 55,
    'sub_menu_name' => 'User Refer Report',
    'menus_id' => 40,
    'routes' => 'refer-user-report',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2026-05-13 09:53:31',
    'updated_at' => '2026-05-13 09:53:31',
  ),
  54 => 
  array (
    'id' => 56,
    'sub_menu_name' => 'Module code',
    'menus_id' => 40,
    'routes' => 'module-code-report',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2026-05-14 09:19:22',
    'updated_at' => '2026-05-14 09:19:22',
  ),
  55 => 
  array (
    'id' => 57,
    'sub_menu_name' => 'University Report',
    'menus_id' => 40,
    'routes' => 'university-report',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2026-05-15 07:12:01',
    'updated_at' => '2026-05-15 07:12:01',
  ),
  56 => 
  array (
    'id' => 58,
    'sub_menu_name' => 'Feedbacks',
    'menus_id' => NULL,
    'routes' => 'feedback-list',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2026-05-15 12:10:33',
    'updated_at' => '2026-05-15 12:10:33',
  ),
  57 => 
  array (
    'id' => 59,
    'sub_menu_name' => 'Qc Sheet',
    'menus_id' => 43,
    'routes' => 'Qc-Sheets',
    'sort_order' => 1,
    'show' => 'Y',
    'created_at' => '2026-05-21 07:30:59',
    'updated_at' => '2026-05-21 07:30:59',
  ),
  58 => 
  array (
    'id' => 60,
    'sub_menu_name' => 'Writer order checker',
    'menus_id' => 43,
    'routes' => 'order-writer',
    'sort_order' => 3,
    'show' => 'Y',
    'created_at' => '2026-05-21 07:32:07',
    'updated_at' => '2026-05-21 07:32:07',
  ),
  59 => 
  array (
    'id' => 61,
    'sub_menu_name' => 'Writer Available',
    'menus_id' => 43,
    'routes' => 'writer-available',
    'sort_order' => 3,
    'show' => 'Y',
    'created_at' => '2026-05-21 07:33:51',
    'updated_at' => '2026-05-21 07:33:51',
  ),
  60 => 
  array (
    'id' => 62,
    'sub_menu_name' => 'Follow Up Report',
    'menus_id' => 40,
    'routes' => 'follow-up-report',
    'sort_order' => 5,
    'show' => 'Y',
    'created_at' => '2026-06-05 06:33:56',
    'updated_at' => '2026-06-05 06:33:56',
  ),
  61 => 
  array (
    'id' => 63,
    'sub_menu_name' => 'Revoke Payment',
    'menus_id' => 40,
    'routes' => 'my-revoke-payments',
    'sort_order' => 0,
    'show' => 'Y',
    'created_at' => '2026-06-06 05:35:03',
    'updated_at' => '2026-06-06 05:35:03',
  ),
  62 => 
  array (
    'id' => 64,
    'sub_menu_name' => 'Revoke Payment',
    'menus_id' => 40,
    'routes' => 'revoke-payments',
    'sort_order' => 0,
    'show' => NULL,
    'created_at' => '2026-06-06 10:53:30',
    'updated_at' => '2026-06-06 10:53:30',
  ),
  63 => 
  array (
    'id' => 65,
    'sub_menu_name' => 'Source',
    'menus_id' => 3,
    'routes' => 'sources',
    'sort_order' => 1,
    'show' => 'Y',
    'created_at' => '2026-06-24 07:26:35',
    'updated_at' => '2026-06-24 07:26:35',
  ),
);

        foreach ($menus as $menu) {
            DB::table('menu')->insert($menu);
        }

        foreach ($submenus as $submenu) {
            DB::table('submenus')->insert($submenu);
        }

        Schema::enableForeignKeyConstraints();
    }
}
