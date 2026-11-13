<?php

namespace App\Services;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PrimeAssignmentSetupService
{
    protected static bool $initialized = false;

    /**
     * Ensure database tables, sidebar menus, and asset directories exist for Prime Assignment.
     */
    public static function ensureSetup(): void
    {
        if (self::$initialized) {
            return;
        }

        self::$initialized = true;

        try {
            self::ensureTables();
            self::ensureMenus();
            self::cleanUnusedPrimeImages();
        } catch (\Throwable $e) {
            \Log::warning('PrimeAssignmentSetupService failed: ' . $e->getMessage());
        }
    }

    /**
     * Ensure required tables are created.
     */
    protected static function ensureTables(): void
    {
        // 1. Prime Prefixes / Subjects
        if (!Schema::hasTable('prime_subjects')) {
            Schema::create('prime_subjects', function (Blueprint $table) {
                $table->id();
                $table->string('name', 120)->unique();
                $table->string('slug', 191)->index();
                $table->boolean('is_active')->default(true)->index();
                $table->timestamps();
            });
        }

        // 2. Prime Dynamic Service Pages
        if (!Schema::hasTable('prime_service_pages')) {
            Schema::create('prime_service_pages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('prime_subject_id')->nullable()->constrained('prime_subjects')->nullOnDelete();
                $table->string('type', 50)->default('service')->index();
                $table->string('slug', 191)->unique();
                $table->string('meta_title', 255);
                $table->text('meta_description');
                $table->string('hero_heading', 255);
                $table->string('hero_highlight', 255)->nullable();
                $table->string('brand', 50)->nullable();
                $table->string('brand_soft', 50)->nullable();
                $table->json('stats')->nullable();
                $table->json('pricing')->nullable();
                $table->text('hero_content');
                $table->string('section_two_heading', 255)->nullable();
                $table->text('section_two_content')->nullable();
                $table->string('section_three_heading', 255)->nullable();
                $table->text('section_three_content')->nullable();
                $table->string('why_heading', 255)->nullable();
                $table->text('why_subheading')->nullable();
                $table->json('why_items')->nullable();
                $table->text('cta_content')->nullable();
                $table->string('cta_button_label', 100)->nullable();
                $table->string('cta_button_url', 500)->nullable();
                $table->longText('long_content')->nullable();
                $table->json('expert_ids')->nullable();
                $table->json('review_ids')->nullable();
                $table->json('faqs')->nullable();
                $table->json('topics')->nullable();
                $table->boolean('is_published')->default(true)->index();
                $table->timestamps();

                $table->index(['prime_subject_id', 'is_published']);
                $table->index(['type', 'is_published']);
            });
        } else {
            try {
                DB::statement('ALTER TABLE `prime_service_pages` MODIFY `prime_subject_id` BIGINT UNSIGNED NULL');
            } catch (\Throwable $e) {}

            try {
                if (!Schema::hasColumn('prime_service_pages', 'type')) {
                    Schema::table('prime_service_pages', function (Blueprint $table) {
                        $table->string('type', 50)->default('service')->after('prime_subject_id')->index();
                    });
                }
            } catch (\Throwable $e) {}

            try {
                if (!Schema::hasColumn('prime_service_pages', 'topics')) {
                    Schema::table('prime_service_pages', function (Blueprint $table) {
                        $table->json('topics')->nullable()->after('faqs');
                    });
                }
            } catch (\Throwable $e) {}

            try {
                if (!Schema::hasColumn('prime_service_pages', 'brand')) {
                    Schema::table('prime_service_pages', function (Blueprint $table) {
                        $table->string('brand', 50)->nullable()->after('hero_highlight');
                    });
                }
            } catch (\Throwable $e) {}

            try {
                if (!Schema::hasColumn('prime_service_pages', 'brand_soft')) {
                    Schema::table('prime_service_pages', function (Blueprint $table) {
                        $table->string('brand_soft', 50)->nullable()->after('brand');
                    });
                }
            } catch (\Throwable $e) {}

            try {
                if (!Schema::hasColumn('prime_service_pages', 'stats')) {
                    Schema::table('prime_service_pages', function (Blueprint $table) {
                        $table->json('stats')->nullable()->after('brand_soft');
                    });
                }
            } catch (\Throwable $e) {}

            try {
                if (!Schema::hasColumn('prime_service_pages', 'pricing')) {
                    Schema::table('prime_service_pages', function (Blueprint $table) {
                        $table->json('pricing')->nullable()->after('stats');
                    });
                }
            } catch (\Throwable $e) {}

            try {
                // Only default legacy rows where type is completely null or empty
                DB::table('prime_service_pages')
                    ->whereNull('type')
                    ->orWhere('type', '')
                    ->update(['type' => 'service']);
            } catch (\Throwable $e) {}
        }

