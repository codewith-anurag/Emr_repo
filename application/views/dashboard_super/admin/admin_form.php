<style type="text/css">
  .page-sidebar {
      height: auto;
      width: 260px;
      position: fixed;
      padding: 0px;
      background-color: #ffffff;
      margin-top: 60px;
      overflow: hidden;
      box-shadow: 0 0 0px 0 rgba(33,33,33,.2) !important;
      }

   .sidebar-li{
        border-bottom: 2px #eee solid;
        }
        #main-menu-wrapper li a:hover {
        background-color: #d5f3f2;
        border-left: 4px solid #4d9cf8;
        color: #00b3ac;
        }
         #main-menu-wrapper li a:hover {
              color: #150aec;
               font-weight: 800;
        }
        .text-primary:hover {
                color: #150aec;
               font-weight: 800;
        }
        .table>tbody>tr>td:hover {
       font-weight: 600;
      }
      #main-menu-wrapper li a {
        color: #150aec;
      }
      .text-primary {
color: #150aec;
}
.active .modal-content {
border-radius: 16px;
box-shadow: 2px 14px 16px -3px #5a5a5a;
}
.active .modal-body {
border-radius: 15px;
padding: 20px;
}
.active .modal-body h4 {
padding-top: 20px;
font-weight: 700;
color: #150aec;
}
.active .modal-body span {
padding-top: 10px;
}
.table>tbody>tr>td:hover {
font-weight: 600;
}
.delete .modal-content {
border-radius: 15px;
}
.delete .modal-title  {
color: #150aec;
font-size: 18px;
font-weight: 700;
}
.delete .modal-body p {
color: red;
font-weight: 700;
}
.delete .modal-body {
padding: 20px;
}
.text-primary .color-black {
color: #848484;
font-size: 15px;
}
.light-grey {
color: #a9a9a9;
padding-right: 10px;
font-weight: 600;
}
.ml-15 {
margin-left: 15px;
}
.pt-15 {
padding-top: 15px !important;
}
.p-0 {
padding: 0px;
}
.border-right {
border-right: 1px solid #ccc;
}
section header {
  border:none !important;
  border-bottom: 1px solid #ccc !important;
      margin-bottom: 10px;
background: #efefef !important;
min-height: 0px;
padding-left: 5px !important;
}
.insurance {
  position: relative;
left: 13px;
top: 18px;
}
.pos-icon {
  display: flex;
position: absolute;
right: 15px;
bottom: 13px;
}
.w-100 {
width: 100%;
}
.imp-red {
color: red !important;
}
.page-sidebar.chat_shift .wraplist li .title, .page-sidebar.collapseit .wraplist li .title, .page-sidebar.chat_shift .wraplist li .arrow, .page-sidebar.collapseit .wraplist li .arrow {
display: block;
position: absolute;
left: 0;
top: 38px;
font-size: 13px;
margin-left: 5px;
max-width: 139px;
word-break: break-word;
width: 70px;
white-space: break-spaces;
line-height: 17px;
}
.page-sidebar.chat_shift .wraplist li:hover .title, .page-sidebar.collapseit .wraplist li:hover .title {
top: 30px;
}
.page-sidebar.chat_shift #main-menu-wrapper .wraplist li a, .page-sidebar.collapseit #main-menu-wrapper .wraplist li a {
padding-left: 0px;
margin-bottom: 33px;
}
.page-sidebar.chat_shift .wraplist li:hover .title, .page-sidebar.collapseit .wraplist li:hover .title {
top: 40px;
}
.page-sidebar {
width: 180px;
}
#main-content {
  margin-left: 165px;
}
.insurance-table thead {
color: #000;
background: #ccc;
font-size: 12px;
}
.m-0 {
margin: 0px;
}
.bg-light-grey {
  background: #eee;
padding: 10px;
}
.border-bottom {
border-bottom: 1px solid #ccc;

}
.modal-content h4 {
    color: #150aec;
    font-weight: 700;
}
.modal-content {
    border-radius: 15px !important;
}
.modal-body {
    padding: 15px;
    height: 350px;
    max-height: 500px;
    overflow-y: auto;
    overflow-x: hidden;
}
.down-arrow {
  text-align: right;
    position: relative;
    right: 10px;
    top: 13px;
    font-size: 18px;
    color: #2d2d2d;
}
.intl-tel-input {
    display: block !important;
}

/* Crop image box css */
.box-2 {
    padding: 0em;
    width: calc(100% - 1em);
}

