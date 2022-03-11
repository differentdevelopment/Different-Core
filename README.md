
  

# Different-Core

## Telepítés

  

Egy teljesen új projekt esetén a telepítéshez a következő parancsokat kell futtatni:

  

> laravel new **PROJEKT-NEVE**  <br  />

> composer require backpack/crud:"^5.0" <br  />

> composer require --dev backpack/generators <br  />

Adatbázis létrehozása és az `.env`-ben felvenni!

> php artisan backpack:install <br  />

Fontos: Ha a Different-Core csomagot szeretnéd fejleszteni akkor létre kell hozni egy `packages` nevű mappát a gyökérkönyvtárban, és oda kicheckoutolni a repo-t!

> composer require differentdevelopment/Different-Core <br  />

  

<br  />

  

A parancsok lefuttatása után állítsuk be az alábbi értékeket a konfigurációs fájlokban:

  

`config\backpack\base.php - 262. sor`<br  />

'view_namespace' => 'different-core::',<br  />

<br  />

`config\backpack\base.php - 15., 16., 250. sor `<br  />

'default_date_format' => 'YYYY. MMM. D.',<br  />

'default_datetime_format' => 'YYYY. MMM. D. HH:mm',<br  />

'avatar_type' => 'getProfileImageUrl',<br  />

<br  />

`config\backpack\base.php - 27. sor`<br  />

'project_name' => '**PROJEKT-NEVE**',<br  />

<br  />

`config\app.php - 70. sor`<br  />

'timezone' => 'Europe/Budapest',<br  />

<br  />

`config\app.php - 83. sor`<br  />

'locale' => 'hu',<br  />

<br  />

`config\auth.php - 65. sor`<br  />

'model' => Different\DifferentCore\app\Models\User::class,<br  />

<br  />

`database\seeders\DatabaseSeeder.php`<br  />

```
    public function run()
    {
        $this->call(\Different\DifferentCore\Database\Seeds\DifferentSeeder::class);
    }
```

<br  />

**Ne felejtsd el az `.env` fájl helyes kitöltését!**

  

Ha ez megvolt akkor már csak az alábbi pár parancsot kell lefuttatni:

  

> php artisan migrate --seed<br  />

> php artisan vendor:publish --tag=config<br  />

## Opcionális <br />
**Módosított Backpack design**<br />
> php artisan vendor:publish --tag=scss --force

Módosítsd a színeket a `backpack-overrides.scss` fájlban a `:root {` selectorban. Színek generálásához érdemes ezt használni: https://ionicframework.com/docs/theming/color-generator

Új sort felvenni a `webpack.mix.js` fájlban:<br />
> .sass('resources/scss/backpack-overrides.scss', 'public/css')

Az új buildelt css fájlt felvenni a `config/backpack/base.php -> styles` tömbhöz:<br />
> 'css/backpack-overrides.css',

<br  />**Rendszer szintű logolás**<br  />
Nyisd meg a `app\Exceptions\Handler.php` fájlt és módosítsd a `register` metódust erre:
```
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            if ($this->shouldReport($e)) {
                store_system_logs($e);
            }
        });
    }
```

<br  />**Larastan**<br  />

A csomag előre telepítve van viszont rendszerenként a `phpstan.neon` config fájlt létre kell hozni.<br  />

https://packagist.org/packages/nunomaduro/larastan#1.0.3<br/>

Később futtatni a `./vendor/bin/phpstan analyse` vagy automatizálni.<br/>
