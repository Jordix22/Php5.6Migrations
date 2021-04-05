<?php


interface iDatabase
{
    function connectToDatabase();
    function fetch($query);
    function execute($query);
}