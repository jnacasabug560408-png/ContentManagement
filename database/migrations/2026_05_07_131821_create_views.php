<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE OR REPLACE VIEW view_admin_content_control AS
            SELECT c.id, c.title, c.status, c.created_at,
                   u.name AS author_name, u.role AS author_role,
                   cat.name AS category_name
            FROM contents c
            LEFT JOIN users u ON c.user_id = u.id
            LEFT JOIN categories cat ON c.category_id = cat.id
        ");

        DB::statement("
            CREATE OR REPLACE VIEW view_category_stats AS
            SELECT cat.id, cat.name AS category_name,
                   COUNT(c.id) AS total_contents,
                   SUM(CASE WHEN c.status = 'published' THEN 1 ELSE 0 END) AS published_count,
                   SUM(CASE WHEN c.status = 'draft' THEN 1 ELSE 0 END) AS draft_count
            FROM categories cat
            LEFT JOIN contents c ON cat.id = c.category_id
            GROUP BY cat.id, cat.name
        ");

        DB::statement("
            CREATE OR REPLACE VIEW view_published_posts AS
            SELECT c.id, c.title, c.slug, c.body, c.created_at, c.published_at,
                   u.name AS author_name, cat.name AS category_name
            FROM contents c
            LEFT JOIN users u ON c.user_id = u.id
            LEFT JOIN categories cat ON c.category_id = cat.id
            WHERE c.status = 'published'
        ");
    }

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS view_admin_content_control");
        DB::statement("DROP VIEW IF EXISTS view_category_stats");
        DB::statement("DROP VIEW IF EXISTS view_published_posts");
    }
};