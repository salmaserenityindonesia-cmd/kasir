<?php
/**
 * @var string $controller_name
 * @var string $table_headers
 * @var array $config
 */
?>

<?= view('partial/header') ?>

<script type="text/javascript">
    $(document).ready(function () {
        <?= view('partial/bootstrap_tables_locale') ?>

        table_support.init({
            resource: '<?= esc($controller_name) ?>',
            headers: <?= $table_headers ?>,
            pageSize: <?= $config['lines_per_page'] ?>,
            uniqueId: 'people.person_id',
            enableActions: function () {
                var email_disabled = $("td input:checkbox[name='btSelectItem']:checked").parents("tr").find("td a[href^='mailto:']").length == 0;
                $("#email").prop('disabled', email_disabled);
            }
        });

        $("#email").click(function (event) {
            var recipients = $.map($("tr.selected a[href^='mailto:']"), function (element) {
                return $(element).attr('href').replace(/^mailto:/, '');
            });
            location.href = "mailto:" + recipients.join(",");
        });

        <?php if ($controller_name === 'customers') { ?>
            $("#mass_bonus_btn").click(function (event) {
                $(this).blur(); // Menghilangkan fokus dari tombol agar tidak terlihat tenggelam

                var selectedRows = [];
                var $selectedCheckboxes = $(".mass_bonus_cb:checked");
                $selectedCheckboxes.each(function () {
                    selectedRows.push($(this).val());
                });

                if (selectedRows.length === 0) {
                    $.notify("Silakan pilih minimal satu pelanggan untuk ditambahkan topup.", { type: 'danger' });
                    return;
                }

                var amount = <?= isset($config['mass_bonus_amount']) ? $config['mass_bonus_amount'] : 50000 ?>;
                // Format the amount
                var formattedAmount = "Rp " + amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");

                BootstrapDialog.confirm({
                    title: 'Konfirmasi Top Up Saldo Massal',
                    message: 'Apakah Anda yakin ingin menambahkan topup sejumlah ' + formattedAmount + ' kepada ' + selectedRows.length + ' pelanggan terpilih?',
                    type: BootstrapDialog.TYPE_INFO,
                    btnOKLabel: 'Ya, Tambahkan',
                    btnCancelLabel: 'Batal',
                    callback: function (result) {
                        if (result) {
                            $.ajax({
                                type: 'POST',
                                url: '<?= site_url("$controller_name/addMassBonus") ?>',
                                data: {
                                    'ids[]': selectedRows,
                                    'csrf_ospos_v4': csrf_token()
                                },
                                success: function (response) {
                                    var data = JSON.parse(response);
                                    if (data.success) {
                                        $.notify(data.message, { type: 'success' });

                                        // Me-refresh tabel secara otomatis menggunakan fungsi dari sistem
                                        setTimeout(function () {
                                            table_support.refresh();

                                            // Hilangkan centangan dari baris yang masih terekam terpilih secara UI
                                            $(".mass_bonus_cb").prop('checked', false);
                                        }, 1000);
                                    } else {
                                        $.notify(data.message, { type: 'danger' });
                                    }
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    $.notify("Gagal memproses permintaan: " + textStatus, { type: 'danger' });
                                }
                            });
                        }
                    }
                });
            });
        <?php } ?>

        $(document).on('click', '#select_all_mass_bonus', function() {
            var isChecked = $(this).is(':checked');
            $(".mass_bonus_cb:not(:disabled)").prop('checked', isChecked);
        });

        // Reset the "Select All" checkbox when the table is refreshed or loaded
        $("#table").on('load-success.bs.table post-body.bs.table', function() {
            $("#select_all_mass_bonus").prop('checked', false);
        });

        // Fallback: If the header is escaped by bootstrap-table, inject the checkbox manually
        var fixMassBonusHeader = function() {
            $("th[data-field='mass_bonus'] .th-inner").each(function() {
                var $this = $(this);
                if ($this.text().indexOf('<input') !== -1) {
                    $this.html($this.text());
                }
            });
        };

        $("#table").on('post-header.bs.table load-success.bs.table post-body.bs.table', fixMassBonusHeader);
        fixMassBonusHeader();
    });
</script>

<div class="page-header-stitch">
    <h1><?= lang('Module.' . $controller_name) ?></h1>
</div>

<div id="title_bar" class="btn-toolbar">
    <?php if ($controller_name === 'customers') { ?>
        <button class="btn btn-success btn-sm pull-right" id="mass_bonus_btn" title="Tambah Saldo Masal">
            <span class="material-symbols-rounded" style="vertical-align: middle; font-size: 18px;">add_circle</span> Tambah Saldo Massal
        </button>
        <button class="btn btn-info btn-sm pull-right modal-dlg" data-btn-submit="<?= lang('Common.submit') ?>"
            data-href="<?= "$controller_name/csvImport" ?>"
            title="<?= lang(ucfirst($controller_name) . '.import_items_csv') ?>">
            <span class="material-symbols-rounded" style="vertical-align: middle; font-size: 18px;">upload_file</span> <?= lang('Common.import_csv') ?>
        </button>
    <?php } ?>
    <button class="btn btn-info btn-sm pull-right modal-dlg" data-btn-submit="<?= lang('Common.submit') ?>"
        data-href="<?= "$controller_name/view" ?>" title="<?= lang(ucfirst($controller_name) . '.new') ?>">
        <span class="material-symbols-rounded" style="vertical-align: middle; font-size: 18px;">person_add</span> <?= lang(ucfirst($controller_name) . '.new') ?>
    </button>
</div>

<div id="toolbar">
    <div class="pull-left btn-toolbar">
        <button id="delete" class="btn btn-default btn-sm">
            <span class="material-symbols-rounded" style="vertical-align: middle; font-size: 18px; color: #d93025;">delete</span> <?= lang('Common.delete') ?>
        </button>
        <button id="email" class="btn btn-default btn-sm">
            <span class="material-symbols-rounded" style="vertical-align: middle; font-size: 18px;">mail</span> <?= lang('Common.email') ?>
        </button>
    </div>
</div>

<div id="table_holder">
    <table id="table"></table>
</div>

<?= view('partial/footer') ?>