.options label,
.options input{
    width:4em;
    padding:0.5em 1em;
    display: none;
}
.btn{
    background:white;
    color:black;
    border:1px solid black;
    padding: 0.5em 1em;
    text-decoration:none;
    margin:0.8em 0.3em;
    display:inline-block;
    cursor:pointer;
}

.hide {
    display: none;
}
.cropped{
 width:150px!important;
}
/* Crop image box */
</style>
<div class="clearfix"></div>
<div class="col-lg-12 p-0">
  <div class="col-lg-12 col-xs-12 p-0">
    <section>
      <header class="panel_header">
        <?php if(isset($admin->user_id)){
        ?>
        <h2 class="title pull-left" id="form-title">Edit Admin Account</h2>
        <?php }else{ ?>
        <h2 class="title pull-left" id="form-title">Create Admin Account</h2>
        <?php }
        ?>
      </header>
      <?php echo form_open_multipart('dashboard_super/admin/admin/create','class="form-inner"') ?>
      <?php echo form_hidden('user_id',$admin->user_id); ?>
      <div class="row ml-10 mr-10">
        <div class="col-12 col-md-3 form-group">
          <label>First Name<span class="imp-red">*</span></label>
          <input type="text" required name="firstname" class="form-control text-field" value="<?php echo $admin->firstname ?>" id="firstname" placeholder="First Name">
        </div>
        <div class="col-12 col-md-3 form-group">
          <label>Last Name<span class="imp-red">*</span></label>
          <input type="text" required name="lastname" class="form-control text-field" id="lastname" value="<?php echo $admin->lastname ?>" placeholder="Last Name">
        </div>
        <div class="col-12 col-md-3 form-group">
          <label>Hospital Name<span class="imp-red">*</span></label>
          <input type="text" required name="hospitalName" class="form-control text-field" id="hospitalName" value="<?php echo $admin->hospitalname ?>" placeholder="Hospital Name">
        </div>
      </div>

      <div class="row ml-10 mr-10">
          <div class="col-12 col-md-3 form-group">
            <label>Email<span class="imp-red">*</span></label>
            <input type="text" name="email" required class="form-control text-field" value="<?php echo $admin->email ?>" id="email" placeholder="Email Address">
          </div>
          <div class="col-12 col-md-3 form-group">
            <label>Office Phone</label>
            <input type="number" maxlength="16" onkeydown="return event.keyCode !== 69" name="phone" class="form-control text-field"  value="<?php echo $admin->phone ?>" id="phone" placeholder="Phone">
          </div>
          <div class="col-12 col-md-3 form-group">
            <label>Mobile<span class="imp-red">*</span></label>
            <input type="number" onkeydown="return event.keyCode !== 69" name="mobile" class="form-control text-field" value="<?php echo $admin->mobile ?>" maxlength="16" id="mobile" placeholder="mobile">
          </div>
        </div>
        <div class="row ml-10 mr-10">
            <div class="col-12 col-md-3 form-group">
              <label>Address</label>
              <input type="text" name="address" value="<?php echo $admin->address ?>" class="form-control text-field" id="address" placeholder="Address">
            </div>
            <div class="col-12 col-md-3 form-group">
              <label>City</label>
              <input type="text" name="city" value="<?php echo $admin->city ?>" class="form-control text-field" id="city" placeholder="City">
            </div>
            <div class="col-12 col-md-3 form-group">
              <label>State</label>
              <input type="text" name="state" value="<?php echo $admin->state ?>" class="form-control text-field" id="state" placeholder="State">
            </div>
          </div>

          <div class="row ml-10 mr-10">
            <div class="col-12 col-md-3 form-group">
              <label>Country</label>
              <input type="text" name="country" value="<?php echo $admin->country ?>" class="form-control text-field" id="country" placeholder="Country">
            </div>
            <div class="col-12 col-md-3 form-group">
              <label>Zip Code</label>
              <input type="text" name="zipcode" value="<?php echo $admin->zipcode ?>" class="form-control text-field" id="zipcode" placeholder="Zip Code">
            </div>
          <div class="col-12 col-md-3 form-group">
            <label>Date Of Birth<span class="imp-red">*</span></label>
            <input type="text" name="date_of_birth" value="<?php echo $admin->date_of_birth ?>" max="<?php echo date('Y-m-d'); ?>" class="form-control text-field" id="dob">
          </div>
      </div>
      <div class="row ml-10 mr-10 mb-10">
        <div class="col-12 col-md-3 form-group">
          <label>Sex<span class="imp-red">*</span></label>
          <select class="form-control" name="sex" id="sex">
            <option value="">Select</option>
            <option value="Male" <?php echo ($admin->sex=='Male')?'Selected':''; ?> >Male</option>
            <option value="Female" <?php echo ($admin->sex=='Female')?'Selected':''; ?> >Female</option>
            <option value="Other" <?php echo ($admin->sex=='Other')?'Selected':''; ?> >Other</option>
          </select>
        </div>
        <div class="col-12 col-md-3 form-group">
          <label>Hospital Logo<span class="imp-red"></span></label>
          <input type="file" class="form-control" name="picture" id="picture">
          <!--
          onchange="document.getElementById('picture').src = window.URL.createObjectURL(this.files[0])" -->
          <input type="hidden" name="croppicture" id="croppicture">
        </div>
        <?php if($admin->picture){?>
        <div class="col-12 col-md-2 form-group">
            <img height="50px" style="width: auto;" src="<?php echo base_url().$admin->picture;?>" alt="" />

          <a href="<?php echo base_url();?>dashboard_super/admin/admin/delete_profile_pic/<?php echo $admin->user_id;?>" class="btn btn-xs btn-danger" onclick="return confirm('Are You Sure ?') "> <i class="fa fa-trash"></i></a>
        </div>
         <?php }?>
      </div>
      <!-- image Crope -->
      <div class="row form-row">
        <div class="col-xs-12">
          <div class="form-group">
          <div class="col-xs-4">
              <div class="box-2">
                <div class="result"></div>
              </div>
              <div class="box1">
                <div class="options hide">
                  <label> Width</label>
                  <input type="number" class="img-w" value="300" min="100" max="6 00" />
                </div>
                <button class="btn save hide">Crop</button>
              </div>
            </div>
            <div class="col-xs-2">
              <div class="box-2 img-result hide">
                <img class="cropped" src="" alt="">
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- image Crope -->

      <div class="row ml-10 mr-10 mb-10">
        <div class="col-12 col-md-6 form-group">
          <label>Features<span class="imp-red">*</span></label>
          <?php $feature = explode(',',$admin->features);   ?>
          <div class="row" style="margin-left:0px;">
            <div class="col-12 col-md-6">
              <div class="form-check">
                <input class="form-check-input" id="fearureCheck1" type="checkbox" name="feature[]" value="schedule" <?php echo (in_array('schedule',$feature)?'checked':'') ?> >
                <label class="form-check-label ml-10" for="fearureCheck1">
                  Schedule
                </label>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-check">
                <input class="form-check-input" id="fearureCheck2" type="checkbox" name="feature[]" <?php echo (in_array('online_profile',$feature)?'checked':'') ?> value="online_profile">
                <label class="form-check-label ml-10" for="fearureCheck2">
                  Online Profiles
                </label>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-check">
                <input class="form-check-input" id="fearureCheck3" type="checkbox" name="feature[]" value="message" <?php echo (in_array('message',$feature)?'checked':'') ?> >
                <label class="form-check-label ml-10" for="fearureCheck3">
                  Message
                </label>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-check">
                <input class="form-check-input" id="fearureCheck4" type="checkbox" name="feature[]" value="reporting" <?php echo (in_array('reporting',$feature)?'checked':'') ?> >
                <label class="form-check-label ml-10" for="fearureCheck4">
                  Reporting
                </label>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-check">
                <input class="form-check-input" id="fearureCheck5" type="checkbox" name="feature[]" value="health_record" <?php echo (in_array('health_record',$feature)?'checked':'') ?> >
                <label class="form-check-label ml-10" for="fearureCheck5">
                  Health Record
                </label>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-check">
                <input class="form-check-input" id="fearureCheck6" type="checkbox" name="feature[]" value="insurance_eligiblility" <?php echo (in_array('insurance_eligiblility',$feature)?'checked':'') ?> >
                <label class="form-check-label ml-10" for="fearureCheck6">
                  Insurance  Eligiblility
                </label>
              </div>
            </div>
            <?php /*?>
            <div class="col-12 col-md-6">
              <div class="form-check">
                <input class="form-check-input" id="fearureCheck7" type="checkbox" name="feature[]" value="e_priscribing" <?php echo (in_array('e_priscribing',$feature)?'checked':'') ?> >
                <label class="form-check-label ml-10" for="fearureCheck7">
                  E-priscribing
                </label>
              </div>
            </div>
            <?php */?>

            <div class="col-12 col-md-6">
              <div class="form-check">
                <input class="form-check-input" id="fearureCheck8" type="checkbox" name="feature[]" value="telemedicine" <?php echo (in_array('telemedicine',$feature)?'checked':'') ?> >
                <label class="form-check-label ml-10" for="fearureCheck8">
                  Telemedicine
                </label>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-check">
                <input class="form-check-input" id="fearureCheck10" type="checkbox" name="feature[]" value="billing" <?php echo (in_array('billing',$feature)?'checked':'') ?> >
                <label class="form-check-label ml-10" for="fearureCheck10">
                  Billing
                </label>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-check">
                <input class="form-check-input" id="fearureCheck11" type="checkbox" name="feature[]" value="growth_charts" <?php echo (in_array('growth_charts',$feature)?'checked':'') ?> >
                <label class="form-check-label ml-10" for="fearureCheck11">
                  Growth Charts
                </label>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="text-center mb-10 mt-10">
      <button type="submit" class="btn btn-default" >Save</button>
      <a href ="<?php echo base_url()."dashboard_super/admin/admin/"; ?>" ><button type="button" class="btn btn-default">Close</button></a>
    </div>
  </form>