        // 3. Prime Blogs
        if (!Schema::hasTable('prime_blogs')) {
            Schema::create('prime_blogs', function (Blueprint $table) {
                $table->id();
                $table->string('title', 255);
                $table->string('slug', 191)->unique();
                $table->longText('content');
                $table->string('image', 255)->nullable();
                $table->unsignedBigInteger('author_id')->nullable();
                $table->string('author_name', 150)->nullable();
                $table->string('category', 100)->nullable();
                $table->string('meta_title', 255)->nullable();
                $table->text('meta_description')->nullable();
                $table->text('meta_keywords')->nullable();
                $table->json('faqs')->nullable();
                $table->boolean('is_published')->default(true)->index();
                $table->timestamps();
            });
        }

        // 4. Prime Sample Categories
        if (!Schema::hasTable('prime_sample_categories')) {
            Schema::create('prime_sample_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name', 120)->unique();
                $table->string('slug', 191)->index();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // 5. Prime Samples
        if (!Schema::hasTable('prime_samples')) {
            Schema::create('prime_samples', function (Blueprint $table) {
                $table->id();
                $table->foreignId('category_id')->nullable()->constrained('prime_sample_categories')->nullOnDelete();
                $table->string('title', 255);
                $table->string('slug', 191)->unique();
                $table->longText('content');
                $table->string('meta_title', 255)->nullable();
                $table->text('meta_description')->nullable();
                $table->integer('pages_count')->default(1);
                $table->integer('words_count')->default(250);
                $table->boolean('is_published')->default(true)->index();
                $table->timestamps();

                $table->index(['category_id', 'is_published']);
            });
        }
    }

    /**
     * Ensure submenus exist under Prime Assignment in menu and submenus tables.
     */
    public static function ensureMenus(): void
    {
        if (!Schema::hasTable('menu') || !Schema::hasTable('submenus')) {
            return;
        }

        $primeMenu = DB::table('menu')
            ->where('menu_name', 'Prime Assignment')
            ->orWhere('routes', 'prime')
            ->first();

        if (!$primeMenu) {
            return;
        }

        $now = now();
        $newSubmenuIds = [];

        // Definition of submenus under Prime Assignment
        $definitions = [
            [
                'name'       => 'Prefix',
                'routes'     => 'prime-subjects',
                'sort_order' => 4,
            ],
            [
                'name'       => 'Dynamic Pages',
                'routes'     => 'prime-service-pages',
                'sort_order' => 5,
            ],
            [
                'name'       => 'Blogs',
                'routes'     => 'prime-blogs',
                'sort_order' => 6,
            ],
            [
                'name'       => 'Samples',
                'routes'     => 'prime-samples',
                'sort_order' => 7,
            ],
        ];

        foreach ($definitions as $def) {
            $sub = DB::table('submenus')
                ->where('menus_id', $primeMenu->id)
                ->where(function ($query) use ($def) {
                    $query->where('routes', $def['routes'])
                          ->orWhere('sub_menu_name', $def['name']);
                })
                ->first();

            if (!$sub) {
                $subId = DB::table('submenus')->insertGetId([
                    'sub_menu_name' => $def['name'],
                    'menus_id'      => $primeMenu->id,
                    'routes'        => $def['routes'],
                    'sort_order'    => $def['sort_order'],
                    'show'          => 'Y',
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ]);
            } else {
                $subId = $sub->id;
                if ($sub->routes !== $def['routes'] || $sub->show !== 'Y') {
                    DB::table('submenus')->where('id', $subId)->update([
                        'routes'     => $def['routes'],
                        'show'       => 'Y',
                        'updated_at' => $now,
                    ]);
                }
            }

            $newSubmenuIds[] = (string) $subId;
        }

        // Grant permissions in permission table
        if (Schema::hasTable('permission')) {
            $permissions = DB::table('permission')->get();

            foreach ($permissions as $perm) {
                $menuIds = json_decode((string) $perm->menu_id, true) ?: [];
                $submenuIds = json_decode((string) $perm->submenu_id, true) ?: [];

                $menuIdsStr = array_map('strval', $menuIds);
                $submenuIdsStr = array_map('strval', $submenuIds);

                $shouldHaveAccess = in_array((string) $primeMenu->id, $menuIdsStr) || (int) $perm->role_id === 1;

                if ($shouldHaveAccess) {
                    $dirty = false;

                    foreach ($newSubmenuIds as $subIdStr) {
                        if (!in_array($subIdStr, $submenuIdsStr)) {
                            $submenuIdsStr[] = $subIdStr;
                            $dirty = true;
                        }
                    }

                    if ($dirty) {
                        DB::table('permission')
                            ->where('id', $perm->id)
                            ->update([
                                'submenu_id' => json_encode(array_values(array_unique($submenuIdsStr))),
                            ]);
                    }
                }
            }
        }
    }

