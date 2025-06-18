<?php require APPROOT . '/views/inc/header.php'; ?>
<div>
<div class="flex m-8 pr-6">
    <a class="" href="javascript:history.go(-1)"><?php include APPROOT.'/views/components/icons/backicon.php'; ?>Back</a>
    <BR>
    <p class=" ml-3 self-center text-center"><?php echo $data->workorder->pdesc; ?></p>
</div>
<div class="flex flex-wrap justify-center text-sm">
    
</div>
</div>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #fdfdfc;
      margin: 2rem;
      color: #33294A;
    }
    .header {
      display: flex;
      justify-content: center;
    }
    .header div {
      flex: 1;
      text-align: center;
      padding: 1rem;
      color: white;
      font-weight: bold;
    }
    .work {
    background:rgb(142, 102, 252);
      border-top-left-radius: 10px;
    }
    .product {
      border-top-right-radius: 10px;
    background:rgb(92, 102, 252);
    }
    .container {
      display: flex;
      justify-content: space-evenly;
      border: 2px solid #33294A;
      border-top: none;
      border-radius: 0 0 10px 10px;
      overflow: hidden;
    }
    .section {
      padding: 2rem;
      background: #fffaf5;
    }
    .label {
      font-weight: bold;
      color: #33294A;
    }
    .value {
      margin-bottom: 0.5rem;
    }
    .footer {
      margin-top: 1.5rem;
      padding-top: 1rem;
      border-top: 2px solid #33294A;
      font-weight: bold;
    }
  </style>
</head>
<body>


  <div class="header">
    <div class="work">Work Order Details</div>
    <div class="product">Product Information</div>
  </div>
  
  <div class="container">
    <div class="section">
      <div class="value"><span class="label">WKO:</span> <?php echo $data->workorder->wko; ?> <span class="label">     AVN:</span> <?php echo $data->workorder->avn; ?></div>
      <div class="value"><span class="label">Quantity Required:</span> <?php echo $data->workorder->quantity_required; ?></div>
      <div class="value"><span class="label">Unit Price:</span> <?php echo $data->workorder->model->build_price; ?></div>
      <div class="value"><span class="label">Serial(s):</span> <?php echo $data->workorder->serials; ?></div>
      <div class="value"><span class="label">Current Status:</span> <?php echo $data->workorder->wko_status; ?></div>
      <div class="value"><span class="label">Transport:</span> <?php echo $data->workorder->wko_delivery; ?></div>
      <div class="value"><span class="label">Notes:</span> <?php echo $data->workorder->wko_notes; ?></div>
      <div class="value"><span class="label">Added:</span> <?php echo $data->workorder->created_at; ?></div>
      <div class="value"><span class="label">Updated At:</span> 2024-12-20 11:23:01</div>
    </div>
    <div class="section">
      <div class="value"><span class="label">Model:</span> <?php echo $data->workorder->model->name; ?></div>
      <div class="value"><span class="label">Finish:</span> <?php echo $data->workorder->cab_finish->name; if($data->workorder->cab_finish->ral_code != '') {echo ' ('.$data->workorder->cab_finish->ral_code.')';}; ?></div>
      <?php if($data->workorder->product->grille_finish_id != ''): ?>
        <div class="value"><span class="label">Grille Finish:</span> <?php echo $data->workorder->grille_finish->name; if($data->workorder->grille_finish->ral_code != '') {echo ' ('.$data->workorder->grille_finish->ral_code.')';}; ?></div>
      <?php endif ?>
      <?php if($data->workorder->waveguide != ''):?>
        <div class="value"><span class="label">Waveguide Finish:</span> <?php echo $data->workorder->waveguide->name; ?></div>
      <?php endif ?>
      <div class="value"><span class="label">Connectors:</span> <?php echo $data->workorder->product->connectors; ?></div>
      <div class="value"><span class="label">Fixings:</span> <?php echo $data->workorder->product->fixings; ?></div>
      <?php if($data->workorder->wheels != ''): ?>
        <div class="value"><span class="label">Wheels Fitted</span></div>
      <?php endif ?>
      <div class="value"><span class="label">Part Number:</span> <?php echo $data->workorder->product->part_number; ?></div>
    </div>
  </div>

 


<!-- <pre><?php print_r($data);?></pre> -->

<?php require APPROOT . '/views/inc/footer.php'; ?>