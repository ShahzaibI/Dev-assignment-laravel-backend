<?php

use App\Models\Menu;
use App\Models\Page;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

// ── Helpers ───────────────────────────────────────────────────────────────────

function seedRolesAndPermissions(): void
{
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    $all = [
        'pages.list', 'pages.create', 'pages.edit', 'pages.delete', 'pages.restore',
        'menus.list', 'menus.create', 'menus.edit', 'menus.delete',
        'users.list', 'users.create', 'users.edit', 'users.delete',
        'roles.list', 'roles.create', 'roles.edit', 'roles.delete',
    ];

    foreach ($all as $perm) {
        Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'sanctum']);
    }

    $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'sanctum']);
    $admin->syncPermissions(Permission::where('guard_name', 'sanctum')->get());

    $moderator = Role::firstOrCreate(['name' => 'moderator', 'guard_name' => 'sanctum']);
    $moderator->syncPermissions(['pages.list', 'pages.create', 'pages.edit', 'menus.list']);
}

function makeAdmin(): User
{
    $user = User::factory()->create();
    $user->assignRole(Role::findByName('admin', 'sanctum'));
    return $user;
}

function makeModerator(): User
{
    $user = User::factory()->create();
    $user->assignRole(Role::findByName('moderator', 'sanctum'));
    return $user;
}

beforeEach(fn() => seedRolesAndPermissions());

// ── Auth ──────────────────────────────────────────────────────────────────────

test('user can login with valid credentials', function () {
    $user = User::factory()->create(['password' => bcrypt('secret')]);
    $user->assignRole(Role::findByName('admin', 'sanctum'));

    $this->postJson('/api/login', ['email' => $user->email, 'password' => 'secret'])
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonStructure(['data' => ['token']]);
});

test('login fails with wrong password', function () {
    $user = User::factory()->create(['password' => bcrypt('secret')]);

    $this->postJson('/api/login', ['email' => $user->email, 'password' => 'wrong'])
        ->assertStatus(401);
});

test('unauthenticated request to protected route returns 401', function () {
    $this->getJson('/api/pages')->assertStatus(401);
});

// ── Pages – Admin ─────────────────────────────────────────────────────────────

test('admin can list pages', function () {
    $this->actingAs(makeAdmin(), 'sanctum')
        ->getJson('/api/pages')
        ->assertOk();
});

test('admin can create a page', function () {
    $this->actingAs(makeAdmin(), 'sanctum')
        ->postJson('/api/pages', [
            'title'  => 'Test Page',
            'body'   => '<p>Hello</p>',
            'status' => 'draft',
        ])
        ->assertStatus(201)
        ->assertJsonPath('data.title', 'Test Page');
});

test('admin can edit a page', function () {
    $page = Page::factory()->create();

    $this->actingAs(makeAdmin(), 'sanctum')
        ->putJson("/api/pages/{$page->id}", [
            'title'  => 'Updated Title',
            'body'   => '<p>Updated</p>',
            'status' => 'published',
        ])
        ->assertOk()
        ->assertJsonPath('data.title', 'Updated Title');
});

test('admin can soft-delete a page', function () {
    $page = Page::factory()->create();

    $this->actingAs(makeAdmin(), 'sanctum')
        ->deleteJson("/api/pages/{$page->id}")
        ->assertOk();

    $this->assertSoftDeleted('pages', ['id' => $page->id]);
});

test('admin can restore a soft-deleted page', function () {
    $page = Page::factory()->create();
    $page->delete();

    $this->actingAs(makeAdmin(), 'sanctum')
        ->postJson("/api/pages/{$page->id}/restore")
        ->assertOk();

    $this->assertNotSoftDeleted('pages', ['id' => $page->id]);
});

// ── Pages – Moderator ─────────────────────────────────────────────────────────

test('moderator can list pages', function () {
    $this->actingAs(makeModerator(), 'sanctum')
        ->getJson('/api/pages')
        ->assertOk();
});

test('moderator can create a page', function () {
    $this->actingAs(makeModerator(), 'sanctum')
        ->postJson('/api/pages', [
            'title'  => 'Mod Page',
            'body'   => '<p>Content</p>',
            'status' => 'draft',
        ])
        ->assertStatus(201);
});

