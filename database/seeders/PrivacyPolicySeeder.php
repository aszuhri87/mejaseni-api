<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class PrivacyPolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $result = DB::transaction(function (){
            $privacy_policy = \App\Models\PrivacyPolicy::updateOrCreate([
                'description' => 'Kami sangat menghargai Anda sebagai Mitra kami. Kebijakan privasi ini kami buat untuk memberikan kenyamanan bagi Anda saat menggunakan layanan kami yang melibatkan data pribadi Anda.
                Untuk menggunakan layanan yang disediakan di situs ini, kami memerlukan beberapa data pribadi Anda, baik yang bersifat sensitif (misalnya; nomor rekening dan kartu identitas) maupun informasi publik seperti nama Anda. Karena Anda menjadi mitra yang dibatasi oleh ketentuan-ketentuan yang kami terapkan, kami ingin Anda mengetahui dengan jelas cara kami menggunakan informasi dan cara yang dapat Anda lakukan untuk melindungi privasi Anda. Jadi, luangkan waktu untuk memahami apa yang kami lakukan dengan informasi yang telah Anda berikan pada kami. Apabila ada pertanyaan terkait hal ini, jangan ragu untuk menghubungi kami melalui email:info@mejaseni.com atau kontak atau Live Chat yang kami sediakan dalam website ini.',
            ]);

            $items = [
                [
                    'title' => 'Gimana cara belinya?',
                    'description' => 'Informasi yang anda berikan saat pendaftaran member baik sebagai calon
                                    pembeli di halaman store dan peserta kursus. Contohnya, nama lengkap, email, nomor
                                    kontak, alamat rumah, alamat pengiriman, nomor rekening bank dll untuk disimpan
                                    dalam akun Anda. Layanan kami mengharuskan Anda memberikan informasi dengan benar
                                    dan dapat dipertanggungjawabkan secara hukum di Indonesia. Pendaftaran subcribe,
                                    formulir download,transaksi dan permintaan kepada sales, billing, abuse, dan
                                    technical support. Mungkin Anda menggunakan salah satu jalur komunikasi tersebut
                                    yang meminta Anda memberikan data personal Anda yang selanjutnya tercatat dalam
                                    sistem kami.'
                ],
                [
                    'title' => 'Tau ga?',
                    'description' => 'Don’t fucking lie to yourself. Be fucking impossible to ignore. Stand so tall that
                                    they can’t look past you. Intuition is fucking important. Keep fucking going. It
                                    isn’t what you are, but what you’re going to become. You’ve been placed in the
                                    crucial moment. Abandon the shelter of insecurity. Respect your fucking craft.
                                    Accomplishment validates belief, and belief inspires accomplishment. Practice won’t
                                    get you anywhere if you mindlessly fucking practice the same thing. Change only
                                    occurs when you work deliberately with purpose toward a goal. You need to sit down
                                    and sketch more fucking ideas because stalking your ex on facebook isn’t going to
                                    get you anywhere. To surpass others is fucking tough, if you only do as you are told
                                    you don’t have it in you to succeed. You’ve been placed in the crucial moment.'
                ],
            ];

            foreach ($items as $key => $item) {
                if ($item != null) {
                    \App\Models\PrivacyPolicyItem::firstOrCreate([
                        'title' => $item['title'],
                        'description' => $item['description'],
                        'privacy_policy_id' => $privacy_policy->id
                    ]);
                }
            }
        });
    }
}
