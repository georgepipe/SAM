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
            - Fix pagination row classes<br>
            - Add search bar to filter results<br>
            - Pop-up function to add serials to AVNs with To Be Confirmed serial range<br>
            - Fix pagination<br>
            - Fix grille saving/loading/pdesc for mb212 brushed steel <br>
            - Fix serial retreival from PDF in weird edge cases where product name is specified in given serial ranges <br>
        <BR>
        <strong class="border-b-2">Transport Page:</strong><BR>
            - Calculate total weight for delivery<BR>  
            - Add search bar to filter results
            - categorise avliable complete wkos into: -to funktion one, -to storage & unassigned<br>
        <BR><BR>
        <strong class="border-b-2">Inventory Page:</strong><BR>
        - Everything<BR> 
        </p>
        <BR>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>