</section>
</div>
</div>
<script>
    function goBack() {
      window.history.back();
    }
</script>
<div class="clearfix"></div>
<div id="insurance" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Insurance</h4>
      </div>
      <div class="modal-body">
         <section>
                       <div class="row ml-10 mr-10">
         <div class="col-12 ml-10">
                      <div class="form-check">
       <input class="form-check-input" type="radio" name="status" id="exampleRadios1" value="Active" checked>
        <label class="form-check-label" for="exampleRadios1">
        Active
       </label>

       <input class="form-check-input ml-10" type="radio" name="status" id="exampleRadios2" value="Inactive" checked>
        <label class="form-check-label" for="exampleRadios2">
        Inactive
       </label>
      </div>
    </div>
  </div>
  <div class="row ml-10 mr-10">
    <div class="col-12 col-md-6 form-group">
                            <label>PAYER NAME<span class="imp-red">*</span></label>
                            <input type="text" name="payer_name" class="form-control text-field"  placeholder="Search payer list">
                            <i class="fa fa-search pos-icon"></i>
                          </div>
                           <div class="col-12 col-md-6 form-group">
                            <label>PLAN NAME AND TYPE<span class="imp-red">*</span></label>
                            <input type="text" name="plan_name_and_type" class="form-control text-field" placeholder="Search">
                            <i class="fa fa-search pos-icon"></i>
                          </div>
                          <div class="col-12">
                            <span>ELIGIBILITY NOT SUPPORTED</span>
                            <i class="fa fa-exclamation-circle color-orange"></i>
                          </div>

  </div>
  <div class="row  ml-10 mr-10">
  <div class="col-12 col-md-5 form-group">
                        <label>ORDER OF BENEFITS<span class="imp-red">*</span></label>
                            <select class="form-control" name="order_of_benefits" id="order_of_benefits" placeholder="order of benefits" >
                                                <option value="Primary">Primary</option>
                                                <option value="Secondary">Secondary</option>
                                                <option value="Tertiary">Tertiary</option>
                                                <option value="Unknown">Unknown/None</option>
                                 </select>
                          </div>
                            <div class="col-12 col-md-7 mt-30">
                              <div class="form-check">
