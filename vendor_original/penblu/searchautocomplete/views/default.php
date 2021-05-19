<?php

$html = '';

/**
 * Header
 */
$arr_pos = [];
if (!empty($headers)) {
    $html .= '<tr>';
    for ($i = 0; $i < count($headers); $i++) {
        if(array_search($headers[$i], $colsVisible) !== FALSE){
            $html .= "<th>".$headers[$i]."</th>";
            $arr_pos[] = $i;
        }else   
            $html .= "<th style='display: none;'>".$headers[$i]."</th>"; 
    }
    $html .= '</tr>';
}

/**
 * Body
 */
if (!empty($rows)) {
    foreach ($rows as $row) {
        $html .= '<tr>';
        $i = 0;
        foreach ($row as $columnName => $column) {
            $cssClass = "";
            if(array_search($i, $arr_pos) === FALSE){
                $cssClass = "style='display: none;'";
            }
            if (is_array($column)) {
                $content = '';
                foreach ($column as $aColumnKey => $aColumnValue) {
                    $content .= "$aColumnKey : $aColumnValue ";
                }
                $content = htmlspecialchars($content);
                $html .= "<td ".$cssClass." class='ccl'>$content</td>";
            } else {
                $column = htmlspecialchars($column);
                $html .= "<td ".$cssClass." class='ccd'>$column</td>";
            }
            $i++;
        }
        $html .= '</tr>';
    }
} else {
    // No result

    // To prevent XSS prevention convert user input to HTML entities
    $query = htmlentities($query, ENT_NOQUOTES, 'UTF-8');

    // there is no result - return an appropriate message.
    $html .= "<tr><td>$noResult \"{$query}\"</td></tr>";
}

echo $html;

?>

