<div class="large-3 medium-4 small-6 columns">
    <select id="activeSemesterPicker" name="timesheetActiveSemesterPicker">
        <?php foreach (schedule::getSemesters() as $semester): ?>
            <option value="<?= $semester['id']; ?>"  <?php
            if ($semester['id'] == schedule::getActiveSemesterID()) {
                echo 'selected="selected"';
            }
            ?>><?= $semester['desc']; ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<div class="large-4 medium-6 small-6 columns">
    <a href="<?= schedule::getActiveSemesterROW()['calendarLink']; ?>" target="_blank" data-open="Academic Calendar">
        <img src="/LibServices/assets/ess_assets/img/calendarICO.png" />
    </a>
</div>