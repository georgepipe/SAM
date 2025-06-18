

<?php require APPROOT . '/views/inc/header.php'; ?>


<?php 
        $url = rtrim($_SERVER['REQUEST_URI'], '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $url = explode('/',$url);?>

<div class="fade-in">
<div class=" border-b-4 ">
    <ul 
                class="hidden w-full py-5 px-5 flex-row sm:flex sm:w-fit gap-3 items-center" 
                type="menu" 
                data-target="nav" 
        >
        <li class="py-3 px-2 rounded-md group relative">
                <a 
                    class="p-2 text-black actWkosBtn" 
                    href="<?php echo URLROOT.'workorders/index?p=aw';?>">
                Active Workorders</a>
                <span class="absolute -bottom-1 left-0 w-0 fill-black block"></span>
        </li>
        <li class="py-3 px-2 rounded-md group relative">
                <a 
                    class="text-black p-2 comWkosBtn" 
                    href="<?php echo URLROOT.'workorders/index?p=cw';?>">
                Completed Workorders</a>
                <span class="absolute -bottom-1 left-0 w-0 fill-black block"></span>
        </li>
        <li class="py-3 px-2 rounded-md group relative">
                <a 
                    class="text-black p-2 addWkoBtn" 
                    href="<?php echo URLROOT.'workorders/add';?>">
                Add Workorder</a>
                <span class="absolute -bottom-1 left-0 w-0 fill-black block"></span>
        </li>
    </ul>
</div>

<?php if(isset($_SESSION['post_message'])) {echo flash('post_message');} ?>

<div class="activeWkos <?php if($url[3] === 'index?p=cw') { echo 'hidden'; } else {echo 'block';} ?>">
    <div class="border-red-600">
        <h1 class="flex justify-center text-lg"><strong><U>Active Work Orders</U></strong></h1>
        <section class="flex flex-col">
            <table class="text-sm justify-evenly w-full border-4" id="tblAwo">
                <thead>
                    <tr class="text-center">
                        <th scope="col" class="pr-3">Date In</th>
                        <th scope="col" class="pr-3">WKO</th>
                        <th scope="col" class="pr-3">AVN</th>
                        <th scope="col" class="pr-3">Description</th>
                        <th scope="col" class="pr-3">Quantity Req</th>
                        <th scope="col">Serials</th>
                        <th scope="col">Status</th>
                        <th scope="col">Delivery</th>
                        <th scope="col">Notes</th>
                    </tr>
                </thead>
                <tbody id="tbodyAwo">
                    <?php foreach($data['activeWorkorders'] as $workorder) : ?>
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
                                <td class="text-[10px]"><?php echo $workorder->pdesc ?></td>
                                <td><?php echo $workorder->quantity_required ?></td>
                                <td><?php echo $workorder->serials ?></td>
                                <td><?php echo $workorder->wko_status ?></td>
                                <td><?php echo $workorder->wko_delivery ?></td>
                                <td><?php echo $workorder->wko_notes ?></td>
                                <td><a href="<?php echo URLROOT; ?>workorders/complete/<?php echo $workorder->work_order_id?>"><?php include APPROOT.'/views/components/icons/checkicon.php'; ?></a></td>
                                <td><a href="javascript:void(0)" data-qty="<?php echo $workorder->quantity_required?>"><?php include APPROOT.'/views/components/icons/cuticon.php'; ?></a></td>
                                <td><a href="<?php echo URLROOT; ?>workorders/edit/<?php echo $workorder->work_order_id?>"><?php include APPROOT.'/views/components/icons/editicon.php'; ?></a></td>
                                <td class="dltBtn"><a href="<?php echo URLROOT; ?>workorders/delete/<?php echo $workorder->work_order_id?>" class="delete" data-confirm="Are you sure to delete wko <?php echo $workorder->wko;?> avn <?php echo $workorder->avn;?>?"><?php include APPROOT.'/views/components/icons/deleteicon.php'; ?></td>
                                <?php endif ;?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </div>

    <div class="flex items-center justify-between px-4 py-3 sm:px-6">
    <div class="flex flex-1 justify-between sm:hidden">
        <a href="#" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Previous</a>
        <a href="#" class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Next</a>
    </div>

    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
        <div>
            <p class="text-sm text-gray-700 pageInfoA">
            Showing
            <span class="font-medium"><?php echo '1'?></span>
            to
            <span class="font-medium"><?php  echo ($data['acWkoCount'] < 10) ? $data['acWkoCount']:'10'; ?></span>
            of
            <span class="font-medium Acount" data-count="<?php echo $data['acWkoCount']; ?>"><?php echo $data['acWkoCount']; ?></span>
            results
            </p>
        </div>
        <div>
        <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
            
            <a href="#" class="pgBtn pgBtnArrow rounded-l-md hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
            <span class="sr-only">Previous</span><?php include APPROOT.'/views/components/icons/previousicon.php';?>
            </a>
            <!-- Current: "z-10 bg-indigo-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600", Default: "text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:outline-offset-0" -->
            <?php if($data['acWkoCount']>0) :?>
                <?php 
                    $i = 0;
                    $pages = $data['acWkoCount'] % 10 != 0 ? round($data['acWkoCount'] / 10 + 1,0,PHP_ROUND_HALF_DOWN) : $data['acWkoCount'] / 10; //calculate no of pages from results
                    switch (TRUE) {
                        case ($pages > 5):
                            //page btn 1
                            echo '<a href="#" class="pgBtn pgBtnNum hover:bg-gray-50 focus:z-20 focus:outline-offset-0" data-page="0">1</a>'; 
                            //page btn 2
                            echo '<a href="#" class="pgBtn pgBtnNum hover:bg-gray-50 focus:z-20 focus:outline-offset-0" data-page="1">2</a>'; 
                            echo '<span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 focus:outline-offset-0">...</span>';
                            //page btn $pages -1
                            echo '<a href="#" class="pgBtn pgBtnNum hover:bg-gray-50 focus:z-20 focus:outline-offset-0" id="'.($pages-2).'"">'.($pages-1).'</a>'; 
                            //page btn $pages
                            echo '<a href="#" class="pgBtn pgBtnNum hover:bg-gray-50 focus:z-20 focus:outline-offset-0" data-page="'.($pages-1).'">'.$pages.'</a>'; 
                            break;
                        default: 
                            for ($i = 1; $i <= $pages; $i++) {
                                echo '<a class="pgBtn pgBtnNum hover:bg-gray-50 focus:z-20 focus:outline-offset-0 '.($i===1 ? '' : '').'" data-page="'.($i-1).'">'.$i.'</a>'; 
                            }
                            break;
                    }
                    
                ?>
                <!-- <a href="#" aria-current="page" class="relative z-10 inline-flex items-center bg-indigo-600 px-4 py-2 text-sm font-semibold text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">1</a> -->
                <!-- <a href="#" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">2</a> -->
                <!-- <a href="#" class="relative hidden items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 md:inline-flex">3</a> -->
                <!-- <span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 focus:outline-offset-0">...</span> -->
                <!-- <a href="#" class="relative hidden items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 md:inline-flex">8</a> -->
                <!-- <a href="#" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">9</a> -->
                <!-- <a href="#" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">10</a> -->
            <?php endif ?>
            <a href="#" class="pgBtn pgBtnArrow rounded-r-md  hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
            <span class="sr-only">Next</span><?php include APPROOT.'/views/components/icons/nexticon.php';?>
            </a>
        </nav>
        </div>
    </div>
    </div>
</div>
<div class="completed <?php if($url[3] != 'index?p=cw') { echo 'hidden'; } else {echo 'block';} ?>">
    <div class="border-red-600">
        <h1 class="flex justify-center text-lg"><strong><U>Completed Work Orders</U></strong></h1>
        <section class="flex flex-col">
            <table class="text-sm justify-evenly w-full border-4" id="tblCwo">
                <thead>
                    <tr class="text-center">
                        <th scope="col" class="pr-3">Date Completed</th>
                        <th scope="col" class="pr-3">WKO</th>
                        <th scope="col" class="pr-3">AVN</th>
                        <th scope="col" class="pr-3">Description</th>
                        <th scope="col">Quantity Built</th>
                        <th scope="col">Serials</th>
                        <th scope="col">Delivery</th>
                        <th scope="col">Notes</th>
                    </tr>
                </thead>
                <tbody id="tbodyCwo">
                <?php foreach($data['completedWorkorders'] as $workorder) : ?>
                        <tr class="text-center items-center border-4 worow min-h-4" data-id="<?php echo $workorder->work_order_id; ?>">
                            <?php if($workorder->wko_status ='Completed') :?>
                                <td class="text-nowrap"><?php  !$workorder->completed_at ? $myDateTime = 'N/A' : $myDateTime = $workorder->completed_at; echo substr($myDateTime,0,10); ?></td>
                                <td><?php echo $workorder->wko ?></td>
                                <td><?php echo $workorder->avn ?></td>
                                <td class="text-[10px]"><?php echo $workorder->pdesc ?></td>
                                <td><?php echo $workorder->quantity_built ?></td>
                                <td><?php echo $workorder->serials ?></td>
                                <td><?php echo $workorder->wko_delivery ?></td>
                                <td><?php echo $workorder->wko_notes ?></td>
                                <?php endif ;?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </div>

    <div class="flex items-center justify-between px-4 py-3 sm:px-6">
    <div class="flex flex-1 justify-between sm:hidden">
        <a href="#" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Previous</a>
        <a href="#" class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Next</a>
    </div>

    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
        <div>
            <p class="text-sm text-gray-700 pageInfoB">
            Showing
            <span class="font-medium"><?php echo '1'?></span>
            to
            <span class="font-medium"><?php  echo ($data['coWkoCount'] < 10) ? $data['coWkoCount']:'10'; ?></span>
            of
            <span class="font-medium Ccount" data-count="<?php echo $data['coWkoCount']; ?>"><?php echo $data['coWkoCount']; ?></span>
            results
            </p>
        </div>
        <div>
        <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination" data-wkos="com">
            
            <a href="#" class="pgBtn pgBtnArrow rounded-l-md hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
            <span class="sr-only">Previous</span><?php include APPROOT.'/views/components/icons/previousicon.php';?>
            </a>
            <!-- Current: "z-10 bg-indigo-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600", Default: "text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:outline-offset-0" -->
            <?php if($data['coWkoCount']>0) :?>
                <?php 
                    $i = 0;
                    $pages = $data['coWkoCount'] % 10 != 0 ? round($data['coWkoCount'] / 10 + 1,0,PHP_ROUND_HALF_DOWN) : $data['coWkoCount'] / 10; //calculate no of pages from results
                    switch (TRUE) {
                        case ($pages > 5):
                            //page btn 1
                            echo '<a href="#" class="pgBtn pgBtnNum hover:bg-gray-50 focus:z-20 focus:outline-offset-0" data-page="0">1</a>'; 
                            //page btn 2
                            echo '<a href="#" class="pgBtn pgBtnNum hover:bg-gray-50 focus:z-20 focus:outline-offset-0" data-page="1">2</a>'; 
                            echo '<span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 focus:outline-offset-0">...</span>';
                            //page btn $pages -1
                            echo '<a href="#" class="pgBtn pgBtnNum hover:bg-gray-50 focus:z-20 focus:outline-offset-0" id="'.($pages-2).'"">'.($pages-1).'</a>'; 
                            //page btn $pages
                            echo '<a href="#" class="pgBtn pgBtnNum hover:bg-gray-50 focus:z-20 focus:outline-offset-0" data-page="'.($pages-1).'">'.$pages.'</a>'; 
                            break;
                        default: 
                            for ($i = 1; $i < $pages; $i++) {
                                echo '<a class="pgBtn pgBtnNum hover:bg-gray-50 focus:z-20 focus:outline-offset-0 '.($i===1 ? '' : '').'" data-page="'.($i-1).'">'.$i.'</a>'; 
                            }
                            break;
                    }
                    
                ?>
                <!-- <a href="#" aria-current="page" class="relative z-10 inline-flex items-center bg-indigo-600 px-4 py-2 text-sm font-semibold text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">1</a> -->
                <!-- <a href="#" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">2</a> -->
                <!-- <a href="#" class="relative hidden items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 md:inline-flex">3</a> -->
                <!-- <span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 focus:outline-offset-0">...</span> -->
                <!-- <a href="#" class="relative hidden items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 md:inline-flex">8</a> -->
                <!-- <a href="#" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">9</a> -->
                <!-- <a href="#" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">10</a> -->
            <?php endif ?>
            <a href="#" class="pgBtn pgBtnArrow rounded-r-md  hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
            <span class="sr-only">Next</span><?php include APPROOT.'/views/components/icons/nexticon.php';?>
            </a>
        </nav>
        </div>
    </div>
    </div>
</div>
<p id="demo">demo</p>


</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
