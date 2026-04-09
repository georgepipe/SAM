<?php require APPROOT . '/views/inc/header.php'; ?>

<style>
  body#table {
    background: #ffffff;
    color: #111111;
    font-family: Arial, Helvetica, sans-serif;
}

.back-link-wrap {
    margin: 1.5rem 0 1rem;
    padding: 0 1.25rem;
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    color: #111111;
    text-decoration: none;
    font-size: 0.95rem;
    font-weight: 500;
}

.back-link:hover {
    text-decoration: underline;
}

.workorder-description {
    max-width: 1100px;
    margin: 0 auto 2rem;
    padding: 0 1.25rem;
    text-align: center;
    font-size: 0.98rem;
    line-height: 1.6;
    color: #444444;
}

.spec-page {
    max-width: 1100px;
    margin: 0 auto 4rem;
    padding: 0 1.25rem;
}

.spec-section__heading {
    border-bottom: 1px solid #d9d9d9;
    padding-bottom: 0.8rem;
    margin-bottom: 2rem;
}

.spec-section__heading h1 {
    margin: 0;
    font-size: 1.8rem;
    font-weight: 600;
    letter-spacing: 0.02em;
    color: #111111;
}

.spec-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 2rem;
}

.spec-panel {
    background: #ffffff;
}

.spec-panel__title {
    margin: 0 0 1rem;
    font-size: 0.9rem;
    font-weight: 700;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: #666666;
}

.spec-row {
    display: grid;
    grid-template-columns: 1fr;
    gap: 0.35rem;
    padding: 0.95rem 0;
    border-bottom: 1px solid #ebebeb;
}

.spec-row:first-of-type {
    border-top: 1px solid #ebebeb;
}

.spec-label {
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.14em;
    color: #6a6a6a;
}

.spec-value {
    font-size: 1rem;
    line-height: 1.5;
    color: #111111;
    word-break: break-word;
}

.spec-value a {
    color: #111111;
    text-decoration: none;
    border-bottom: 1px solid transparent;
}

.spec-value a:hover {
    border-bottom-color: #111111;
}

@media (min-width: 800px) {
    .spec-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 3rem;
    }

    .spec-row {
        grid-template-columns: minmax(160px, 220px) 1fr;
        align-items: start;
        gap: 1rem;
    }
}
</style>
<?php
// model
$model = $data->workorder->model->name ?? '';

// finish
$ral = $data->workorder->cab_finish->ral_code ?? '';
$finish = $data->workorder->cab_finish->name ?? '';
if ($finish !== '') {
    $finish .= $ral !== '' ? ' (' . $ral . ')' : '';
}

// grille
$grille = $data->workorder->product->grille_finish_id ?? '';

// waveguide
$waveguide = $data->workorder->waveguide->name ?? '';

// avn pdf
$avnNumber = str_pad($data->workorder->avn ?? 0, 5, '0', STR_PAD_LEFT);
$avnUrl = URLROOT . 'advice_notes/AVN_' . $avnNumber . '.pdf';
?>

<div class="back-link-wrap">
    <a class="back-link" href="javascript:history.go(-1)">
        <?php include APPROOT . '/views/components/icons/backicon.php'; ?>
        <span>Back</span>
    </a>
</div>

<?php if (!empty($data->workorder->pdesc)): ?>
    <p class="workorder-description">
        <?php echo htmlspecialchars($data->workorder->pdesc); ?>
    </p>
<?php endif; ?>

