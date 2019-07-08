<!-- Add Exception -->
<?php
/*$attributesForAdd = array(
  'data-confirm'  => 'Are you sure you want to proceed with this action?',
  'id' => 'addExceptions'
);
echo form_open($exceptionUriToPost, $attributesForAdd);
*/
$uid = new webuser();
?>
<form class="addExceptions">
  <fieldset class="medium-9 medium-centered columns">
    <h3 class="sectionTitle text-center">Date Exceptions</h3>
    <div class="large-4 columns">
      <label class="inputLabel" for="semesterNoWork">Exception date</label>
      <input class="dateField semesterNoWork text-center" type="text" name="onDate" value="00-00-00" />
    </div>

    <div class="large-4 columns">
      <label class="inputLabel" for="semesterReplaceWith">Replace with</label>
      <input class="dateField semesterReplaceWith text-center" type="text" name="swapDate" value="00-00-00" />
    </div>

    <div class="noReplaceDateContainer large-3 columns">
      <label class="inputLabel nowrap">
        No replace <input type="checkbox" name="noWorkBool" class="noreplaceDate" />
      </label>
    </div>

    <div id="addExceptionContainer" class="large-1 columns">
      <button id="addException" class="default button buttonMarginTop addShift" type="submit" name="addDateException">Add</button>
    </div>
  </fieldset>
  <input type="hidden" name="uid" value="<?= $uid->getUID() ?>" />
  <input type="hidden" name="semesterID" value="<?= $semesterData['id'] ?>" />
</form>
<br>
<!--Added Exceptions-->
<?php
/*
$attributesForAdded = array(
    'data-confirm' => 'Are you sure you want to proceed?',
    'class' => 'dbExceptions'
);
echo form_open($exceptionUriToPost, $attributesForAdded);
*/

$schObj = new schedule();
$exceptions =  $schObj->getExceptionDates($semesterData['id']);

$i = 0;
$dbExcpClass = "dbAddedExceptions" . $i;

foreach ($exceptions as $exception) {
  ?>

  <form class="<?= $dbExcpClass ?>">
    <fieldset class="exceptionContainerAdded medium-9 medium-centered columns">
      <div class="large-4 columns">
        <label class="inputLabel" for="semesterNoWork">No work date</label>
        <input class="semesterNoWork text-center" type="text" name="onDate" value="<?= $exception['onDate'] ?>" disabled />
      </div>

      <div class="large-4 columns">
        <label class="inputLabel" for="semesterReplaceWith">Replace with</label>
        <input class="text-center" type="text" name="swapDate" value="<?= $exception['swapDate'] ?>" disabled />
      </div>

      <div class="large-2 columns">
        <button class="removeException delete buttonMarginTop default button" name="removeException" type="submit">Remove</button>
      </div>
      <div class="columns"><a class="editExcpAnnReveal" data-open="editExcpAnnReveal">View announcement</a></div>
    </fieldset>
    <input type="hidden" name="exceptionID" value="<?= $exception['id'] ?>" />
    <br>
  </form>
  <?php $i++;
} ?>