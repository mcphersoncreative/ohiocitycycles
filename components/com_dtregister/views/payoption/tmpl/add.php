<?php

/**
* @version 2.7.0
* @package Joomla 1.5
* @subpackage DT Register
* @copyright Copyright (C) 2006 DTH Development
* @copyright contact dthdev@dthdevelopment.com
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

	$document =& JFactory::getDocument();

	$document->addScript( JURI::root(true).'/components/com_dtregister/assets/js/jquery.js');

	$document->addScript( JURI::root(true).'/components/com_dtregister/assets/js/validate.js');
   
?>
 <?php
 
 $config = $this->getModel('config');
 $paymentmethod = $this->getModel('paymentmethod');
 $cardtype = $this->getModel('cardtype');
 $paylater = $this->getModel('paylater');
 $currency = $this->getModel('currency');
  
 ?>
<script type="text/javascript">

DTjQuery(function(){
   //DTjQuery.validator.messages.required = " ";

    DTjQuery(document.adminForm).validate({
		    rules :{
			      "data[paylater][]"	: {
					   uniquevalue : true ,
					   required :true
					  }
			},
	        success: function(label) {
				label.addClass("success");
			}

	});
	// DTjQuery(document.adminForm).find("input[name='data\\[paylater\\]\\[\\]']").rules('add','uniquevalue');
	
 })

function submitbutton(pressbutton){
    if(pressbutton != 'cancel'){
		if(DTjQuery(document.adminForm).valid()){
		 submitform(pressbutton);
		}
	}else{
	  submitform(pressbutton);	
	}
}

</script>

<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">

<table class="adminform" width="100%" cellpadding="10" cellspacing="10">
 <tr>

      <td><strong><?php echo JText::_( 'DT_PAYMENT_NAME'); ?></strong></td>

      <td><input type="text" id="dataPaymentName" class="required" size="30" name="data[payment][name]" /></td>

	  <td><?php echo JHTML::tooltip((JText::_( 'DT_PAYMENT_NAME_HELP' )), '', 'tooltip.png', '', ''); ?> </td>

      <td> </td>

   </tr>
    <tr align="center" valign="middle">
	
	  <td align="left" valign="top"><strong><?php echo JText::_( 'DT_CURRENCY_CODE' );?>:</strong></td>

      <td align="left" valign="top">

		<?php			

			$options=DtHtml::options($currency->getCurrency());

			if(!isset($row->config['currency_code']) || $row->config['currency_code']==""){
				$currency_code='USD';
			}else{

			   $currency_code = $row->config['currency_code'] ;

			}

			echo JHTML::_('select.genericlist', $options,'data[config][currency_code]','','value','text',$currency_code);

         ?>

	  </td>

      <td><?php echo JHTML::tooltip((JText::_( 'DT_CURRENCY_CODE_HELP' )), '', 'tooltip.png', '', ''); ?> </td>

    </tr>
    
    <tr align="center" valign="middle">
	
	    <td align="left" valign="top"><strong><?php echo JText::_( 'CURRENCY_SEPARATOR' );?>:</strong></td>

		<td align="left" valign="top">

                <?php

                   	$options=array();

					$options[]=JHTML::_('select.option', '1', JText::_( 'COMMA' )." ( , )");

					$options[]=JHTML::_('select.option', '2', JText::_( 'DOT' )." ( . )");
                     if(!isset($row->config['currency_separator']) || $row->config['currency_separator']==""){
						 $currency_separator=2;
					}else{

			             $currency_separator = $row->config['currency_separator'] ;

			         }
					echo JHTML::_('select.genericlist', $options, 'data[config][currency_separator]',' ', 'value','text', $currency_separator);

				?>

		</td>

		<td><?php echo JHTML::tooltip((JText::_( 'CURRENCY_SEPARATOR_HELP' )), '', 'tooltip.png', '', ''); ?> </td>

    </tr>
                 <tr valign="middle"><td align="left" class="dt_heading" colspan="4"><?php echo JText::_( 'DT_GENERAL_PAY_OPTIONS' ); ?></td></tr>

								 <!-- ********** General Payment Options ******************	-->
<tr align="center" valign="middle">  <td align="left" valign="top"><strong><?php echo JText::_( 'DT_PAYMENT_MODE' );?>:</strong></td>

							    <td align="left" valign="top">

							    <?php

								    $options=array();

								    $options[]=JHTML::_('select.option', 'test', JText::_( 'DT_TEST' ));//

								    $options[]=JHTML::_('select.option', 'live', JText::_( 'DT_LIVE' ));

								    echo JHTML::_('select.genericlist', $options,'data[config][paymentmode]','','value','text',$config->getGlobal('paymentmode'));

							     ?>

							    </td>

								  <td><?php echo JHTML::tooltip((JText::_( 'DT_PAYMENT_MODE_HELP' )), '', 'tooltip.png', '', ''); ?> </td>
								
								<td valign="top" rowspan="2"><?php echo JText::_( 'DT_NOTES_PAYMENT_METHODS' ); ?></td>

							  </tr>
							   <tr align="center" valign="middle">

                   <td width="210" align="left" valign="top"><strong><?php echo JText::_( 'PAYMENT_METHOD' ); ?>:</strong></td>

							     <td width="200" align="left" valign="top">

							     <!-- Here we will need to select payment methods-->

							     <?php
                                   
                                   //Create the selected array
                                    $options=DtHtml::options($paymentmethod->getMethods());
									
									echo JHTML::_('select.genericlist', $options,'data[config][paymentmethod][]',' multiple=true ', 'value','text',array());

							     ?>

							     </td>

								   <td width="40"><?php echo JHTML::tooltip((JText::_( 'DT_PAYMENT_METHOD_HELP' )), '', 'tooltip.png', '', ''); ?> </td>

							   </tr>

							   <!-- *************** Authorize.net Options **************** -->

                 <tr valign="middle"><td align="left" class="dt_heading" colspan="4"><?php echo JText::_( 'AUTH_NET' ); ?></td></tr>

	 						 	 <tr align="center" valign="middle">

                     <td align="left" valign="top">

										 <strong><?php echo JText::_( 'DT_MERCHANT_ID' ); ?>:</strong>

										 </td>

									   <td align="left" valign="top"> <input type="text" name="data[config][merchid]" size="30" value="<?php echo $config->getGlobal('merchid',''); ?>" /> </td>

										 <td><?php echo JHTML::tooltip((JText::_( 'DT_MERCHANT_ID' )), '', 'tooltip.png', '', ''); ?> </td>
										 									   
									   <td valign="top" rowspan="3"><?php echo JText::_( 'DT_NOTES_AUTHNET' ) ;?></td>

								 </tr>

								 <tr align="center" valign="middle">

                     <td align="left" valign="top"><strong><?php echo JText::_( 'DT_TRANS_KEY' ); ?>:</strong></td>

									   <td align="left" valign="top">	<input type="text" name="data[config][transkey]" size="30" value="<?php echo $config->getGlobal('transkey',''); ?>" /> </td>

										 <td><?php echo JHTML::tooltip((JText::_( 'DT_TRANS_KEY' )), '', 'tooltip.png', '', ''); ?> </td>

								</tr>

								<tr align="center" valign="middle">  <td align="left" valign="top"><strong><?php echo JText::_( 'DT_CARD_TYPE' ); ?>:</strong></td>

							    <td align="left" valign="top">
                                <?php
								 
								 echo DtHtml::checkboxList('data[config][cardtype]',$cardtype->gettypes(),$config->getGlobal('cardtype',array()));
								?>
                               </td>

									<td><?php echo JHTML::tooltip((JText::_( 'DT_CARD_TYPE_HELP' )), '', 'tooltip.png', '', ''); ?> </td>

							  </tr>
							  
							  <!-- *************** Google Checkout Options ***************	-->
							  
							  <tr valign="middle"><td align="left" class="dt_heading" colspan="4"><?php echo JText::_( 'DT_GOOGLE_CHECKOUT' ); ?></td></tr>
                                 
                 <tr align="center" valign="middle">

                     <td align="left" valign="top">

										 <strong><?php echo JText::_( 'DT_GOOGLE_MERCHANT_ID' ); ?>:</strong>

										 </td>

									   <td align="left" valign="top"> <input type="text" name="data[config][googlemerchid]" size="30" value="<?php echo $config->getGlobal('googlemerchid') ; ?>" /> </td>

										 <td><?php echo JHTML::tooltip((JText::_( 'DT_GOOGLE_MERCHANT_ID_HELP' )), '', 'tooltip.png', '', ''); ?> </td>
										 
										 <td valign="top" rowspan="2"><?php echo JText::_( 'DT_NOTES_GOOGLE_CHECKOUT' ) ;?></td>

								 </tr>
                                 
                 <tr align="center" valign="middle">

                     <td align="left" valign="top"><strong><?php echo JText::_( 'DT_GOOGLE_TRANS_KEY' ); ?>:</strong></td>

									   <td align="left" valign="top">	<input type="text" name="data[config][googleapikey]" size="30" value="<?php echo $config->getGlobal('googleapikey'); ?>" /> </td>

										 <td><?php echo JHTML::tooltip((JText::_( 'DT_GOOGLE_TRANS_KEY_HELP' )), '', 'tooltip.png', '', ''); ?> </td>

								 </tr>

                          <!-- Net Deposit Options -->
                          
                            <tr valign="middle"><td align="left" class="dt_heading" colspan="4"><?php echo JText::_( 'DT_NETDEPOSIT' ); ?></td></tr>
                                 
                 <tr align="center" valign="middle">

                     <td align="left" valign="top">

										 <strong><?php echo JText::_( 'DT_NETDEPOSIT_CLIENTID' ); ?>:</strong>

										 </td>

									   <td align="left" valign="top"> <input type="text" name="data[config][netdeposit_clientid]" size="30" value="<?php echo $config->getGlobal('netdeposit_clientid'); ?>" /> </td>

										 <td><?php echo JHTML::tooltip((JText::_( 'DT_NETDEPOSIT_CLIENTID_HELP' )), '', 'tooltip.png', '', ''); ?> </td>
										 
										 <td valign="top" rowspan="2"><?php echo JText::_( 'DT_NOTES_NETDEPOSIT' ) ;?></td>

								 </tr>
                                 
                 <tr align="center" valign="middle">

                     <td align="left" valign="top"><strong><?php echo JText::_( 'DT_NETDEPOSIT_CLIENTCODE' ); ?>:</strong></td>

									   <td align="left" valign="top">	<input type="text" name="data[config][netdeposit_clientcode]" size="30" value="<?php echo $config->getGlobal('netdeposit_clientcode'); ?>" /> </td>

										 <td><?php echo JHTML::tooltip((JText::_( 'DT_NETDEPOSIT_CLIENTCODE_HELP' )), '', 'tooltip.png', '', ''); ?> </td>

								 </tr>
                
		  					<!-- *************** PayPal Options ***************	-->

							  <tr valign="middle"><td align="left" class="dt_heading" colspan="4"><?php echo JText::_( 'PAYPAL' ); ?></td></tr>

								<tr align="center" valign="middle">  <td align="left" valign="top"><strong><?php echo JText::_( 'PAYPAL_ID' ); ?>:</strong></td>

							    <td align="left" valign="top"><input type="text" name="data[config][paypalid]" size="30" value="<?php echo stripslashes($config->getGlobal('paypalid')); ?>"></td>

								  <td><?php echo JHTML::tooltip((JText::_( 'PAYPAL_ID_HELP' )), '', 'tooltip.png', '', ''); ?> </td>
								  
								  <td valign="top"><?php echo JText::_( 'DT_NOTES_PAYPAL' ); ?></td>

							  </tr>

	 <tr valign="middle"><td align="left" class="dt_heading" colspan="4"><?php echo JText::_( 'DT_EWAY' ); ?></td></tr>
                                 
                <tr align="center" valign="middle">

                     <td align="left" valign="top">

										 <strong><?php echo JText::_( 'DT_EWAY_CUSTOMERID' ); ?>:</strong>

										 </td>

									   <td align="left" valign="top"> <input type="text" name="data[config][eway_customerid]" size="30" value="" /> </td>

										 <td><?php echo JHTML::tooltip((JText::_( 'DT_EWAY_CUSTOMERID_HELP' )), '', 'tooltip.png', '', ''); ?> </td>
										 
										 <td valign="top" rowspan="3"><?php echo JText::_( 'DT_NOTES_EWAY' ) ;?></td>

								 </tr>

				<!---tr align="center" valign="middle">  <td align="left" valign="top"><strong><?php echo JText::_( 'DT_EWAY_MODE' );?>:</strong></td>

							    <td align="left" valign="top">

							    <?php

								    $options=array();

								    $options[]=JHTML::_('select.option', 'test', JText::_( 'DT_TEST' ));

								    $options[]=JHTML::_('select.option', 'live', JText::_( 'DT_LIVE' ));

								    echo JHTML::_('select.genericlist', $options,'data[config][ewaymode]','','value','text');

							     ?>

							    </td>

								  <td><?php echo JHTML::tooltip((JText::_( 'DT_EWAY_MODE_HELP' )), '', 'tooltip.png', '', ''); ?> </td>

							  </tr--->
                             
                <tr align="center" valign="middle">  <td align="left" valign="top"><strong><?php echo JText::_( 'DT_EWAY_TYPE' );?>:</strong></td>

							    <td align="left" valign="top">

							    <?php

								    $options=array();

								    $options[]=JHTML::_('select.option', 'hosted', JText::_( 'DT_SHARED' ));//

								    $options[]=JHTML::_('select.option', 'live', JText::_( 'DT_HOSTED' ));

								    echo JHTML::_('select.genericlist', $options,'data[config][ewaytype]','','value','text');

							     ?>

							    </td>

								  <td><?php echo JHTML::tooltip((JText::_( 'DT_EWAY_TYPE_HELP' )), '', 'tooltip.png', '', ''); ?> </td>

							  </tr>							

							<!-- **************** iDeal Mollie Options *************	-->

                <tr valign="middle"><td align="left" class="dt_heading" colspan="4"><?php echo JText::_( 'DT_PAY_IDEAL_MOLLIE' ); ?></td></tr>

								<tr align="center" valign="middle">

                  <td align="left" valign="top"><strong><?php echo JText::_( 'IDEAL_ID' ); ?>:</strong></td>

							    <td align="left" valign="top"><input type="text" name="data[config][partner_id]" size="30" value="<?php echo stripslashes($config->getGlobal('partner_id')); ?>"></td>

								  <td><?php echo JHTML::tooltip((JText::_( 'IDEAL_ID_HELP' )), '', 'tooltip.png', '', ''); ?> </td>
								  
								  <td valign="top"> </td>

							  </tr>

								
							  
                <!--- ************************* Ideal Rabobank Lite ************************* -->

                <tr valign="middle"><td align="left" class="dt_heading" colspan="4"><?php echo JText::_( 'DT_PAY_IDEAL_LITE' ); ?></td></tr>

								<tr align="center" valign="middle">

                  <td align="left" valign="top"><strong><?php echo JText::_( 'IDEAL_LITE_MERCHANT_ID' ); ?>:</strong></td>

							    <td align="left" valign="top"><input type="text" name="data[config][idealLiteMerchantId]" size="30" value="<?php echo stripslashes($config->getGlobal('idealLiteMerchantId')); ?>"></td>

								  <td><?php echo JHTML::tooltip((JText::_( 'IDEAL_LITE_MERCHANT_ID_HELP' )), '', 'tooltip.png', '', ''); ?> </td>
								  
								  <td valign="top" rowspan="2"> </td>

							  </tr>
                              
                <tr align="center" valign="middle">

                  <td align="left" valign="top"><strong><?php echo JText::_( 'IDEAL_LITE_HASH_KEY' ); ?>:</strong></td>

							    <td align="left" valign="top"><input type="text" name="data[config][idealLiteHashKey]" size="30" value="<?php echo stripslashes($config->getGlobal('idealLiteHashKey'))  ?>"></td>

								  <td><?php echo JHTML::tooltip((JText::_( 'IDEAL_LITE_HASH_KEY_HELP' )), '', 'tooltip.png', '', ''); ?> </td>

							  </tr>

								
  <tr valign="middle"><td align="left" class="dt_heading" colspan="4"><?php echo JText::_( 'DT_SAGE' ); ?></td></tr>
                                 
                 <tr align="center" valign="middle">

                     <td align="left" valign="top">

										 <strong><?php echo JText::_( 'DT_SAGE_M_ID' ); ?>:</strong>

										 </td>

									   <td align="left" valign="top"> <input type="text" name="data[config][sage_M_id]" size="30" value="<?php echo $config->getGlobal('sage_M_id'); ?>" /> </td>

										 <td><?php echo JHTML::tooltip((JText::_( 'DT_SAGE_M_ID_HELP' )), '', 'tooltip.png', '', ''); ?> </td>
										 
										 <td valign="top" rowspan="2"><?php echo JText::_( 'DT_NOTES_SAGE' ) ;?></td>

								 </tr>
                                 
                 <tr align="center" valign="middle">

                     <td align="left" valign="top"><strong><?php echo JText::_( 'DT_SAGE_M_KEY' ); ?>:</strong></td>

									   <td align="left" valign="top">	<input type="text" name="data[config][sage_M_key]" size="30" value="<?php echo $config->getGlobal('sage_M_key'); ?>" /> </td>

										 <td><?php echo JHTML::tooltip((JText::_( 'DT_SAGE_M_KEY_HELP' )), '', 'tooltip.png', '', ''); ?> </td>

								 </tr>
							<!-- ******************* Pay Later Options ****************	-->

							   <?php
							   		
                                    $options=DtHtml::options($paylater->getOptions());
									$selectedOptions=DtHtml::options($config->getGlobal('pay_later_options',array()));

							   ?>

                <tr valign="middle"><td align="left" class="dt_heading" colspan="4"><?php echo JText::_( 'DT_PAYLATER_OFFLINE' ); ?></td></tr>

							  <tr align="center" valign="middle">

                  <td align="left" valign="top"><strong><?php echo JText::_( 'DT_PAY_LATER_OPTIONS' ) ;?>:</strong></td>

							    <!--td align="left" valign="top">

							   	<?php

							   		//echo JHTML::_('select.genericlist', $options, 'data[config][pay_later_options][]',' multiple=true ', 'value','text', $selectedOptions);

							   	?>

							    </td-->
							     <td>
                                  
                                   <a href="#" id="addmore">Add more</a>
                                     <br/>
                                     <span class="container">
                                     <?php
                                        echo DtHtml::paylatercheckboxlist('data[config][pay_later_options]',$paylater->options);
									  ?>
                                     </span>
                                    
                                   </td>
								  <td><?php echo JHTML::tooltip((JText::_( 'DT_PAY_LATER_OPTIONS_HELP' )), '', 'tooltip.png', '', ''); ?> </td>
								  
								  <td valign="top"><?php echo JText::_( 'DT_NOTES_PAYLATER' ) ;?></td>

							  </tr>

	 						 </table>
      <input type="hidden" name="option" value="com_dtregister" />
     <input type="hidden" name="controller" value="payoption" />

      <input type="hidden" id="task" name="task" value="" />
</form>
 <span style="display:none">
  <span id="newelement">
     <span>
     <input type="checkbox" class="checkboxes" name="data[config][pay_later_options][]" value="" /> 
     <input type="text" name="data[paylater][]" /><input type="hidden" name="data[paylaterIds][]" value="new" /> <a href="#" class="remove"><?php echo JText::_('DT_REMOVE'); ?></a>
     <br/>
     </span>
  </span>
</span>
<script type="text/javascript">
   DTjQuery(function(){
	   
	   DTjQuery("#addmore").click(function(){
		   DTjQuery('.container').append(DTjQuery("#newelement").html());
		   arrangeAddmoreValues();
		   return false ;
	   });
		 
	   DTjQuery('.remove').live('click',function(){
		  
		   
         
		    if(DTjQuery(this).prev().val() != 'new'){
				   DTjQuery(this).prev().prev().rules("remove", "uniquevalue");
				   var ajaxcontext = this ;
			       DTjQuery("form input[name='"+DTjQuery(this).prev().prev().attr('name')+"']").rules("add", { 
				   remote: {
					   url:"index.php?option=com_dtregister&controller=paylater&task=validate&no_html=1&value="+DTjQuery(this).prev().val(), 
					   error:function(){
						    DTjQuery("form input[name='"+DTjQuery(ajaxcontext).prev().prev().attr('name')+"']").rules("add", { uniquevalue: true});
							arrangeAddmoreValues();
						   },
					   success: function (data){
						    if(data == 'false'){
							     
					             DTjQuery(ajaxcontext).prev().prev().rules("remove", "remote");
				                 DTjQuery("form input[name='"+DTjQuery(ajaxcontext).prev().prev().attr('name')+"']").rules("add", { uniquevalue: true});
							}else{
								alert('<?php echo addslashes(JText::_('DT_PAYLATER_REMOVED'));?>');
					            DTjQuery(ajaxcontext).prev().prev().rules("remove", "remote");
				                DTjQuery("form input[name='"+DTjQuery(ajaxcontext).prev().prev().attr('name')+"']").rules("add", { uniquevalue: true});
					            DTjQuery(ajaxcontext).parent().remove();
							}
						    arrangeAddmoreValues();
						  }
					   }});
				   DTjQuery(document.adminForm).validate().element( DTjQuery(this).prev().prev());
			  
			}else{
			   	DTjQuery(this).parent().remove();
			}
        
		   arrangeAddmoreValues();
		   
		   return false;   
	   })
		   
   })
   
   function arrangeAddmoreValues(){
	   
	   DTjQuery.each(DTjQuery('.container').find(".checkboxes"),function(k,v){
		   
		   v.value = k ;
		 
	  });
	      
   }
</script>
