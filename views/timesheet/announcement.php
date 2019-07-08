<?php
$aPanelAnnouncements = '<img src="/LibServices/assets/ess_assets/img/helpICO.png" style="display:none;" class="helpMenu" data-what-help="aPanel_announcements" />';

echo form_open('timesheet/announcements');
echo form_hidden('uid', webuser::getLoggedInUid());
?>

<div class="large-9 medium-9 row">
    <h3 class="text-center">Submit Announcements  <?=$aPanelAnnouncements?></h3>
</div>
<fieldset class="adminformContainer formContainer boxShadow large-9 medium-9 row">

    <div class="large-4 medium-6 small-12 columns">
        <label for="startDate">Show on</label>
        <input readonly="true" class="dateField text-center" type="text" name="startDate" id="startDate" placeholder="yy-mm-dd" />
    </div>

    <div class="large-4 medium-6 small-12 columns">
        <label for="endDate">Stop showing on</label>
        <input readonly="true" class="dateField text-center" type="text" name="endDate" id="endDate" placeholder="yy-mm-dd" />
    </div>

    <div class="small-12 columns">
        <label for="announcementTitle">Title</label>
        <input type="text" name="title" id="announcementTitle" />
    </div>

    <div class="small-12 columns">
        <label for="announcementBody">Body</label>
        <textarea name="body" id="announcementBody" wrap="hard"></textarea>
    </div>

    <div class="columns">

        <div class="large-2 medium-4 small-4 columns">
            <a href="/timesheet/adminPanel/"> <button type="button" class="default button cancelShift">Cancel</button></a>
        </div>

        <div class="large-10 medium-8 small-8 columns">
            <a href="/timesheet/adminPanel/"><button type="submit" name="addAnnouncement" class="default button addShift">Add</button></a>
        </div>

    </div>

</fieldset>
</form>

<br><br>
    <div class="large-9 medium-9 row">
        <h3 class="shifts_userName text-center">Submitted Announcements</h3>
    </div>

<?php echo $this->load->view('timesheet/editAnnouncements', " ", true);?>