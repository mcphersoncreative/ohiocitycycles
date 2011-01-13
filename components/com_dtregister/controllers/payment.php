<?php

class DtregisterControllerPayment extends DtrController {

	

	 

   var $name = "payment";

   

    function __construct($config = array()){

		 parent::__construct($config);

		 $this->view = & $this->getView( 'payment', 'html' );

		 $this->view->setModel($this->getModel('paymentmethod'));

		 

		 $this->view->setModel($this->getModel('user'));
		
		  $this->view->setModel($this->getModel('event'));
		   
		 // $evtTable = $this->getModel('event')->table ;  //prd($_SESSION);
         $evtTable = $this->getModel('event')->table ;  //prd($_SESSION);
		 if(DT_Session::get('register.Event.eventId')){
		   $evtTable->overrideGlobal(DT_Session::get('register.Event.eventId'));
		 }
		 if(DT_Session::get('register.User.eventId') || DT_Session::get('register.User.0.eventId')){
			 if(DT_Session::get('register.User.eventId')){
			    $evtTable->overrideGlobal(DT_Session::get('register.User.eventId'));
			 } else {
				 $evtTable->overrideGlobal(DT_Session::get('register.User.0.eventId'));
			 }
		    
		 }
		
		$this->evtTable = $evtTable;
		
		 $this->cart = new DT_Cart();

		 /*$amount = $this->cart->getAmount();

		 if(!($amount > 0)){

		    	 

		 }*/
		

	}

	

	function methods(){

	    

		$this->view->setLayout('options');
		
		if(DT_Session::get('register.User.process')){
			$this->view->assign('header_eventId',DT_Session::get('register.User.0.eventId'));
		}
        
		$this->view->display();

			

	}

	

	function billinginfo(){

	   global $mainframe ,$Itemid;
       $this->getModel('field');
	   if(isset($_POST['billing'])){

		   

		  

		   DT_Session::set('register.payment.billing',$_POST['billing']) ;

		   

		   $mainframe->redirect("index.php?option=com_dtregister&controller=payment&task=form&Itemid=".$Itemid);

		        

	   }
	   
	   if(DT_Session::get('register.User.process')){
			$this->view->assign('header_eventId',DT_Session::get('register.Event.eventId'));
		}
        $billingInfo = DT_Session::get('register.payment.billing');
	   	$paymentClass = DT_Session::get('register.payment.method');

		require_once( JPATH_SITE.'/components/com_dtregister/lib/payment/'.$paymentClass.'.php');

		

		$payment =  new $paymentClass();
        $payment->setBillinginfo($billingInfo);
		$form =  $payment->billingform();

		$this->view->assign('form',$form);
		
		// echo '<pre>'; print_r(DT_Session::get('register.User.0.fields'));

	   $this->display();

	}
    
	function notification(){
	    global $Itemid ,$mainframe,$DT_mailfrom,$DT_fromname;
		//ob_start();
	    $paypal_session_id = JRequest::getVar('return');
		DT_Session::restoreFromPayment($paypal_session_id);
		$paymethod = DT_Session::get('register.payment.method') ;
		$paymentClass = DT_Session::get('register.payment.method');
		
		require_once( JPATH_SITE.'/components/com_dtregister/lib/payment/'.$paymentClass.'.php');
		$payment =  new $paymentClass();
		
		if($payment->notify()){//$payment->notify()
		    
			if(!$payment->bywebservice){

			 // session restore here ;
             $messageTask = 'index';
			 if(DT_Session::get('register.User.process')==""){

					  $function = 'registerall';
					  $messageTask = 'thanks';

			 }else{

					  $function =  DT_Session::get('register.User.process') ;

			 }
			 $processed = DT_Session::get('register.restore.processed') ;
		 //if(1){
			
		    if($processed !== false && $processed === "0"){
				$tableUser = $this->getModel('user')->table ; 
				$paymethod = DT_Session::get('register.payment.method') ;
			    $tableUser->{$function}(DT_Session::get('register'), $paymethod);
				
				$payment->markprocessed(DT_Session::get('register.restore.id'),$tableUser->userId);
			}
             
			  prd(DT_Session::get('register') );

		 }
			
			
		}
		//echo $debug = ob_get_clean();
		//JUTility::sendMail($DT_mailfrom, $DT_fromname,'gslitt91@yahoo.co.uk',"test",$debug,1);
		//DT_Session::clearAll();
		
	}
	
	function restore(){
	    global $Itemid ,$mainframe;
	    $paypal_session_id = JRequest::getVar('return');
		DT_Session::restoreFromPayment($paypal_session_id);
		
		$baseurl = str_replace('components/com_dtregister/','',Juri::base());
		pr($_SESSION);
		
	    $mainframe->redirect($baseurl."index.php?option=com_dtregister&controller=payment&task=success&Itemid=$Itemid");
	   
	   	
	}

