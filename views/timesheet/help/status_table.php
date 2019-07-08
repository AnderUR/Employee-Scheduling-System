<p id="status_tableHelp">
    
    <img src="/LibServices/assets/ess_assets/img/helpIMG/status_table.png">
    
    <br><br><h3><b>Status Table</b></h3>
    The purpose of the status table is to be able to easily keep track of employees through the various library departments. 
    It is sorted by location first, then by employee earliest due time.<br>
	
    Note that because the status table can be set for future dates, it can serve as a quick method for viewing employee schedules per day. 
    For example, it can be used to tell who will be opening or closing a certain department tomorrow. 
    
    <br><br><h4><b>Shifts Column</b></h4>
    The status table allows the supervisor to make changes to <b>one shift</b> at a time. <b>This is the only method for modifying only one of a repeating set of scheduled shifts</b>.
    As mentioned, future shifts can be viewed, and thus edited.<br><br>
    There are two scenarios when clicking in the Edit link for a given employee.<br> 
    1. For employee shifts with status NA: <b>Opens the Edit Shift form</b> so that the supervisor can modify this scheduled shift before the employee signs in or substitute the employee.<br>  
    2. For employees that have signed in: <b>Opens the Edit Timesheet form</b>. The supervisor can then edit the hours that will be calculated towards the given employee's timesheet. 
    
    <br><br><h4><b>Status Column</b></h4>
    There are various status labels used in this column and each has a given rule, as explained below:
    <br>
    <b>Due: </b>Displayed if the employee is due in 15 minutes.<br>
    <b>On-time: </b>Displayed if the rounded signed in time is equal to the employee's due time.<br>
    Example: Employee signs in at 12:03 and her due time is 12:00. Following the rule of rounding to the nearest 7, 12:03 is rounded to 12:00, which is equal to their due time of 12:00.<br>
    <b>Late: </b>Displayed if the employee did not sign in at their due time.
    <b>Special: </b>Displayed if the supervisor used the <b>Set in Timesheet</b> form to mark the employee as signed in.
    
    <br><br><h4><b>Scheduled Column</b></h4>
    Shows the due times for employees as scheduled by the supervisor using the add shift form, or by having edited an existing shift.
    
    <br><br><h4><b>Start & End Columns</b></h4>
    These columns show the time the employees signed in/out, or "-" if there is no data available yet.

</p>