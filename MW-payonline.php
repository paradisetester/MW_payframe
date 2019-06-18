<?php
/**
 * 
 *  Template name: MW payonline Template
*/
    if ( !defined('ABSPATH') ){ die(); }
    
    global $avia_config;

get_header();
     if( get_post_meta(get_the_ID(), 'header', true) != 'no') echo avia_title();
     
     do_action( 'ava_after_main_title' );
?>
<div class='container_wrap container_wrap_first main_color <?php avia_layout_class( 'main' ); ?>'>

            <div class='container'>

                <main class='template-page content  <?php avia_layout_class( 'content' ); ?> units' <?php avia_markup_helper(array('context' => 'content','post_type'=>'page'));?>>
                     
<?php if ( have_posts() ) : while ( have_posts() ) : the_post();

the_content();
endwhile; else: ?>
<div id="response"></div>
<p>Sorry, no posts matched your criteria.</p>
<?php endif; ?>
    </main>
            </div><!--end container-->

        </div><!-- close default .container_wrap element -->



<!--Include payframe.js-->
    <script src='https://secure.merchantwarrior.com/payframe/payframe.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/components/core.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/components/md5.js"></script>
    <script>
jQuery(document).ready(function() {

jQuery('#paymentForm .mwformbtn').on('click',function(){

  // jQuery(this).find(this).attr('data-disabled',"enabled");
submitForm();
jQuery(this).hide();
jQuery('<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/spinner.gif" class="imgload" width="200" height="60">').insertBefore('.button-form-submit') ; 
});
    
 jQuery(".wpcf7-form-control-wrap input.wpcf7-form-control").change(function(){
     var $i = 0;
    jQuery(".wpcf7-form-control-wrap input.wpcf7-form-control").each(function()
        {
           if(jQuery(this).val()=="")
             {
                $i=$i+1;
            }
        
    });
    
     if($i==0)
         {
             jQuery('a.mwformbtn').show();
             
         }
  });
    
    
    
});



   var merchantUUID = '52e1d8f84c754';
        var apiKey = 'gvursjd1';
        var passphrase = 'uo2gxbdt';
        var style = {
                border: '1px solid #e1e1e1',
                backgroundColor: '#f8f8f8',
                textColor: '#919191',
                borderRadius:'0px',
                fontFamily: 'Arial',
                errorTextColor: 'red',
                errorBorderColor: 'red',
                fontSize: '18px',
                width: '400px',
                cardTypeDisplay: 'right',
                padding: '5px',
                fieldHeight: '60px',
        };
        var acceptedCardTypes = "visa , amex , mastercard";
    //Instantiate the payframe
    //If not set, the method parameter defaults to getPayframeToken
    var mwPayframe = new payframe(merchantUUID, apiKey,'cardData','https://base.merchantwarrior.com/payframe/', 'https://api.merchantwarrior.com/payframe/', style, acceptedCardTypes);
     

    //Set mwCallback
        mwPayframe.mwCallback = function(tokenStatus, payframeToken, payframeKey){
            if(tokenStatus == 'HAS_TOKEN') {
                document.getElementById('payframeToken').value = payframeToken;
                document.getElementById('payframeKey').value = payframeKey;
                document.getElementById('merchantUUID').value = merchantUUID;
                document.getElementById('apiKey').value = apiKey;
                
                var transactionAmount1 = document.getElementById('transactionAmount').value;
                var transactionAmount = parseFloat(transactionAmount1);
                 transactionAmount = transactionAmount.toFixed(2);
                document.getElementById('amtcharges').value = transactionAmount;
                
                var cardtype =  JSON.parse(JSON.stringify(mwPayframe));
                var cardamex = cardtype.cardType;
                document.getElementById('cardtype').value = cardamex.toUpperCase();
                if(cardamex=='amex'){
                   surcharges =  transactionAmount* 0.025;
                   var totalcharges = parseInt(transactionAmount)+parseFloat(surcharges);
                
                transactionAmount = totalcharges.toFixed(2);
                
                }
                document.getElementById('amtoninvoice').value = transactionAmount;

               console.log(transactionAmount);
                
                var transactionCurrency = document.getElementById('transactionCurrency').value;
                var parts = (CryptoJS.MD5(passphrase)+merchantUUID+transactionAmount+transactionCurrency).toLowerCase();
                var hash = CryptoJS.MD5(parts);
                document.getElementById('hash').value = hash;
                console.log('hash:'+hash+'payframeToken:'+payframeToken+'payframeKey:'+payframeKey);
                
                var formdata = jQuery("#paymentForm").serializeArray();
                //var cardform = jQuery("#payframeForm").serializeArray();
           
            
jQuery.ajax({  
    type: "POST",
    datatype:"html",  
    url: woocommerce_params.ajax_url ,  
    data : { action: 'mwpayframe_curl', formdata:formdata}, 
    success: function(response) {  
            console.log(response);
            var obj = JSON.parse(response);
            
             document.getElementById('transactionid').value = obj.TransactionID;
             //document.getElementById('authmsg').value = obj.AuthMessage;
             jQuery('#authmsg').val(obj.AuthMessage);
             if(obj.AuthMessage=='Transaction declined'){
                jQuery('.wpcf7-response-output').html(obj.AuthMessage).show();
                jQuery('.imgload').hide();
             }else{
               return false;
                //document.getElementById('paymentForm').submit();
             }
             
            
    }

});
            //Set other fields, then submit form for processCard transaction
        } else {
            console.log('Failed to get payframeToken');
            if(mwPayframe.responseCode == -2 || mwPayframe.responseCode == -3){
                console.log('Validation failed - ' + mwPayframe.responseMessage);
            }
        }
       };
   
    

    //When the client has entered their card details, call submitPayframe, which will perform the action set by the method parameter
    mwPayframe.deploy();
        function submitForm(){
            mwPayframe.submitPayframe();
            return false;
    
        };
    
</script>



<?php get_footer();