<input class="form-check-input" name="workers_compensation" type="checkbox" value="WORKERS COMPENSATION" id="defaultCheck3">
<label class="form-check-label ml-10" for="defaultCheck3">
WORKER'S COMPENSATION</label>
</div>
                          </div>
  </div>
  <div class="row  ml-10 mr-10">
 <div class="col-12 col-md-6 form-group">
                            <label>INSURANCE ID<span class="imp-red">*</span></label>
                            <input type="text" name="insurance_id" class="form-control text-field" id="insuranceid" placeholder="Insurance ID">
                          </div>
                          <div class="col-12 col-md-6 form-group">
                            <label>GROUP ID</label>
                            <input type="text" name="group_id" class="form-control text-field" id="groupid" placeholder="Group ID">
                          </div>
  </div>
  <div class="row  ml-10 mr-10">
     <div class="col-12 col-md-4 form-group">
                            <label>EFFECTIVE FROM<span class="imp-red">*</span></label>
                            <input type="text" name="effective_from" class="form-control text-field" id="date" placeholder="Group ID">
                          </div>
                           <div class="col-12 col-md-4 form-group">
                            <label>EFFECTIVE TO</label>
                            <input type="text" name="effective_to" class="form-control text-field" id="date" placeholder="Group ID">
                          </div>
                          <div class="col-12 col-md-4 form-group">
                        <label>RELATION TO INSURED</label>
                                <select class="form-control" name="relation_to_insured" id="insured">
                                                <option value="Child">Child</option>
                                                <option value="Other">Other</option>
                                                <option value="Self">Self</option>
                                                <option value="Spouse">Spouse</option>
                                 </select>
                          </div>
  </div>
  <div class="row  ml-10 mr-10">
     <div class="col-12 col-md-4 form-group">
                        <label>COPAY TYPE</label>
                            <select class="form-control" name="copay_type" id="copay_type">
                                                <option value="Fixed">Fixed</option>
                                                <option value="Percent">Percent</option>
                                 </select>
                          </div>


    <div class="col-12 col-md-4 form-group">
                            <label>COPAY AMOUNT<span class="imp-red">*</span></label>
                            <input type="text" name="copay_amount" class="form-control text-field" id="amount" placeholder="$">
                          </div>
                           <div class="col-12 col-md-4 form-group">
                            <label>CLAIM NUMBER</label>
                            <input type="text" name="claim_number" class="form-control text-field" id="cliam" placeholder="Claim number">
                          </div>
                        </div>

                        <div class="row ml-10 mr-10">
                          <div class="col-12 form-group">
                            <label>NOTES</label>
                            <textarea class="w-100" id="notes" name="notes" rows="5" placeholder="Notes"></textarea>
                          </div>
                        </div>
                        <div class="row ml-10 mb-10">
                          <div class="col-12 col-md-7 form-group">
                            <label>ELIGIBILITY</label>
                            <select class="form-control" name="eligiblility" id="eligiblility">
                              <option value="">Select</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                          </div>
                        </div>
                      <header class="panel_header" data-toggle="collapse" data-target="#employer">
                            <h2 class="title pull-left" id="form-title">Employer information</h2>
                            <div class="down-arrow"><i class="fa fa-caret-down"></i></div>
                            <!-- <div class="actions panel_actions pull-right">
                                <a class="box_setting fa fa-cog" data-toggle="modal" href="#section-settings"></a>
                            </div> -->
                        </header>
                        <div  id="employer" class="collapse">
                        <div class="row ml-10 mr-10">
                          <div class="col-12 col-md-6 form-group">
                            <label>EMPLOYER NAME</label>
                            <input type="text" name="employer_name" class="form-control text-field" id="employname" placeholder="Employer Name">
                          </div>
                        </div>
                        <div class="row ml-10 mr-10">
                          <div class="col-12 col-md-6 form-group">
                            <label>EMPLOYER ADDRESS</label>
                            <input type="text" name="employer_address1" class="form-control text-field" id="add1" placeholder="Address 1">
                          </div>
                          <div class="col-12 col-md-6 form-group">
                            <label></label>
                            <input type="text" name="employer_address2" class="form-control text-field" id="add2" placeholder="Address 2">
                          </div>
                        </div>
                        <div class="row ml-10 mr-10 mb-10">
                          <div class="col-12 col-md-6 form-group">
                            <input type="text" name="city" class="form-control text-field" id="city" placeholder="City">
                          </div>
                          <div class="col-12 col-md-4 form-group">
                            <input type="text" name="state" class="form-control text-field" id="state" placeholder="State">
                          </div>
                           <div class="col-12 col-md-2 form-group">
                            <input type="text" name="zip" class="form-control text-field" id="zip" placeholder="Zip">
                          </div>
                        </div>
                      </div>
                         <header class="panel_header" data-toggle="collapse" data-target="#subscriber">
                            <h2 class="title pull-left" id="form-title">Subscriber information</h2>
                            <div class="down-arrow"><i class="fa fa-caret-down"></i></div>
                         <!--    <div class="actions panel_actions pull-right">
                                <a class="box_setting fa fa-cog" data-toggle="modal" href="#section-settings"></a>
                            </div> -->
                        </header>
                         <div  id="subscriber" class="collapse">
                        <div class="row ml-10 mr-10">
                          <div class="col-12">
                            <a href="#">Copy from guarantor information</a>
                          </div>
                        </div>
                        <div class="row ml-10 mr-10">
                           <div class="col-12 col-md-4 form-group">
                            <label>SUBSCRIBER'S FIRST NAME</label>
                            <input type="text" name="subscribers_fname" class="form-control text-field" id="fname" placeholder="Subscriber First Name">
                          </div>
                          <div class="col-12 col-md-4 form-group">
                            <label></label>
                            <input type="text" name="subscribers_mname" class="form-control text-field" id="mname" placeholder="Middle Initial">
                          </div>
                           <div class="col-12 col-md-4 form-group">
                            <label>SUBSCRIBER'S LAST NAME</label>
                            <input type="text" name="subscribers_lname" class="form-control text-field" id="lname" placeholder="Subscriber Last Name">
                          </div>
                        </div>
                        <div class="row ml-10 mr-10">
                          <div class="col-12 col-md-6 form-group">
                            <label>SUBSCRIBER DATE OF BIRTH</label>
                            <input type="text" name="subscribers_dob" class="form-control text-field" id="date">
                          </div>
                               <div class="col-12 col-md-6 form-group">
                        <label>SEX</label>
                            <select class="form-control" name="subscribers_sex" id="subscribers_sex">
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Other">Other</option>
                                 </select>
                          </div>
      <div class="col-12 col-md-6 form-group">
                            <label>SUBSCRIBER SOCIAL SECURITY NUMBER</label>
                            <input type="number" name="subscribers_ssn" class="form-control text-field" id="subscribers_ssn">
                          </div>

                        </div>
                        <div class="row ml-10 mr-10">
                           <div class="col-12 col-md-6 form-group">
                            <label>SUBSCRIBER ADDRESS</label>
                            <input type="text" name="subscribers_address1" class="form-control text-field"  placeholder="Address 1">
                          </div>
                          <div class="col-12 col-md-6 form-group">
                            <label></label>
                            <input type="text" name="subscribers_address2" class="form-control text-field"  placeholder="Address 2">
                          </div>
                        </div>
                        <div class="row ml-10 mr-10 mb-10">
                          <div class="col-12 col-md-6 form-group">
                            <input type="text" name="subscribers_city" class="form-control text-field"  placeholder="City">
                          </div>
                           <div class="col-12 col-md-4 form-group">
                            <input type="text" name="subscribers_state" class="form-control text-field"  placeholder="State">
                          </div>
                           <div class="col-12 col-md-2 form-group">
                            <input type="text" name="subscribers_zip" class="form-control text-field"  placeholder="Zip">
                          </div>
                        </div>
                        <div class="row ml-10 mr-10">
                          <div class="col-12 col-md-6 form-group">
                            <label>SUBSCRIBER PRIMARY PHONE NUMBER</label>
                            <input type="text" name="subscribers_primary_number" class="form-control text-field">
                          </div>
                          <div class="col-12 col-md-4 form-group">
                            <label></label>
                            <input type="text" name="subscribers_primary_ext" class="form-control text-field"  placeholder="Ext">
                          </div>
                        </div>
                         <div class="row ml-10 mr-10">
                          <div class="col-12 col-md-6 form-group">
                            <label>SECONDARY PHONE NUMBER</label>
                            <input type="text" name="subscribers_secondary_number" class="form-control text-field" id="phonenum">
                          </div>
                          <div class="col-12 col-md-4 form-group">
                            <label></label>
                            <input type="text" name="subscribers_secondary_ext" class="form-control text-field" placeholder="Ext">
                          </div>
                        </div>
                      </div>
                    </section>
      </div>
      <div class="modal-footer">
          <button type="button" onclick="add_insurance()" class="btn btn-default" >Save</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script>
