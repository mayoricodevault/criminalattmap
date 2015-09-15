<div class="widget">
<h3>About us</h3>
<?php $aboutimage = get_option('swt_about_image'); echo "<img src='$aboutimage' class='aboutimage' alt='About Us' />"; ?>
<?php $about = get_option('swt_aboutus'); echo $about; ?>
</div>