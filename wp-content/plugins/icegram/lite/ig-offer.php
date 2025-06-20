<?php
global $icegram;
// BFCM 2022 Campaign
if ( ( get_option( 'ig_offer_bfcm_2024_icegram' ) !== 'yes' ) && Icegram::is_offer_period( 'bfcm') ) { 
    $img_url = $this->plugin_url .'/assets/images/bfcm-engage-free-pro-banner-2024.png';
    $ig_plan = $icegram->get_plan();
    if( 'max' === $ig_plan ){
        $img_url = $this->plugin_url .'/assets/images/bfcm-common-max-banner-2024.jpg';
    }
    // elseif( 'plus' === $ig_plan || 'pro' === $ig_plan ){
    //     $img_url = $this->plugin_url .'/assets/images/bfcm2021_pro.png';
    // }

    ?>
    <style type="text/css">
        .ig_sale_offer {
            width: 65%;
            margin: 0 auto;
            text-align: center;
            padding-top: 1.2em;
        }

    </style>
    <div class="ig_sale_offer">
        <a target="_blank" href="?ig_dismiss_admin_notice=1&ig_option_name=ig_offer_bfcm_2024"><img src="<?php echo $img_url; ?>"/></a>
    </div>
<?php } ?>