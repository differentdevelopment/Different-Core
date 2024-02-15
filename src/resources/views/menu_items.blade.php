<x-backpack::menu-dropdown :title="__('different-core::sidebar.system')" icon="la la-puzzle-piece">
    <x-backpack::menu-dropdown-item :title="__('different-core::users.users')" icon="las la-user" :link="url(route('admin.user.index'))" />
    <x-backpack::menu-dropdown-item :title="__('different-core::accounts.accounts')" icon="las la-users" :link="url(route('admin.account.index'))" />
    <x-backpack::menu-dropdown-item :title="__('different-core::roles.roles')" icon="las la-id-badge" :link="url(route('admin.role.index'))" />
    <x-backpack::menu-dropdown-item :title="__('different-core::activities.activities')" icon="las la-history" :link="url(route('admin.activity.index'))" />
    <x-backpack::menu-dropdown-item :title="__('different-core::files.files')" icon="las la-folder" :link="url(route('admin.filemanager.index'))" />
    <x-backpack::menu-dropdown-item :title="__('different-core::settings.settings')" icon="las la-sliders-h" :link="url(route('admin.settings'))" />
</x-backpack::menu-dropdown>
