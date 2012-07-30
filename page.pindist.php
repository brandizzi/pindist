<?php
$action = @$_REQUEST['action'];
if (!$action) { ?>
<div class="pindist pinsets">
    <h2>PIN sets</h2>
    <ul>
    <?php foreach (pindist_get_pin_sets() as $pinset) { ?>
        <li>
            <a href="?display=pindist&action=listpinsets&pinset=<?php echo $pinset['id']; ?>">
                <?php echo $pinset['description']; ?>
            </a>
        </li> 
    <?php } ?>
    </ul>
</div>
<?php } else if ($action == 'listpinsets') { 
    $pinset_id = $_REQUEST['pinset'];
?>
<div class="pindist pins">
    <h2>PINs</h2>
    <h3>Available</h3>
    <table sytle="border: 1px solid #999; background-color: #DDD; text-align: center;">
    <tr>
        <th style="background-color: #AAA; margin: 1em;">PIN</th><th style="background-color: #AAA; margin: 1em;">Associate</th>
    </tr>
    <?php foreach (pindist_get_free_pins($pinset_id) as $pin) { ?>
    <tr>
        <td style="border: 1px solid #999; background-color: #DDD; margin: 1em; padding: 0 1em 0 1em;"><?php echo $pin; ?></td>
        <td style="border: 1px solid #999; background-color: #DDD; margin: 1em; padding: 0 1em 0 1em;">
        <a href="?display=pindist&action=attribute&pin=<?php echo $pin; ?>&pinset=<?php echo $pinset_id; ?>">
            <img src="/admin/images/user_add.png" 
                    alt="Attribute PIN <?php echo $pin; ?> to an user"
                    title="Attribute PIN <?php echo $pin; ?> to an user">
        </a>
        </td>
    </tr> 
    <?php } ?>
    </table>
    <h3>Attributed</h3>
    <table>
    <tr>
        <th style="background-color: #AAA; margin: 1em;">PIN</th>
        <th style="background-color: #AAA; margin: 1em;">Person</th>
        <th style="background-color: #AAA; margin: 1em;">Revoke</th>
    </tr>
    <?php foreach (pindist_get_pin_associations($pinset_id) as $association) { ?> 
    <tr>
        <td style="border: 1px solid #999; background-color: #DDD; margin: 1em; padding: 0 1em 0 1em;"><?php echo $association['pin']; ?> </td>
        <td style="border: 1px solid #999; background-color: #DDD; margin: 1em; padding: 0 1em 0 1em;"><?php echo $association['name']; ?></td>
        <td style="border: 1px solid #999; background-color: #DDD; margin: 1em; padding: 0 1em 0 1em;">
        <a href="?display=pindist&action=revoke&pin=<?php echo $association['pin']; ?>&pinset=<?php echo $pinset_id; ?>">
        <?php $pin = $association['pin']; $name = $association['name']; ?>
            <img src="/admin/images/user_delete.png" 
                    alt="Revoke PIN <?php echo $pin; ?> from <?php echo $pin; ?>"
                    title="Revoke PIN <?php echo $pin; ?> from <?php echo $pin; ?>">
        </a>
        </td>
    </tr> 
    <?php } ?>
    </table>
</div>
<?php } else if ($action == 'attribute') {  
    $pin = $_REQUEST['pin'];
    $pinset_id = $_REQUEST['pinset'];
?>
<div class="pindist attribute">
    <h2>Attribute PIN</h2>
    <form action="?display=pindist&action=attribute_save" method="POST">
        <input name="action" type="hidden" value="attribute_save">
        <input name="pin" type="hidden" value="<?php echo $pin; ?>">
        <input name="pinset" type="hidden" value="<?php echo $pinset_id; ?>">
        PIN: <?php echo $pin; ?><br>
        Person: <input type="text" name="name"><br>
        <input type="submit">
    </form>
</div>
<?php } else if ($action == 'attribute_save') {  
    $pin = $_REQUEST['pin'];
    $pinset_id = $_REQUEST['pinset'];
    $name = $_REQUEST['name'];
    pindist_save_association($pin, $pinset_id, $name);
?>
<a href="?display=pindist&action=listpinsets&pinset=<?php echo $pinset_id; ?>">
    Back
</a>
<?php } else if ($action == 'revoke') {  
    $pin = $_REQUEST['pin'];
    $pinset_id = $_REQUEST['pinset'];
    pindist_revoke_association($pin, $pinset_id);
?>
<a href="?display=pindist&action=listpinsets&pinset=<?php echo $pinset_id; ?>">
    Back
</a>
<?php } ?>
