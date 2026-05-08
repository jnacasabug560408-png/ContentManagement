<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // View 1: view_admin_content_control
        // columns: id, title, status, comment_count
        DB::statement("
            CREATE VIEW view_admin_content_control AS
            SELECT 
                n.id,
                n.title,
                n.status,
                COUNT(nc.id) as comment_count
            FROM news n
            LEFT JOIN news_comments nc ON n.id = nc.news_id
            GROUP BY n.id, n.title, n.status
        ");

        // View 2: view_category_stats
        // columns: name, total_posts
        DB::statement("
            CREATE VIEW view_category_stats AS
            SELECT 
                t.name,
                COUNT(n.id) as total_posts
            FROM tags t
            LEFT JOIN news n ON t.id = n.tag_id
            GROUP BY t.name
        ");

        // View 3: view_published_posts
        // columns: id, title, body, author, category
        DB::statement("
            CREATE VIEW view_published_posts AS
            SELECT 
                n.id,
                n.title,
                n.body,
                u.name as author,
                t.name as category
            FROM news n
            LEFT JOIN users u ON n.user_id = u.id
            LEFT JOIN tags t ON n.tag_id = t.id
            WHERE n.status = 'published'
        ");
    }

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS view_admin_content_control");
        DB::statement("DROP VIEW IF EXISTS view_category_stats");
        DB::statement("DROP VIEW IF EXISTS view_published_posts");
    }
};