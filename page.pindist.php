<?php
/**
 * PIN Distributor (aka pindist): a FreePBX module for registering who has 
 * received which PIN from various PIN sets.
 * Copyright (C) 2012  Adam Victor Nazareth Brandizzi <brandizzi@gmail.com>
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * You can get the latest version of this file at 
 * http://bitbucket.org/brandizzi/pindist
 */
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
    <div class="available" style="float:left; width: 25%;">
    <h3 style="margin-top: 0em;">Available</h3>
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
    </div>
    <div class="attributed" style="margin-left: 30%; width: 60%;"/>
    <h3>Attributed</h3>
    <table>
    <tr>
        <th style="background-color: #AAA; margin: 1em;">PIN</th>
        <th style="background-color: #AAA; margin: 1em;">Person</th>
        <th style="background-color: #AAA; margin: 1em;">Disassociate</th>
    </tr>
    <?php foreach (pindist_get_pin_associations($pinset_id) as $association) { ?> 
    <tr>
        <td style="border: 1px solid #999; background-color: #DDD; margin: 1em; padding: 0 1em 0 1em;"><?php echo $association['pin']; ?> </td>
        <td style="border: 1px solid #999; background-color: #DDD; margin: 1em; padding: 0 1em 0 1em;"><?php echo $association['name']; ?></td>
        <td style="border: 1px solid #999; background-color: #DDD; margin: 1em; padding: 0 1em 0 1em;">
        <a href="?display=pindist&action=revoke&pin=<?php echo $association['pin']; ?>&pinset=<?php echo $pinset_id; ?>">
        <?php $pin = $association['pin']; $name = $association['name']; ?>
            <img src="/admin/images/user_delete.png" 
                    alt="Disassociate PIN <?php echo $pin; ?> from <?php echo $pin; ?>"
                    title="Disassociate PIN <?php echo $pin; ?> from <?php echo $pin; ?>">
        </a>
        </td>
    </tr> 
    <?php } ?>
    </table>
    </div>
    <div style="clear:both;"></div>
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
