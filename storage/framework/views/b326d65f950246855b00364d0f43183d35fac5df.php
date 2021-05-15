<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('style_before'); ?>
    <!-- Notification.css -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/datatables.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/datatable-extension.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

    <script src="<?php echo e(asset('assets/js/datatables/jquery.dataTables.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatables/datatable-extension/dataTables.buttons.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatables/datatable-extension/buttons.bootstrap4.min.js')); ?>"></script>

    <script src="<?php echo e(asset('assets/js/datatables/datatable-extension/dataTables.bootstrap4.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatables/datatable-extension/dataTables.responsive.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatables/datatable-extension/responsive.bootstrap4.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatables/datatable-extension/dataTables.colReorder.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/datatables/datatable-extension/dataTables.rowReorder.min.js')); ?>"></script>

    <script>
        $('#lead-table').DataTable({
            destroy: true,
            stateSave: false,
            order: [[0, 'asc']],
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: '<?php echo route('dashboard.data'); ?>'
            },
            columns: [
                {
                    data: 'id',
                    name: 'id',
                    visible: false
                },
                {
                    data: 'public_id',
                    name: 'public_id'
                },
                {
                    data: 'full_name',
                    name: 'full_name',
                },
                {
                    data: 'country',
                    name: 'country'
                },
                {
                    data: 'nationality',
                    name: 'nationality'
                },
                {
                    data: 'status',
                    name: 'status',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'priority',
                    name: 'priority',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'appointment_date',
                    name: 'appointment_date',
                }
            ],
        });
        $('#today-table').DataTable();
        $('#tomorrow-table').DataTable();
        $('#pending-table').DataTable();
        $('#completed-table').DataTable();
    </script>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('breadcrumb-items'); ?>
    <li class="breadcrumb-item">Dashboard</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb-title'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-9 xl-100 box-col-12">
                <div class="row">
                    <div class="col-12">
                        <div class="project-overview">
                            <div class="row">
                                <div class="col-xl-2 col-sm-4 col-6">
                                    <h2 class="f-w-600 counter font-primary"><?php echo e($allClients); ?></h2>
                                    <p class="mb-0"><?php echo e(__('Total leads')); ?></p>
                                </div>
                                <div class="col-xl-2 col-sm-4 col-6">
                                    <h2 class="f-w-600 counter font-secondary"><?php echo e($olderTask); ?></h2>
                                    <p class="mb-0"><?php echo e(__('Past tasks')); ?></p>
                                </div>
                                <div class="col-xl-2 col-sm-4 col-6">
                                    <h2 class="f-w-600 counter font-success"><?php echo e($completedTasks->count()); ?></h2>
                                    <p class="mb-0"><?php echo e(__('Completed tasks')); ?></p>
                                </div>
                                <div class="col-xl-2 col-sm-4 col-6">
                                    <h2 class="f-w-600 counter font-info"><?php echo e($todayTasks->count()); ?></h2>
                                    <p class="mb-0"><?php echo e(__('Today tasks')); ?></p>
                                </div>
                                <div class="col-xl-2 col-sm-4 col-6">
                                    <h2 class="f-w-600 counter font-warning"><?php echo e($events); ?></h2>
                                    <p class="mb-0"><a
                                            href="<?php echo e(route('events.index', 'today-event')); ?>"><?php echo e(__('Today event(s)')); ?></a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-xl-6 xl-100">
            <div class="card b-t-primary">
                <div class="card-body">
                    <ul class="nav nav-tabs border-tab" id="top-tab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" id="top-lead-tab" data-toggle="tab"
                                                href="#top-lead" role="tab" aria-controls="top-lead"
                                                aria-selected="true"><?php echo e(__('New lead')); ?></a>
                        </li>
                        <li class="nav-item"><a class="nav-link" id="today-top-tab" data-toggle="tab"
                                                href="#top-today" role="tab" aria-controls="top-today"
                                                aria-selected="false"><?php echo e(__('Today tasks')); ?></a></li>
                        <li class="nav-item"><a class="nav-link" id="tomorrow-top-tab" data-toggle="tab"
                                                href="#top-tomorrow" role="tab" aria-controls="top-tomorrow"
                                                aria-selected="false"><?php echo e(__('Tomorrow tasks')); ?></a></li>
                        <li class="nav-item"><a class="nav-link" id="pending-top-tab" data-toggle="tab"
                                                href="#top-pending" role="tab" aria-controls="top-pending"
                                                aria-selected="false"><?php echo e(__('Pending tasks')); ?></a>
                        </li>
                        <li class="nav-item"><a class="nav-link" id="completed-top-tab" data-toggle="tab"
                                                href="#top-completed" role="tab" aria-controls="top-completed"
                                                aria-selected="false"><?php echo e(__('Completed Tasks')); ?></a></li>
                    </ul>
                    <div class="tab-content" id="top-tabContent">
                        <div class="tab-pane fade show active" id="top-lead" role="tabpanel"
                             aria-labelledby="top-lead-tab">
                            <div class="order-history dt-ext table-responsive">
                                <table class="display" id="lead-table">
                                    <thead>
                                    <tr>
                                        <th data-priority="1">ID</th>
                                        <th data-priority="2">N°</th>
                                        <th><?php echo e(__('Name')); ?></th>
                                        <th><?php echo e(__('Country')); ?></th>
                                        <th><?php echo e(__('Nationality')); ?></th>
                                        <th><?php echo e(__('Status')); ?></th>
                                        <th><?php echo e(__('Priority')); ?></th>
                                        <th data-priority="3"><?php echo e(__('Date of coming')); ?></th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="top-today" role="tabpanel" aria-labelledby="profile-top-tab">
                            <div class="dt-ext table-responsive">
                                <table class="display" id="today-table">
                                    <thead>
                                    <tr>
                                        <th><?php echo e(__('Title')); ?></th>
                                        <th><?php echo e(__('Client name')); ?></th>
                                        <th><?php echo e(__('Country')); ?></th>
                                        <th><?php echo e(__('Nationality')); ?></th>
                                        <th><?php echo e(__('Date')); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $__currentLoopData = $todayTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $todayTask): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="unread">
                                            <td><a href="#!" class="email-name"><?php echo e($todayTask->title ?? ''); ?></a>
                                            </td>
                                            <td>
                                                <?php echo e(optional($todayTask->client)->full_name); ?>

                                                <?php echo e(optional($todayTask->agency)->title); ?>

                                            </td>
                                            <td>
                                                <?php if(is_null($todayTask->client->country)): ?>
                                                    <div class="col-form-label">
                                                        <?php echo e($todayTask->client->getRawOriginal('country') ?? ''); ?></div>
                                                <?php else: ?>
                                                    <?php $countries = collect($todayTask->client->country)->toArray() ?>
                                                    <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <span class="badge badge-inverse"><?php echo e($name); ?></span>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if(is_null($todayTask->client->nationality)): ?>
                                                    <?php echo e($todayTask->client->getRawOriginal('nationality') ?? ''); ?>

                                                <?php else: ?>
                                                    <?php $countries = collect($todayTask->client->nationality)->toArray() ?>
                                                    <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <span class="badge badge-inverse"><?php echo e($name); ?></span>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            </td>
                                            <td class="email-time">
                                                <?php echo e(Carbon\Carbon::parse($todayTask->date)->format('Y-m-d H:i')); ?>

                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="top-tomorrow" role="tabpanel" aria-labelledby="tomorrow-top-tab">
                            <div class="dt-ext table-responsive">
                                <table class="display" id="tomorrow-table">
                                    <thead>
                                    <tr>
                                        <th><?php echo e(__('Title')); ?></th>
                                        <th><?php echo e(__('Client name')); ?></th>
                                        <th><?php echo e(__('Country')); ?></th>
                                        <th><?php echo e(__('Nationality')); ?></th>
                                        <th><?php echo e(__('Date')); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $__currentLoopData = $tomorrowTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tomorrowTask): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="unread">
                                            <td><a href="#!" class="email-name"><?php echo e($tomorrowTask->title ?? ' '); ?></a>
                                            </td>
                                            <td>
                                                <?php echo e(optional($tomorrowTask->client)->full_name); ?>

                                                <?php echo e(optional($tomorrowTask->agency)->title); ?>

                                            </td>
                                            <td>
                                                <?php if(is_null($tomorrowTask->client->country)): ?>
                                                    <?php echo e($tomorrowTask->client->getRawOriginal('country') ?? ''); ?>

                                                <?php else: ?>
                                                    <?php $countries = collect($tomorrowTask->client->country)->toArray() ?>
                                                    <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <span class="badge badge-inverse"><?php echo e($name); ?></span>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if(is_null($tomorrowTask->client->nationality)): ?>
                                                    <?php echo e($client->getRawOriginal('nationality') ?? ''); ?>

                                                <?php else: ?>
                                                    <?php $nat = collect($tomorrowTask->client->nationality)->toArray() ?>
                                                    <?php $__currentLoopData = $nat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <span class="badge badge-inverse"><?php echo e($name); ?></span>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            </td>
                                            <td class="email-time">
                                                <?php echo e(Carbon\Carbon::parse($tomorrowTask->date)->format('Y-m-d H:i')); ?>

                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="top-pending" role="tabpanel" aria-labelledby="tomorrow-top-tab">
                            <div class="dt-ext table-responsive">
                                <table class="display" id="pending-table">
                                    <thead>
                                    <tr>
                                        <th><?php echo e(__('Title')); ?></th>
                                        <th><?php echo e(__('Client name')); ?></th>
                                        <th><?php echo e(__('Country')); ?></th>
                                        <th><?php echo e(__('Nationality')); ?></th>
                                        <th><?php echo e(__('Date')); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $__currentLoopData = $pendingTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pendingTask): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><a href="#!" class="email-name"><?php echo e($pendingTask->title ?? ''); ?></a>
                                            </td>
                                            <td>

                                                <?php echo e(optional($pendingTask->client)->full_name); ?>

                                                <?php echo e(optional($pendingTask->agency)->title); ?>

                                            </td>
                                            <td>
                                                <?php if(is_null($pendingTask->client->country)): ?>
                                                    <?php echo e($pendingTask->client->getRawOriginal('country') ?? ''); ?>

                                                <?php else: ?>
                                                    <?php $countries = collect($pendingTask->client->country)->toArray() ?>
                                                    <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <span class="badge badge-light-primary"><?php echo e($name); ?></span>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if(is_null($pendingTask->client->nationality)): ?>
                                                    <?php echo e($pendingTask->client->getRawOriginal('nationality') ?? ''); ?>

                                                <?php else: ?>
                                                    <?php $nat = collect($pendingTask->client->nationality)->toArray() ?>
                                                    <?php $__currentLoopData = $nat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <span class="badge badge-light-primary"><?php echo e($name); ?></span>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            </td>
                                            <td class="email-time">
                                                <?php echo e(Carbon\Carbon::parse($pendingTask->date)->format('Y-m-d')); ?>

                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="top-completed" role="tabpanel"
                             aria-labelledby="completed-top-tab">
                            <div class="users-total table-responsive">
                                <table class="table" id="completed-table">
                                    <thead>
                                    <tr>
                                        <th width="5%"><?php echo e(__('Stat')); ?></th>
                                        <th><?php echo e(__('Title')); ?></th>
                                        <th><?php echo e(__('Client')); ?></th>
                                        <th><?php echo e(__('Country')); ?></th>
                                        <th><?php echo e(__('Nationality')); ?></th>
                                        <th><?php echo e(__('Date')); ?></th>
                                        <th><?php echo e(__('Completed at')); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $__currentLoopData = $completedTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $completedTask): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <div class="round-product"><i
                                                        class="icofont icofont-check"></i></div>
                                            </td>
                                            <td><a href="#!" class="email-name">
                                                    <?php echo e($completedTask->title ?? ''); ?>

                                                </a>
                                            </td>
                                            <td>
                                                <?php echo e(optional($completedTask->client)->full_name); ?>

                                                <?php echo e(optional($completedTask->agency)->title); ?>

                                            </td>
                                            <td>
                                                <?php if(is_null($completedTask->client->country)): ?>
                                                    <?php echo e($completedTask->client->getRawOriginal('country') ?? ''); ?>

                                                <?php else: ?>
                                                    <?php $countries = collect($completedTask->client->country)->toArray() ?>
                                                    <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <span class="badge badge-light-primary"><?php echo e($name); ?></span>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if(is_null($completedTask->client->nationality)): ?>
                                                    <?php echo e($completedTask->client->getRawOriginal('nationality') ?? ''); ?>

                                                <?php else: ?>
                                                    <?php $nat = collect($completedTask->client->nationality)->toArray() ?>
                                                    <?php $__currentLoopData = $nat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <span class="badge badge-light-primary"><?php echo e($name); ?></span>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php echo e(Carbon\Carbon::parse($completedTask->date)->format('Y-m-d')); ?>

                                            </td>
                                            <td>
                                                <?php echo e(Carbon\Carbon::parse($completedTask->updated_at)->format('Y-m-d')); ?>

                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vertical.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\hp\resources\views/dashboard/index.blade.php ENDPATH**/ ?>