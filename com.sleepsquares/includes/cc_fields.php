<?php
include_once('st_and_co1.php');

 ?>

<tr><td align="right">Credit Card Type:</td><td align="left"><SELECT name="cc_type" id="cc_type">
<OPTION value=""></option>
<option value="mc"<?php if($cc_type == "mc") { echo " SELECTED"; } ?>>Mastercard</option>
<option value="vi"<?php if($cc_type == "vi") { echo " SELECTED"; } ?>>Visa</option>
<option value="am"<?php if($cc_type == "am") { echo " SELECTED"; } ?>>American Express</option>
<option value="di"<?php if($cc_type == "di") { echo " SELECTED"; } ?>>Discover</option>
</select>&nbsp; <img src="<?=$current_base?>images/store_ccs.gif" border="0" align="absmiddle"></td></tr>
<tr><td align="right" NOWRAP>Your First Name on Credit Card:</td><td align="left"><input type="text" name="cc_first_name" id="cc_first_name" size="30" maxlength="50"<?php if($cc_first_name) { echo " value=\"$cc_first_name\""; } ?>></td></tr>
<tr><td align="right" NOWRAP>Your Last Name on Credit Card:</td><td align="left"><input type="text" name="cc_last_name" id="cc_last_name" size="30" maxlength="50"<?php if($cc_last_name) { echo " value=\"$cc_last_name\""; } ?>></td></tr>
<tr><td align="right">Credit Card Number:</td><td align="left"><input type="text" name="cc_num" id="cc_num" size="16" maxlength="20"<?php if($cc_num) { echo " value=\"$cc_num\""; } ?>></td></tr>
<tr><td align="right">Security Code:</td><td align="left"><input type="text" name="cid" id="cid" size="4" optional="true" maxlength="4"<?php if($cid) { echo " value=\"$cid\""; } ?>> &nbsp; 
<a href="javascript:void(0)" class="cid_link smaller">What is this?</a>
<div class="no_display absolute cid_pop">
    <img src="/images/close.gif" class="right hand" onClick="$('.cid_pop').fadeOut(300)" />
    The Security Code is a three (3) or four (4) digit number listed on the back of your credit card immediately following your card number. (On American Express cards, the security code may be on the front.)<br>
    <br>
    This number prevents fraudulent charges to your credit card, such as someone stealing your credit card receipt and using that information to make a purchase.<br>
    <br>
    Note: Some older cards may not have a Security Code. In these cases, simply leave the Security Code field blank.
</div></td></tr>
<tr><td align="right">Expiration Date:</td><td align="left"><SELECT name="cc_exp_m" id="cc_exp_m">
<option value=""></option>
<option value="01"<?php if($cc_exp_m == "01") { echo " SELECTED"; } ?> class="text_right">January - 01</option>
<option value="02"<?php if($cc_exp_m == "02") { echo " SELECTED"; } ?> class="text_right">February - 02</option>
<option value="03"<?php if($cc_exp_m == "03") { echo " SELECTED"; } ?> class="text_right">March - 03</option>
<option value="04"<?php if($cc_exp_m == "04") { echo " SELECTED"; } ?> class="text_right">April - 04</option>
<option value="05"<?php if($cc_exp_m == "05") { echo " SELECTED"; } ?> class="text_right">May - 05</option>
<option value="06"<?php if($cc_exp_m == "06") { echo " SELECTED"; } ?> class="text_right">June - 06</option>
<option value="07"<?php if($cc_exp_m == "07") { echo " SELECTED"; } ?> class="text_right">July - 07</option>
<option value="08"<?php if($cc_exp_m == "08") { echo " SELECTED"; } ?> class="text_right">August - 08</option>
<option value="09"<?php if($cc_exp_m == "09") { echo " SELECTED"; } ?> class="text_right">September - 09</option>
<option value="10"<?php if($cc_exp_m == "10") { echo " SELECTED"; } ?> class="text_right">October - 10</option>
<option value="11"<?php if($cc_exp_m == "11") { echo " SELECTED"; } ?> class="text_right">November - 11</option>
<option value="12"<?php if($cc_exp_m == "12") { echo " SELECTED"; } ?> class="text_right">December - 12</option>
</select> <SELECT name="cc_exp_y">
<option value=""></option>
<?php
	for ($x=date('Y'); $x<=date('Y')+10; $x++) {
		echo '<option value="'.$x.'"';
		if( $cc_exp_y == $x ) {
			echo ' selected';
		}
		echo '>'.$x.'</option>';
	}
?>
</select></td></tr>

<tr><td colspan="2">&nbsp;</td></tr>
<tr><td align="left" colspan="2"><b>Billing Address</b>&nbsp; &nbsp;This must be the billing address exactly as it appears on your billing statement for the credit card or money order entered above.</td></tr>
<tr><td align="left" colspan="2"><i>(Required = *)</i></td></tr>
<tr><td align="right">Billing Name *:</td><td align="left"><input type="text" name="bill_name" id="bill_name" size="30" maxlength="200" value="<?php echo stripslashes($bill_name); ?>"></td></tr>
<tr><td align="right">Address 1 *:</td><td align="left"><input type="text" name="bill_address1" id="bill_address1" size="30" maxlength="30" value="<?php echo stripslashes($bill_address1); ?>"></td></tr>
<tr><td align="right">Address 2:</td><td align="left"><input type="text" name="bill_address2" id="bill_address2" optional="true" size="30" maxlength="30" value="<?php echo stripslashes($bill_address2); ?>"></td></tr>
<tr><td align="right">City *:</td><td align="left"><input type="text" name="bill_city" id="bill_city" size="30" maxlength="40" value="<?php echo stripslashes($bill_city); ?>"></td></tr>
<tr><td align="right">State/Province (* if applicable):</td><td align="left"><select name="bill_state" id="bill_state">
<?php
state_build_active($bill_state);
?>
</select></td></tr>
<tr><td align="right">Zip/Postal Code (* if applicable):</td><td align="left"><input type="text" name="bill_zip" id="bill_zip" size="10" maxlength="10" value="<?php echo $bill_zip; ?>"></td></tr>
<tr><td align="right">Country *:</td><td align="left"><select name="bill_country" id="bill_country">
<?php
country_build_active($bill_country);
?>
</select></td></tr>
<tr><td align="right">Phone:</td><td align="left"><input type="text" name="bill_phone" id="bill_phone" optional="true" size="30" maxlength="30" value="<?php echo stripslashes($bill_phone); ?>"></td></tr>
<tr><td align="right">Email Address *:</td><td align="left"><input type="text" name="bill_email" id="bill_email" size="30" maxlength="200" value="<?php echo stripslashes($bill_email); ?>"></td></tr>