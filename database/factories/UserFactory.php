<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),

            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'username' => $this->faker->userName(),
            'password' => Hash::make('faker'),
            'department' => 'City Information and Communications Technology Office (CICTO)',
            'role' => 'CICTO Head',
            'permissions' => json_encode(["purchaseRequestHasView","purchaseRequestHasSend","purchaseRequestHasUpdate","dashboardHasOfficeRequestChart","dashboardHasApprovalChart","dashboardHasRequestCards","purchaseRequestHasRemove","purchaseRequestHasRequestItems","purchaseRequestHasManageAttachments","managePpmpHasView","managePpmpHasAdd","managePpmpHasUpdate","managePpmpHasRemove","managePpmpHasManageItems","managePpmpHasManageAttachments","ppmpItemsMasterListHasView"]),
            'status' => 'Active',
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
