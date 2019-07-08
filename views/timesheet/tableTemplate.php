<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<?php
/*
This container displays a table

Variables Required Definitions:
$header:
    Must be two-dimensional array
    First element on specify column Width
    Second element on the value for the table header
    EX: $header[0][0] = column 1 width
        $header[0][1] = column 1 header
        $header[1][0] = column 2 width
        $header[1][1] = column 2 header
        ...

$results:
    Must be two-dimensional array
    EX: $results[0][0] = line 1 column 1 data
        $results[0][1] = line 1 column 2 data
        $results[0][2] = line 1 column 2 data
        $results[0][3] = line 1 column 2 data
        $results[1][0] = line 2 column 1 data
        ...

$linkUrl (Optional):
$linkText
    URL path of the row. Also keep in mind to have an additional column heading for this field
    EX:
        $data['linkUrl'] = '/projects/framework/';
        $data['linkText'] = "EDIT"

Ex:
    $data['header'] = array();
    $data['header'][0] = array(200, 'column1');
    $data['header'][1] = array(200, 'column2');

    $data['linkUrl'] = '/projects/framework/';
    $data['content'] = array();
    $data['content'][0] = array('row1 column1', 'row1 column2');
*/

$floatHeaderClass = "stickyHeader";
?>
<table class=<?= $floatHeaderClass ?>>
    <thead>
        <tr>
            <?php
            foreach ($header as $column) {
                ?>
                <th id="<?= 'thead' . $column[1] ?>" width="<?= $column[0]; ?>"><?= $column[1]; ?></th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php
        $revealIdentifier = $revealBoolean; // change based on data from controller
        if (isset($results)) {
            foreach ($results as $row) {
                ?>
                <tr>
                    <td>

                        <?php
                        if ($revealIdentifier == "noReveal") {
                            if (isset($linkUrl)) {
                                echo (anchor($linkUrl . $row['id'], $linkText, 'class="referenceForTabFocus"'));
                            }
                        } else {
                            ?>
                            <a class="<?= $revealIdentifier; ?>" data-open="<?= $revealIdentifier; ?>"><?= $linkText; ?></a>
                            <span class="shiftID hide"><?= $row['id'] ?></span>
                        <?php } ?>

                    </td>
                    <?php
                    foreach ($row as $key => $value) {
                        ?>

                        <?php

                        if (($key != 'id') && ($key != 'name') && ($key != 'url') && ($key != 'project_status_id')) {
                            echo '<td>' . $value . '</td>';
                        }


                        if ($key == 'url') {
                            echo ('<td>' . anchor($row['url'], 'Link', 'target="_blank" data-tooltip aria-haspopup="true" class="has-tip" data-disable-hover="false" title="' . $row['url'] . '"') . '</td>');
                        }

                        ?>
                    <?php
                }
                ?>
                </tr>
            <?php
        } //end of foreach
    } //end of if
    ?>
    </tbody>
</table>