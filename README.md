
## Telepítés

Egy teljesen új projekt esetén a telepítéshez a következő parancsokat kell futtatni:

> laravel new **PROJEKT-NEVE** <br />
> composer require backpack/crud:"4.1.*" <br />
> composer require --dev backpack/generators <br />
> php artisan backpack:install <br />
> composer require differentdevelopment/Different-Core <br />

<br />

A parancsok lefuttatása után állítsuk be az alábbi értékeket a konfigurációs fájlokban:

`config\backpack\base.php - 262. sor`<br />
'view_namespace' => 'different-core::',<br />
<br />
`config\backpack\base.php - 15. - 16. sor `<br />
'default_date_format' => 'YYYY. MMM. D.',<br />
'default_datetime_format' => 'YYYY. MMM. D. HH:mm',<br />
<br />
`config\backpack\base.php - 27. sor`<br />
'project_name' => '**PROJEKT-NEVE**',<br />
<br />
`config\app.php - 70. sor`<br />
'timezone' => 'Europe/Budapest',<br />
 <br />
`config\app.php - 83. sor`<br />
'locale' => 'hu',<br />
<br />
`config\auth.php - 65. sor`<br />
'model' => Different\DifferentCore\app\Models\User::class,<br />
<br />

**Ne felejtsd el az .env fájl helyes kitöltését, főleg az adatbázis részt!**

  

Ha ez megvolt akkor nincs más mint mint az alábbi pár parancsot lefuttatni:

> php artisan migrate<br />
> php artisan db:seed --class=Different\\DifferentCore\\Database\\Seeds\\DifferentSeeder<br />

<br /><br />
## Opcionális<br />
<br />
### Larastan
A csomag előre telepítve van viszont rendszerenként a `phpstan.neon` config fájlt létre kell hozni.<br />
[https://packagist.org/packages/nunomaduro/larastan#1.0.3](https://packagist.org/packages/nunomaduro/larastan#1.0.3)<br/>
Később futtatni a `./vendor/bin/phpstan analyse` vagy automatizálni.
