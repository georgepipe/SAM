<?php require APPROOT . '/views/inc/header.php'; ?>

<!-- <div class="container"> -->
<div class="flex flex-col w-full sm:flex sm:w-fit fade-in">
    <h1 class="text-center pt-3"> <strong><?php echo $data['title']; ?></strong></h1>
    <BR>
    <p class="text-center"><?php echo 'This page will show an overview of the active work orders as well as the progress for the month in terms of 
                    number of speakers built and income earnt compared with historical data.';?></p>


    <BR>
    <div class=" ml-8" >
        <h1 class="text-center text-lg"><strong>To Do:</strong></h1>
        <p><strong class="mb-4 border-b-2">Workorders Page:</strong><BR>
            - Fix pagination bug to view page past the max results page<br>
            - Add search bar to filter results<br>
            - Fix serial retreival from PDF in weird edge cases where product name is specified in given serial ranges <br>
            - Fix serial issue where single serials are contracted into ranges i.e 500 => 500 - 500 <BR>
            - Fix pagination buttons on completed WKOs page: ... needs to dynamically change based on current page <br>
        <strong>Suggested Improvements:</strong><br>
        - Batch avn uploads<br>

        <BR>
        <strong class="border-b-2">Transport Page:</strong><BR>
            - Add search bar to filter results <BR>
            - categorise avliable complete wkos into: -to funktion one, -to storage & unassigned<br>
        <BR><BR>
        <strong class="border-b-2">Inventory Page:</strong><BR>
        - Everything<BR> 
        </p>
        <BR>
    </div>
</div>

<?php dumpAndDie($_REQUEST) ?>

<?php require APPROOT . '/views/inc/footer.php'; ?>