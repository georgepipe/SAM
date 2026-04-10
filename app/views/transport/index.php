<?php require APPROOT . '/views/inc/header.php'; ?>


<div class="relative rounded-2xl p-8 fade-in">
<?php if(!empty($data->avaliableworkorders)) : ?>
<div class="uassTrans m-8 border-4 border-red-600">
        <h1 class="flex justify-center text-lg"><strong><U>Product Ready for Transport</U></strong></h1>
        <section class="flex flex-col">
            <table class="text-sm justify-evenly w-full border-4">
                <thead>
                    <tr class="text-center">
                        <th scope="col" class="pr-5"></th>
                        <th scope="col" class="pr-5">AVN</th>
                        <th scope="col" class="pr-5">Description</th>
                        <th scope="col" class="pr-5">Quantity</th>
                        <th scope="col" class="pr-5">Weight (kg)</th>
                    </tr>
                </thead>
                    <?php foreach($data->avaliableworkorders as $workorder) : ?>
                        <tbody class="">
                            <tr class="text-center items-center border-4 " data-weight="<?php echo ($workorder->weight * $workorder->quantity)?>" >
                                <?php if($transport->completed !=1) :?>
									<td data-id="<?php echo $workorder->work_order_id; ?>"><input type="checkbox" class="tCheck"></input></td>
									<!-- <td class=""><?php echo $workorder->avn; ?></td> -->
                                    <td class=" text-blue-500 wkoAvn"><a href="#" onclick="AVNwindow=window.open('<?php echo URLROOT?>advice_notes/AVN_<?php echo str_pad($workorder->avn, 5,'0', STR_PAD_LEFT).'.pdf'?>', 'AVNwindow', 'width=400, height=600');"><?php if($workorder->avn) {echo $workorder->avn;} else {echo 'N/A';} ?></a></td>
									<td class=" text-[15px]"><?php echo $workorder->pdesc; ?></td>
									<td class=""><?php echo $workorder->quantity; ?></td>
                                    <td class="" ><?php echo ($workorder->weight * $workorder->quantity)?></td>
                                <?php endif ;?>
                            </tr>
                        </tbody>
                    <?php endforeach; ?>
            </table>
        </section>
    </div>
<!-- create deliver/collection note buttons -->
<div class="flex flex-row m-auto justify-center btnContainer hidden">
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mx-6 h-16 text-nowrap colBtn ">Create Collection</button>
    <button class="btn btn-blue mx-6 h-16 delBtn ">Create Dellivery</button>
    <div class=" border-2">Current total Delivery Weight: <br><text class="weightTxt">kg</text></div>
</div>
<?php endif; ?>
<?php if(!empty($data->activetransportnotes)) : ?>
	<div class="assTrans dispContainer">
        <h1 class="flex justify-center text-lg"><strong><U>Scheduled Collections & Deliveries</U></strong></h1>
        <section class="flex flex-col">
            <table class="text-sm justify-evenly w-full border-4">
                <thead>
                    <tr class="text-center">
                        <th scope="col" class="pr-5">Date Created</th>
						<th scope="col" class="pr-5">ID</th>
						<th scope="col" class="pr-5">Transport</th>
						<th scope="col" class="pr-5">WKOs</th>
						<th scope="col" class="pr-5"></th>
                    </tr>
                </thead>
                    <?php foreach($data->activetransportnotes as $transport) : ?>
                        <tbody class="">
                            <tr class="text-center items-center border-4 tnoterow" data-id="<?php echo $transport->tnid ?>">
                                <?php if($transport->completed !=1) :?>
                                    <td class="text-nowrap"><?php  
										$myDateTime = $transport->created_at; echo substr($myDateTime,0,10); 
									?></td>
									<td class=""><?php echo $transport->tnid; ?></td>
									<td class=""><?php echo $transport->transport; ?></td>
									<?php 
										$wkos = [];
										foreach ($data->assignedworkorders as $key => $value) {
											if ($value->tnid === $transport->tnid) {
												$wkos[$key] = $value->wko;
											}
										}
									?>
									<td>
										<?php echo implode(', ', $wkos) ?>
									</td>
									<td class="flex"><a href="<?php echo URLROOT; ?>transport/complete/<?php echo $transport->tnid?>" class="" data-confirm="Mark transport note <?php echo $transport->tnid;?> as complete?"><?php include APPROOT.'/views/components/icons/checkicon.php'; ?></td>
                                    <td class=""><a href="<?php echo URLROOT; ?>transport/delete/<?php echo $transport->tnid?>" class="" data-confirm="Are you sure to delete transport note  <?php echo $transport->tnid;?>?"><?php include APPROOT.'/views/components/icons/deleteicon.php'; ?></td>
                                <?php endif ;?>
                            </tr>
                        </tbody>
                    <?php endforeach; ?>
            </table>
        </section>
    </div>
</div>
<?php endif; ?>
<?php if(!empty($data->completedtransportnotes)) : ?>
	<div class="m-8 border-4 border-red-600">
        <h1 class="flex justify-center text-lg"><strong><U>Completed Collections & Deliveries</U></strong></h1>
        <section class="flex flex-col">
            <table class="text-sm justify-evenly w-full border-4">
                <thead>
                    <tr class="text-center">
                        <th scope="col" class="pr-5">Date Completed</th>
						<th scope="col" class="pr-5">ID</th>
						<th scope="col" class="pr-5">Transport</th>
						<th scope="col" class="pr-5">WKOs</th>
                    </tr>
                </thead>
                    <?php foreach($data->completedtransportnotes as $transport) : ?>
                        <tbody class="">
                            <tr class="text-center items-center border-4 tnoterow" data-id="<?php echo $transport->tnid ?>">
                                <?php if($transport->completed === 1) :?>
                                    <td class="text-nowrap"><?php  
										$myDateTime = $transport->completed_at; echo substr($myDateTime,0,10); 
									?></td>
									<td class=""><?php echo $transport->tnid; ?></td>
									<td class=""><?php echo $transport->transport; ?></td>
									<?php 
										$wkos = [];
										foreach ($data->assignedworkorders as $key => $value) {
											if ($value->tnid === $transport->tnid) {
												$wkos[$key] = $value->wko;
											}
										}
									?>
									<td>
										<?php echo implode(', ', $wkos) ?>
									</td>
                                <?php endif ;?>
                            </tr>
                        </tbody>
                    <?php endforeach; ?>
            </table>
        </section>
    </div>
</div>
<?php endif; ?>



<?php 
//     echo '<PRE>';
// print_r($data);
//     echo '</PRE>';

?> 

<?php require APPROOT . '/views/inc/footer.php'; ?>