test('moderator can edit a page', function () {
    $page = Page::factory()->create();

    $this->actingAs(makeModerator(), 'sanctum')
        ->putJson("/api/pages/{$page->id}", [
            'title'  => 'Mod Edit',
            'body'   => '<p>Edited</p>',
            'status' => 'draft',
        ])
        ->assertOk();
});

test('moderator cannot delete a page', function () {
    $page = Page::factory()->create();

    $this->actingAs(makeModerator(), 'sanctum')
        ->deleteJson("/api/pages/{$page->id}")
        ->assertStatus(403);
});

test('moderator cannot restore a page', function () {
    $page = Page::factory()->create();
    $page->delete();

    $this->actingAs(makeModerator(), 'sanctum')
        ->postJson("/api/pages/{$page->id}/restore")
        ->assertStatus(403);
});

test('moderator cannot list users', function () {
    $this->actingAs(makeModerator(), 'sanctum')
        ->getJson('/api/users')
        ->assertStatus(403);
});

test('moderator cannot create users', function () {
    $this->actingAs(makeModerator(), 'sanctum')
        ->postJson('/api/users', ['name' => 'X', 'email' => 'x@x.com', 'password' => 'password', 'role' => 'moderator'])
        ->assertStatus(403);
});

test('moderator cannot list roles', function () {
    $this->actingAs(makeModerator(), 'sanctum')
        ->getJson('/api/roles')
        ->assertStatus(403);
});

test('moderator cannot delete menus', function () {
    $menu = Menu::factory()->create();

    $this->actingAs(makeModerator(), 'sanctum')
        ->deleteJson("/api/menus/{$menu->id}")
        ->assertStatus(403);
});

// ── Menus ─────────────────────────────────────────────────────────────────────

test('admin can create a menu', function () {
    $this->actingAs(makeAdmin(), 'sanctum')
        ->postJson('/api/menus', ['name' => 'Main Menu', 'sort_order' => 1])
        ->assertStatus(201)
        ->assertJsonPath('data.name', 'Main Menu');
});

test('admin can reorder menus', function () {
    $m1 = Menu::factory()->create(['sort_order' => 1]);
    $m2 = Menu::factory()->create(['sort_order' => 2]);

    $this->actingAs(makeAdmin(), 'sanctum')
        ->putJson('/api/menus/reorder', [
            'items' => [
                ['id' => $m1->id, 'sort_order' => 2, 'parent_id' => null],
                ['id' => $m2->id, 'sort_order' => 1, 'parent_id' => null],
            ],
        ])
        ->assertOk();

    $this->assertDatabaseHas('menus', ['id' => $m1->id, 'sort_order' => 2]);
    $this->assertDatabaseHas('menus', ['id' => $m2->id, 'sort_order' => 1]);
});

// ── Scheduled publishing ──────────────────────────────────────────────────────

test('page with future publish_date is not visible on public endpoint', function () {
    $page = Page::factory()->create([
        'status'       => 'published',
        'publish_date' => now()->addDays(5),
        'slug'         => 'future-page',
    ]);

    $this->getJson("/api/public/pages/{$page->slug}")->assertStatus(404);
});

test('page with past publish_date is visible on public endpoint', function () {
    $page = Page::factory()->create([
        'status'       => 'published',
        'publish_date' => now()->subDay(),
        'slug'         => 'past-page',
    ]);

    $this->getJson("/api/public/pages/{$page->slug}")->assertOk();
});

test('draft page is not visible on public endpoint', function () {
    $page = Page::factory()->create([
        'status' => 'draft',
        'slug'   => 'draft-page',
    ]);

    $this->getJson("/api/public/pages/{$page->slug}")->assertStatus(404);
});

test('published page with no publish_date is visible on public endpoint', function () {
    $page = Page::factory()->create([
        'status'       => 'published',
        'publish_date' => null,
        'slug'         => 'live-page',
    ]);

    $this->getJson("/api/public/pages/{$page->slug}")->assertOk();
});
