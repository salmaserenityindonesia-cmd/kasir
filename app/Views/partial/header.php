<?php
/**
 * @var object $user_info
 * @var array $allowed_modules
 * @var CodeIgniter\HTTP\IncomingRequest $request
 * @var array $config
 */

use Config\Services;

$request = Services::request();
?>

<!doctype html>
<html lang="<?= $request->getLocale() ?>">

<head>
    <meta charset="utf-8">
    <base href="<?= base_url() ?>">
    <title><?= esc($config['company']) . ' | ' . lang('Common.powered_by') . ' kasir ' . esc(config('App')->application_version) ?></title>
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">
    <link rel="stylesheet" href="<?= 'resources/bootswatch/' . (empty($config['theme']) ? 'flatly' : esc($config['theme'])) . '/bootstrap.min.css' ?>">

    <?php if (ENVIRONMENT == 'development' || get_cookie('debug') == 'true' || $request->getGet('debug') == 'true') : ?>
        <!-- inject:debug:css -->
        <link rel="stylesheet" href="resources/css/jquery-ui-fe010342cb.css">
        <link rel="stylesheet" href="resources/css/bootstrap-dialog-1716ef6e7c.css">
        <link rel="stylesheet" href="resources/css/jasny-bootstrap-40bf85f3ed.css">
        <link rel="stylesheet" href="resources/css/bootstrap-datetimepicker-66374fba71.css">
        <link rel="stylesheet" href="resources/css/bootstrap-select-66d5473b84.css">
        <link rel="stylesheet" href="resources/css/bootstrap-table-ed9d1a3360.css">
        <link rel="stylesheet" href="resources/css/bootstrap-table-sticky-header-07d65e7533.css">
        <link rel="stylesheet" href="resources/css/daterangepicker-85523b7dfe.css">
        <link rel="stylesheet" href="resources/css/chartist-c19aedb81a.css">
        <link rel="stylesheet" href="resources/css/chartist-plugin-tooltip-2e0ec92e60.css">
        <link rel="stylesheet" href="resources/css/bootstrap-tagsinput-5a6d46a06c.css">
        <link rel="stylesheet" href="resources/css/bootstrap-toggle-e12db6c1f3.css">
        <link rel="stylesheet" href="resources/css/bootstrap-4875cf7b0d.autocomplete.css">
        <link rel="stylesheet" href="resources/css/invoice-a99a4dfac3.css">
        <link rel="stylesheet" href="resources/css/ospos_print-bf10c1438b.css">
        <link rel="stylesheet" href="resources/css/ospos-28f7f540a3.css">
        <link rel="stylesheet" href="resources/css/popupbox-57d45cb822.css">
        <link rel="stylesheet" href="resources/css/receipt-0606f1c54e.css">
        <link rel="stylesheet" href="resources/css/register-a6a6cc948d.css">
        <link rel="stylesheet" href="resources/css/reports-ace7faf688.css">
        <!-- endinject -->
        <!-- inject:debug:js -->
        <script src="resources/js/jquery-12e87d2f3a.js"></script>
        <script src="resources/js/jquery-4fa896f615.form.js"></script>
        <script src="resources/js/jquery-a0350e8820.validate.js"></script>
        <script src="resources/js/jquery-ui-cbc65ff85e.js"></script>
        <script src="resources/js/bootstrap-894d79839f.js"></script>
        <script src="resources/js/bootstrap-dialog-27123abb65.js"></script>
        <script src="resources/js/jasny-bootstrap-7c6d7b8adf.js"></script>
        <script src="resources/js/bootstrap-datetimepicker-25e39b7ef8.js"></script>
        <script src="resources/js/bootstrap-select-b01896a67b.js"></script>
        <script src="resources/js/bootstrap-table-bdb06552ea.js"></script>
        <script src="resources/js/bootstrap-table-export-6389dc2aa5.js"></script>
        <script src="resources/js/bootstrap-table-mobile-fc655b68ab.js"></script>
        <script src="resources/js/bootstrap-table-sticky-header-cb4d83d172.js"></script>
        <script src="resources/js/moment-d65dc6d2e6.min.js"></script>
        <script src="resources/js/daterangepicker-048c56a690.js"></script>
        <script src="resources/js/es6-promise-855125e6f5.js"></script>
        <script src="resources/js/FileSaver-e73b1946e8.js"></script>
        <script src="resources/js/html2canvas-e1d3a8d7cd.js"></script>
        <script src="resources/js/jspdf-6eb90bf5a3.umd.js"></script>
        <script src="resources/js/jspdf-4f52bd767f.plugin.autotable.js"></script>
        <script src="resources/js/tableExport-0df60917ca.min.js"></script>
        <script src="resources/js/chartist-8a7ecb4445.js"></script>
        <script src="resources/js/chartist-plugin-pointlabels-0a1ab6aa4e.js"></script>
        <script src="resources/js/chartist-plugin-tooltip-116cb48831.js"></script>
        <script src="resources/js/chartist-plugin-axistitle-80a1198058.js"></script>
        <script src="resources/js/chartist-plugin-barlabels-4165273742.js"></script>
        <script src="resources/js/bootstrap-notify-376bc6eb87.js"></script>
        <script src="resources/js/js-fa93e8894e.cookie.js"></script>
        <script src="resources/js/bootstrap-tagsinput-855a7c7670.js"></script>
        <script src="resources/js/bootstrap-toggle-1c7a19a049.js"></script>
        <script src="resources/js/clipboard-908af414ab.js"></script>
        <script src="resources/js/imgpreview-1db063409f.full.jquery.js"></script>
        <script src="resources/js/manage_tables-9b98d5573a.js"></script>
        <script src="resources/js/nominatim-89be77a11a.autocomplete.js"></script>
        <!-- endinject -->
    <?php else : ?>
        <!--inject:prod:css -->
        <link rel="stylesheet" href="resources/opensourcepos-8e34d6a398.min.css">
        <!-- endinject -->

        <!-- Tweaks to the UI for a particular theme should drop here  -->
        <?php if ($config['theme'] != 'flatly' && file_exists($_SERVER['DOCUMENT_ROOT'] . '/public/css/' . esc($config['theme']) . '.css')) { ?>
            <link rel="stylesheet" href="<?= 'css/' . esc($config['theme']) . '.css' ?>">
        <?php } ?>
        <!-- inject:prod:js -->
        <script src="resources/jquery-2c872dbe60.min.js"></script>
        <script src="resources/opensourcepos-39c74204a5.min.js"></script>
        <!-- endinject -->
    <?php endif; ?>

    <link rel="stylesheet" href="resources/css/local-fonts.css">
    <link rel="stylesheet" href="resources/css/stitch-menu.css">

    <?= view('partial/header_js') ?>
    <?= view('partial/lang_lines') ?>

    <style>
        html {
            overflow: auto;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="topbar">
            <div class="container">
                <div class="navbar-left" style="display: flex; align-items: center; gap: 4px;">
                    <span class="material-symbols-rounded" style="font-size: 18px; color: var(--stitch-primary);">schedule</span>
                    <div id="liveclock" style="display: flex; align-items: center;"><?= date($config['dateformat'] . ' ' . $config['timeformat']) ?></div>
                </div>

                <div class="navbar-right" style="margin: 0; display: flex; align-items: center; gap: 15px;">
                    <span class="material-symbols-rounded" style="font-size: 18px; color: var(--stitch-text-secondary);">account_circle</span>
                    <?= anchor("home/changePassword/$user_info->person_id", "$user_info->first_name $user_info->last_name", ['class' => 'modal-dlg', 'data-btn-submit' => lang('Common.submit'), 'title' => lang('Employees.change_password')]) ?>
                    <span style="color: #dadce0;">|</span>
                    <a href="<?= site_url('home/logout') ?>" style="display: flex; align-items: center; gap: 4px;">
                        <span class="material-symbols-rounded" style="font-size: 18px; color: #d93025;">logout</span>
                        <?= lang('Login.logout') ?>
                    </a>
                </div>

                <div class="navbar-center" style="text-align: center;">
                    <a href="<?= site_url() ?>" style="text-decoration: none; color: inherit;">
                        <h4><strong><?= esc($config['company']) ?></strong></h4>
                    </a>
                </div>
            </div>
        </div>

        <div class="navbar navbar-default" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                   <!-- <a class="navbar-brand hidden-sm" href="<?php // echo site_url() ?>">kasir</a>-->
                </div>

                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <?php
                        $icon_mapping = [
                            'customers'   => 'group',
                            'items'       => 'inventory_2',
                            'item_kits'   => 'layers',
                            'suppliers'   => 'local_shipping',
                            'reports'     => 'analytics',
                            'receivings'  => 'call_received',
                            'sales'       => 'shopping_cart',
                            'employees'   => 'badge',
                            'giftcards'   => 'card_giftcard',
                            'messages'    => 'mail',
                            'expenses'    => 'payments',
                            'cashups'     => 'account_balance_wallet',
                            'config'      => 'settings',
                            'home'        => 'home',
                            'attributes'  => 'label',
                            'taxes'       => 'percent',
                            'office'      => 'business'
                        ];
                        ?>
                        <?php foreach ($allowed_modules as $module): ?>
                            <li class="<?= $module->module_id == $request->getUri()->getSegment(1) ? 'active' : '' ?>">
                                <a href="<?= base_url($module->module_id) ?>" title="<?= lang("Module.$module->module_id") ?>" class="menu-icon">
                                    <span class="material-symbols-rounded">
                                        <?= $icon_mapping[$module->module_id] ?? 'apps' ?>
                                    </span>
                                    <img src="<?= base_url("images/menubar/$module->module_id.svg") ?>" style="border: none;" alt="Module Icon">
                                    <?= lang('Module.' . $module->module_id) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
