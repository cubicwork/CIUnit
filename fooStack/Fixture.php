<?php

/*
* fooStack, Fixture Class
* Copyright (c) 2008 Clemens Gruenberger
* Released with permission from www.redesignme.com, thanks guys!
* Released under the MIT license, see:
* http://www.opensource.org/licenses/mit-license.php
*/

/**
* Fixture Class
* loads fixtures
* can be used with CIUnit
*/
class Fixture {

  function __construct(){
    if(!defined('CIUnit_Version')){
     exit('can\'t load fixture library class when not in test mode!');
    }
  }

  /**
  * loads fixture data $fixt into corresponding table
  */
  function load($table, $fixt){
    
    $this->CI = &get_instance();
    //FIXME, this has to be done only once
    $db_name_len = strlen($this->CI->db->database);
    if(substr($this->CI->db->database, $db_name_len-5, $db_name_len) != '_test'){
      die("\nSorry, the name of your test database must end on '_test'.\nThis prevents deleting important data by accident.\n");
    }

    # $fixt is supposed to be an associative array outputted by spyc from YAML file
    $this->CI->db->simple_query('truncate table '.$table.';');
    foreach($fixt as $id=>$row){
      foreach($row as $key=>$val){
        if($val!=""){
            //fix for bad field names requiring backticks:
            $row['`'.$key.'`']=$val;
        }
        //unset the rest
        unset($row[$key]);
      }
      //print_r($row);
      $this->CI->db->insert($table, $row);
      //log_message('debug', "fixture: '$id' for $table loaded");
    }
  }
  
}