	function success(){

	  	global $Itemid ,$mainframe;
		$tableUser = $this->getModel('user')->table ;
        $paymentClass = DT_Session::get('register.payment.method');
        require_once( JPATH_SITE.'/components/com_dtregister/lib/payment/'.$paymentClass.'.php');
		$payment =  new $paymentClass();

		if(!$payment->bywebservice){

			 // session restore here ;
             $messageTask = 'index';
			 if(DT_Session::get('register.User.process')==""){

					  $function = 'registerall';
					  $messageTask = 'thanks';

			 }else{

					  $function =  DT_Session::get('register.User.process') ;

			 }
			 $processed = DT_Session::get('register.restore.processed') ;
		 //if(1){
		    if($processed !== false && $processed === "0"){
				 $paymethod = DT_Session::get('register.payment.method') ;
			    $tableUser->{$function}(DT_Session::get('register'), $paymethod);
				
				$payment->markprocessed(DT_Session::get('register.restore.id'),$tableUser->userId);
			 }
             
			

		 }
		pr(DT_Session::get('register'));
         //DT_Session::clearAll();
		 $mainframe->redirect("index.php?option=com_dtregister&controller=message&Itemid=$Itemid&task=".$messageTask);
		 echo "event details Thanks";

	

	}

	

	function cancel(){

       global $xhtml_url,$Itemid ; 
	  	
       $msg  = '<h3>'.JText::_( 'DT_PAYMENT_CANCELLED' ).'</h3>';
       DT_Session::clearAll();
	echo $msg.'<a href="'. JRoute::_('index.php',$xhtml_url).'">'.JText::_( 'RETURN_TO_SITE' ).'</a>';
	   
	   

	   

	}

	

	function form(){
		global $Itemid ;
		
		if(DT_Session::get('register.User.process')){
			$this->view->assign('header_eventId',DT_Session::get('register.Event.eventId'));
			$this->header_eventId = DT_Session::get('register.Event.eventId') ;
		}
		
        if(DT_Session::get('register.User.process')){
            include(JPATH_SITE.DS.'components'.DS.'com_dtregister'.DS.'views'.DS.'user'.DS.'tmpl'.DS.'event_header.php');
        }else{
       ?>
         <div class="componentheading"><?php echo JText::_('DT_EVENT_REGISTRATION')?>: <?php echo JText::_( 'DT_PAYMENT' );?>

		</div>
      <?php	
        }
		global $mainframe ,$Itemid;
		$userIndex = DT_Session::get('register.Setting.current.userIndex');
		if($userIndex !== false && $userIndex !=""){
           DT_Session::clear('register.User.'.$userIndex.'.TableEvent');
		   DT_Session::clear('register.User.'.$userIndex.'.TableUser');
		}
		$billingInfo = DT_Session::get('register.payment.billing');

		if(isset($_REQUEST['paymentmethod'])){

		   	if($_REQUEST['paymentmethod']=="pay_later"){

			   	

				DT_Session::set('register.payment.method',$_REQUEST['pay_later_option']);

				

			}else{

				

			   DT_Session::set('register.payment.method',$_REQUEST['paymentmethod']);

			   

			}

		}

		

		$no_billing =  array('GoogleCheckout','paypal','pay_later');

		if(empty($billingInfo) && (!in_array($_REQUEST['paymentmethod'],$no_billing))){

		   $mainframe->redirect("index.php?option=com_dtregister&controller=payment&task=billinginfo&Itemid=".$Itemid);

	    }


	    $tableUser = $this->getModel('user')->table ;

		$paymentClass = DT_Session::get('register.payment.method');

		if(isset($_REQUEST['paymentmethod']) && $_REQUEST['paymentmethod']=="pay_later"){			 

			 $paymethod = DT_Session::get('register.payment.method') ;
			 
			 $tableUser->registerall(DT_Session::get('register'),$paymethod);
			 
			 $mainframe->redirect("index.php?option=com_dtregister&controller=message&task=paylater&Itemid=".$Itemid);

		}else{
			

		  require_once( JPATH_SITE.'/components/com_dtregister/lib/payment/'.$paymentClass.'.php');

		  $payment =  new $paymentClass();

		  

		  $payment->setBillinginfo($billingInfo);
			 if(DT_Session::get('register.User.process')==""){

					  $function = 'registerall';
					  $messageTask = 'thanks';
					  
					  $count = count(DT_Session::get('register.User'));
					  
					  $payment->confirmNum = DT_Session::get('register.User.0.confirmNum');
					  if ($count == 1) {
					  		$payment->description = $this->evtTable->title;  
					  } else {
						  	$payment->description = JText::_('DT_EVENT_PAYMENT_DESCRIPTION');
					  }

				  }else{

					  $function =  DT_Session::get('register.User.process') ;
					  $payment->confirmNum = DT_Session::get('register.User.0.confirmNum');
					  $payment->description = $this->evtTable->title;

				  }	  
			
		  $success =   $payment->process();

		   if($payment->bywebservice){

			 if($success === true ){
                 
				 
                  $paymethod = DT_Session::get('register.payment.method') ;
				  
				  $tableUser->{$function}(DT_Session::get('register'),$paymethod);
                 
				$mainframe->redirect("index.php?option=com_dtregister&controller=message&Itemid=$Itemid&task=".$messageTask);

			  }else if($success === false){

				  $payment->afterFailed();

				  echo $payment->tryAgain();

			  }

		   }

		}

	  	$this->view->setLayout('form');

		$this->view->display();

	}

   	

}