$(document).ready(function() {
  if($('#mobilecheck').prop("checked") == true){
    $("#mobile").prop("readonly", true);
  }
});
$('#mobilecheck').change(function() {
        if(this.checked) {
            //var returnVal = confirm("Are you sure?");
            //$(this).prop("checked", returnVal);
            $("#mobile").prop("readonly", true);
        }else{
          $("#mobile").prop("readonly", false);
        }
      //  $('#textbox1').val(this.checked);
    });
 $('input[type=radio][name=ethnicity_race]').change(function() {
    if (this.value == 'choose') {
        $("#showss").css('display','block');
    }
    else  {
        $("#showss").css('display','none');
    }
});
    function popupopen(){
        var BASE_URL = '<?php echo base_url(); ?>';
        if($("#fname").val()==''){
          $("#fname").focus();
        }else{
          $("#insurance").modal();
        }
    }
    function add_insurance(){
      alert('test');
var BASE_URL = '<?php echo base_url(); ?>';
  var status =  $("input[name=status]:checked").val();
  var payer_name =  $("input[name=payer_name]").val();
  var plan_name_and_type =  $("input[name=plan_name_and_type]").val();
  var order_of_benefits =  $("#order_of_benefits :selected").val();
  var workers_compensation =  $("input[name=workers_compensation]").val();
  var insurance_id =  $("input[name=insurance_id]").val();
  var group_id =  $("input[name=group_id]").val();
  var effective_from =  $("input[name=effective_from]").val();
  var effective_to =  $("input[name=effective_to]").val();
  var relation_to_insured =  $("input[name=relation_to_insured]").val();
  var copay_type =  $("#copay_type :selected").val();
  var copay_amount =  $("input[name=copay_amount]").val();
  var claim_number =  $("input[name=claim_number]").val();
  var notes =  $("textarea#notes").val();
  var employer_name =  $("input[name=employer_name]").val();
  var employer_address1 =  $("input[name=employer_address1]").val();
  var employer_address2 =  $("input[name=employer_address2]").val();
  var city =  $("input[name=city]").val();
  var state =  $("input[name=state]").val();
  var zip =  $("input[name=zip]").val();
  var subscribers_fname =  $("input[name=subscribers_fname]").val();
  var subscribers_mname =  $("input[name=subscribers_mname]").val();
  var subscribers_lname =  $("input[name=subscribers_lname]").val();
  var subscribers_dob =  $("input[name=subscribers_dob]").val();
  var subscribers_sex =  $("#subscribers_sex :selected").val();
  var subscribers_ssn =  $("input[name=subscribers_ssn]").val();
  var subscribers_address1 =  $("input[name=subscribers_address1]").val();
  var subscribers_address2 =  $("input[name=subscribers_address2]").val();
  var subscribers_city =  $("input[name=subscribers_city]").val();
  var subscribers_state =  $("input[name=subscribers_state]").val();
  var subscribers_zip =  $("input[name=subscribers_zip]").val();
  var subscribers_primary_number =  $("input[name=subscribers_primary_number]").val();
  var subscribers_primary_ext =  $("input[name=subscribers_primary_ext]").val();
  var subscribers_secondary_number =  $("input[name=subscribers_secondary_number]").val();
  var subscribers_secondary_ext =  $("input[name=subscribers_secondary_ext]").val();
  var eligiblility =  $("#eligiblility :selected").val();
///exit;


if(payer_name==''){
  $("input[name=payer_name]").focus();
}else if(plan_name_and_type=='')
  {
    $("input[name=plan_name_and_type]").focus();
  }else if(order_of_benefits==''){
    $("input[name=order_of_benefits]").focus();
  }else if(insurance_id==''){
    $("input[name=insurance_id]").focus();
  }else if(effective_from==''){
    $("input[name=effective_from]").focus();
  }else if(eligiblility==''){
    $("#eligiblility").focus();
  }else{
      $.ajax({
//dashboard_super/schedule/schedule/get_appoitmentlistallforcalenderblock

          url: BASE_URL+"dashboard_super/insurance/insurance/creates",
          data: 'status='+status+'&payer_name='+payer_name+'&plan_name_and_type='+plan_name_and_type+'&order_of_benefits='+order_of_benefits+'&workers_compensation='+workers_compensation+'&insurance_id='+insurance_id+'&group_id='+group_id+
          '&effective_from='+effective_from+'&effective_to='+effective_to+'&relation_to_insured='+relation_to_insured+'&copay_type='+copay_type+'&copay_amount='+copay_amount+'&claim_number='+claim_number+'&notes='+notes+'&employer_name='+employer_name+'&employer_address1='+employer_address1+'&employer_address2='+employer_address2+'&city='+city+'&state='+state+'&zip='+zip+'&subscribers_fname='+subscribers_fname+'&subscribers_mname='+subscribers_mname+'&subscribers_lname='+subscribers_lname+
          '&subscribers_dob='+subscribers_dob+'&subscribers_sex='+subscribers_sex+'&subscribers_ssn='+subscribers_ssn+'&subscribers_address1='+subscribers_address1+'&subscribers_address2='+subscribers_address2+'&subscribers_city='+subscribers_city+'&subscribers_state='+subscribers_state+'&subscribers_zip='+subscribers_zip+'&subscribers_primary_number='+subscribers_primary_number+'&subscribers_primary_ext='+subscribers_primary_ext+'&subscribers_secondary_number='+subscribers_secondary_number+
          '&subscribers_secondary_ext='+subscribers_secondary_ext+'&eligiblility='+eligiblility,
          success: function(msg){
//alert(msg);
              var myObj = JSON.parse(msg);
//$("#add").empty();
//$(".table-fixed").empty();
$('#insurance').modal('toggle');
 $("#insurance_u_id").val(myObj.insurance_u_id);
  // alert(v);
  // alert(myObj.insurance_u_id);
              if(myObj!=''){
                  //alert(myObj);
                  //$(".table-fixed").empty();


                //  $.each(myObj, function(index,value) {
                  //  var c = myObj[index].insurance_u_id;
                  //  var v =   $("#insurance_u_id").val(c);

                      txt = '<tr><td>'+myObj.payer_name+'</td><td>'+myObj.plan_name_and_type+'</td><td>'+myObj.insurance_id+'</td><td>'+myObj.effective_from+'</td><td>'+myObj.effective_to+'</td><td>'+myObj.copay_type+'</td><td>'+myObj.eligiblility+'</td><td>'+myObj.status+'</td></tr>';
                      $("#in").append(txt);

                  //});
              }else{
                  $("#in").empty();

                  // $.each(myObj, function(index,value) {
                  //  alert(myObj[index].email);
                  //$(".item").empty();
                  txt= ' No data found ';
                  $("#in").append(txt);

                  // });
              }
          }
      });
    }
    }
    //$("#outhide").mouseout(function(){
    //  $("#suggesstion-box").hide();
    //});