<main class="spec-page">
    <section class="spec-section">
        <div class="spec-section__heading">
            <h1>Technical Specification</h1>
        </div>

        <div class="spec-grid">
            <article class="spec-panel">
                <h2 class="spec-panel__title">Work Order Details</h2>

                <div class="spec-row">
                    <span class="spec-label">WKO</span>
                    <span class="spec-value"><?php echo htmlspecialchars($data->workorder->wko); ?></span>
                </div>

                <div class="spec-row">
                    <span class="spec-label">AVN</span>
                    <span class="spec-value">
                        <a
                            href="#"
                            onclick="window.open('<?php echo $avnUrl; ?>', 'AVNwindow', 'width=400,height=600'); return false;"
                        >
                            <?php echo htmlspecialchars($data->workorder->avn); ?>
                        </a>
                    </span>
                </div>

                <div class="spec-row">
                    <span class="spec-label">Quantity Required</span>
                    <span class="spec-value"><?php echo htmlspecialchars($data->workorder->quantity); ?></span>
                </div>

                <div class="spec-row">
                    <span class="spec-label">Unit Price</span>
                    <span class="spec-value"><?php echo htmlspecialchars($data->workorder->model->build_price); ?></span>
                </div>

                <div class="spec-row">
                    <span class="spec-label">Serial(s)</span>
                    <span class="spec-value"><?php echo htmlspecialchars($data->workorder->serials); ?></span>
                </div>

                <div class="spec-row">
                    <span class="spec-label">Current Status</span>
                    <span class="spec-value"><?php echo htmlspecialchars($data->workorder->wko_status); ?></span>
                </div>

                <div class="spec-row">
                    <span class="spec-label">Transport</span>
                    <span class="spec-value"><?php echo htmlspecialchars($data->workorder->wko_delivery); ?></span>
                </div>

                <div class="spec-row">
                    <span class="spec-label">Notes</span>
                    <span class="spec-value"><?php echo nl2br(htmlspecialchars($data->workorder->wko_notes)); ?></span>
                </div>

                <div class="spec-row">
                    <span class="spec-label">Added</span>
                    <span class="spec-value"><?php echo htmlspecialchars($data->workorder->created_at); ?></span>
                </div>

                <div class="spec-row">
                    <span class="spec-label">Updated At</span>
                    <span class="spec-value"><?php echo htmlspecialchars($data->workorder->updated_at ?? ''); ?></span>
                </div>
            </article>

            <article class="spec-panel">
                <h2 class="spec-panel__title">Product Information</h2>

                <div class="spec-row">
                    <span class="spec-label">Model</span>
                    <span class="spec-value">
                        <a href="<?php echo URLROOT; ?>models/viewmodel/<?php echo urlencode($data->workorder->product->cab_model_id); ?>">
                            <?php echo htmlspecialchars($model); ?>
                        </a>
                    </span>
                </div>

                <?php if ($finish !== ''): ?>
                    <div class="spec-row">
                        <span class="spec-label">Cab Finish</span>
                        <span class="spec-value"><?php echo htmlspecialchars($finish); ?></span>
                    </div>
                <?php endif; ?>

                <?php if ($grille !== ''): ?>
                    <div class="spec-row">
                        <span class="spec-label">Grille Finish</span>
                        <span class="spec-value"><?php echo htmlspecialchars($grille); ?></span>
                    </div>
                <?php endif; ?>

                <?php if ($waveguide !== ''): ?>
                    <div class="spec-row">
                        <span class="spec-label">Waveguide Finish</span>
                        <span class="spec-value"><?php echo htmlspecialchars($waveguide); ?></span>
                    </div>
                <?php endif; ?>

                <div class="spec-row">
                    <span class="spec-label">Connectors</span>
                    <span class="spec-value"><?php echo htmlspecialchars($data->workorder->product->connectors); ?></span>
                </div>

                <div class="spec-row">
                    <span class="spec-label">Fixings</span>
                    <span class="spec-value"><?php echo htmlspecialchars($data->workorder->product->fixings); ?></span>
                </div>

                <?php if ($data->workorder->wheels === '1'): ?>
                    <div class="spec-row">
                        <span class="spec-label">Wheels</span>
                        <span class="spec-value">Fitted</span>
                    </div>
                <?php endif; ?>

            </article>
        </div>
    </section>
</main>




<pre><?php print_r($data);?></pre>
<?php require APPROOT . '/views/inc/footer.php'; ?>