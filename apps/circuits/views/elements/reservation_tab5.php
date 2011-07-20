<!-- CONFIRMATION -->
<br/>

<h2><?php echo _('Endpoints'); ?></h2>
<?php $this->addElement('view_flow', $flow); ?>

<br/>

<h2 style="display: inline"><?php echo _('Bandwidth'); ?>:</h2><label id="lb_bandwidth"></label>

<br/>

<h2><?php echo _('Timer'); ?></h2>
<?php $this->addElement('view_timer', $timer); ?>


<div class="controls">
    <input type="button" id="bc3" class="cancel" value="<?php echo _('Cancel'); ?>" onClick="redir('<?php echo $this->buildLink(array("action" => "show")); ?>');"/>
    <input type="button" id="bp3" class="back" value="<?php echo _('Previous'); ?>" onClick="prevTab(this);"/>
    <input type="button" id="bf"  class="ok" value="<?php echo _('Finished'); ?>" onClick="validateForm();"/>
</div>