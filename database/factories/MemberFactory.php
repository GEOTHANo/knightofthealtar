<?php

namespace Database\Factories;

use App\Enums\Gender;
use App\Enums\MemberStatus;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Member>
 */
class MemberFactory extends Factory
{
    /**
     * Filipino first names for realistic sample data.
     *
     * @var array<int, string>
     */
    private const MALE_FIRST_NAMES = [
        'Juan',
        'Jose',
        'Mark',
        'Paolo',
        'Gabriel',
        'Miguel',
        'Aldrin',
        'Benedict',
        'Christian',
        'Daniel',
    ];

    /**
     * Filipino first names for realistic sample data.
     *
     * @var array<int, string>
     */
    private const FEMALE_FIRST_NAMES = [
        'Maria',
        'Rizalina',
        'Angelica',
        'Catherine',
        'Jessa',
        'Nicole',
        'Patricia',
        'Sofia',
        'Theresa',
        'Ysabelle',
    ];

    /**
     * Common Filipino surnames.
     *
     * @var array<int, string>
     */
    private const LAST_NAMES = [
        'Cruz',
        'Santos',
        'Reyes',
        'Dela Cruz',
        'Garcia',
        'Mendoza',
        'Torres',
        'Navarro',
        'Bautista',
        'Flores',
        'Ramos',
        'Villanueva',
    ];

    /**
     * Catholic communities and parishes used as GKK/BEC examples.
     *
     * @var array<int, string>
     */
    private const GKK_LIST = [
        'St. Joseph GKK',
        'Our Lady of the Rosary BEC',
        'Sto. Nino GKK',
        'San Isidro Labrador BEC',
        'Our Lady of Fatima GKK',
        'St. Michael the Archangel BEC',
        'Sacred Heart GKK',
        'Mary Help of Christians BEC',
    ];

    /**
     * Catholic or private schools used for sample data.
     *
     * @var array<int, string>
     */
    private const SCHOOLS = [
        'Ateneo de Davao University',
        'Ateneo de Manila University',
        'University of San Carlos',
        'University of Santo Tomas',
        'San Pedro College',
        'Holy Cross of Davao College',
        'Xavier University',
        'Immaculate Conception School',
    ];

    /**
     * Typical Filipino occupation examples.
     *
     * @var array<int, string>
     */
    private const OCCUPATIONS = [
        'Teacher',
        'Nurse',
        'Farmer',
        'Store Owner',
        'Driver',
        'Office Staff',
        'Seamstress',
        'Construction Worker',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = fake()->randomElement(Gender::cases());
        $firstName = $gender === Gender::Male
            ? fake()->randomElement(self::MALE_FIRST_NAMES)
            : fake()->randomElement(self::FEMALE_FIRST_NAMES);

        $middleName = fake()->optional(0.8)->randomElement(self::MALE_FIRST_NAMES);
        $lastName = fake()->randomElement(self::LAST_NAMES);

        return [
            'username' => Str::lower(Str::slug($firstName . '.' . $lastName . '.' . fake()->unique()->numerify('####'))),
            'password' => 'password',
            'profile_picture' => fake()->optional(0.4)->randomElement([
                'members/avatars/default-1.jpg',
                'members/avatars/default-2.jpg',
                'members/avatars/default-3.jpg',
            ]),
            'first_name' => $firstName,
            'middle_name' => $middleName,
            'last_name' => $lastName,
            'birth_date' => fake()->dateTimeBetween('-28 years', '-12 years')->format('Y-m-d'),
            'gender' => $gender->value,
            'complete_address' => fake()->streetAddress() . ', ' . fake()->city() . ', ' . fake()->state() . ', Philippines',
            'contact_number' => fake()->numerify('09#########'),
            'email_address' => fake()->unique()->safeEmail(),
            'school_attended' => fake()->optional(0.7)->randomElement(self::SCHOOLS),
            'mother_name' => fake()->name('female'),
            'mother_occupation' => fake()->optional(0.7)->randomElement(self::OCCUPATIONS),
            'father_name' => fake()->name('male'),
            'father_occupation' => fake()->optional(0.7)->randomElement(self::OCCUPATIONS),
            'number_of_siblings' => fake()->numberBetween(0, 6),
            'gkk' => fake()->optional(0.9)->randomElement(self::GKK_LIST),
            'date_of_acceptance' => fake()->dateTimeBetween('-6 years', 'now')->format('Y-m-d'),
            'batch_year' => fake()->numberBetween((int) Carbon::now()->subYears(10)->format('Y'), (int) Carbon::now()->format('Y')),
            'date_added' => now(),
            'status' => fake()->randomElement(MemberStatus::cases())->value,
            'is_deleted' => false,
            'remember_token' => Str::random(10),
        ];
    }
}