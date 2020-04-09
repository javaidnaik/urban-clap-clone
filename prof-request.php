<?php 
include "includes/title.php";
if(!(isset($_SESSION['pid'])) && !(isset($_SESSION['pemail'])) && !(isset($_SESSION['pfname'])))
{
header("location:login");
echo "<script>window.location='login'</script>";
}
$del=isset($del)?$del:'';
if(isset($_REQUEST['del'])){
	$del=base64_decode($_REQUEST['del']);
	$db->insertrec("update sent_enquiry set prof_response='6' where id='$del'");
	header("Location: prof-request?canceled");
	echo "<script>href.location='prof-request?canceled'</script>";
	exit;
}
$uid=$_SESSION['pid'];
$res_number=isset($res_number)?$res_number:'';
$res_email=isset($res_email)?$res_email:'';
$res_message=isset($res_message)?$res_message:'';
$_senterid=isset($_senterid)?$_senterid:'';
$_receiverid=isset($_receiverid)?$_receiverid:'';
$form_id=isset($form_id)?$form_id:'';
$_cat_id=isset($_cat_id)?$_cat_id:'';
if(isset($profes_response)){
	$res_number=$db->escapstr($res_number);
	$res_email=$db->escapstr($res_email);
	$res_message=$db->escapstr($res_message);
	$_senterid=$db->escapstr(base64_decode($_senterid));
	$_receiverid=$db->escapstr(base64_decode($_receiverid));
	$_senterid=$db->escapstr($_senterid);
	$_receiverid=$db->escapstr($_receiverid);
	$form_id=$db->escapstr(base64_decode($form_id));
	$_cat_id=$db->escapstr(base64_decode($_cat_id));
	
	$date=date("Y-m-d H:i:s");
	$set="senter_id='$_senterid'";
	$set.=",responser_id='$_receiverid'";
	$set.=",responser_autoid='$form_id'";
	$set.=",cat_id='$_cat_id'";
	$set.=",contact_no='$res_number'";
	$set.=",email='$res_email'";
	$set.=",message='$res_message'";
	$set.=",crcdt='$date'";
	$set.=",status='1'";
	
	$db->insertrec("insert into response set $set");
	$db->insertrec("update sent_enquiry set prof_response='1' where id='$form_id'");
	header("Location: prof-pending?success");
	echo "<script>href.location='prof-pending?success'</script>";
	exit;
}
?>
<body>

    <div class="layer"></div>
    <!-- Mobile menu overlay mask -->

    <!-- Header================================================== -->
     <?php include "includes/header.php"; ?>
    <section class="parallax-window" data-parallax="scroll" data-image-src="img/home_bg_2.jpg" data-natural-width="1400" data-natural-height="470">
               <?php include"welcomeprofession.php"; ?>
            </section>
	
	<section class="container-fluid margin_31 test3_bg">
       <div class="container mt30 mb30">
		  <div class="row">
			<?php include "profleftmenu.php"; ?>
			<div class="col-lg-9 col-md-9">
<?php if(isset($_REQUEST['success'])) { ?> 
	<div class="alert alert-block alert-success">
		<button type="button" class="close" data-dismiss="alert">
			<i class="icon-remove"></i>
		</button>
	 <i class="icon-ok green"></i>
		<strong class="green">
			Response Sent Successfully !!!
		</strong>

	</div>
<?php } ?>
<?php if(isset($_REQUEST['canceled'])) { ?> 
	<div class="alert alert-block alert-success">
		<button type="button" class="close" data-dismiss="alert">
			<i class="icon-remove"></i>
		</button>
	 <i class="icon-ok green"></i>
		<strong class="green">
			User order cancelled Successfully !!!
		</strong>

	</div>
<?php } ?>
                     <div class="col-sm-12 profile_box">
					  
		<div class="row mb10">
		   <div class="col-sm-12">
		       <h3 class="clr_txt">My Request</h3>
		   </div>
		   <div class="clearfix"></div>
		   <div class="col-sm-12">
		       <div class="table-responsive">
			       <table id="example" class="table table-striped table-bordered appointments_tbl" cellspacing="0" width="100%">
				  
				      <thead>
					       <tr>
						      <th>S.No</th>
						      <th>User Name</th>
						      <th>Order Date</th>
						      <th>Budget</th>
						      <th>Location</th>
						      <th>Status</th>
						      <th style="min-width:100px;">Response</th>
						      <!-- <th>View</th> -->
						   </tr>
					   </thead>

					   <tbody>
<?php
$psesid=$_SESSION['pid'];
$i=1;
$catid=$db->singlerec("select cat_id from register where id='$psesid' and user_role='1' and active_status='1'");
$catid=$catid['cat_id'];
$sql="";
if(!empty($catid)){
	$sql .=" and cat_id='$catid'";
}


