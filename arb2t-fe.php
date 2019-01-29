<?php
/*
* The Display Page
 */
?>

<div class="arbtt-container" id="arbtt-container"> <a href="#" class="arbtt" id="arbtt"><span class="fa fa-<?php print_r($arbtt_fi); ?>"></span></a> </div>

<style type="text/css">
.arbtt {width:<?php echo $arbtt_btndmw; ?>px; height:<?php echo $arbtt_btndmh; ?>px;line-height:<?php echo $arbtt_btndmh; ?>;padding: 0;text-align:center;font-weight: bold;color:<?php echo $arbtt_clr; ?>!important;text-decoration: none!important;position: fixed;bottom:75px; <?php echo $arbtt_btnps; ?> :40px;display:none; background-color: <?php echo $arbtt_bgc; ?> !important;opacity: <?php echo $arbtt_btnoc; ?>;border-radius: <?php echo $arbtt_bdrd; ?>px;z-index: 9999;}
.arbtt:hover {color: <?php //echo $arbtt_bgc; ?> !important;background-color: <?php //echo $arbtt_clr; ?>!important;opacity: 0.7;}
.arbtt .fa{line-height: <?php echo $arbtt_btndmh; ?>px;font-size: <?php echo $arbtt_fz; ?>px;height: <?php echo $arbtt_btndmh; ?>px;width:<?php echo $arbtt_btndmw; ?>px;}
.arbtt:visited, .arbtt:focus{color: #fff;outline: 0;}
.form-table input#arbtt_btndm.ardm {width: 72px;}
</style>