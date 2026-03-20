
<?php require APPROOT . '/views/inc/header.php'; ?>

<main class="container mx-auto mt-8 flex flex-col items-end gap-8 fade-in">
    <ul class="btn-list list-none m-0 p-0 flex gap-4 flex-wrap">
        <li>
            <a class="rounded-lg btn" href="<?php echo URLROOT . 'workorders/add' ?>">Add Work Order</a>
        </li>
    </ul>
    <?php 
        $active = 0;

        foreach ($data['workorders'] as $workorder) {
            if($workorder->wko_status != 'Completed') {
                $active++;
            }
        }
        if($active > 0) :?>
    <div class="border-red-600">
        <h1 class="flex justify-center text-lg"><strong><U>Active Work Orders</U></strong></h1>
        <section class="flex flex-col">
            <table class="text-sm justify-evenly w-full border-4">
                <thead>
                    <tr class="text-center">
                        <th scope="col" class="pr-3">Date In</th>
                        <th scope="col" class="pr-3">WKO</th>
                        <th scope="col" class="pr-3">AVN</th>
                        <th scope="col" class="pr-3">Description</th>
                        <th scope="col" class="pr-3">Wheels</th>
                        <th scope="col" class="pr-3">Quantity Req</th>
                        <th scope="col">Quantity Built</th>
                        <th scope="col">Serials</th>
                        <th scope="col">Status</th>
                        <th scope="col">Delivery</th>
                        <th scope="col">Notes</th>
                    </tr>
                </thead>
                    <?php foreach($data['workorders'] as $workorder) : ?>
                        <tbody class="">
                            <?php 
                                switch($workorder->wko_status) {
                                    case 'In Progress':
                                        $trClass = 'inprogress';
                                        break;
                                    case 'To Be Built':
                                        $trClass = 'tobebuilt';
                                        break;
                                    case 'On Hold':
                                        $trClass = 'onhold';
                                        break;
                                    case 'Waiting For Parts':
                                        $trClass = 'waitingforparts';
                                        break;
                                    case 'Upcoming':
                                        $trClass = 'upcoming';
                                        break;
                                    default:
                                        $trClass = null;
                                        break;
                                }
                            ?>
                            <tr class="text-center items-center border-4 worow <?php if(!empty($trClass)){echo $trClass;} ?>" data-id="<?php echo $workorder->work_order_id; ?>">
                                <?php if($workorder->wko_status !='Completed') :?>
                                    <td class="text-nowrap"><?php  $myDateTime = $workorder->created_at; echo substr($myDateTime,0,10); ?></td>
                                    <td><?php echo $workorder->wko ?></td>
                                    <td><?php echo $workorder->avn ?></td>
                                    <td style="font-size:10px"><?php echo $workorder->pdesc ?></td>
                                    <td><?php echo $workorder->wheels ? 'Yes' : 'No' ?></td>
                                    <td><?php echo $workorder->quantity ?></td>
                                    <td><?php echo $workorder->quantity_built ?></td>
                                    <td><?php echo $workorder->serials ?></td>
                                    <td><?php echo $workorder->wko_status ?></td>
                                    <td><?php echo $workorder->wko_delivery ?></td>
                                    <td><?php echo $workorder->wko_notes ?></td>
                                    <td><a href="<?php echo URLROOT; ?>workorders/complete/<?php echo $workorder->work_order_id?>"><?php include APPROOT.'/views/components/icons/checkicon.php'; ?></a></td>
                                    <td><a href="#" data-qty="<?php echo $workorder->quantity?>"><?php include APPROOT.'/views/components/icons/cuticon.php'; ?></a></td>
                                    <td><a href="<?php echo URLROOT; ?>workorders/edit/<?php echo $workorder->work_order_id?>"><?php include APPROOT.'/views/components/icons/editicon.php'; ?></a></td>
                                    <td class="dltBtn"><a href="<?php echo URLROOT; ?>workorders/delete/<?php echo $workorder->work_order_id?>" class="delete" data-confirm="Are you sure to delete wko <?php echo $workorder->wko;?> avn <?php echo $workorder->avn;?>?"><?php include APPROOT.'/views/components/icons/deleteicon.php'; ?></td>
                                <?php endif ;?>
                            </tr>
                        </tbody>
                    <?php endforeach; ?>
            </table>
        </section>
    </div>
    <?php endif; ?>
    <br>
    <div class=" border-4 border-red-600">
        <h1 class="flex justify-center text-lg text-sm"><strong><U>Completed Work Orders</U></strong></h1>
        <section class="flex flex-col">
            <table class="text-sm justify-evenly w-full border-4">
                <thead>
                    <tr class="text-center">
                    <th scope="col" class="pr-3">Date In</th>
                        <th scope="col" class="pr-3">WKO</th>
                        <th scope="col" class="pr-3">AVN</th>
                        <th scope="col" class="pr-3">Description</th>
                        <th scope="col" class="pr-3">Wheels</th>
                        <th scope="col" class="pr-3">Quantity Req</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Serials</th>
                        <th scope="col">Status</th>
                        <th scope="col">Delivery</th>
                        <th scope="col">Notes</th>
                    </tr>
                </thead>
                    <tbody class=""></tbody>
                        <?php foreach($data['workorders'] as $workorder): ?>
                            <?php if($workorder!=null) : ?>
                            <tr class="text-center items-center border-4 worow" data-id="<?php echo $workorder->work_order_id; ?>">
                                <?php if($workorder->wko_status ==='Completed') :?>
                                    <td class="text-nowrap"><date><?php  $myDateTime = $workorder->created_at; echo substr($myDateTime,0,10); ?></date></td>
                                    <td><?php echo $workorder->wko ?></td>
                                    <td><?php echo $workorder->avn ?></td>
                                    <td style="font-size:10px"><?php echo $workorder->pdesc ?></td>
                                    <td><?php echo $workorder->wheels ? 'Yes' : 'No' ?></td>
                                    <td><?php echo $workorder->quantity ?></td>
                                    <td><?php echo $workorder->quantity_built ?></td>
                                    <td><?php echo $workorder->serials ?></td>
                                    <td><?php echo $workorder->wko_status ?></td>
                                    <td><?php echo $workorder->wko_delivery ?></td>
                                    <td><?php echo $workorder->wko_notes ?></td>
                                <?php endif ;?>
                            </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
            </table>
        </section>
    </div>
</main>
<?php require APPROOT . '/views/inc/footer.php'; ?>
