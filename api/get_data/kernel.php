<?php

class database{

    function __construct($group, $zach,$semestr) {
        $this->group=$group;
        $this->zach=$zach;
		$this->semestr=$semestr;
    }

    function execute_query ($query, $flag, $all){
    $result = mysql_query($query);
    if($flag)
    return $result;
    if($all){
        while($res = mysql_fetch_array($result,MYSQL_ASSOC)){
            $data['data'][$res['zach']] = $res['data'];
            $data['group']=$res['groups'];
        }
        return $data;
    }
     return mysql_fetch_array($result,MYSQL_ASSOC);
    }

    function insert ($table, $place, $value){
      return  $this->execute_query("INSERT INTO `$table` $place VALUES $value");
    }

    function select ($place, $table, $where){
        return  $this->execute_query("SELECT `$place` FROM $table WHERE $where");
    }

    function update ($table, $set, $where){
        return  $this->execute_query("update $table Set $set where $where");
    }

    function command($com,$f){
    $result = mysql_query($com);
    if($f != 1)
     $row = mysql_fetch_array($result,MYSQL_ASSOC);
     else
     $row = mysql_num_rows($result);
     return $row;
    }

    function getDataZach(){
        return $this->execute_query("SELECT data, zach, change_time FROM cache WHERE zach = '".$this->zach."' and semestr='".$this->semestr."'");
    }

    function getDataGroup(){
        return $this->execute_query("SELECT * FROM cache WHERE groups = '".$this->group."' and semestr='".$this->semestr."'","","1");
    }
	
    function getSemestr(){
        $this->execute_query("SELECT count(*) FROM cache WHERE zach = '".$this->zach."' and semestr='".$this->semestr."'");
    }
	

}

    function ex($n){
            $res= preg_replace("/Зачет с оценкой/",'Зач.с.Оц.',$n);
            $res= preg_replace("/Зачет/",'Зач.',$res);
            $res= preg_replace("/Дифференцированный зачет/",' Диф.Зач.',$res);
            $res= preg_replace("/Экзамен/",'Экз.',$res);
            return $res;
    }

?>
