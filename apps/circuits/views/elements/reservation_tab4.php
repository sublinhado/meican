<!-- TIMER -->
<br/>
<div align="center">
    <?php $this->addElement('timer_form', $args); ?>
</div>
<br/>

<div class="controls">

    <?php //if ($args->sel_timer): ?>
        <input type="button" id="bn2" class="next" value="<?php echo _('Next'); ?>" onClick="nextTab(this);"/>
    <?php //else: ?>
    <!--    <input class="next" type="button" id="bn4" disabled="disabled" value="<?php echo _("Next"); ?>"/> -->
    <?php //endif; ?>
    <input type="button" id="bc2" class="cancel" value="<?php echo _('Cancel'); ?>" onClick="redir('<?php echo $this->buildLink(array("action" => "show")); ?>');"/>          
    <input type="button" id="bp2" class="back" value="<?php echo _('Previous'); ?>" onClick="prevTab(this);"/>

</div>
