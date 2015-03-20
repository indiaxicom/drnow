<?php echo form_open('', array('method' => 'get')); ?>
	<div class="row mtop15">
		<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
			<tr valign="top">
				<td align="left" class="searchbox">
					<div class="floatleft">
						  <table cellspacing="0" cellpadding="4" border="0">
						  <tr valign="top">
							  <td valign="middle" align="left" >
								  <input type="text" class="input" name="keywords" placeholder="Enter Keywords" style="width:300px;">
							  </td>
							  <td valign="middle" align="left" >
								  <select class="select" name="search_by" style="width:300px;">
								  <option value="name">By Name</option>
								  <option value="email">By Email</option>
								</select></td>
							  <td valign="middle" align="left"><div class="black_btn2"><span class="upper"><input type="submit" value="Search" name=""></span></div></td>
							</tr>
						</table>
					</div>
					
					<div class="floatright top5">
						<a href="<?php echo base_url().'admin/' . $current_module . '/add' ?>" class="black_btn">
							<span>Add New <?php echo $this->user_text; ?></span>
						</a>
						<a href="<?php echo base_url().'admin/' . $current_module ?>" class="black_btn mleft5">
							<span>Manage <?php echo $this->user_text; ?>s</span>
						</a>
					</div>
				</td>
			</tr>
		</table>
	</div>
</form>
<?php echo form_open(base_url() . '/admin/' . $this->current_module . '/change_status'); ?>
    <div class="row mtop30">
    	<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" class="listing">
			<tr>
				<th width="4%" align="center">Unique Id</th>
				<th width="10%" align="left">First Name</th>
				<th width="10%" align="left">Last Name</th>
				<th width="20%" align="left">Email</th>
				<th width="10%" align="left">Created On</th>
				<th width="10%" align="left">Modified On</th>
				<th width="10%" align="center">Status</th>
				<th width="5%" align="center">Action</th>
				<th width="5%" align="center">Select</th>
			</tr>
			<?php if (!empty($get_all_doctors)) : ?>
				<?php foreach ($get_all_doctors as $val) : ?>
					<tr>
						<td align="center"><?php echo $val->app_user_id; ?></td>
						<td align="left"><span class="blue"><?php echo $val->first_name; ?></span></td>
						<td align="left"><span class="blue"><?php echo $val->last_name; ?></span></td>
						<td align="left"><span class="blue"><?php echo $val->email; ?></span></td>
						<td align="left"><span class="blue"><?php echo convert_from_sql_time('d M Y', $val->created); ?></span></td>
						<td align="left"><span class="blue"><?php echo convert_from_sql_time('d M Y', $val->modified); ?></span></td>
						<td align="center"><?php echo $this->config->item('userStatusArr')[$val->status]; ?></td>

						<td align="center">
							<a href="<?php echo base_url() . 'admin/' . $current_module .'/edit/' . $val->id; ?>"><img border="0" src="<?php echo base_url() ?>assets/images/edit_icon.gif"></a>
						</td>

						<td valign="middle" align="center">
							<input type="checkbox" class="check_ids" value="<?php echo $val->id ?>" name="update_ids[]">
						</td>
					</tr>
				<?php endforeach; ?>
				<?php else : ?>
				<td align="left" colspan="6"><?php echo 'No Record Found' ?></td>
			<?php endif; ?>
			<tr align="right">
				<td colspan="9" align="left" class="bordernone">
					<div class="floatleft mtop7">
						<div class="pagination">
							<?php echo $page_data ?>
						</div>
					</div>
					<div class="floatright">
						<div class="floatleft">
							<select name="status" required="required"  class="select">
								<option value="">Select Option</option>
								<option value="<?php echo DELETED ?>">Delete</option>
								<option value="<?php echo ACTIVE ?>">Activate</option>
								<option value="<?php echo IN_ACTIVE ?>">Deactivate</option>
							</select>
						</div>
						<div class="floatleft mleft10">
							<div class="black_btn2">
								<span class="upper">
									<input type="submit" value="SUBMIT" name="change_status">
								</span>
							</div>
						</div>
					</div>                 
				</td>
			</tr>
		</table>
    </div>
</form>