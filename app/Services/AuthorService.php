<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Cache;

class AuthorService
{
    const CACHE_KEY = 'blog_authors_grouped_v1';
    const CACHE_TTL_SECONDS = 300; // 5 minutes

    /**
     * Ensure the author table has the required columns and syncs all experts and writers.
     */
    public static function syncAll(): void
    {
        // 1. Ensure table columns exist
        self::ensureColumnsExist();

        // 2. Map existing Kate Williams (id 14) to expert 267 if present
        $kate = DB::table('author')->where('id', 14)->first();
        if ($kate && (empty($kate->expert_id) || empty($kate->author_type))) {
            DB::table('author')->where('id', 14)->update([
                'expert_id' => 267,
                'author_type' => 'expert',
                'updated_at' => now(),
            ]);
        }

        // 3. Sync all Expert Writers from `expert` table
        if (Schema::hasTable('expert')) {
            $experts = DB::table('expert')->get();
            foreach ($experts as $expert) {
                $exists = DB::table('author')
                    ->where('expert_id', $expert->id)
                    ->orWhere(function ($q) use ($expert) {
                        $q->where('name', $expert->name)->where('author_type', 'expert');
                    })
                    ->first();

                if (!$exists) {
                    $cleanDesc = trim(strip_tags($expert->content ?? ''));
                    if (mb_strlen($cleanDesc) > 500) {
                        $cleanDesc = mb_substr($cleanDesc, 0, 497) . '...';
                    }

                    DB::table('author')->insert([
                        'name' => $expert->name,
                        'photo' => $expert->image ?: null,
                        'description' => $cleanDesc ?: 'Subject Expert Writer at Assignment in Need',
                        'author_type' => 'expert',
                        'expert_id' => $expert->id,
                        'user_id' => null,
                        'created_at' => $expert->created_at ?? now(),
                        'updated_at' => now(),
                    ]);
                } else if (empty($exists->expert_id)) {
                    DB::table('author')->where('id', $exists->id)->update([
                        'expert_id' => $expert->id,
                        'author_type' => 'expert',
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        // 4. Sync all Active Staff/CRM Writers from `users` table
        if (Schema::hasTable('users')) {
            $writers = DB::table('users')
                ->whereIn('role_id', [6, 7, 8, 10]) // 6: WriterTL, 7: Sub Writer, 8: Writer Admin, 10: SEO/Content
                ->where('flag', 0)
                ->whereNotNull('name')
                ->where('name', '!=', '')
                ->get();

            foreach ($writers as $writer) {
                $exists = DB::table('author')
                    ->where('user_id', $writer->id)
                    ->orWhere(function ($q) use ($writer) {
                        $q->where('name', $writer->name)->where('author_type', 'writer');
                    })
                    ->first();

                if (!$exists) {
                    $desc = 'Academic Writer at Assignment in Need';
                    if ((int)$writer->role_id === 6) {
                        $desc = 'Senior Academic Lead & Writer';
                    } elseif ((int)$writer->role_id === 10) {
                        $desc = 'Content & SEO Research Writer';
                    }

                    DB::table('author')->insert([
                        'name' => $writer->name,
                        'photo' => $writer->photo ?: 'assets/media/avatars/blank.png',
                        'description' => $desc,
                        'author_type' => 'writer',
                        'expert_id' => null,
                        'user_id' => $writer->id,
                        'created_at' => $writer->created_at ?? now(),
                        'updated_at' => now(),
                    ]);
                } else if (empty($exists->user_id)) {
                    DB::table('author')->where('id', $exists->id)->update([
                        'user_id' => $writer->id,
                        'author_type' => 'writer',
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        self::clearCache();
    }

    /**
     * Ensure database columns exist on the author table.
     */
    public static function ensureColumnsExist(): void
    {
        if (!Schema::hasTable('author')) {
            return;
        }

        if (!Schema::hasColumn('author', 'author_type')) {
            Schema::table('author', function (Blueprint $table) {
                $table->string('author_type', 50)->default('author')->after('description');
            });
        }

        if (!Schema::hasColumn('author', 'expert_id')) {
            Schema::table('author', function (Blueprint $table) {
                $table->integer('expert_id')->nullable()->after('author_type');
            });
        }

        if (!Schema::hasColumn('author', 'user_id')) {
            Schema::table('author', function (Blueprint $table) {
                $table->integer('user_id')->nullable()->after('expert_id');
            });
        }
    }

    /**
     * Clear the cached authors list.
     */
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Get all authors grouped for the dropdown.
     *
     * @return array{authors: \Illuminate\Support\Collection, expertAuthors: \Illuminate\Support\Collection, writerAuthors: \Illuminate\Support\Collection, otherAuthors: \Illuminate\Support\Collection}
     */
    public static function getAuthorsGrouped(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL_SECONDS, function () {
            // If table has less than 2 entries (e.g. initial state on a new/live server), trigger sync
            if (Schema::hasTable('author') && DB::table('author')->count() <= 1) {
                self::syncAll();
            }

            $allAuthors = DB::table('author')
                ->orderBy('name', 'asc')
                ->get();

            $expertAuthors = $allAuthors->where('author_type', 'expert')->values();
            $writerAuthors = $allAuthors->where('author_type', 'writer')->values();
            $otherAuthors = $allAuthors->whereNotIn('author_type', ['expert', 'writer'])->values();

            return [
                'authors' => $allAuthors,
                'expertAuthors' => $expertAuthors,
                'writerAuthors' => $writerAuthors,
                'otherAuthors' => $otherAuthors,
            ];
        });
    }
}