</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/8.5.0/css/intlTelInput.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/8.5.0/js/intlTelInput.js"></script>
<script>
$("#mobile").intlTelInput({
autoHideDialCode: true,
autoPlaceholder: true,
separateDialCode: true,
nationalMode: true,
geoIpLookup: function (callback) {
    $.get("https://ipinfo.io", function () {}, "jsonp").always(function (resp) {
        var countryCode = (resp && resp.country) ? resp.country : "";
        //
      //  alert(resp);
        //console.log(resp);
        callback(countryCode);
    });
},
initialCountry: "auto",
});

// get the country data from the plugin
var countryData = $.fn.intlTelInput.getCountryData(),
telInput = $("#mobile"),
addressDropdown = $("#listcountry");
//alert(countryData);
// init plugin
telInput.intlTelInput({
utilsScript: "../wp-content/themes/saba/build/js/utils.js" // just for formatting/placeholders etc
});

// populate the country dropdown
$.each(countryData, function(i, country) {
  //console.log(country);

addressDropdown.append($("<option></option>").attr("value", country.iso2).text(country.name));
});

// listen to the telephone input for changes
telInput.on("countrychange", function() {
  var a = telInput.intlTelInput("getSelectedCountryData").dialCode;
  //alert(a);
  $("#mobile_prefix").val(a);
var countryCode = telInput.intlTelInput("getSelectedCountryData").iso2;
addressDropdown.val(countryCode);
});

