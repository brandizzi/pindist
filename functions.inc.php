<?php
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
    $sql = "SELECT passwords FROM pinsets;";
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

function pindist_revoke_association($pin, $pinset) {
    $pin = mysql_real_escape_string($pin);
    $pinset = mysql_real_escape_string($pinset);
    $sql = "DELETE FROM pin_association WHERE pin=$pin AND  pinset=$pinset;";
    sql($sql);
}
?>
