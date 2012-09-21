<?php
// model.php

function open_database_connection()
{
    $link = mysql_connect('localhost', 'root', '');
    mysql_select_db('symfony-billing', $link);
    return $link;
}

function close_database_connection($link)
{
    mysql_close($link);
}

function get_all_calls()
{
    $link = open_database_connection();

    $result = mysql_query('SELECT id, recipient, duration FROM mobile_call', $link);
   
    $calls = array();
    while ($row = mysql_fetch_assoc($result)) {
        $calls[] = $row;
    }
    close_database_connection($link);

    return $calls;
}

function get_call_by_id($id)
{
    $link = open_database_connection();

    $id = mysql_real_escape_string($id);
    $query = 'SELECT * FROM mobile_call WHERE id = '.$id;
    $result = mysql_query($query);
    $row = mysql_fetch_assoc($result);

    close_database_connection($link);

    return $row;
}