@extends(backpack_view('blank'))

@section('after_styles')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.5.1/styles/atom-one-dark.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.5.1/highlight.min.js"></script>
    <script>hljs.highlightAll();</script>
@endsection

@section('content')
<div class="py-3">
    <h4 class="text-primary">Törlés modal / pop-up</h4>
    <p>Az adott CRUD <b>setup()</b> metódusába kell ezt a kódrészletet beletenni.</p>
    <pre><code class="language-php">use Different\DifferentCore\app\Http\Controllers\Operations\DeleteOperation;

public function setup() {
    // ...
    $this->crud->data['delete_modal'] = [
        'title' => __('different-core::users.delete_title'),
        'text' =>  __('different-core::users.delete_text'),
    ];
    // ...
}</code></pre>

    <h4 class="text-primary mt-5">Fájl / kép feltöltés field</h4>
    <p>
        Példa kód itt található a profil kép feltöltésnél: <b>Different\DifferentCore\app\Models\User.php</b>
        <br/><br/>
        'name': mező megadása kötelező, fontos, hogy ugyan az legyen mint a realtion.
        <br/><br/>
        A `store` és `update` metódust felül kell írni és első sorba ezt meghívni: `$this->handleFileUpload();`
        <br/><br/>
        A model-ben (pl.: User) le kell kezelni, hogy ha a sort törlik akkor a fájlt is töröljük ki vele együtt. (pl.: Different\DifferentCore\app\Models\User.php -> boot())
        <br/><br/>
        Ha szeretnénk előnézetet is (kép esetén) akkor a 'has_preview' paraméter értékét igazra kell állítani.
        <br/><br/>
        <b class="text-danger">
        FONTOS: Ahhoz hogy a fájl feltöltés működjön a `'upload' => true` kötelező megadni. Ez a Backpack miatt van így.
        Adatbázis szinten figyelni kell hogy ne legyen cascade törlés hiszen akkor a fájl törléssel együtt az adott elem is törlődik amit éppen szerkesztünk.
        </b>
    </p>
    <pre><code class="language-php">[
    'name' => 'profile_image', // Relation neve
    'label' => __('different-core::users.profile_image'), // Cím
    'view_namespace' => 'different-core::fields', // KÖTELEZŐ
    'type' => 'file', // KÖTELEZŐ
    'has_preview' => true, // Kép esetén true
    'upload' => true, // KÖTELEZŐ
    'wrapper' => [ // Opcionális
        'class' => 'form-group col-12',
    ],
],</code></pre>

    <h4 class="text-primary mt-5">Sidebar / menü</h4>
    <p>Új menüpontokat az alábbi fájlban tudsz felvenni: <b>config/different-core/config.php</b>
    <h6 class="my-0">Menüpont</h6>
    <pre><code class="language-php">new SidebarMenuItem(
    '/admin/songs', // Hivatkozás
    'songs.songs', // Fordítható cím
    'las la-music', // Ikon (https://icons8.com/line-awesome)
    // 'song', // Permission a menüpont megjelenítéséhez
),</code></pre>
    <h6 class="my-0">Csoport</h6>
    <pre><code class="language-php">new SidebarMenuGroup(
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
),</code></pre>

    <h4 class="text-primary mt-5">"Breadcrumbs" menü</h4>
    <p>Az adott CRUD <b>setup()</b> metódusába kell ezt a kódrészletet beletenni.</p>
    <pre><code class="language-php">$this->data['breadcrumbs_menu'] = [
        new BreadcrumbMenuItem(
            backpack_url('dashboard'), // Hivatkozás
            __('backpack::crud.admin'), // Cím
            'las la-tachometer-alt', // Ikon (https://icons8.com/line-awesome)
        ),
    ];</code></pre>

    <h4 class="text-primary mt-5">Crud permission(s)</h4>
    <p>Az adott CRUD <b>setup()</b> metódusába kell ezt a kódrészletet beletenni.</p>
    <p>Letiltja az egyes CRUD műveletet ha nincs a $name-el kezdődő permission. Például user estén a következő permission-ök: user-list, user-show, user-delete stb.</p>
    <pre><code class="language-php">crud_permissions($this->crud, 'ide_kell_a_permission');</code></pre>
    <p>Letiltja az összes CRUD műveletet ha nincs ez a permission ($name) a felhasználónak.</p>
    <pre><code class="language-php">crud_permission($this->crud, 'ide_kell_a_permission');</code></pre>

    <h4 class="text-primary mt-5">Általános jogkezelés</h4>
    <p>Az átadott permission-höz van-e joga a felhasználónak.</p>
    <pre><code class="language-php">user_can('ide_kell_a_permission');</code></pre>
    <p>Az átadott permission-ök közül van-e joga a felhasználónak legalább az egyikhez.</p>
    <pre><code class="language-php">user_can_any(['ide_kell_a_permission', 'ide_kell_a_masodik_permission']);</code></pre>
</div>
@endsection
