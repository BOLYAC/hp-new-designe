<?php

use Illuminate\Support\Facades\Route;

Auth::routes();

/**
 * Clients
 */
//Route::get('clients/list', 'ClientsController@list')->name('clients.list');
Route::group(['prefix' => 'clients', 'middleware' => 'auth'], function () {
    Route::get('data', 'ClientsController@anyData')->name('clients.data');
    Route::get('importExportView', 'ClientsController@importExportView')->name('importExport');
    Route::get('importExportViewZoho', 'ClientsController@importExportViewZoho')->name('importExportZoho');
    Route::post('import', 'ClientsController@import')->name('import');
    Route::post('zoho-import', 'ClientsController@importFromZoho')->name('zohoImport');
    Route::post('notes/ajax', 'NotesController@ajax')->name('getNote');
    Route::post('task/add', 'TasksController@addTask')->name('postTask');
    Route::post('task/archive', 'TasksController@archive')->name('archive');
    Route::post('task/archived', 'TasksController@archived')->name('archived');
    Route::post('fetch', 'ClientsController@fetch')->name('autocomplete.fetch');
});
Route::group(
    ['middleware' => ['auth']],
    function () {
        Route::get('/', 'HomeController@index')->name('home');
        Route::get('clients/all-new', 'HomeController@userNew')->name('dashboard.data');
        Route::resource('clients', 'ClientsController');
        Route::resource('notes', 'NotesController');
        Route::resource('users', 'UsersController');
        Route::resource('roles', 'RolesController');
        Route::resource('projects', 'ProjectsController');
        Route::resource('leads', 'LeadsController');
        Route::resource('tasks', 'TasksController');
        Route::resource('events', 'EventsController');
        Route::resource('sources', 'SourcesController');
        Route::resource('invoices', 'OrderController');
        Route::resource('documents', 'DocumentController');
        Route::resource('payments', 'PaymentController');
        Route::get('/payments-data/{invoice}', 'PaymentController@paymentsDataTable')->name('invoice.paymentsDataTable');
        Route::get('stats', 'StatisticController@index')->name('static.index');
        Route::get('stats/filter', 'StatisticController@getData')->name('static.filter');
        Route::post('/covert-to-order/{lead}', 'LeadsController@convertToOrder')->name('lead.convert.order');
        Route::post('/comments/{type}/{external_id}', 'CommentController@store')->name('comments.create');
        Route::get('calender', 'CalenderController')->name('calender.index');
        Route::post('sales/transfer', 'SalesController@transfer')->name('sales.transfer');
        Route::post('sales/share', 'SalesController@share')->name('sales.share');
        Route::post('sales/mass/share', 'SalesController@massShare')->name('sales.share.mass');
        Route::post('lead/share', 'SalesController@shareLead')->name('sales.shareLead');
        Route::post('lead/stage-change', 'LeadsController@changeStage')->name('stage.change');
        Route::post('lead/change-owner', 'LeadsController@dealChangeOwner')->name('deal.change.owner');
        Route::get('event/search/client', 'EventsController@dataAjax')->name('event.client.filter');
        Route::resource('teams', 'TeamController');

        // Report For events
        Route::get('/show-report/{val}', 'EventsController@showReport')->name('view.report');
        Route::get('/create-report/{val}', 'EventsController@createReport')->name('generate.report');
        Route::post('/create-report/custom', 'EventsController@customReport')->name('generate.custom.report');
        Route::get('/events/duplicate/{event}', 'EventsController@replicate')->name('replicate.event');

        /// Users
        Route::get('/taskdata/{id?}', 'UsersController@taskData')->name('users.taskdata');
        Route::get('/leaddata/{id?}', 'UsersController@leadData')->name('users.leaddata');
        Route::get('/clientdata/{id?}', 'UsersController@clientData')->name('users.clientdata');
        Route::get('/appointmentdata/{id?}', 'UsersController@appointmentData')->name('users.appointmentdata');
        //// Tasks
        Route::get('data', 'TasksController@anyData')->name('tasks.data');
        Route::post('assigne/task', 'TasksController@assigneTask')->name('tasks.assigne');
        //// Projects
        Route::get('/project', 'ProjectsController@getProject')->name('project.api');
        Route::get('/project/show/{id}', 'ProjectsController@getSingleProject')->name('project.api.show');
        // Agencies
        Route::resource('agencies', 'AgencyController');


        // Settings
        Route::get('/settings', 'SettingsController@index')->name('settings.index');

        // Clear Cache
        Route::get('/clear-cache', function () {
            Artisan::call('config:cache');
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');
            return redirect()->back()->with('toast_success', 'Cache cleared!');
        })->name('cacheClear');

        // Backup
        Route::get('/backup-database', function () {
            Artisan::call('backup:run --only-db');
            return redirect()->back()->with('toast_success', 'Databes backup done!');
        })->name('backupDatabes');

        Route::get('/backup-files', function () {
            Artisan::call('backup:run --only-files');
            return redirect()->back()->with('toast_success', 'Files backup done!');
        })->name('backupFiles');

        // Audit
        Route::get('audits', 'AuditController@index');

        // Country and nationality & languages
        Route::get('select/country', 'HomeController@getCountry')->name('country.name');
        Route::get('select/nationality', 'HomeController@getNationality')->name('nationality.name');
        Route::get('select/language', 'HomeController@getLanguage')->name('language.name');
        // Testing

        Route::get('/example-2', 'ClientsController@exampleClient');
        Route::post('/example-2', 'ClientsController@updateClient')->name('mass.update');

        Route::get('/master-details', function () {
            $sources = \App\Models\Source::all();
            $users = \App\Models\User::all();
            return view('clients.master-details')->with(['sources' => $sources, 'users' => $users]);
        })->name('master_details');

        Route::get('/report-details', function () {
            return view('statics.reports');
        })->name('report_details');

        Route::get('/report-leads', function () {
            return view('statics.reports-second');
        })->name('report_leads');
    }
);
