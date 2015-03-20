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
                              <td valign="middle" align="left"><div class="black_btn2"><span class="upper"><input type="submit" value="Search" name=""></span></div></td>
                            </tr>
                        </table>
                    </div>
                    <div class="floatright top5">
                        <a href="<?php echo base_url().'admin/' . $current_module . '/add' ?>" class="black_btn">
                            <span>Add New Shift</span>
                        </a>
                        <a href="<?php echo base_url().'admin/' . $current_module ?>" class="black_btn mleft5">
                            <span>Manage Shifts</span>
                        </a>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</form>
<?php echo form_open(base_url() . 'admin/' . $this->current_module . '/change_status'); ?>
    <div class="row mtop30">
        <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" class="listing">
            <tr>
                <th width="4%" align="center">S No.</th>
                <th width="4%" align="center">Unique Id</th>
                <th width="10%" align="left">Date</th>
                <th width="10%" align="left">Start Time</th>
                <th width="10%" align="left">End Time</th>
                <th width="20%" align="center">Doctor Assigned</th>
                <th width="10%" align="left">Created On</th>
                <th width="10%" align="left">Modified On</th>
                <th width="5%" align="center">Action</th>
                <th width="5%" align="center">Select</th>
            </tr>
            <?php if (!empty($get_all_records)) : ?>
                <?php $i = 1; ?>
                <?php foreach ($get_all_records as $val) : ?>
                    <tr>
                        <td align="center"><?php echo $i; ?></td>
                        <td align="center"><?php echo $val->app_id; ?></td>
                        <td align="left"><span class="blue"><?php echo convert_from_sql_time('d M Y', $val->start_time); ?></span></td>
                        <td align="left"><span class="blue"><?php echo convert_from_sql_time('H:i', $val->start_time); ?></span></td>
                        <td align="left"><span class="blue"><?php echo convert_from_sql_time('H:i', $val->end_time); ?></span></td>
                        <td align="center"><span class="blue"><?php echo !empty($val->doctor_id) ? 'Dr. ' . $val->first_name . ' ' . $val->last_name : 'Not Assigned'; ?></span></td>
                        <td align="left"><span class="blue"><?php echo convert_from_sql_time('d M Y', $val->created); ?></span></td>
                        <td align="left"><span class="blue"><?php echo convert_from_sql_time('d M Y', $val->modified); ?></span></td>

                        <td align="center">
                            <a href="<?php echo base_url() . 'admin/' . $current_module .'/edit/' . $val->id; ?>"><img border="0" src="<?php echo base_url() ?>assets/images/edit_icon.gif"></a>
                        </td>

                        <td valign="middle" align="center">
                            <input type="checkbox" class="check_ids" value="<?php echo $val->id ?>" name="update_ids[]">
                        </td>
                    </tr>
                    <?php $i++;?>
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