// trigger a fake "change" event now, to trigger an initial sync
telInput.trigger("countrychange");

// listen to the address dropdown for changes
addressDropdown.change(function() {

var countryCode = $(this).val();
//alert(countryCode);
telInput.intlTelInput("setCountry", countryCode);
});
// Image crop
 // vars
let result = document.querySelector('.result'),
img_result = document.querySelector('.img-result'),
img_w = document.querySelector('.img-w'),
img_h = document.querySelector('.img-h'),
options = document.querySelector('.options'),
save = document.querySelector('.save'),
cropped = document.querySelector('.cropped'),
upload = document.querySelector('#picture'),
cropper = '';

// on change show image with crop options
upload.addEventListener('change', (e) => {
  if (e.target.files.length) {
    // start file reader
    const reader = new FileReader();
    reader.onload = (e)=> {
      if(e.target.result){
        // create new image
        let img = document.createElement('img');
        img.id = 'image';
        img.src = e.target.result
        // clean result before
        result.innerHTML = '';
        // append new image
        result.appendChild(img);
        // show save btn and options
        save.classList.remove('hide');
        options.classList.remove('hide');
        // init cropper
        cropper = new Cropper(img);
      }
    };
    reader.readAsDataURL(e.target.files[0]);

  }
});

// save on click
save.addEventListener('click',(e)=>{
  e.preventDefault();
  // get result to data uri
  let imgSrc = cropper.getCroppedCanvas({
    width: img_w.value // input value
  }).toDataURL();
    $('#croppicture').val(imgSrc);
  // remove hide class of img
    cropped.classList.remove('hide');
  img_result.classList.remove('hide');
  // show image cropped
  cropped.src = imgSrc;
});
// Image crop
    </script>
