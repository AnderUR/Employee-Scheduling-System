<style>
    .innerContainer {
        margin-top: 2em;
        margin-bottom: 2em;
    }
    .announcementSpan {
        display: block;
        font-family: Candara;
        font-size: 18px;
        text-align: center;
    }
    .announcementTitle {
        font-family: Candara;
        font-size: 28px;
    }
</style>

<br>
<div class="row small-10 columns">

    <div class="tableMenuWrapper boxShadow row">
        <h3 class="shifts_userName">Announcements</h3>
    </div>

    <div id="emplShiftsOuterContainer" class="boxShadow row">

        <?php
        if (sizeof($announcements) == 0) {
            echo "<br><center>No Announcements (Yet!)</center>";
        }

        foreach ($announcements as $announcement) {
            ?>
            <div class="innerContainer small-12 columns">
                <div class="row">
                    <p>
                    <h1 class='announcementTitle text-center'><?= $announcement['title']; ?></h1>
                    <br><br><span class='announcementSpan'><?= $announcement['body']; ?></span>
                    </p>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<br>