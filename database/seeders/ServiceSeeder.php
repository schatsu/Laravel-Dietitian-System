<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Kilo Kontrolü',
                'description' => 'Sağlıklı ve sürdürülebilir şekilde kilo verme ve kilo alma süreçlerinde danışmanlık.',
                'content' => '
                <h2>Sağlıklı Kilo Yönetimi Neden Önemlidir?</h2>
                <p>Kilo kontrolü, yalnızca görünüm açısından değil; metabolizma sağlığı, enerji dengesi ve yaşam kalitesi açısından da büyük önem taşır. Kişiye özel beslenme planlarıyla hem kilo verme hem de kilo alma süreçleri sağlıklı şekilde yönetilir.</p>
                <h3>Nasıl Çalışıyoruz?</h3>
                <ul>
                    <li>İlk görüşmede detaylı vücut analizi yapılır.</li>
                    <li>Yaşam tarzınıza ve hedefinize göre kişisel beslenme planı hazırlanır.</li>
                    <li>Haftalık takiplerle ilerleme değerlendirilir ve plan güncellenir.</li>
                </ul>
                <p>Hedefiniz ne olursa olsun, dengeli ve sürdürülebilir bir beslenme planı ile ulaşmanız mümkündür.</p>
            ',
                'order' => 1,
                'status' => true,
                'image' => 'services/kilo-kontrolu.jpg',
                'seo_title' => 'Kilo Kontrolü - Diyetisyen Hizmeti',
                'seo_description' => 'Kilo verme ve kilo alma süreçlerinde uzman diyetisyen danışmanlığı.',
            ],
            [
                'name' => 'Sporcu Beslenmesi',
                'description' => 'Sporcular için performans artırıcı ve sağlıklı beslenme programları.',
                'content' => '
                <h2>Sporcu Beslenmesinde Performans Odaklı Yaklaşım</h2>
                <p>Doğru beslenme, sporcuların performansını artırmanın ve sakatlık riskini azaltmanın en etkili yollarından biridir. Her sporcunun enerji, protein ve karbonhidrat ihtiyacı farklıdır.</p>
                <h3>Sunduğumuz Hizmetler</h3>
                <ul>
                    <li>Branşa özel enerji ve makro dağılım planlaması</li>
                    <li>Antrenman öncesi ve sonrası beslenme stratejileri</li>
                    <li>Yarışma dönemi ve toparlanma beslenmesi</li>
                </ul>
                <p>Performansınızı doğal yollarla artırmak için bilimsel temellere dayanan beslenme planları oluşturuyoruz.</p>
            ',
                'order' => 2,
                'status' => true,
                'image' => 'services/sporcu-beslenmesi.jpg',
                'seo_title' => 'Sporcu Beslenmesi - Performans Diyeti',
                'seo_description' => 'Sporculara özel enerji ve performans odaklı beslenme planları.',
            ],
            [
                'name' => 'Gebelik ve Emzirme Dönemi Beslenmesi',
                'description' => 'Anne adayları ve emziren anneler için özel beslenme danışmanlığı.',
                'content' => '
                <h2>Gebelik ve Emzirme Döneminde Sağlıklı Beslenme</h2>
                <p>Anne ve bebek sağlığı için bu dönemlerde yeterli ve dengeli beslenme büyük önem taşır. Eksik veya dengesiz beslenme, hem annenin hem de bebeğin gelişimini olumsuz etkileyebilir.</p>
                <h3>Program İçeriği</h3>
                <ul>
                    <li>Gebelik öncesi kilo planlaması ve sağlıklı kilo alımı</li>
                    <li>Demir, kalsiyum, folik asit gibi önemli besin öğelerinin dengesi</li>
                    <li>Emzirme döneminde süt miktarını artıran beslenme önerileri</li>
                </ul>
                <p>Bu süreçte size özel planlar ile hem kendi sağlığınızı hem de bebeğinizin gelişimini destekleyebilirsiniz.</p>
            ',
                'order' => 3,
                'status' => true,
                'image' => 'services/gebelik-beslenmesi.jpg',
                'seo_title' => 'Gebelik ve Emzirme Dönemi Beslenmesi',
                'seo_description' => 'Anne ve bebek sağlığı için gebelik ve emzirme döneminde doğru beslenme.',
            ],
            [
                'name' => 'Kronik Hastalıklarda Beslenme',
                'description' => 'Diyabet, hipertansiyon ve benzeri kronik hastalıklarda beslenme desteği.',
                'content' => '
                <h2>Kronik Hastalıklarda Beslenme Desteği</h2>
                <p>Kronik rahatsızlıkların kontrol altına alınmasında beslenme en önemli unsurlardan biridir. Diyabet, hipertansiyon, kalp hastalıkları gibi durumlarda kişiye özel planlar sayesinde yaşam kalitesi artırılabilir.</p>
                <h3>Öne Çıkan Alanlar</h3>
                <ul>
                    <li>Diyabette kan şekeri dengesi için karbonhidrat sayımı eğitimi</li>
                    <li>Hipertansiyonda tuz ve mineral dengesi</li>
                    <li>Kolesterol ve kalp sağlığı odaklı beslenme modelleri</li>
                </ul>
                <p>Uzman desteğiyle uygulanan kişisel planlar, kronik hastalık yönetiminde önemli fark yaratır.</p>
            ',
                'order' => 4,
                'status' => true,
                'image' => 'services/kronik-hastalik.jpg',
                'seo_title' => 'Kronik Hastalıklarda Beslenme',
                'seo_description' => 'Kronik rahatsızlıkları olan bireyler için sağlıklı beslenme çözümleri.',
            ],
        ];

        collect($services)->each(function ($service) {
            Service::query()->create($service);
        });
    }
}
