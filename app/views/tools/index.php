<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="flex flex-col items-left flex-wrap text-lg pl-8 pt-8">
    <h1 class="text-large underline pb-3"><strong>Admin Tools</strong></h1>
    <ul>
        <li><a class="" href="<?php echo URLROOT.'tools/addP';?>">Add Product</a></li>
        <li><a class="" href="<?php echo URLROOT.'tools/addCab';?>">Add Cabinet</a></li>
        <li><a class="" href="<?php echo URLROOT.'tools/addF';?>">Add Finish</a></li>
        <li><a class="" href="<?php echo URLROOT.'tools/addComp';?>">Add Component</a></li>
        <li><a class="" href="<?php echo URLROOT.'tools/addSA';?>">Add Subassembly</a></li>
        <li><a class="" href="<?php echo URLROOT.'tools/addD';?>">Add Driver</a></li>
        <li><a class="" href="<?php echo URLROOT.'tools/editMComp';?>">Edit Model Component(s)</a></li>
    </ul>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