$reqInfo=$db->get_all("select * from sent_enquiry where  role='0' and status!='6' and prof_response='0' and receiver_id='$psesid' and status='0'");
$i=1;
foreach($reqInfo as $req){
	$id=$req['id'];
	$senterid=$req['senter_id'];
	$genInfo=$db->singlerec("select * from register where id='$senterid' and user_role='0'");
	$receiverid=$req['receiver_id'];
	$catid=$req['cat_id'];
	$catname=$com_obj->cat_name($catid);
	$username=$com_obj->get_name($senterid);
	$date=$req['crcdt'];
	$date=date("Y-m-d",strtotime($date));
	$budget_from=$req['comp_budget_from'];
	$budget_to=$req['comp_budget_to'];
	$location=ucfirst($req['comp_location']);
	$comp_qus=$req['comp_qusans'];
	$comp_qus=json_decode($comp_qus, true);
	if(!empty($comp_qus['ans1'])) $status=$comp_qus['ans1']; 
	elseif(!empty($comp_qus['ans2'])) $status=$comp_qus['ans2']; 
	elseif(!empty($comp_qus['ans3'])) $status=$comp_qus['ans3'];
	elseif(!empty($comp_qus['ans4'])) $status=$comp_qus['ans4']; 
	elseif(!empty($comp_qus['ans5'])) $status=$comp_qus['ans5']; else $status="-";
	$date=$req['crcdt'];
	$date=date("Y-m-d",strtotime($date));
	//$genInfo=$com_obj->getProfInfo($receiverid);
?>                      
					        <tr>
							   <td><?php echo $i; ?></td>
							   <td><?php echo ucfirst($username); ?></td>
							   <td><?php echo $date; ?></td>
							   <td><?php echo $budget_from." - ".$budget_to; ?></td>
							   <td><?php if(!empty($location)) echo $location; else echo "-"; ?></td>
							   <td><?php echo $status; ?></td>
							   <td>
							     <ul class="edt_list">
								    <li><a href="#" class="btn-sm btn-success" data-toggle="modal" data-target="#prof_response-<?php echo $id; ?>"><i class="fa fa-reply" aria-hidden="true"></i></a></li>
									<li><a href="prof-request?del=<?php echo base64_encode($id); ?>" onclick="if(confirm('Are you sure want to cancel this order?')) { return true; }else { return false; }" class="btn-sm btn-danger"><i class=" icon-trash"></i></a></li><li><a href="prof-request-details?id=<?php echo base64_encode($id); ?>" class="btn-sm btn-info"><i class="fa fa-eye" aria-hidden="true"></i></a></li>
								 </ul>
							   </td>
							   <!--<td><a href="prof-request-details.php" target="_blank">View</a> </td>-->
							</tr>
<div class="modal fade deflt_mdl" id="prof_response-<?php echo $id; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">X</span></button>
        <h4 class="modal-title" id="myModalLabel">Response To <?php echo ucfirst($username); ?></h4>
      </div>
      <div class="modal-body">
		<div class="row">
			<div class="col-sm-12">
				<form method="post">
					<div class="form-group">
						<label>Contact Number</label>
						<input class="form-control" name="res_number" value="<?php echo !empty($genInfo['contact_no1'])?$genInfo['contact_no1']:''; ?>" type="text">
					</div>
					<div class="form-group">
						<label>Email</label>
						<input class="form-control" name="res_email" value="<?php echo !empty($genInfo['email'])?$genInfo['email']:''; ?>" type="text" readonly>
					</div>
					<div class="form-group">
						<label>Enter The Message</label>
						<textarea class="form-control" name="res_message" rows="5"></textarea>
					</div>
					
					<input type="hidden" name="form_id" value="<?php echo base64_encode($id); ?>" />
					<input type="hidden" name="_senterid" value="<?php echo base64_encode($senterid); ?>" />
					<input type="hidden" name="_receiverid" value="<?php echo base64_encode($uid); ?>" />
					<input type="hidden" name="_cat_id" value="<?php echo base64_encode($catid); ?>" />
					<div class="form-group text-center">
						<button class="btn_2 btn agelogbt" type="submit" name="profes_response">Send Now</button>
					</div>
				</form>
			</div>
		</div>
      </div>
    </div>
  </div>
</div>
<?php $i++; } ?>
					   </tbody>
				   </table>
			   </div>
		   </div>
		</div>
		
		
					 </div>
                  </div>
		  </div>
      </div>
    </section> 
	

		
<!--*********************** Footer ****************************-->

<?php include "includes/footer.php"; ?>

<div id="toTop"></div><!-- Back to top button -->

 <!-- Common scripts -->
<script src="js/jquery-1.11.2.min.js"></script>
<script src="js/common_scripts_min.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
<script src="js/functions.js"></script>
<script>
$(document).ready(function() {
    $('#example').DataTable();
} );
</script>

  </body>
</html>