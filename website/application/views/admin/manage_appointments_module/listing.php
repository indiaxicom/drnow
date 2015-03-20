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
                </td>
            </tr>
        </table>
    </div>
</form>
<?php echo form_open(base_url() . '/admin/' . $this->current_module . '/change_status'); ?>
    <div class="row mtop30">
        <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" class="listing">
            <tr>
                <th width="4%" align="center">S No.</th>
                <th width="10%" align="left">Doctor</th>
                <th width="10%" align="left">Appointment Time</th>
                <th width="10%" align="left">Appointment Created</th>
                <th width="30%" align="left">Session Key</th>
            </tr>
            <?php if (!empty($get_all_appointments)) : ?>
                <?php $i = 1; ?>
                <?php foreach ($get_all_appointments as $val) : ?>
                    <tr>
                        <td align="center"><?php echo $i; ?></td>
                        <td align="left"><span class="blue"><?php echo 'Dr. ' . $val[USER_DOCTOR]->first_name . ' ' . $val[USER_DOCTOR]->last_name; ?></span></td>
                        <td align="left"><span class="blue"><?php echo convert_from_sql_time('d M Y h:i a', current($val)->start_time); ?></span></td>
                        <td align="left"><span class="blue"><?php echo convert_from_sql_time('d M Y h:i a', current($val)->created); ?></span></td>
                        <td align="left"><span class="blue"><?php echo current($val)->tokbox_session_key; ?></span></td>
                    </tr>
                    <?php $i++;?>
                <?php endforeach; ?>
                <?php else : ?>
                <td align="left" colspan="6"><?php echo 'No Record Found' ?></td>
            <?php endif; ?>
        </table>
    </div>
</form>
