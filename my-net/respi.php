<?php
function pluralize( $count, $text ) 
{ 
    return $count . ( ( $count == 1 ) ? ( " $text" ) : ( " ${text}" ) );
}

function ago( $datetime )
{
    $interval = date_create('now')->diff( $datetime );
    $suffix = ( $interval->invert ? ' 之前' : '' );
    if ( $v = $interval->y >= 1 ) return pluralize( $interval->y, '年' ) . $suffix;
    if ( $v = $interval->m >= 1 ) return pluralize( $interval->m, '个月' ) . $suffix;
    if ( $v = $interval->d >= 1 ) return pluralize( $interval->d, '天' ) . $suffix;
    if ( $v = $interval->h >= 1 ) return pluralize( $interval->h, '小时' ) . $suffix;
    if ( $v = $interval->i >= 1 ) return pluralize( $interval->i, '分钟' ) . $suffix;
    return pluralize( $interval->s, '秒' ) . $suffix;
}

function text_to_array($str) {

    //Initialize arrays
    $keys = array();
    $values = array();
    $output = array();

    //Is it an array?
    if( substr($str, 0, 5) == 'Array' ) {

        //Let's parse it (hopefully it won't clash)
        $array_contents = substr($str, 7, -2);
        $array_contents = str_replace(array('[', ']', '=>'), array('#!#', '#?#', ''), $array_contents);
        $array_fields = explode("#!#", $array_contents);

        //For each array-field, we need to explode on the delimiters I've set and make it look funny.
        for($i = 0; $i < count($array_fields); $i++ ) {

            //First run is glitched, so let's pass on that one.
            if( $i != 0 ) {

                $bits = explode('#?#', $array_fields[$i]);
                if( $bits[0] != '' ) $output[$bits[0]] = $bits[1];

            }
        }

        //Return the output.
        return $output;

    } else {

        //Duh, not an array.
        echo 'The given parameter is not an array.';
        return null;
    }

}

error_reporting(E_ALL);
// if request contain 'respi_info', save it
// other wise, get last
if (isset($_POST['respi_info'])){
    // delete all file in dir:
    array_map('unlink', glob("./dir_respi_req_info/info.txt"));
    file_put_contents('./dir_respi_req_info/info.txt', print_r($_POST,true));
} else {
    $ip = file_get_contents("./dir_respi_req_info/info.txt");
    $lastArr = text_to_array($ip);
    $date_to_cal = trim($lastArr["updateAt:_"]);
    print_r('<pre>'.file_get_contents('./dir_respi_req_info/info.txt').'</pre>');
    echo 'Server Time: ' . date('Y-m-d H:i:s') . '<span style="color:red;"> [ '.ago(date_create($date_to_cal)).'更新 ]</span>';
}

