<?php require APPROOT . '/views/inc/header.php'; ?>


<div class="relative rounded-2xl p-8 fade-in">
<?php if(!empty($data->avaliableworkorders)) : ?>
<div class=" m-8 border-4 border-red-600">
        <h1 class="flex justify-center text-lg"><strong><U>Product Ready for Transport</U></strong></h1>
        <section class="flex flex-col">
            <table class="text-sm justify-evenly w-full border-4">
                <thead>
                    <tr class="text-center">
                        <th scope="col" class="pr-5"></th>
                        <th scope="col" class="pr-5">WKO</th>
                        <th scope="col" class="pr-5">Description</th>
                        <th scope="col" class="pr-5">Quantity</th>
                    </tr>
                </thead>
                    <?php foreach($data->avaliableworkorders as $workorder) : ?>
                        <tbody class="" ">
                            <tr class="text-center items-center border-4 " >
                                <?php if($transport->completed !=1) :?>
									<td data-id="<?php echo $workorder->work_order_id; ?>"><input type="checkbox" class="tCheck"></input></td>
									<td class=""><?php echo $workorder->wko; ?></td>
									<td class=""><?php echo $workorder->pdesc; ?></td>
									<td class=""><?php echo $workorder->quantity_built; ?></td>
                                <?php endif ;?>
                            </tr>
                        </tbody>
                    <?php endforeach; ?>
            </table>
        </section>
    </div>
<!-- create deliver/collection note buttons -->
<div class="flex flex-row m-auto justify-center">
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mx-6 h-16 text-nowrap colBtn hidden">Create Collection</button>
    <button class="btn btn-blue mx-6 h-16 delBtn hidden">Create Dellivery</button>
</div>
<?php endif; ?>
<?php if(!empty($data->activetransportnotes)) : ?>
	<div class="m-8 border-4 border-red-600">
        <h1 class="flex justify-center text-lg"><strong><U>Active Collections & Deliveries</U></strong></h1>
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