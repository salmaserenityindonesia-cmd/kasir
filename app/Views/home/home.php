<?php
/**
 * @var array $allowed_modules
 */
?>

<?= view('partial/header') ?>

<script type="text/javascript">
    dialog_support.init("a.modal-dlg");
</script>

<div class="page-header-stitch">
    <h1>Beranda Toko</h1>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="stitch-card welcome-banner" style="background: linear-gradient(135deg, #34A853 0%, #1b7a33 100%); color: white; padding: 40px; border-radius: 24px; margin-bottom: 30px; position: relative; overflow: hidden;">
            <div style="position: relative; z-index: 2;">
                <h2 style="font-weight: 700; margin-bottom: 10px; font-size: 32px;">Halo, <?= $user_info->first_name ?>!</h2>
                <p style="font-size: 16px; opacity: 0.9;">Pilih salah satu menu di bawah atau di navigasi atas untuk mulai melayani pelanggan.</p>
            </div>
            <span class="material-symbols-rounded" style="position: absolute; right: -20px; bottom: -20px; font-size: 200px; opacity: 0.1; transform: rotate(-15deg);">storefront</span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="stitch-card" style="padding: 24px; text-align: left;">
            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                <div style="padding: 10px; border-radius: 12px; background: rgba(66, 133, 244, 0.1); color: #4285F4;">
                    <span class="material-symbols-rounded">shopping_cart</span>
                </div>
                <h3 style="margin: 0; font-size: 18px; font-weight: 600;">Penjualan</h3>
            </div>
            <p style="color: var(--stitch-text-secondary); font-size: 14px; margin-bottom: 20px;">Lakukan transaksi penjualan, cetak struk, dan kelola keranjang belanja pelanggan.</p>
            <a href="<?= base_url('sales') ?>" class="btn btn-info btn-sm" style="width: 100%; border-radius: 12px !important;">Mulai Jualan</a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stitch-card" style="padding: 24px; text-align: left;">
            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                <div style="padding: 10px; border-radius: 12px; background: rgba(52, 168, 83, 0.1); color: #34A853;">
                    <span class="material-symbols-rounded">group</span>
                </div>
                <h3 style="margin: 0; font-size: 18px; font-weight: 600;">Pelanggan</h3>
            </div>
            <p style="color: var(--stitch-text-secondary); font-size: 14px; margin-bottom: 20px;">Kelola informasi member, riwayat saldo, dan diskon khusus pelanggan loyal.</p>
            <a href="<?= base_url('customers') ?>" class="btn btn-default btn-sm" style="width: 100%; border-radius: 12px !important;">Pusat Pelanggan</a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stitch-card" style="padding: 24px; text-align: left;">
            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                <div style="padding: 10px; border-radius: 12px; background: rgba(234, 67, 53, 0.1); color: #EA4335;">
                    <span class="material-symbols-rounded">call_received</span>
                </div>
                <h3 style="margin: 0; font-size: 18px; font-weight: 600;">Penerimaan</h3>
            </div>
            <p style="color: var(--stitch-text-secondary); font-size: 14px; margin-bottom: 20px;">Input stok masuk dari supplier dan update harga beli barang masuk.</p>
            <a href="<?= base_url('receivings') ?>" class="btn btn-default btn-sm" style="width: 100%; border-radius: 12px !important;">Input Barang</a>
        </div>
    </div>
</div>

<!-- Redundant modules hidden as requested -->
<?php /* ?>
<h3 class="text-center"><?= lang('Common.welcome_message') ?></h3>

<div id="home_module_list">
    <?php foreach($allowed_modules as $module) { ?>
        <div class="module_item" title="<?= lang("Module.$module->module_id" . '_desc') ?>">
            <a href="<?= base_url($module->module_id) ?>"><img src="<?= base_url("images/menubar/$module->module_id.svg") ?>" style="border-width: 0; height: 64px; max-width: 64px;" alt="Menubar Image"></a>
            <a href="<?= base_url($module->module_id) ?>"><?= lang("Module.$module->module_id") ?></a>
        </div>
    <?php } ?>
</div>
<?php */ ?>

<?= view('partial/footer') ?>
