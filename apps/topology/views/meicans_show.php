<?php

$meicans = $this->passedArgs;

?>

<h1><?php echo _("MEICANs"); ?></h1>

<form method="POST" action="<?php echo $this->buildLink(array('action' => 'delete')); ?>">

    <table class="list">
        
        <thead>
        <tr>
            <th></th>
            <th></th>
            <th><?php echo _("Local?"); ?></th>
            <th><?php echo _("Name"); ?></th>
            <th><?php echo _("MEICAN IP"); ?></th>
            <th><?php echo _("Directory name"); ?></th>
        </tr>
        </thead>

        <tbody>
        <?php foreach ($meicans as $m): ?>
        <tr>
            <td>
                <input type="checkbox" name="del_checkbox[]" value="<?php echo $m->id; ?>">
            </td>
            <td>
                <a href="<?php echo $this->buildLink(array('action' => 'edit', 'param' => "meican_id:$m->id")); ?>">
                    <img class="edit" src="<?php echo $this->url(''); ?>webroot/img/edit_1.png"/>
                </a>
            </td>
            <td>
                    <?php echo $m->local; ?>
            </td>
            <td>
                    <?php echo $m->descr; ?>
            </td>
            <td>
                    <?php echo $m->ip; ?>
            </td>
            <td>
                    <?php echo $m->dir_name; ?>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>

        <tfoot>
        <tr>
            <td colspan="6">
                <input class="add" type="button" value="<?php echo _('Add'); ?>" onclick="redir('<?php echo $this->buildLink(array('action' => 'add_form')); ?>');">
            </td>
        </tr>
        </tfoot>

    </table>
    
    <div class="controls">
        <input class="delete" type="submit" value="<?php echo _('Delete'); ?>" onClick="return confirm('<?php echo _('The selected MEICANs will be deleted.'); echo '\n'; echo _('Do you confirm?'); ?>')">
    </div>
    
</form>