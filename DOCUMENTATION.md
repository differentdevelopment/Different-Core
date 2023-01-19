# Dokumentáció

## Törlés modal / pop-up

Az adott CRUD **setup()** metódusába kell ezt a kódrészletet beletenni.

```
use Different\DifferentCore\app\Http\Controllers\Operations\DeleteOperation;

public function setup() {
    // ...
    $this->crud->data['delete_modal'] = [
        'title' => __('different-core::users.delete_title'),
        'text' =>  __('different-core::users.delete_text'),
    ];
    // ...
}
```

## Fájl / kép feltöltés field

Példa kód itt található a profil kép feltöltésnél: **Different\DifferentCore\app\Models\User.php**

**'name': mező megadása kötelező**, fontos, hogy ugyan az legyen mint a realtion.

A `store` és `update` metódust felül kell írni és első sorba ezt meghívni: `$this->handleFileUpload();`

A modelben (pl.: User) le kell kezelni, hogy ha a sort törlik akkor a fájlt is töröljük ki vele együtt. (pl.: Different\DifferentCore\app\Models\User.php -> boot())

Ha szeretnénk előnézetet is (kép esetén) akkor a 'has_preview' paraméter értékét igazra kell állítani.

**FONTOS: Ahhoz hogy a fájl feltöltés működjön a `'upload' => true` kötelező megadni. Ez a Backpack miatt van így. Adatbázis szinten figyelni kell hogy ne legyen cascade törlés hiszen akkor a fájl törléssel együtt az adott elem is törlődik amit éppen szerkesztünk.**

```
[
    'name' => 'profile_image', // Reláció
    'label' => __('different-core::users.profile_image'), // Cím
    'view_namespace' => 'different-core::fields', // KÖTELEZŐ
    'type' => 'file', // KÖTELEZŐ
    'has_preview' => true, // Kép esetén true
    'upload' => true, // KÖTELEZŐ
    'wrapper' => [ // Opcionális
        'class' => 'form-group col-12',
    ],
],
```

## Sidebar / menü

Új menüpontokat az alábbi fájlban tudsz felvenni: **config/different-core/config.php**

### Menüpont

```
new SidebarMenuItem(
    '/admin/songs', // Hivatkozás
    'songs.songs', // Fordítható cím
    'las la-music', // Ikon (https://icons8.com/line-awesome)
    // 'song', // Permission a menüpont megjelenítéséhez
),
```

### Csoport

```
new SidebarMenuGroup(
    'different-core::sidebar.system', // Fordítható cím
    [
        // Menüpont tömb pl.:
        new SidebarMenuItem(route('admin.user.index'), 'different-core::users.users', 'las la-user', 'user-list'),
        new SidebarMenuItem(route('admin.account.index'), 'different-core::accounts.accounts', 'las la-users', 'account-list'),
        new SidebarMenuItem(route('admin.role.index'), 'different-core::roles.roles', 'las la-id-badge', 'role-manage'),
        new SidebarMenuItem(route('admin.activity.index'), 'different-core::activities.activities', 'las la-history', 'activity-list'),
        new SidebarMenuItem(route('admin.settings'), 'different-core::settings.settings', 'las la-sliders-h', 'setting-manage'),
        new SidebarMenuItem(route('admin.documentation'), 'different-core::documentation.documentation', 'las la-book', 'documentation-read'),
    ],
    'las la-cog', // Ikon (https://icons8.com/line-awesome)
    [
        // Permission a csoport megjelenítéséhez, ha már 1 van ezek közül a felhasználónak akkor megjelenik a csoport
        'user-list',
        'role-manage',
        'activity-list',
        'setting-manage',
        'documentation-read',
    ]
),
```

### Címke

```
new SidebarMenuLabel(
    'Címke',
    [
        'user-list',
    ],
);
```

## "Breadcrumbs" menü

Az adott CRUD **setup()** metódusába kell ezt a kódrészletet beletenni.

```
use Different\DifferentCore\app\Utils\Breadcrumb\BreadcrumbMenuItem;

$this->data['breadcrumbs_menu'] = [
    new BreadcrumbMenuItem(
        backpack_url('dashboard'), // Hivatkozás
        __('backpack::crud.admin'), // Cím
        'las la-tachometer-alt', // Ikon (https://icons8.com/line-awesome)
    ),
];
```

## "Tabs" menü

Az adott CRUD **setup()** metódusába kell ezt a kódrészletet beletenni.

```
use Different\DifferentCore\app\Utils\Tab\TabItem;

$this->data['tabs'] = [
    new TabItem(
        route('songs.index'),
        __('songs.songs'),
        'las la-music',
        'song.list',
        false,
        true,
        true,
        true,
        true
    ),
    new TabItem(
        route('admin.user.index'),
        __('different-core::users.users'),
        'las la-user',
        'user.list',
        false,
        true,
        true,
        true,
        true
    ),
];
```

## CRUD permission(s) / jogosultságok

Az adott CRUD **setup()** metódusába kell ezt a kódrészletet beletenni.

Letiltja az egyes CRUD műveletet ha nincs a $name-el kezdődő permission. Például user estén a következő permission-ök: user-list, user-show, user-delete stb.

```
crud_permissions($this->crud, 'ide_kell_a_permission');
```

Letiltja az összes CRUD műveletet ha nincs ez a permission a felhasználónak.

```
crud_permission($this->crud, 'ide_kell_a_permission');
```

## Általános jogkezelés

Az átadott permission-höz van-e joga a felhasználónak.

```
user_can('ide_kell_a_permission');
```

Az átadott permission-ök közül van-e joga a felhasználónak legalább az egyikhez.

```
user_can_any(['ide_kell_a_permission', 'ide_kell_a_masodik_permission']);
```

## Beállítások

A seeder fájlt úgy kell előkészíteni hogy az minden egyes alkalommal le fog futni amikor deploy-olva lesz a rendszer.

Új beállítást a seeder fájlban így kell felvenni:

```
SettingsManagerController::create([
    [
        'name' => 'company_name', // Egyedi azonosító
        'type' => 'text',
        'tab' => 'Rendszer',
        'label' => 'Cégnév',
        'wrapper' => [
            'class' => 'form-group col-md-4',
        ],
        'value' => 'Different Fejlesztő Kft.',
    ],
]);
```

Ha később egy beállítás már nincs használva akkor az alábbi sornak kell belekerülnie a seederbe, hogy törölve legyen:

```
SettingsManagerController::delete('company_name');
```

Beállítás lekérésehez pedig használd az alábbi funkciót:

```
SettingsManagerController::get('company_name');
```

Ha később a kódból szeretnénk a beállítás értékét módosítani akkor:

```
SettingsManagerController::set('company_name', 'új érték);
```
