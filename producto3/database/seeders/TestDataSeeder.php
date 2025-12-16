<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Hotel;
use App\Models\HotelUser;
use App\Models\Reservation;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        $zonas = ['Norte', 'Sur', 'Este', 'Oeste', 'Centro'];
        $hoteles = [];

        foreach ($zonas as $zona) {
            $hotel = Hotel::create([
                'name' => 'Hotel ' . $zona,
                'address' => 'Calle ' . $zona . ' 123',
                'city' => 'Barcelona',
                'zona' => $zona,
                'comision_porcentaje' => rand(10, 20),
            ]);

            $user = User::create([
                'name' => 'Usuario Hotel ' . $zona,
                'email' => strtolower($zona) . '@hotel.com',
                'password' => Hash::make('password'),
                'role' => 'hotel',
                'hotel_id' => $hotel->id,
            ]);

            $hotelUser = HotelUser::create([
                'hotel_id' => $hotel->id,
                'name' => 'Usuario Hotel ' . $zona,
                'email' => strtolower($zona) . '_user@hotel.com',
                'password' => Hash::make('password'),
            ]);

            $hoteles[] = ['hotel' => $hotel, 'user' => $user, 'hotelUser' => $hotelUser];
        }

        foreach ($hoteles as $item) {
            for ($i = 0; $i < rand(5, 10); $i++) {
                $checkIn = now()->subDays(rand(1, 60));
                $checkOut = $checkIn->copy()->addDays(rand(2, 7));

                Reservation::create([
                    'hotel_id' => $item['hotel']->id,
                    'hotel_user_id' => $item['hotelUser']->id,
                    'check_in' => $checkIn,
                    'check_out' => $checkOut,
                    'guests' => rand(1, 4),
                    'total_price' => rand(100, 500),
                    'status' => 'confirmada',
                ]);
            }
        }
    }
}
