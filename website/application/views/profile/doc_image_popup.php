<script>
$(document).ready(function()
{
    $('img#crop_image').imgAreaSelect({
        minWidth: <?php echo $width; ?>,
        aspectRatio: "<?php echo ($image_type == PROFILE_IMAGE) ? '1:1' : FALSE ?>",
        x1: 0,
        y1: 0,
        x2: <?php echo $width; ?>,
        y2: <?php echo $height; ?>,
        persistent:true,
        handles: true,
        show: true,
        onSelectEnd: function (img, selection) {
            $('input[name=x_axis_1]').val(selection.x1);
            $('input[name=x_axis_2]').val(selection.x2);
            $('input[name=y_axis_1]').val(selection.y1);
            $('input[name=y_axis_2]').val(selection.y2);
        }
    });
});
</script>

<div align="center">
    <img id="crop_image" src="<?php echo base_url() . $path . $image_name; ?>" />
     <?php
        echo form_open(base_url() . 'operations/crop_image', 'id="form_crop_iamge"');
        echo form_hidden('x_axis_1', 0);
        echo form_hidden('x_axis_2', $width);
        echo form_hidden('y_axis_1', 0);
        echo form_hidden('y_axis_2', $height);
        echo form_submit('submit', 'Save', 'class=sub-btn');
        echo form_close();
    ?>
</div>
