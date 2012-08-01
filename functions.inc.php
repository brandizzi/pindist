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
function pindist_get_pin_sets() {
    $sql = "SELECT pinsets_id as id, description FROM pinsets;";
    return sql($sql, "getAll", DB_FETCHMODE_ASSOC);
}

function pindist_get_used_pins($pinset_id) {
    $pinset_id = (int) $pinset_id;
    $sql = "SELECT pin FROM pin_association WHERE pinset = $pinset_id;";
    $results = sql($sql, "getAll");
    $pins = array();
    foreach ($results as $result) {
        array_push($pins, array_pop($result));
    }
    return $pins;
}

function pindist_get_free_pins($pinset_id) {
    $pinset_id = (int) $pinset_id;
    $sql = "SELECT passwords FROM pinsets WHERE pinsets_id = $pinset_id;";
    $pins = explode("\n", sql($sql, "getOne"));
    $used = pindist_get_used_pins($pinset_id);
    $available = array();
    foreach ($pins as $pin) {
        if (!in_array($pin, $used)) {
            array_push($available, $pin);
        }
    }
    return $available;
}

function pindist_get_pin_associations($pinset_id) {
    $pinset_id = (int) $pinset_id;
    $sql = "SELECT id, pin, pinset, name FROM pin_association WHERE pinset = $pinset_id;";
    return sql($sql, "getAll", DB_FETCHMODE_ASSOC);
}

function pindist_save_association($pin, $pinset, $name) {
    $pin = mysql_real_escape_string($pin);
    $pinset = mysql_real_escape_string($pinset);
    $name = mysql_real_escape_string($name);
    $sql = "INSERT INTO pin_association (pin, pinset, name) ".
            " VALUES ($pin, $pinset, '$name');";
    sql($sql);
}

function pindist_disassociate_association($pin, $pinset) {
    $pin = mysql_real_escape_string($pin);
    $pinset = mysql_real_escape_string($pinset);
    $sql = "DELETE FROM pin_association WHERE pin=$pin AND  pinset=$pinset;";
    sql($sql);
}

function pindist_revoke_association($pin, $pinset) {
    pindist_disassociate_association($pin, $pinset);
    $pinset = (int) $pinset;
    error_log("SELECT passwords FROM pinsets WHERE pinsets_id = $pinset;");
    $pins = sql("SELECT passwords FROM pinsets WHERE pinsets_id = $pinset;",
            "getOne");
    $pins = str_replace("$pin\n", "", $pins);
    $pins = str_replace("\n$pin", "", $pins); // If it is the last one
    $pins = str_replace("$pin", "", $pins); // If it is the only one
    $escaped = mysql_real_escape_string($pins);
    sql("UPDATE pinsets SET passwords='$escaped' WHERE pinsets_id = $pinset;");
}
?>
