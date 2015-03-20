<?php
$record = NULL;

$this->ckeditor->basePath = base_url().'assets/ckeditor/';
$this->ckeditor->config['width'] = '750px';
$this->ckeditor->config['height'] = '300px'; 

if (!empty($get_all_records)) {
    $record = current($get_all_records);
}
?>

<div class="row">
    <div class="floatleft">
        <h1>Edit Content</h1>
    </div>
    <div class="floatright">
        <a href="<?php echo base_url() . 'admin/' . $current_module ?>" class="black_btn">
            <span>Back To Manage Content</span>
        </a>
    </div>
</div>
<?php echo form_open('', array('id' => 'add_template')); ?>
<div class="form_validation_errors">
    <?php echo validation_errors(); ?>
</div>

<div align="center" class="whitebox mtop15">
    <table cellspacing="0" cellpadding="7" border="0" align="center">
        <tr>
            <td align="left"><strong class="upper">Content Heading</strong></td>
                <td align="left">
                    <?php
                        $options = array();
                        foreach ($this->config->item('cmsArr') as $key => $val)
                        {
							$options[$key] = $val['title'];
						}
                        echo form_dropdown('content_type', $options, !empty($record->content_type) ? 
							$record->content_type : set_value('content_type'), !empty($record->id) ? 'readonly="readonly"' : NULL);
                    ?>
                </td>
        </tr>

        <tr>
            <td align="left"><strong class="upper">Body</strong></td>
            <td align="left">
                <?php echo $this->ckeditor->editor("body", !empty($record->body) ? htmlspecialchars_decode($record->body) : set_value('body')); ?>
            </td>
        </tr>

        <tr>
            <td align="left"><strong class="upper">Status</strong></td>
            <td align="left">
                <?php
                    $options = $this->config->item('userStatusArr');
                    echo form_dropdown('status', $options, !empty($record->status) ? $record->status : set_value('status'));
                ?>
            </td>
        </tr>

        <tr>
            <td align="center">&nbsp;</td>
            <td align="left">
                <div class="black_btn2">
                    <span class="upper">
                        <?php 
                        if (!empty($record->id))
                        {
                            echo form_hidden('id', $record->id);
                        }
                        ?>
                        <?php echo form_submit('submit', 'SUBMIT'); ?>
                    </span>
                </div>
            </td>
        </tr>
    </table>
</div>      
</form>
