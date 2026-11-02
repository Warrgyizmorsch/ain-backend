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
            self::syncPrimeBlogImages();
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
                $table->string('slug', 191)->unique();
                $table->string('meta_title', 255);
                $table->text('meta_description');
                $table->string('hero_heading', 255);
                $table->string('hero_highlight', 255)->nullable();
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
                $table->boolean('is_published')->default(false)->index();
                $table->timestamps();

                $table->index(['prime_subject_id', 'is_published']);
            });
        } else {
            try {
                DB::statement('ALTER TABLE `prime_service_pages` MODIFY `prime_subject_id` BIGINT UNSIGNED NULL');
                DB::table('prime_service_pages')->where('is_published', 0)->update(['is_published' => 1]);
                if (Schema::hasTable('prime_blogs')) {
                    DB::table('prime_blogs')->where('is_published', 0)->update(['is_published' => 1]);
                }
                if (Schema::hasTable('prime_samples')) {
                    DB::table('prime_samples')->where('is_published', 0)->update(['is_published' => 1]);
                }
            } catch (\Throwable $e) {
                // Ignore if already nullable or driver doesn't support modify
            }
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
     * Ensure existing prime blog images are replicated across base_path, public_path, and public_html
     * so they load on live cPanel/Apache setups regardless of docroot configuration.
     */
    public static function syncPrimeBlogImages(): void
    {
        $cleanSubPath = 'assets/media/prime/blogs';
        $parent = dirname(base_path());

        $targetDirs = [
            base_path($cleanSubPath),
            public_path($cleanSubPath),
        ];

        if (!empty($_SERVER['DOCUMENT_ROOT'])) {
            $targetDirs[] = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . '/' . $cleanSubPath;
        }

        $targetDirs[] = rtrim($parent, '/\\') . '/public_html/' . $cleanSubPath;
        $targetDirs[] = rtrim($parent, '/\\') . '/ain.warrgyizmorsch.com/' . $cleanSubPath;
        $targetDirs = array_values(array_unique(array_filter($targetDirs)));

        foreach ($targetDirs as $dir) {
            if (!file_exists($dir)) {
                @mkdir($dir, 0777, true);
                @chmod($dir, 0777);
            }
        }

        // Also check blogthumbnail directory for existing files
        $sourceDirs = $targetDirs;
        $sourceDirs[] = base_path('assets/media/blogthumbnail');
        $sourceDirs[] = public_path('assets/media/blogthumbnail');
        if (!empty($_SERVER['DOCUMENT_ROOT'])) {
            $sourceDirs[] = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . '/assets/media/blogthumbnail';
        }
        $sourceDirs = array_values(array_unique(array_filter($sourceDirs)));

        $allFiles = [];
        foreach ($sourceDirs as $dir) {
            if (is_dir($dir)) {
                $files = @scandir($dir) ?: [];
                foreach ($files as $file) {
                    if ($file === '.' || $file === '..' || $file === '.gitkeep') {
                        continue;
                    }
                    $fullPath = rtrim($dir, '/\\') . '/' . $file;
                    if (is_file($fullPath) && filesize($fullPath) > 0) {
                        if (!isset($allFiles[$file])) {
                            $allFiles[$file] = $fullPath;
                        }
                    }
                }
            }
        }

        foreach ($allFiles as $file => $sourcePath) {
            foreach ($targetDirs as $dir) {
                if (is_dir($dir)) {
                    $destPath = rtrim($dir, '/\\') . '/' . $file;
                    if (!file_exists($destPath) || filesize($destPath) === 0) {
                        @copy($sourcePath, $destPath);
                        @chmod($destPath, 0644);
                    }
                }
            }
        }
    }
}
