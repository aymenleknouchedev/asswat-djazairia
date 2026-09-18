<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BreakingNewsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ComingSoonController;
use App\Http\Controllers\ContentActionController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\ContentReviewController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PrincipalTrendController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TopContentController;
use App\Http\Controllers\TrendController;
use App\Http\Controllers\WindowController;
use App\Http\Controllers\WindowManagementController;
use App\Http\Controllers\WritterController;
use Illuminate\Support\Facades\Route;


Route::prefix('dashboard')->group(function () {

    Route::middleware(['guesto'])->group(function () {
        Route::get('/auth', [AuthController::class, 'auth'])->name('dashboard.user.auth');
        Route::post('/login', [AuthController::class, 'login'])->name('dashboard.login');
    });

    // Routes dashboard  
    Route::middleware(['auth'])->group(function () {

        // Contact messages (contact-us inbox)
        Route::get('/contacts', [ContactMessageController::class, 'index'])->name('dashboard.contacts');
        Route::post('/contact/{id}/update-status', [ContactMessageController::class, 'update_status'])->name('dashboard.contacts.update_status');
        Route::delete('/delete-contact/{id}', [ContactMessageController::class, 'destroy'])->name('dashboard.contacts.delete');

        // Coming soon
        Route::get('/cvs', [ComingSoonController::class, 'index'])->name('dashboard.join-team');
        Route::post('/cv/{id}/update-status', [ComingSoonController::class, 'update_status'])->name('dashboard.join-team.update_status');
        Route::delete('/delete-cv/{id}', [ComingSoonController::class, 'destroy'])->name('dashboard.join-team.delete');

        // Api for select2 ajax search
        Route::get('/api/search-contents', [ApiController::class, 'search_contents'])->name('api.search.contents');
        Route::get('/api/search-categories', [ApiController::class, 'search_categories'])->name('api.search.categories');
        Route::get('/api/search-trends', [ApiController::class, 'search_trends'])->name('api.search.trends');
        Route::get('/api/search-windows', [ApiController::class, 'search_windows'])->name('api.search.windows');
        Route::get('/api/search-tags', [ApiController::class, 'search_tags'])->name('api.search.tags');
        Route::get('/api/search-writers', [ApiController::class, 'search_writers'])->name('api.search.writers');

        Route::get('/api/search-cities', [ApiController::class, 'search_cities'])->name('api.search.cities');
        Route::post('/api/add-category', [ApiController::class, 'add_category'])->name('api.add.category');
        Route::post('/api/add-trend', [ApiController::class, 'add_trend'])->name('api.add.trend');
        Route::post('/api/add-window', [ApiController::class, 'add_window'])->name('api.add.window');
        Route::post('/api/add-writer', [ApiController::class, 'add_writer'])->name('api.add.writer');
        Route::post('/api/add-tag', [ApiController::class, 'add_tag'])->name('api.add.tag');
        Route::post('/api/add-city', [ApiController::class, 'add_city'])->name('api.add.city');

        // Content Reviews
        Route::get('/api/content/{id}/reviews', [ContentReviewController::class, 'getContentReviews'])->name('api.content.reviews');
        Route::post('/api/store/reviews', [ContentReviewController::class, 'store'])->name('api.content.reviews.store');
        Route::put('/api/update/reviews/{id}', [ContentReviewController::class, 'update'])->name('api.content.reviews.update');
        Route::delete('/api/delete/reviews/{id}', [ContentReviewController::class, 'destroy'])->name('api.content.reviews.destroy');

        // List all content reviews
        Route::get('/content-reviews{id}', [ContentReviewController::class, 'index'])->name('content.reviews.index');

        // Dashboard
        Route::get('/home', [DashboardController::class, 'index'])->name('dashboard.index');

        // Settings
        Route::get('/settings', [SettingsController::class, 'settings'])->name('dashboard.settings');
        Route::post('/settings', [SettingsController::class, 'update'])->name('dashboard.settings.update');

        // get all media paginated (for JS fetch)
        Route::get('/media/getAllMediaPaginated', [MediaController::class, 'getAllMediaPaginated'])
            ->name('dashboard.media.getAllMediaPaginated');

        // Entities
        $entities = [
            'user' => AuthController::class,
            'content' => ContentController::class,
            'writer' => WritterController::class,
            'tag' => TagController::class,
            'section' => SectionController::class,
            'categorie' => CategoryController::class,
            'page' => PagesController::class,
            'breakingnew' => BreakingNewsController::class,
            'media' => MediaController::class,
            'trend' => TrendController::class,
            'window' => WindowController::class,
            'location' => LocationController::class,
            'role' => RoleController::class,
            'permission' => PermissionController::class,
        ];

        Route::get('/window-management', [WindowManagementController::class, 'windows_management'])->name('dashboard.windows_management');
        Route::put('/dashboard/sections/{section}/update-window', [WindowManagementController::class, 'updateWindow'])
            ->name('dashboard.sections.updateWindow');

        foreach ($entities as $entity => $controller) {
            $plural = $entity . 's'; // مثال: user => users
            Route::get("/{$plural}", [$controller, 'index'])->name("dashboard.{$plural}.index");
            Route::get("/{$entity}-create", [$controller, 'create'])->name("dashboard.{$entity}.create");
            Route::post("/{$entity}-store", [$controller, 'store'])->name("dashboard.{$entity}.store");
            Route::get("/{$entity}-{id}", [$controller, 'show'])->name("dashboard.{$entity}.show");
            Route::get("/{$entity}{id}", [$controller, 'edit'])->name("dashboard.{$entity}.edit");
            Route::put("/{$entity}-{id}", [$controller, 'update'])->name("dashboard.{$entity}.update");
            Route::delete("/{$entity}-{id}", [$controller, 'destroy'])->name("dashboard.{$entity}.destroy");
        }


        Route::get('/readmore', [ContentController::class, 'readmore'])->name('dashboard.readmore');
        Route::delete('/contents/bulk-delete', [ContentController::class, 'bulkDestroy'])->name('dashboard.contents.bulkDestroy');
        Route::delete('/medias/bulk-delete', [MediaController::class, 'bulkDestroy'])->name('dashboard.medias.bulkDestroy');
        Route::get('/top-contents', [TopContentController::class, 'index'])->name('dashboard.topcontents');
        Route::get('/top-contents/search', [TopContentController::class, 'searchContents'])->name('dashboard.topcontents.search');
        Route::post('/top-contents/{id}', [TopContentController::class, 'store'])->name('dashboard.topcontents.store');
        Route::post('/dashboard/top-contents/update-order', [TopContentController::class, 'updateOrder'])->name('dashboard.topcontents.updateOrder');
        Route::delete('/top-contents/delete-{id}', [TopContentController::class, 'destroy'])->name('dashboard.topcontents.destroy');
        Route::get('/principal-trend', [PrincipalTrendController::class, 'index'])->name('dashboard.principal_trend');
        Route::put('/update-principal-trend', [PrincipalTrendController::class, 'update'])->name('dashboard.principal_trend.update');
        Route::get('/{id}', [ContentActionController::class, 'content_actions'])->name('dashboard.content.actions');
        Route::post('/logout', [AuthController::class, 'logout'])->name('dashboard.logout');
        Route::post('/media-url-store', [MediaController::class, 'storeMediaUrl'])->name('dashboard.media_url.store');
    });
});
