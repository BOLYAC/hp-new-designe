<?php

use Illuminate\Support\Facades\Route;

Auth::routes();
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
    Route::get('/compose/email/{email?}/{client?}', 'ClientsController@composeEmail')->name('clients.compose.email');
    Route::post('/send/email', 'ClientsController@sendEmail')->name('clients.send.email');
    Route::get('/fields-select', 'ClientsController@getFieldReport')->name('clients.field.report');
    Route::post('/fields-select', 'ClientsController@postFieldReport')->name('clients.field.report.post');
    Route::post('/clients/reports', 'ClientsController@postViewReport')->name('clients.report');
});
Route::group(
    ['middleware' => ['auth']],
    function () {
        Route::get('/', 'HomeController@index')->name('home');
        Route::get('clients/all-new', 'HomeController@userNew')->name('dashboard.data');
        Route::get('agencies/all-new', 'HomeController@agenciesAll')->name('dashboard.agencies.data');
        Route::get('/task-today', 'HomeController@todayTask')->name('dashboard.task_today');
        Route::get('/task-tomorrow', 'HomeController@tomorrowTasks')->name('dashboard.task_tomorrow');
        Route::get('/task-pending', 'HomeController@pendingTasks')->name('dashboard.task_pending');
        Route::get('/task-completed', 'HomeController@completedTasks')->name('dashboard.task_completed');
        Route::resource('clients', 'ClientsController');
        Route::resource('notes', 'NotesController');
        Route::resource('users', 'UsersController');
        Route::resource('roles', 'RolesController');
        Route::resource('tasks', 'TasksController');
        Route::resource('sources', 'SourcesController');
        Route::resource('documents', 'DocumentController');
        Route::resource('payments', 'PaymentController');


        // Deals
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
        Route::post('/lead/reservation-form', 'LeadsController@reservationForm')->name('deal.reservation.form');

        // Teams
        Route::resource('teams', 'TeamController');

        // Invoices
        Route::group(['prefix' => 'invoices'], function () {
            Route::post('/commission-stat', 'OrderController@commissionStat')->name('change.commission_stat');
            Route::get('/payments-data/{invoice}', 'PaymentController@paymentsDataTable')->name('invoice.paymentsDataTable');
            Route::post('/status-change', 'OrderController@changeStatus')->name('invoice.status.change');
            Route::get('/print/{invoice}', 'OrderController@printInvoice')->name('invoices.print');
            Route::get('/data', 'OrderController@anyData')->name('invoices.data');
        });

        Route::resource('invoices', 'OrderController');

        // Events & events report
        Route::get('/events/data', 'EventsController@anyData')->name('events.data');
        Route::get('/show-report/{val}', 'EventsController@showReport')->name('view.report');
        Route::get('/create-report/{val}', 'EventsController@createReport')->name('generate.report');
        Route::post('/create-report/custom', 'EventsController@customReport')->name('generate.custom.report');
        Route::get('/events/duplicate/{event}', 'EventsController@replicate')->name('replicate.event');
        Route::resource('events', 'EventsController');

        // Reports
        Route::get('stats', 'StatisticController@index')->name('static.index');
        Route::get('stats/filter', 'StatisticController@getData')->name('static.filter');
        Route::get('call-report', 'StatisticController@callsIndex')->name('calls.index');
        Route::get('call-report/filter', 'StatisticController@getCallsData')->name('calls.filter');

        /// Users
        Route::get('/taskdata/{id?}', 'UsersController@taskData')->name('users.taskdata');
        Route::get('/leaddata/{id?}', 'UsersController@leadData')->name('users.leaddata');
        Route::get('/clientdata/{id?}', 'UsersController@clientData')->name('users.clientdata');
        Route::get('/appointmentdata/{id?}', 'UsersController@appointmentData')->name('users.appointmentdata');
        Route::get('/contact', 'UsersController@getContact')->name('contact.index');
        //// Tasks
        Route::get('data', 'TasksController@anyData')->name('tasks.data');
        Route::post('assigne/task', 'TasksController@assigneTask')->name('tasks.assigne');
        /**
         * Departments
         */
        Route::group(['prefix' => 'departments'], function () {
            Route::get('/indexData', 'DepartmentController@indexData')->name('departments.indexDataTable');
        });
        Route::resource('departments', 'DepartmentController');
        //// Projects
        Route::get('/projects/getProject/{id}', 'ProjectsController@getProject')->name('project.api');
        //Route::get('/project/show/{id}', 'ProjectsController@getSingleProject')->name('project.api.show');
        Route::get('/project/get-properties/{id}', 'ProjectsController@getProperties')->name('project.properties');
        Route::get('/project/single-project/{id}', 'ProjectsController@getSingleProject')->name('project.single');
        Route::resource('projects', 'ProjectsController');
        // Apartments
        Route::get('/properties/single-property/{id}', 'PropertyController@getSingleProperty')->name('properties.single');
        Route::resource('properties', 'PropertyController');
        // Agencies
        Route::get('/sales/agencies/{id}', 'AgencyController@getAgencySellsOffice')->name('agencies.sells-office-edit');
        Route::resource('agencies', 'AgencyController');

        // Leads
        Route::get('/leads/data', 'LeadsController@anyData')->name('leads.data');
        Route::resource('leads', 'LeadsController');

        // Calls
        Route::post('/alotech/login', 'AloTechController@loginAloTech')->name('alotech.login');
        Route::post('/click-to-call', 'AloTechController@getCall')->name('click2call');
        Route::post('/click-to-hang', 'AloTechController@getHang')->name('click2hang');


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
