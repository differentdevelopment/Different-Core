
## Telepítés

Egy teljesen új projekt esetén a telepítéshez a következő parancsokat kell futtatni:

> laravel new **PROJEKT-NEVE** <br />
> composer require backpack/crud:"4.1.*" <br />
> composer require --dev backpack/generators <br />
> php artisan backpack:install <br />
> composer require **MAJD_IDE_KELL_A_SAJÁT_CSOMAGUNK_NEVE** <br />

<br />

A parancsok lefuttatása után állítsuk be az alábbi értékeket a konfigurációs fájlokban:

`config\backpack\base.php - 262. sor`
'view_namespace' => 'different-core::',<br />
<br />
`config\backpack\base.php - 15. - 16. sor `
'default_date_format' => 'YYYY. MMM. D.',<br />
'default_datetime_format' => 'YYYY. MMM. D. HH:mm',<br />
<br />
`config\backpack\base.php - 27. sor`
'project_name' => '**PROJEKT-NEVE**',<br />
<br />
`config\app.php - 70. sor`
'timezone' => 'Europe/Budapest',<br />
 <br />
`config\app.php - 83. sor`
'locale' => 'hu',<br />
<br />
`config\auth.php - 65. sor`
'model' => Different\DifferentCore\app\Models\User::class,<br />
<br />

**Ne felejtsd el az .env fájl helyes kitöltését, főleg az adatbázis részt!**

  

Ha ez megvolt akkor nincs más mint mint az alábbi pár parancsot lefuttatni:

> php artisan migrate<br />
> php artisan db:seed --class=Different\\DifferentCore\\Database\\Seeds\\DifferentSeeder<br />
