<?php

namespace Database\Seeders;

use App\Models\Amenity;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ──────────────────────────────────────────────────
        User::updateOrCreate(
            ['email' => 'admin@hotelcompare.ao'],
            [
                'name'     => 'Administrador',
                'password' => Hash::make('Admin@1234'),
                'role'     => 'admin',
                'is_active'=> true,
            ]
        );

        // ── Utilizador de teste — gestor ───────────────────────────
        User::updateOrCreate(
            ['email' => 'gestor@hotelcompare.ao'],
            [
                'name'     => 'Gestor de Teste',
                'password' => Hash::make('Gestor@1234'),
                'role'     => 'hotel_manager',
                'is_active'=> true,
            ]
        );

        // ── Comodidades base ───────────────────────────────────────
        $amenities = [
            // Serviços
            ['name' => 'Wi-Fi Gratuito',       'icon' => 'wifi',           'category' => 'Serviços'],
            ['name' => 'Recepção 24h',          'icon' => 'clock',          'category' => 'Serviços'],
            ['name' => 'Serviço de Quartos',    'icon' => 'bell',           'category' => 'Serviços'],
            ['name' => 'Lavandaria',            'icon' => 'sparkles',       'category' => 'Serviços'],
            ['name' => 'Transfer Aeroporto',    'icon' => 'truck',          'category' => 'Serviços'],
            ['name' => 'Concierge',             'icon' => 'user-tie',       'category' => 'Serviços'],

            // Lazer
            ['name' => 'Piscina',               'icon' => 'pool',           'category' => 'Lazer'],
            ['name' => 'Academia / Ginásio',    'icon' => 'dumbbell',       'category' => 'Lazer'],
            ['name' => 'Spa',                   'icon' => 'spa',            'category' => 'Lazer'],
            ['name' => 'Bar',                   'icon' => 'martini-glass',  'category' => 'Lazer'],
            ['name' => 'Restaurante',           'icon' => 'utensils',       'category' => 'Lazer'],
            ['name' => 'Salão de Eventos',      'icon' => 'building',       'category' => 'Lazer'],

            // Comodidades
            ['name' => 'Estacionamento Gratuito', 'icon' => 'car',          'category' => 'Comodidades'],
            ['name' => 'Ar Condicionado',        'icon' => 'wind',          'category' => 'Comodidades'],
            ['name' => 'Pequeno-Almoço Incluído','icon' => 'coffee',        'category' => 'Comodidades'],
            ['name' => 'Aceita Animais',         'icon' => 'paw',           'category' => 'Comodidades'],
            ['name' => 'Acessível Cadeira de Rodas', 'icon' => 'wheelchair', 'category' => 'Comodidades'],
            ['name' => 'Cofre no Quarto',        'icon' => 'lock',          'category' => 'Comodidades'],
        ];

        foreach ($amenities as $amenity) {
            Amenity::updateOrCreate(
                ['name' => $amenity['name']],
                $amenity
            );
        }
    }
}