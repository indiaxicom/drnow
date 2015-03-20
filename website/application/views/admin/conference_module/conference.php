<?php if ($this->session->flashdata('tokbox_token') != NULL) : ?>
    <script src="//static.opentok.com/webrtc/v2.2/js/opentok.min.js" ></script>

    <script type="text/javascript">
        var apiKey    = "<?php echo $this->config->item('tokbox_api_key'); ?>";
        var sessionId = "<?php echo $this->session->flashdata('tokbox_session_id'); ?>";
        var token     = "<?php echo $this->session->flashdata('tokbox_token'); ?>";
        var publisher;
        var session = OT.initSession(apiKey, sessionId);
     
    session.on("streamCreated", function(event) {
        session.subscribe(event.stream, 'subscriber', {width:430, height:410});
      });

    session.connect(token, function(error) {
        publisher = OT.initPublisher('myPublisher', {name: "<?php echo $this->session->flashdata('name'); ?>", width:200, height:200});
        publish();
      });

    function unpublish() {
        session.unpublish(publisher);
    }
    function publish() {
        session.publish(publisher);
    }
    function unsubscribe() {
        session.unpublish(publisher);
    }
    </script>
<?php endif; ?>

<?php echo form_open('', array('method' => 'post')); ?>
    <div class="row mtop15">
        <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
            <tr valign="top">
                <td align="left" class="searchbox">
                    <div class="floatleft">
                        <table cellspacing="0" cellpadding="4" border="0">
                            <tr valign="top">
                                <td><input required="required" class="input" placeholder="Your name" type="text" name="name" value=""></td>
                                <td><input size="60" required="required" class="input" placeholder="Put the tokbox session key here" type="text" name="session_id" value=""></td>
                                <td><input size="100" required="required" class="input" placeholder="Put the patient token Here" type="text" name="token_id" value=""></td>
                                <td valign="middle" align="left">
                                    <div class="black_btn2">
                                        <span class="upper">
                                            <input type="submit" name="create_token" value="Start Video" name="">
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</form>
<?php if ($this->session->flashdata('tokbox_token') != NULL) : ?>
    <div class="row mtop30" align="center" style="text-align:center !important">
        <table width="100%" cellspacing="5" cellpadding="0" border="0" align="center">
            <tr>
                <td align="center" width="75%" style="background: none repeat scroll 0 0 #000;height: 600px;">
                    <div id="subscriber" style="color:#fff">
                        Waiting for patient to enter appointment
                    </div>
                </td>
                <td align="right"  width="20%" style="vertical-align: bottom;">
                    <div class="black_btn2" style="margin-bottom:10px">
                        <span class="upper">
                            <input type="button" value="Unpublish your video" name="disconnect" onclick="unpublish()">
                        </span>
                    </div>
                    <div style="background: none repeat scroll 0 0 #000; height: 200px;" id="myPublisher">dfdf</div>
                </td>
            </tr>
        </table>
    </div>
<?php endif; ?>
