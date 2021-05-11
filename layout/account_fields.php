

<input type="hidden" name="success_url" value=""> <input
									type="hidden" name="error_url" value="">
								<div class="field field-name-firstname required">
									<label class="label" for="fname"> <span>First Name</span>
									</label>
									<input type="hidden" name="customers_id" value="<?php if(isset($_SESSION['customers_id'])){ echo $_SESSION['customers_id']; } else {  } ?>">
									<div class="control">
										<input type="text" id="fname" name="fname" value="<?php echo @$fname; ?>"
											title="First Name" class="single-input required-entry"
											data-validate="{required:true}" autocomplete="off"
											aria-required="true" required="required">
									</div>
								</div>
								<div class="field field-name-lastname required">
									<label class="label" for="lname"> <span>Last Name</span>
									</label>
									<div class="control">
										<input type="text" id="lname" name="lname" value="<?php echo @$lname; ?>"
											title="Last Name" class="single-input required-entry"
											data-validate="{required:true}" autocomplete="off"
											aria-required="true">
									</div>
								</div>
								<div class="field">
									<label for="company" class="label"><span>Company Name(Optional)</span></label>
									<div class="control">
										<input type="text" name="company" id="company" value="<?php echo @$company; ?>"
											title="Company" class="single-input" maxlength="30"
											aria-required="true">
									</div>
								</div>
								<div class="required default-select">
									<label for="country" class="label"><span>Country</span></label>
									
											<select name="country" id="country" class="select2" style="width: 100%">
            	<?php
    $countries = DB::query("SELECT  c.`id`, c.`name` FROM countries c ORDER BY c.`name`");
    foreach ($countries as $contri) {
        echo '<option value="' . $contri['name'] . '"';
        
        if(isset($country)){
            if ($contri['name'] == $country)
                echo ' SELECTED';
        } else {
            if ($contri['id'] == 231)
                echo ' SELECTED';
        }
        
            echo '>' . $contri['name'] . '</option>';
    }
    ?>
                </select>
										
								</div>
								<div class="field field-name-firstname required">
								<br>
									<label class="label" for="address1"> <span>Street Address</span>
									</label>
									<div class="control mt-10">
										<textarea id="address" name="address"
											title="Address" class="single-textarea required-entry"
											required="required"
											placeholder="House number and street name"><?php echo @$address ?></textarea>
									</div>
								</div>
								
								<div class="field required">
									<label for="city" class="label"><span>Town / City</span></label>
									<div class="control">
										<input type="text" name="city" id="city" value="<?php echo @$city ?>"
											title="City Town" class="single-input" maxlength="25"
											aria-required="true" >
									</div>
								</div>
								<div class="required">
									<label for="state" class="label"><span>State</span></label>
								
										
											<select name="state" id="state" class="select2" style="width: 100%">
            	<?php
            	if(isset($country)){
            	    $states = DB::query("SELECT st.`name`, st.`id` FROM states st, countries c WHERE c.`id`=st.`country_id` AND c.`name` LIKE '".$country."'");
            	} else {
            	    $states = DB::query("SELECT `name` FROM states s WHERE s.`country_id` = '231'");
            	}
   
    foreach ($states as $st) {
        echo '<option value="' . $st['name'] . '"';
        if ($contri['name'] == @$state)
            echo ' SELECTED';
        echo '>' . $st['name'] . '</option>';
    }
    ?>
                </select>
										
								</div>
								<div class="field required">
									<label for="zip" class="label"><span>Zip</span></label>
									<div class="control">
										<input type="tel" name="zip" id="zip" autocomplete="off"
											value="<?php echo @$zip; ?>" title="Zip Code" class="single-input" maxlength="10"
											aria-required="true">
									</div>
								</div>
								<div class="field required">
									<label for="contact" class="label"><span>Phone</span></label>
									<div class="control">
										<input type="tel" name="contact" id="contact"
											autocomplete="off" value="<?php echo @$contact; ?>" title="Contact Number"
											class="single-input" maxlength="11" aria-required="true">
									</div>
								</div>
								<div class="field required">
									<label for="email" class="label"><span>Email</span></label>
									<div class="control">
										<input type="email" name="email" id="email"
											autocomplete="email" value="<?php echo @$email; ?>" title="Email"
											class="single-input"
											data-validate="{required:true, 'validate-email':true}"
											aria-required="true">
									</div>
								</div>