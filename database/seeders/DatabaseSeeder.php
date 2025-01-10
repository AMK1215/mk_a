<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            BonusTypeTableSeeder::class,
            PermissionsTableSeeder::class,
            RolesTableSeeder::class,
            PermissionRoleTableSeeder::class,
            PaymentTypeTableSeeder::class,
            UsersTableSeeder::class,
            RoleUserTableSeeder::class,
            BannerSeeder::class,
            BannerTextSeeder::class,
            BannerAdsSeeder::class,
            ContactTypeSeeder::class,
            BankTableSeeder::class,
            GameTypeTableSeeder::class,
            GameProductSeeder::class,
            GameTypeProductTableSeeder::class,
            PragmaticPlaySlotSeeder::class,
            PragmaticPlayLiveCasinoSeeder::class,
            PGSoftGameListSeeder::class,
            JILIGameTableSeeder::class,
            Live22GameTableSeeder::class,
            CQ9GameTableSeeder::class,
            UUSlotGameListSeeder::class, // 8
            MegaH5GameTableSeeder::class,
            EpicWinGameTableSeeder::class,
            YellowBatGameTableSeeder::class,
            EVOPLAYGameTableSeeder::class,
            FACHAIGameTableSeeder::class,
            BNGGameTableSeeder::class,
            YGRGameTableSeeder::class,
            FUNTAGameTableSeeder::class,
            SimplePlayGameTableSeeder::class,

        ]);

    }
}
