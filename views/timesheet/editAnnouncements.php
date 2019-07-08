<?php
$schedule = new schedule();
$announcements = $schedule->getAnnouncements();

if (sizeof($announcements) !== 0) {

    foreach ($announcements as $announcement) {

        echo form_open('timesheet/announcements');
        echo form_hidden('id', $announcement['id']);
        ?>
        <div class="large-9 medium-9 row">
            <hr>
        </div>
        <fieldset class=" formContainer boxShadow large-9 medium-9 row">

            <div class="large-4 medium-6 small-12 columns">
                <label for="startDate">Show on</label>
                <input class="dateField text-center" type="text" name="startDate" value="<?= $announcement['startDate']; ?>" />
            </div>

            <div class="large-4 medium-6 small-12 columns">
                <label for="endDate">Stop showing on</label>
                <input class="dateField text-center" type="text" name="endDate" value="<?= $announcement['endDate']; ?>" />
            </div>

            <div class="small-12 columns">
                <label for="announcementTitle">Title</label>
                <input type="text" name="title" value="<?= $announcement['title']; ?>" />
            </div>

            <div class="small-12 columns">
                <label for="announcementBody">Body</label>
                <textarea name="body" wrap="hard"><?= $announcement['body']; ?></textarea>
            </div>

            <div class="columns">

                <?php if ($announcement['type'] == "manual") { ?>
                    <div class="text-center">
                        <a href="/timesheet/adminPanel/"> <button class="addShift default button" type="submit" name="editAnnouncement">Edit</button></a>
                        <a href="/timesheet/adminPanel/"> <button class="delete default button" type="submit" name="removeAnnouncement">Remove</button></a>
                    </div>
                <?php } ?>
            </div>

        </fieldset>
        </form>
    <?php
}
} else { ?>
    <div class="large-9 medium-9 row">
        <p class="text-center">There are no announcements to show</p>
    </div>
<?php
}
?>