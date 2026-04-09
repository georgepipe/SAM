
<!-- <div class="backBtn">
    <a class="" href="javascript:history.go(-1)"><?php include APPROOT.'/views/components/icons/backicon.php'; ?>Back</a>
    <BR>
</div>
<p class=" ml-3 self-center text-center"><?php echo $data->workorder->pdesc; ?></p>
<?php

//model
$model = $data->workorder->model->name;

//finish
$ral = $data->workorder->cab_finish->ral_code ?? '';
$finish = $data->workorder->cab_finish->name ?? '';
$finish != '' ? $finish = $finish.' ('.$ral.')': '';
//$data->workorder->cab_finish->name; if($data->workorder->cab_finish->ral_code != '') {echo ' ('.$data->workorder->cab_finish->ral_code.')';}; 

//grille
$grille = $data->workorder->product->grille_finish_id ?? '';

//waveguide
$waveguide = $data->workorder->waveguide->name ?? '';

?>

<body id="table">


  <div class="header">
    <div class="work">Work Order Details</div>
    <div class="product">Product Information</div>
  </div>
  
  <div class="container">
    <div class="section">
      <div class="value"><span class="label">WKO:</span><?php echo $data->workorder->wko; ?> <span class="label">     AVN:<a href="#" onclick="AVNwindow=window.open('<?php echo URLROOT?>advice_notes/AVN_<?php echo str_pad($data->workorder->avn, 5,'0', STR_PAD_LEFT).'.pdf'?>', 'AVNwindow', 'width=400, height=600');"></span> <?php echo $data->workorder->avn; ?></a></div>
      <div class="value"><span class="label">Quantity Required:</span> <?php echo $data->workorder->quantity; ?></div>
      <div class="value"><span class="label">Unit Price:</span> <?php echo $data->workorder->model->build_price; ?></div>
      <div class="value"><span class="label">Serial(s):</span> <?php echo $data->workorder->serials; ?></div>
      <div class="value"><span class="label">Current Status:</span> <?php echo $data->workorder->wko_status; ?></div>
      <div class="value"><span class="label">Transport:</span> <?php echo $data->workorder->wko_delivery; ?></div>
      <div class="value"><span class="label">Notes:</span> <?php echo $data->workorder->wko_notes; ?></div>
      <div class="value"><span class="label">Added:</span> <?php echo $data->workorder->created_at; ?></div>
      <div class="value"><span class="label">Updated At:</span> 2024-12-20 11:23:01</div>
    </div>
    <div class="section">
      <div class="value"><span class="label">Model:</span> <a href="<?php echo URLROOT?>models/viewmodel/<?php echo $data->workorder->product->cab_model_id ?>"><?php echo $model; ?></a></div>
      <?php if($finish != ''): ?>
        <div class="value"><span class="label">Cab Finish:</span> <?php echo $finish?></div>
      <?php endif ?>
      <?php if($grille != ''): ?>
        <div class="value"><span class="label">Grille Finish:</span> <?php echo $grille ?></div>
      <?php endif ?>
      <?php if($waveguide != ''):?>
        <div class="value"><span class="label">Waveguide Finish:</span> <?php echo $waveguide; ?></div>
      <?php endif ?>
      <div class="value"><span class="label">Connectors:</span> <?php echo $data->workorder->product->connectors; ?></div>
      <div class="value"><span class="label">Fixings:</span> <?php echo $data->workorder->product->fixings; ?></div>
      <?php if($data->workorder->wheels === '1'): ?>
        <div class="value"><span class="label">Wheels Fitted</span></div>
      <?php endif ?>
      <div class="value"><span class="label">Part Number:</span> <?php echo $data->workorder->product->part_number; ?></div>
    </div>
  </div>

 -->