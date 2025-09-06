<?php require APPROOT . '/views/inc/header.php'; ?>

<!-- <div class="container"> -->
<div class="flex flex-col w-full sm:flex sm:w-fit fade-in">
    <h1 class="text-center pt-3"> <strong><?php echo $data['title']; ?></strong></h1>
    <BR>
    <p class="text-center"><?php echo 'This page will show an overview of the active work orders as well as the progress for the month in terms of 
                    number of speakers built and income earnt compared with historical data.';?></p>


    <BR>
    <h1 class="text-center">To Do:</h1>
    <p><strong>Workorders Page:</strong><BR>
        - check serials for duplicates (not working)<BR>
        - confirmation before delete<BR>
        - refactor serial regex (sometimes misses old serials)<BR>
        - PID management (stop duplicate PIDs)<BR>
        - Fix edit page: does not populate form correctly on load<br>
        - Add search bar to filter results<br>
        - Need to add avn pdf handler to show 404 errors rather than redirect to homepage<br>
        - Pop-up function to add serials to AVNs with To Be Confirmed serial range<br>
        - Fix pagination<br>
       <BR><BR>
       <strong>Transport Page:</strong><BR>
        - Calculate total weight for delivery<BR>  
        - Add search bar to filter results
       <BR><BR>
       <strong>Invertory Page:</strong><BR>
       - Everything<BR> 
    </p>
    <BR>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>