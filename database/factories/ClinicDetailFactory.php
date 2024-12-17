<?php

namespace Database\Factories;

use App\Models\ClinicDetail;
use App\Models\MfClinicDetails; // Replace with your model's namespace
use App\Models\User; // Import the User model
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ClinicDetailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ClinicDetail::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Fetch a random admin user if it exists, otherwise do not create a clinic
        $adminUser = User::where('user_type', 'admin')->inRandomOrder()->first();

        // If no admin user is found, prevent the creation of a clinic
        if (!$adminUser) {
            throw new ModelNotFoundException('No admin user found, unable to create clinic.');
        }

        return [
            'user_id' => $adminUser->id, // Assign the random admin user's ID
            'clinic_name' => $this->faker->company,
            'clinic_tag_line' => $this->faker->catchPhrase,
            'contact_no_1' => $this->faker->regexify('[0-9]{10}'),
            'contact_no_2' => $this->faker->regexify('[0-9]{10}'),
            'gstin' => $this->faker->optional()->regexify('[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}[Z]{1}[0-9A-Z]{1}'),
            'about_clinic' => $this->faker->optional()->text(200),
            'web_address' => $this->faker->optional()->url,

            'address' => $this->faker->address,
            'country' => $this->faker->country,
            'state' => $this->faker->state,
            'district' => $this->faker->city,
            'locality' => $this->faker->streetName,
            'pincode' => $this->faker->regexify('[0-9]{6}'),
            'longitude' => $this->faker->longitude,
            'latitude' => $this->faker->latitude,

            'fees_based_on' => $this->faker->randomElement(['nofee', 'specificationBased', 'clinicBased']),
            'consultation_fee' => $this->faker->optional()->numberBetween(100, 1000),


            'status' => $this->faker->randomElement(['0', '1']),
            'added_by' => $adminUser->id, // Use the admin user's ID
            'modified_by' => $this->faker->optional()->randomDigitNotNull,
            'ip_address' => $this->faker->ipv4,
            'ip_modified' => $this->faker->optional()->ipv4,

            'communication_email' => $this->faker->optional()->safeEmail,
            'communication_contact_number' => $this->faker->optional()->regexify('[0-9]{10}'),

        ];
    }
}