    /**
     * Clean up any legacy or unwanted images from prime blogs folders,
     * keeping ONLY the legitimate images that are actually registered in prime_blogs.
     */
    public static function cleanUnusedPrimeImages(): array
    {
        $cleanSubPath = 'assets/media/prime/blogs';
        $parent = dirname(base_path());

        $targetDirs = [
            base_path($cleanSubPath),
            public_path($cleanSubPath),
            base_path('public/' . $cleanSubPath),
        ];

        if (!empty($_SERVER['DOCUMENT_ROOT'])) {
            $targetDirs[] = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . '/' . $cleanSubPath;
        }

        $targetDirs[] = rtrim($parent, '/\\') . '/public_html/' . $cleanSubPath;
        $targetDirs[] = rtrim($parent, '/\\') . '/ain.warrgyizmorsch.com/' . $cleanSubPath;
        $targetDirs = array_values(array_unique(array_filter($targetDirs)));

        // Ensure directories exist with .gitkeep
        foreach ($targetDirs as $dir) {
            if (!file_exists($dir)) {
                @mkdir($dir, 0777, true);
                @chmod($dir, 0777);
            }
            $gitkeep = rtrim($dir, '/\\') . '/.gitkeep';
            if (!file_exists($gitkeep)) {
                @touch($gitkeep);
            }
        }

        // Legitimate images registered in prime_blogs
        $allowedFiles = ['.gitkeep'];
        if (Schema::hasTable('prime_blogs')) {
            $primeImages = DB::table('prime_blogs')
                ->whereNotNull('image')
                ->where('image', '!=', '')
                ->pluck('image');

            foreach ($primeImages as $imagePath) {
                $fileName = basename($imagePath);
                if (!empty($fileName) && $fileName !== '.' && $fileName !== '..') {
                    $allowedFiles[] = $fileName;
                }
            }
        }

        $deleted = [];
        foreach ($targetDirs as $dir) {
            if (is_dir($dir)) {
                $files = @scandir($dir) ?: [];
                foreach ($files as $file) {
                    if ($file === '.' || $file === '..') {
                        continue;
                    }
                    if (!in_array($file, $allowedFiles, true)) {
                        $full = rtrim($dir, '/\\') . '/' . $file;
                        if (is_file($full)) {
                            if (@unlink($full)) {
                                $deleted[] = $file;
                            }
                        }
                    }
                }
            }
        }

        return array_values(array_unique($deleted));
    }
}
