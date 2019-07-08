<?php
    echo form_open('timesheet/announcements');
    echo form_hidden('id', $id);
?>
<div class="loadActivityICO"><img class="" src="/LibServices/assets/ess_assets/img/activityICO.gif" /></div>
    <div class="large-9 medium-9 row">
    </div>
    <fieldset class="formContainer boxShadow large-9 medium-9 row">

        <div class="large-4 medium-6 small-12 columns">
            <label for="startDate">Show on</label>
            <input class="dateField text-center" type="text" name="startDate" value="<?= $startDate ?>" />
        </div>

        <div class="large-4 medium-6 small-12 columns">
            <label for="endDate">Stop showing on</label>
            <input class="dateField text-center" type="text" name="endDate" value="<?= $endDate ?>" />
        </div>

        <div class="small-12 columns">
            <label for="announcementTitle">Title</label>
            <input type="text" name="title" value="<?= $title ?>" />
        </div>

        <div class="small-12 columns">
            <label for="announcementBody">Body</label>
            <textarea name="body"><?= $body ?></textarea>
        </div>

        <div class="columns">
            <div class="text-center">
                <a href="/timesheet/adminPanel/"> <button class="addShift default button" type="submit" name="editAnnouncement">Edit</button></a>
            </div>
        </div>

    </fieldset>
    </form>