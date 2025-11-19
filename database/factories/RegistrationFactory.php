<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Registration>
 */
class RegistrationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = fake()->randomElement(['male', 'female', 'non-binary', 'prefer_not_to_say']);
        $ticketPrice = fake()->randomFloat(2, 50, 500);
        $discount = fake()->randomFloat(2, 0, 50);
        $tax = round($ticketPrice * 0.12, 2);
        $total = max($ticketPrice - $discount + $tax, 0);
        $paymentStatus = fake()->randomElement(['pending', 'paid', 'refunded', 'failed']);
        $paymentDate = in_array($paymentStatus, ['paid', 'refunded'], true) ? fake()->dateTimeBetween('-6 months', 'now') : null;
        $checkedIn = $paymentStatus === 'paid' && fake()->boolean(60);

        return [
            'uuid' => (string) Str::uuid(),
            'registration_code' => 'REG-' . strtoupper(Str::uuid()->toString()),

            'first_name' => fake()->firstName(),
            'middle_name' => fake()->optional(0.3)->firstName(),
            'last_name' => fake()->lastName(),
            'preferred_name' => fake()->optional()->name(),
            'email' => sprintf('user-%s@example.com', Str::uuid()),
            'alternate_email' => fake()->optional()->safeEmail(),
            'phone_country_code' => '+' . fake()->numberBetween(1, 999),
            'phone_number' => fake()->phoneNumber(),
            'whatsapp_number' => fake()->optional()->phoneNumber(),
            'date_of_birth' => fake()->optional()->dateTimeBetween('-60 years', '-18 years'),
            'gender' => $gender,
            'marital_status' => fake()->randomElement(['single', 'married', 'divorced', 'widowed', null]),
            'nationality' => fake()->country(),
            'identification_type' => fake()->randomElement(['passport', 'national_id', 'driver_license']),
            'identification_number' => strtoupper(fake()->bothify('??######')),
            'passport_expiry' => fake()->optional()->dateTimeBetween('now', '+10 years'),

            'address_line1' => fake()->streetAddress(),
            'address_line2' => fake()->optional()->secondaryAddress(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'postal_code' => fake()->postcode(),
            'country' => fake()->country(),

            'company_name' => fake()->optional()->company(),
            'job_title' => fake()->optional()->jobTitle(),
            'experience_years' => fake()->optional()->numberBetween(0, 40),
            'department' => fake()->optional()->randomElement(['Sales', 'Marketing', 'Engineering', 'Finance', 'HR']),
            'industry' => fake()->optional()->randomElement(['Technology', 'Healthcare', 'Finance', 'Education', 'Manufacturing']),

            'dietary_preferences' => fake()->optional()->randomElement(['vegan', 'vegetarian', 'halal', 'kosher', 'gluten-free']),
            'tshirt_size' => fake()->optional()->randomElement(['XS', 'S', 'M', 'L', 'XL', 'XXL']),
            'accessibility_requirements' => fake()->optional(0.2)->sentence(),

            'emergency_contact_name' => fake()->name(),
            'emergency_contact_phone' => fake()->phoneNumber(),
            'emergency_contact_relation' => fake()->randomElement(['partner', 'friend', 'parent', 'sibling']),

            'event_id' => fake()->numberBetween(1, 25),
            'event_name' => fake()->randomElement(['Global Tech Summit', 'Marketing Masters', 'Startup Weekend', 'Healthcare Expo']),
            'event_session' => fake()->randomElement(['Morning', 'Afternoon', 'Evening']),
            'event_date' => fake()->dateTimeBetween('-1 year', '+1 year'),
            'ticket_type' => fake()->randomElement(['standard', 'vip', 'student', 'press']),
            'ticket_price' => $ticketPrice,
            'currency' => fake()->randomElement(['USD', 'EUR', 'GBP', 'CAD']),

            'payment_status' => $paymentStatus,
            'payment_method' => fake()->randomElement(['credit_card', 'bank_transfer', 'paypal', 'cash']),
            'transaction_id' => $paymentStatus === 'pending' ? null : strtoupper(fake()->bothify('TXN########')),
            'payment_date' => $paymentDate,
            'discount_code' => fake()->optional(0.2)->bothify('DISC-##??'),
            'discount_amount' => $discount,
            'tax_amount' => $tax,
            'total_paid' => $paymentStatus === 'paid' ? $total : 0,

            'check_in_status' => $checkedIn,
            'checked_in_at' => $checkedIn ? fake()->dateTimeBetween($paymentDate ?? '-1 week', 'now') : null,
            'badge_printed_at' => $checkedIn ? fake()->dateTimeBetween('-1 day', 'now') : null,
            'seat_number' => fake()->optional()->bothify('A##'),

            'marketing_opt_in' => fake()->boolean(70),
            'sms_opt_in' => fake()->boolean(40),
            'email_opt_in' => fake()->boolean(80),

            'source_channel' => fake()->randomElement(['website', 'linkedin', 'email', 'partner', 'referral']),
            'referral_code' => fake()->optional()->bothify('REF-###'),
            'lead_score' => fake()->optional()->numberBetween(10, 100),
            'follow_up_status' => fake()->optional()->randomElement(['pending', 'contacted', 'converted']),
            'onboarding_status' => fake()->optional()->randomElement(['not_started', 'in_progress', 'completed']),

            'custom_fields' => fake()->optional()->randomElement([
                [
                    'has_newsletter_subscription' => fake()->boolean(),
                    'preferred_language' => fake()->randomElement(['en', 'es', 'fr']),
                ],
                [
                    'wants_demo' => fake()->boolean(),
                    'budget' => fake()->numberBetween(5000, 25000),
                ],
            ]),
            'notes' => fake()->optional()->paragraph(),
            'attachments_count' => fake()->numberBetween(0, 5),

            'last_contacted_at' => fake()->optional()->dateTimeBetween('-3 months', 'now'),
            'created_by' => fake()->optional()->numberBetween(1, 10),
            'updated_by' => fake()->optional()->numberBetween(1, 10),
            'archived_at' => fake()->optional(0.05)->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
