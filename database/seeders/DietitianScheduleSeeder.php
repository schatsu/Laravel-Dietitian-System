<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\BookAppointmentService;
use Illuminate\Database\Seeder;

class DietitianScheduleSeeder extends Seeder
{
    /**
     * Diyetisyen için örnek müsaitlik ve randevu verileri oluştur.
     */
    public function run(): void
    {
        $dietitian = User::role('super_admin')->first();

        if (!$dietitian) {
            $this->command->info('Super admin kullanıcı bulunamadı. Seeder atlandı.');
            return;
        }

        $service = new BookAppointmentService($dietitian);

        // 1. Hafta içi çalışma saatlerini tanımla (09:00-12:00, 14:00-17:00)
        $this->command->info('Çalışma saatleri tanımlanıyor...');

        // Sabah mesaisi
        $service->setupAvailability(
            days: ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
            startTime: '09:00',
            endTime: '12:00'
        );

        // Öğleden sonra mesaisi
        $service->setupAvailability(
            days: ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
            startTime: '14:00',
            endTime: '17:00'
        );

        $this->command->info('✅ Çalışma saatleri tanımlandı.');

        // 2. Öğle arasını blokla
        $this->command->info('Öğle arası bloklama yapılıyor...');

        $service->blockTime(
            name: 'Öğle Arası',
            startTime: '12:00',
            endTime: '14:00',
            days: ['monday', 'tuesday', 'wednesday', 'thursday', 'friday']
        );

        $this->command->info('✅ Öğle arası bloklandı.');

        // 3. Cumartesi yarım gün çalışma
        $this->command->info('Cumartesi programı tanımlanıyor...');

        $service->setupAvailability(
            days: ['saturday'],
            startTime: '10:00',
            endTime: '13:00'
        );

        $this->command->info('✅ Cumartesi programı tanımlandı.');

        // 4. Örnek randevular oluştur
        $this->command->info('Örnek randevular oluşturuluyor...');

        // Yarın için bir randevu
        $tomorrow = now()->addDay()->format('Y-m-d');
        $service->bookAppointment(
            date: $tomorrow,
            startTime: '10:00',
            endTime: '11:00',
            clientData: [
                'name' => 'Ahmet Yılmaz',
                'email' => 'ahmet@example.com',
                'phone' => '5551234567',
                'note' => 'İlk görüşme - kilo verme programı',
                'status' => 'approved',
            ]
        );

        // 2 gün sonra için bir randevu
        $dayAfter = now()->addDays(2)->format('Y-m-d');
        $service->bookAppointment(
            date: $dayAfter,
            startTime: '14:00',
            endTime: '15:00',
            clientData: [
                'name' => 'Fatma Kaya',
                'email' => 'fatma@example.com',
                'phone' => '5559876543',
                'note' => 'Kontrol randevusu',
                'status' => 'pending',
            ]
        );

        // 3 gün sonra için bir randevu
        $threeDays = now()->addDays(3)->format('Y-m-d');
        $service->bookAppointment(
            date: $threeDays,
            startTime: '09:00',
            endTime: '10:00',
            clientData: [
                'name' => 'Mehmet Demir',
                'email' => 'mehmet@example.com',
                'phone' => '5554567890',
                'note' => 'Sporcular için beslenme danışmanlığı',
                'status' => 'approved',
            ]
        );

        $this->command->info('✅ Örnek randevular oluşturuldu.');

        $this->command->info('');
        $this->command->info('🎉 Diyetisyen takvim verisi başarıyla oluşturuldu!');
        $this->command->info('   - Hafta içi: 09:00-12:00 ve 14:00-17:00 müsaitlik');
        $this->command->info('   - Cumartesi: 10:00-13:00 müsaitlik');
        $this->command->info('   - Öğle arası: 12:00-14:00 bloklu');
        $this->command->info('   - 3 adet örnek randevu');
    }
}
