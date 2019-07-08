<?php
$hasShift = true;

$aPanelSmtrExcpts = '<img src="/LibServices/assets/ess_assets/img/helpICO.png" class="helpMenu" data-what-help="aPanel_smtr_excpts" />';

if (isset($semesterData)) { ?>

  <div class="loadActivityICO"><img class="" src="/LibServices/assets/ess_assets/img/activityICO.gif" /></div>

  <div class="editSemesterform row">

      <div class="editFormTitle_editSemester">Edit Semester <?=$aPanelSmtrExcpts;?></div>

      <div class="medium-11 medium-centered">
        <br>
        <div class="row">
        <?php
          $attributes = array('data-confirm' => 'Are you sure you want to proceed with this action?');
          echo form_open($postUrl, $attributes);
          echo form_hidden('$semesterID', $semesterData['id']);
          ?>

            <fieldset class="editSemesterContainer small-12 columns">

              <div class="columns">
                <label class="inputLabel" for="semesterCalendarURL">Calendar URL</label>
                <input type="text" id="calendarLink" name="calendarURL" value="<?= $semesterData['calendarLink'] ?>" />
              </div>

              <div class="row">
                <div class="small-12 columns">
                  <div class="row">

                    <div class="large-3 columns">
                      <label class="inputLabel" for="semesterName">Start</label>
                      <input class="semesterName text-center" type="text" name="semester_name" value="<?= $semesterData['desc'] ?>" />
                    </div>

                    <div class="large-3 columns">
                      <label class="inputLabel" for="semesterStartDate">Start</label>
                      <input class="dateField semesterStartDate text-center" type="text" name="startDate" value="<?= $semesterData['startDate'] ?>" />
                    </div>

                    <div class="large-3 columns">
                      <label class="inputLabel" for="semesterEndDate">End</label>
                      <input class="dateField semesterEndDate text-center" type="text" name="endDate" value="<?= $semesterData['endDate'] ?>" />
                    </div>
                    <?php if($hasShift) {?>
                      <div class="large-3 columns">
                        <button class="edit editSemester buttonMarginTop default button float-center" type="submit" name="edit" value="editSemester">Edit</button>
                      </div>
                    <?php } else { ?>
                      <div class="large-3 columns">
                        <button class="delete deleteSemester buttonMarginTop default button float-center" type="submit" name="delete" value="deleteSemester">Remove</button>
                      </div>
                    <?php }?>
                  </div><!--row-->
                </div><!--small-12-->
              </div><!--row-->
            </fieldset>
          </form>
          <hr>
          <?= $this->load->view('timesheet/semesterDateException', $semesterData, true); ?>
        </div><!--row-->
      </div><!--medium-11-->
    </div> <!--  editSemesterform row -->
<?php
} else {echo ("No semesters data was received"); }