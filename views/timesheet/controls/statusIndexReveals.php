
<div id="editForm"  class="row">
    <div class="editFormTitle_setInTimesheet">Set in timesheet: <span class="shifts_userName"><?= $ca->getProperties()['first_name'] . " " . $ca->getProperties()['last_name']?></span></div>
    	<div class="small-12 small-centered columns">
        <br>     
        <fieldset id="editShiftContainer" class="large-12 medium-12 small-12 small-centered columns shiftFields">
            <div class="large-3 columns">
                <label class="inputLabel" for="dateField">Date</label> 
                <input class="dateField text-center" type="text" name="scheduledDate" value="<?= date('Y-m-d', strtotime($schedule->getScheduledDate())); ?>"></input>
            </div>

            <div class="large-3 columns">
                <label class="inputLabel" for="startTime">Start</label> 
                <input class="startTime text-center" type="text" name="startTime" value="<?= date('H:i', strtotime($schedule->getStartTime())); ?>"></input>
            </div>

            <div class="large-3 columns">
                <label class="inputLabel" for="endTime">End</label> 
                <input class="endTime text-center" name="endTime" value="<?= date('H:i', strtotime($schedule->getEndTIme())); ?>" type="text"></input>
            </div>

            <div class="large-3 columns">
                <label class="inputLabel" for="location">Location</label> 
                <select class="location" name="scheduledLocation_id">
                    <?php foreach (shift::getLocations() as $location): ?>
                        <option value="<?= $location['id']; ?>"  
                        <?php
                        if ($location['id'] == $schedule->getLocationID()) {
                            echo 'selected="selected"';
                        }
                        ?> > 
                                <?= $location['locationText']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

        </fieldset>
        
		<div class="columns">		
	       	<div id="editShiftButtonContainer" class="large-2 columns">
	        	<button id="markTimesheet" class="nowrap default button addShift" type="submit" name="markTimesheet">Mark as worked</button>
	        </div>
		    <div class="large-4 columns">
		        <button id="supervisorSignin" class="default button editSchMarkTimesheet addShift nowrap" type="submit" name="supervisorSignin">Mark as signed in</button>
		    </div> 
        </div>  
                                          
    </div>
</div>

