<?php require APPROOT . '/views/inc/header.php'; ?> <!--  mx-auto -->
<div class="flex flex-col">
    <div class="backBtn">
        <a class="" href="javascript:history.go(-1)"><?php include APPROOT.'/views/components/icons/backicon.php'; ?>Back</a>
    </div>

    <div class="flex flex-col sm:flex sm:w-fit pb-8 pt-2 px-4  align-left">
        <h1 class=" text-lg"><?php echo $data->transportnote->transport; ?> Note</h1>
        <p class="mb2"><?php echo $data->transportnote->transport." ID: ".$data->transportnote->tnid;?></p>
        <p class="mb2"><?php echo "Date Created: ".substr($data->transportnote->created_at,0,10);?></p>
        <p class="mb2"><?php echo 'Total weight: '.$data->transportnote->weight.'Kg'; ?></p>
    </div>
    <div class="px-4 flex justify-center">
    <section class="">
                <table class="text-sm border-4">
                    <thead>
                        <tr class="text-center">
                            <th scope="col" class="pr-5 pl-10">WKO</th>
                            <th scope="col" class="pr-5 pl-5">AVN</th>
                            <th scope="col" class="pr-5 pl-5">Description</th>
                            <th scope="col" class="pr-5 pl-5">Quantity</th>
                            <th scope="col" class="pr-5 pl-5">Serials</th>
                            <th scope="col" class="pr-5 pl-5">Weight</th>
                            <th scope="col" class="pr-10 pl-5">Notes</th>
                        </tr>
                    </thead>
                        <tbody class=""></tbody>
                            <?php foreach($data->workorders as $workorder): ?>
                                <?php if($workorder!=null) : ?>
                                <tr class="text-center items-center border-4 worow p-4" data-id="<?php echo $workorder->work_order_id; ?>">
                                        <td><?php echo $workorder->wko ?></td>
                                        <td><?php echo $workorder->avn ?></td>
                                        <td class="text-left"><?php echo $workorder->pdesc ?></td>
                                        <td><?php echo $workorder->quantity ?></td>
                                        <td><?php echo $workorder->serials ?></td>
                                        <td><?php echo 'kg'?></td>
                                        <td><?php echo $workorder->wko_notes ?></td>
                                </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                </table>
            </section>
    </div>
</div>
<pre>
<!-- <?php print_r($data); ?> -->
</pre>
<?php require APPROOT . '/views/inc/footer.php'; ?>




