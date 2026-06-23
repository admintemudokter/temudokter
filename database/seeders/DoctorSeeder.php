<?php

namespace Database\Seeders;

use App\Models\Doctor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = [
            [
                'name'           => 'Dr. Andi Wijaya, Sp.PD',
                'email'          => 'andi@konsulku.id',
                'specialization' => 'Dokter Spesialis Penyakit Dalam',
                'phone'          => '08121234001',
                'status'         => 'online',
                'bio'            => 'Dokter spesialis penyakit dalam dengan pengalaman 10 tahun di wilayah Bekasi.',
            ],
            [
                'name'           => 'Dr. Sari Putri, Sp.A',
                'email'          => 'sari@konsulku.id',
                'specialization' => 'Dokter Spesialis Anak',
                'phone'          => '08121234002',
                'status'         => 'online',
                'bio'            => 'Dokter spesialis anak dengan pengalaman 8 tahun dan fokus pada kesehatan pediatrik.',
            ],
            [
                'name'           => 'Dr. Budi Santoso',
                'email'          => 'budi@konsulku.id',
                'specialization' => 'Dokter Umum',
                'phone'          => '08121234003',
                'status'         => 'offline',
                'bio'            => 'Dokter umum dengan pendekatan holistik dan berpengalaman di Bekasi Timur.',
            ],
            [
                'name'           => 'Dr. Rini Kusuma, Sp.OG',
                'email'          => 'rini@konsulku.id',
                'specialization' => 'Dokter Spesialis Kandungan',
                'phone'          => '08121234004',
                'status'         => 'offline',
                'bio'            => 'Dokter spesialis obstetri dan ginekologi untuk kesehatan perempuan.',
            ],
        ];

        foreach ($doctors as $data) {
            Doctor::firstOrCreate(
                ['email' => $data['email']],
                array_merge($data, ['password' => Hash::make('password')])
            );
        }
    }
}
