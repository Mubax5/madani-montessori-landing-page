<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('admin_users', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('role_id')->constrained('roles')->restrictOnDelete();
            $table->string('name', 120);
            $table->string('email', 150)->unique();
            $table->string('password_hash');
            $table->string('avatar_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('media_assets', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('uploaded_by')->nullable()->constrained('admin_users')->nullOnDelete();
            $table->string('file_name', 180);
            $table->string('file_path');
            $table->string('mime_type', 100);
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('alt_text', 180);
            $table->text('caption')->nullable();
            $table->timestamps();
        });

        Schema::create('pages', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('updated_by')->nullable()->constrained('admin_users')->nullOnDelete();
            $table->string('slug', 120)->unique();
            $table->string('title', 180);
            $table->string('meta_title', 180)->nullable();
            $table->text('meta_description')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('page_sections', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('page_id')->constrained('pages')->cascadeOnDelete();
            $table->foreignId('image_id')->nullable()->constrained('media_assets')->nullOnDelete();
            $table->string('section_key', 120);
            $table->string('section_name', 180);
            $table->string('heading')->nullable();
            $table->text('subheading')->nullable();
            $table->longText('body')->nullable();
            $table->json('payload')->nullable();
            $table->string('cta_label', 120)->nullable();
            $table->string('cta_url')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['page_id', 'section_key']);
        });

        Schema::create('programs', function (Blueprint $table): void {
            $table->id();
            $table->enum('program_type', ['kb', 'tk_a', 'tk_b', 'tk_c']);
            $table->string('name', 150);
            $table->enum('category', ['reguler', 'half_day', 'full_day'])->default('reguler');
            $table->text('description')->nullable();
            $table->string('age_range', 100)->nullable();
            $table->string('duration', 100)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('featured_programs', function (Blueprint $table): void {
            $table->id();
            $table->string('title', 180);
            $table->text('description')->nullable();
            $table->string('icon', 100)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('bimbel_packages', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 150);
            $table->string('slug', 150)->unique();
            $table->text('description')->nullable();
            $table->string('target', 180)->nullable();
            $table->string('cta_label', 120)->nullable();
            $table->text('cta_message')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('bimbel_package_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('package_id')->constrained('bimbel_packages')->cascadeOnDelete();
            $table->string('title', 150);
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('training_events', function (Blueprint $table): void {
            $table->id();
            $table->string('title', 180);
            $table->string('topic', 180)->nullable();
            $table->enum('target_audience', ['guru', 'orang_tua', 'guru_dan_orang_tua'])->default('guru_dan_orang_tua');
            $table->date('event_date')->nullable();
            $table->string('event_time', 80)->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['draft', 'published', 'closed'])->default('draft');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('gallery_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('media_id')->constrained('media_assets')->cascadeOnDelete();
            $table->enum('category', ['sekolah', 'bimbel', 'event'])->default('sekolah');
            $table->string('title', 180)->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('faqs', function (Blueprint $table): void {
            $table->id();
            $table->string('page_scope', 100);
            $table->string('question');
            $table->text('answer');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('leads', function (Blueprint $table): void {
            $table->id();
            $table->string('parent_name', 150);
            $table->string('child_name', 150);
            $table->integer('child_age')->nullable();
            $table->string('selected_program', 100);
            $table->string('whatsapp_number', 30);
            $table->text('note')->nullable();
            $table->string('source_page', 100)->nullable();
            $table->enum('status', ['baru', 'dihubungi', 'follow_up', 'terdaftar', 'batal'])->default('baru');
            $table->timestamps();
        });

        Schema::create('whatsapp_templates', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 150);
            $table->string('template_key', 100)->unique();
            $table->text('message');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('site_settings', function (Blueprint $table): void {
            $table->id();
            $table->string('setting_key', 120)->unique();
            $table->longText('setting_value')->nullable();
            $table->enum('setting_type', ['text', 'textarea', 'image', 'url', 'json', 'color'])->default('text');
            $table->timestamps();
        });

        Schema::create('navigation_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('navigation_items')->nullOnDelete();
            $table->string('label', 120);
            $table->string('url');
            $table->string('location', 40)->default('header');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('admin_user_id')->nullable()->constrained('admin_users')->nullOnDelete();
            $table->string('action', 100);
            $table->string('module', 100);
            $table->unsignedBigInteger('record_id')->nullable();
            $table->json('old_data')->nullable();
            $table->json('new_data')->nullable();
            $table->string('ip_address', 80)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('navigation_items');
        Schema::dropIfExists('site_settings');
        Schema::dropIfExists('whatsapp_templates');
        Schema::dropIfExists('leads');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('gallery_items');
        Schema::dropIfExists('training_events');
        Schema::dropIfExists('bimbel_package_items');
        Schema::dropIfExists('bimbel_packages');
        Schema::dropIfExists('featured_programs');
        Schema::dropIfExists('programs');
        Schema::dropIfExists('page_sections');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('media_assets');
        Schema::dropIfExists('admin_users');
        Schema::dropIfExists('roles');
    }
};
