<?php

namespace Database\Seeders;

use App\Models\DataOpd;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@simaset',
            'nip' => '198802072021031001',
            'id_opd' => '16',
            'level' => 'admin',
            'password' => Hash::make('asdf1234')
        ]);

        // DataOpd::insert(
        //     [
        //         [
        //             'opd' => 'Dinas Pendidikan dan Kebudayaan',
        //             'parent' => null,
        //             'created_at' => date("Y-m-d H:i:s"),
        //             'updated_at' => date("Y-m-d H:i:s"),
        //         ],
        //         [
        //             'opd' => 'Dinas Kesehatan, Pengendalian Penduduk dan Keluarga Berencana',
        //             'parent' => null,
        //             'created_at' => date("Y-m-d H:i:s"),
        //             'updated_at' => date("Y-m-d H:i:s"),
        //         ],
        //         [
        //             'opd' => 'Dinas Pekerjaan Umum dan Perumahan Rakyat',
        //             'parent' => null,
        //             'created_at' => date("Y-m-d H:i:s"),
        //             'updated_at' => date("Y-m-d H:i:s"),
        //         ],
        //         [
        //             'opd' => 'Dinas Kebakaran dan Penyelamatan, Penanggulangan Bencana dan Satuan Polisi Pamong Praja',
        //             'parent' => null,
        //             'created_at' => date("Y-m-d H:i:s"),
        //             'updated_at' => date("Y-m-d H:i:s"),
        //         ],
        //         [
        //             'opd' => 'Dinas Sosial, Pemberdayaan Perempuan dan Perlindangan Anak',
        //             'parent' => null,
        //             'created_at' => date("Y-m-d H:i:s"),
        //             'updated_at' => date("Y-m-d H:i:s"),
        //         ],
        //         [
        //             'opd' => 'Dinas Tenaga Kerja, Transmigrasi, Energi dan Sumber Daya Mineral',
        //             'parent' => null,
        //             'created_at' => date("Y-m-d H:i:s"),
        //             'updated_at' => date("Y-m-d H:i:s"),
        //         ],
        //         [
        //             'opd' => 'Dinas Lingkungan Hidup, Kehutanan dan Pertanahan',
        //             'parent' => null,
        //             'created_at' => date("Y-m-d H:i:s"),
        //             'updated_at' => date("Y-m-d H:i:s"),
        //         ],
        //         [
        //             'opd' => 'Dinas Kependudukan dan Pencatatan Sipil, Pemberayaan Masyarakat dan Kampung',
        //             'parent' => null,
        //             'created_at' => date("Y-m-d H:i:s"),
        //             'updated_at' => date("Y-m-d H:i:s"),
        //         ],
        //         [
        //             'opd' => 'Dinas Perhubungan',
        //             'parent' => null,
        //             'created_at' => date("Y-m-d H:i:s"),
        //             'updated_at' => date("Y-m-d H:i:s"),
        //         ],
        //         [
        //             'opd' => 'Dinas Komunikasi, Informatika, Statistik dan Persandian',
        //             'parent' => null,
        //             'created_at' => date("Y-m-d H:i:s"),
        //             'updated_at' => date("Y-m-d H:i:s"),
        //         ],
        //         [
        //             'opd' => 'Dinas Koperasi, Usaha Kecil Menengah, Perindustria dan Perdagangan',
        //             'parent' => null,
        //             'created_at' => date("Y-m-d H:i:s"),
        //             'updated_at' => date("Y-m-d H:i:s"),
        //         ],
        //         [
        //             'opd' => 'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu',
        //             'parent' => null,
        //             'created_at' => date("Y-m-d H:i:s"),
        //             'updated_at' => date("Y-m-d H:i:s"),
        //         ],
        //         [
        //             'opd' => 'Dinas Kepemudaan, Olahraga, Pariwisita dan Ekonomi Kreatif',
        //             'parent' => null,
        //             'created_at' => date("Y-m-d H:i:s"),
        //             'updated_at' => date("Y-m-d H:i:s"),
        //         ],
        //         [
        //             'opd' => 'Dinas ertanian, Pangan, Kelautan dan Perikanan',
        //             'parent' => null,
        //             'created_at' => date("Y-m-d H:i:s"),
        //             'updated_at' => date("Y-m-d H:i:s"),
        //         ],
        //         [
        //             'opd' => 'Badan Perencanaan Pembangunan, Riset dan Inovasi Daerah',
        //             'parent' => null,
        //             'created_at' => date("Y-m-d H:i:s"),
        //             'updated_at' => date("Y-m-d H:i:s"),
        //         ],
        //         [
        //             'opd' => 'Badan Pengelolaan Pendapatan, Keuangan dan Aset Daerah',
        //             'parent' => null,
        //             'created_at' => date("Y-m-d H:i:s"),
        //             'updated_at' => date("Y-m-d H:i:s"),
        //         ],
        //         [
        //             'opd' => 'Badan Kepegawaiaan dan Pengembangan Sumber Daya Manusia',
        //             'parent' => null,
        //             'created_at' => date("Y-m-d H:i:s"),
        //             'updated_at' => date("Y-m-d H:i:s"),
        //         ],
        //         [
        //             'opd' => 'Badan Kesatuan Bangsa dan Politik',
        //             'parent' => null,
        //             'created_at' => date("Y-m-d H:i:s"),
        //             'updated_at' => date("Y-m-d H:i:s"),
        //         ],
        //         [
        //             'opd' => 'Inspektorat Daerah',
        //             'parent' => null,
        //             'created_at' => date("Y-m-d H:i:s"),
        //             'updated_at' => date("Y-m-d H:i:s"),
        //         ],
        //         [
        //             'opd' => 'Sekretariat Daerah',
        //             'parent' => null,
        //             'created_at' => date("Y-m-d H:i:s"),
        //             'updated_at' => date("Y-m-d H:i:s"),
        //         ],
        //         [
        //             'opd' => 'Biro Umum dan Perlengkapan',
        //             'parent' => 20,
        //             'created_at' => date("Y-m-d H:i:s"),
        //             'updated_at' => date("Y-m-d H:i:s"),
        //         ],
        //         [
        //             'opd' => 'Biro Pemerintahan, Kesejahteraan Masyarakat dan Otonomi Khusus',
        //             'parent' => 20,
        //             'created_at' => date("Y-m-d H:i:s"),
        //             'updated_at' => date("Y-m-d H:i:s"),
        //         ],
        //         [
        //             'opd' => 'Biro Organisasi',
        //             'parent' => 20,
        //             'created_at' => date("Y-m-d H:i:s"),
        //             'updated_at' => date("Y-m-d H:i:s"),
        //         ],
        //         [
        //             'opd' => 'Biro Hukum',
        //             'parent' => 20,
        //             'created_at' => date("Y-m-d H:i:s"),
        //             'updated_at' => date("Y-m-d H:i:s"),
        //         ],
        //         [
        //             'opd' => 'Biro Administrasi Pengadaan Barang dan Jasa',
        //             'parent' => 20,
        //             'created_at' => date("Y-m-d H:i:s"),
        //             'updated_at' => date("Y-m-d H:i:s"),
        //         ],
        //         [
        //             'opd' => 'Biro Administrasi Pembangunan Daerah',
        //             'parent' => 20,
        //             'created_at' => date("Y-m-d H:i:s"),
        //             'updated_at' => date("Y-m-d H:i:s"),
        //         ],
        //         [
        //             'opd' => 'Sekretariat Dewan Perwakilan Rakyat',
        //             'parent' => null,
        //             'created_at' => date("Y-m-d H:i:s"),
        //             'updated_at' => date("Y-m-d H:i:s"),
        //         ],
        //         [
        //             'opd' => 'Sekretariat Majelis Rakyat Papua Barat Daya',
        //             'parent' => null,
        //             'created_at' => date("Y-m-d H:i:s"),
        //             'updated_at' => date("Y-m-d H:i:s"),
        //         ]
        //     ]

        // );
    }
}
