<?php
global $Itemid ;
$mMember = $this->mMember->table ;
?>
<form name="adminForm" method="post" action="index.php" enctype="multipart/form-data">
   <table>
      
      
      

       
     <?php
       
	   echo  $this->form ;
	 
	   
	 ?>
     
   
   </table>
   <input type="text" name="Member[groupMemberId]" value="<?php echo $mMember->groupMemberId; ?>" />
   <input type="text" name="Member[groupUserId]" value="<?php echo $mMember->groupUserId; ?>" />
   <input type="hidden" name="formsubmit" value="edit" />
   <input type="hidden" name="option" value="com_dtregister" />
   <input type="hidden" name="controller" value="member" />
   <input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
   <input type="hidden" name="task" value="edit" />
</form>
<div id="debug">
</div>
<script type="text/javascript">
 
 
 DTjQuery(function(){
   //DTjQuery.validator.messages.required = " ";

    DTjQuery(document.adminForm).validate({
	        success: function(label) {
				label.addClass("success");
			}

	});
	
	
 })
 
 function submitbutton(pressbutton){
      if(pressbutton == "cancel"){
	  submitform(pressbutton);	
	}
	if(DTjQuery(document.adminForm).valid()){
	  	submitform(pressbutton);
	}
	
	return false ;

}

</script>