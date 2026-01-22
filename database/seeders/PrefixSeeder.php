<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Prefix;

class PrefixSeeder extends Seeder
{
    public function run(): void
    {
        $prefixes = [
            // TELKOMSEL
            ['prefix' => '0811', 'operator' => 'TELKOMSEL', 'keterangan' => 'Halo'],
            ['prefix' => '0812', 'operator' => 'TELKOMSEL', 'keterangan' => 'Halo, Simpati'],
            ['prefix' => '0813', 'operator' => 'TELKOMSEL', 'keterangan' => 'Halo, Simpati'],
            ['prefix' => '0821', 'operator' => 'TELKOMSEL', 'keterangan' => 'Simpati'],
            ['prefix' => '0822', 'operator' => 'TELKOMSEL', 'keterangan' => 'Simpati, Kartu Facebook'],
            ['prefix' => '0823', 'operator' => 'TELKOMSEL', 'keterangan' => 'AS'],
            ['prefix' => '0851', 'operator' => 'TELKOMSEL', 'keterangan' => 'AS'],
            ['prefix' => '0852', 'operator' => 'TELKOMSEL', 'keterangan' => 'AS'],
            ['prefix' => '0853', 'operator' => 'TELKOMSEL', 'keterangan' => 'AS'],

            // INDOSAT
            ['prefix' => '0814', 'operator' => 'INDOSAT', 'keterangan' => 'INDOSAT M2 Broadband'],
            ['prefix' => '0815', 'operator' => 'INDOSAT', 'keterangan' => 'Matriks dan Mentari'],
            ['prefix' => '0816', 'operator' => 'INDOSAT', 'keterangan' => 'Matriks dan Mentari'],
            ['prefix' => '0855', 'operator' => 'INDOSAT', 'keterangan' => 'Matriks'],
            ['prefix' => '0856', 'operator' => 'INDOSAT', 'keterangan' => 'IM3'],
            ['prefix' => '0857', 'operator' => 'INDOSAT', 'keterangan' => 'IM3'],
            ['prefix' => '0858', 'operator' => 'INDOSAT', 'keterangan' => 'Mentari'],

            // XL
            ['prefix' => '0817', 'operator' => 'XL', 'keterangan' => 'Prabayar dan Explor'],
            ['prefix' => '0818', 'operator' => 'XL', 'keterangan' => 'Prabayar dan Explor'],
            ['prefix' => '0819', 'operator' => 'XL', 'keterangan' => 'Prabayar dan Explor'],
            ['prefix' => '0859', 'operator' => 'XL', 'keterangan' => 'Prabayar dan Explor'],
            ['prefix' => '0877', 'operator' => 'XL', 'keterangan' => 'Prabayar dan Explor'],
            ['prefix' => '0878', 'operator' => 'XL', 'keterangan' => 'Prabayar dan Explor'],

            // AXIS
            ['prefix' => '0831', 'operator' => 'AXIS'],
            ['prefix' => '0832', 'operator' => 'AXIS'],
            ['prefix' => '0833', 'operator' => 'AXIS'],
            ['prefix' => '0838', 'operator' => 'AXIS'],

            // TRI
            ['prefix' => '0895', 'operator' => 'TRI'],
            ['prefix' => '0896', 'operator' => 'TRI'],
            ['prefix' => '0897', 'operator' => 'TRI'],
            ['prefix' => '0898', 'operator' => 'TRI'],
            ['prefix' => '0899', 'operator' => 'TRI'],

            // SMARTFREN
            ['prefix' => '0881', 'operator' => 'SMARTFREN'],
            ['prefix' => '0882', 'operator' => 'SMARTFREN'],
            ['prefix' => '0883', 'operator' => 'SMARTFREN'],
            ['prefix' => '0884', 'operator' => 'SMARTFREN'],
            ['prefix' => '0885', 'operator' => 'SMARTFREN'],
            ['prefix' => '0886', 'operator' => 'SMARTFREN'],
            ['prefix' => '0887', 'operator' => 'SMARTFREN'],
            ['prefix' => '0888', 'operator' => 'SMARTFREN'],
            ['prefix' => '0889', 'operator' => 'SMARTFREN'],

            // Ceria
            ['prefix' => '0828', 'operator' => 'Ceria'],

            // By.U
            ['prefix' => '0851', 'operator' => 'by.U'],
        ];

        foreach ($prefixes as $item) {
            \App\Models\Prefix::create($item);
        }
    }
}
