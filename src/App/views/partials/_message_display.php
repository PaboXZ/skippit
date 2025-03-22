<?php if(isset($message)): ?>
<div class="message-display" id="message-box">
    <div><?=$message?></div><div class="right"><i onclick="closeDialogBox('message-box')" class="icon-cancel dialog-box-close-ico"></i></div>
</div>
<?php endif; ?>