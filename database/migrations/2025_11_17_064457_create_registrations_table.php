<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('registration_code')->unique();

            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('preferred_name')->nullable();
            $table->string('email')->unique();
            $table->string('alternate_email')->nullable();
            $table->string('phone_country_code', 5)->nullable();
            $table->string('phone_number')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender', 20)->nullable();
            $table->string('marital_status', 25)->nullable();
            $table->string('nationality', 60)->nullable();
            $table->string('identification_type', 40)->nullable();
            $table->string('identification_number')->nullable();
            $table->date('passport_expiry')->nullable();

            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('postal_code', 15)->nullable();
            $table->string('country', 100)->nullable();

            $table->string('company_name')->nullable();
            $table->string('job_title')->nullable();
            $table->unsignedTinyInteger('experience_years')->nullable();
            $table->string('department')->nullable();
            $table->string('industry')->nullable();

            $table->string('dietary_preferences')->nullable();
            $table->string('tshirt_size', 5)->nullable();
            $table->text('accessibility_requirements')->nullable();

            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('emergency_contact_relation')->nullable();

            $table->foreignId('event_id')->nullable()->index();
            $table->string('event_name')->nullable();
            $table->string('event_session')->nullable();
            $table->date('event_date')->nullable();
            $table->string('ticket_type', 50)->nullable();
            $table->decimal('ticket_price', 10, 2)->default(0);
            $table->string('currency', 5)->default('USD');

            $table->string('payment_status', 30)->default('pending');
            $table->string('payment_method', 40)->nullable();
            $table->string('transaction_id')->nullable();
            $table->dateTime('payment_date')->nullable();
            $table->string('discount_code')->nullable();
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('total_paid', 10, 2)->default(0);

            $table->boolean('check_in_status')->default(false);
            $table->dateTime('checked_in_at')->nullable();
            $table->dateTime('badge_printed_at')->nullable();
            $table->string('seat_number', 20)->nullable();

            $table->boolean('marketing_opt_in')->default(false);
            $table->boolean('sms_opt_in')->default(false);
            $table->boolean('email_opt_in')->default(true);

            $table->string('source_channel')->nullable();
            $table->string('referral_code')->nullable();
            $table->unsignedTinyInteger('lead_score')->nullable();
            $table->string('follow_up_status', 40)->nullable();
            $table->string('onboarding_status', 40)->nullable();

            $table->json('custom_fields')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedSmallInteger('attachments_count')->default(0);

            $table->dateTime('last_contacted_at')->nullable();
            $table->foreignId('created_by')->nullable()->index();
            $table->foreignId('updated_by')->nullable();
            $table->dateTime('archived_at')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->index(['email', 'phone_number']);
            $table->index(['event_id', 'event_date']);
            $table->index(['payment_status', 'payment_method']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
