## Telepítés
Egy teljesen új projekt esetén a telepítéshez a következő parancsokat kell futtatni:
> laravel new **PROJEKT-NEVE**
> composer require backpack/crud:"4.1.*"
> composer require --dev backpack/generators
> php artisan backpack:install
> composer require **MAJD_IDE_KELL_A_SAJÁT_CSOMAGUNK_NEVE**

A parancsok lefuttatása után állítsuk be az alábbi értékeket a konfigurációs fájlokban:
`config\backpack\base.php - 262. sor`
	'view_namespace' => 'different-core::',

`config\backpack\base.php - 15. - 16. sor `
	'default_date_format'     => 'YYYY. MMM. D.',
	'default_datetime_format' => 'YYYY. MMM. D. HH:mm',

`config\backpack\base.php - 27. sor`
	'project_name' => '**PROJEKT-NEVE**',

`config\app.php - 70. sor`
	'timezone' => 'Europe/Budapest',

`config\app.php - 83. sor`
	'locale' => 'hu',
	
**Ne felejtsd el az .env fájl helyes kitöltését, főleg az adatbázis részt!**

Ha ez megvolt akkor nincs más mint mint az alábbi pár parancsot lefuttatni:
> php artisan migrate
> php artisan db:seed --class=Different\\DifferentCore\\Database\\Seeds\\DifferentSeeder
