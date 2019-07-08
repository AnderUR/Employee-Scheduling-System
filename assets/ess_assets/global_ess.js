$(document).ready(function () {
  const baseUrl = document.location.origin + '/LibServices/index.php';
   // alert("global_ess");
  const isAdmin = $("#isAdmin").text();
//console.log(isAdmin);

    /*** functions ***/

    function showTime() {
        var curTime = new Date();
        var hour = curTime.getHours();
        var min = curTime.getMinutes();
        var sec = curTime.getSeconds();
        $(".showTime").text(hour + ":" + min + ":" + sec); //uses OpSys to determine time format
    }

    function addFloatThead(tableLocation, tableWrapperClass) {
        var $table = $(tableLocation);
        $table.floatThead({
            scrollContainer: function scrollContainer($table) {
                return $table.closest(tableWrapperClass);
            }
        });
    }

    function insertDatePicker(className) {
        $(className).datepicker({
            dateFormat: 'yy-mm-dd'
        });
    }

    function insertTimepicker(className) {
        $(className).timepicker({
            controlType: 'select',
            oneLine: true,
            stepMinute: 15,
            timeFormat: 'HH:mm',
            hourMin: 7,
            hourMax: 22
        });
    }

    function enableFields(formLoc, fieldToEnable) {
        $(formLoc).find(fieldToEnable).prop('disabled', false);
    }

    function disableFields(formLoc, fieldToDisable) {
        $(formLoc).find(fieldToDisable).prop('disabled', true);
    }

    var Help = {

        openHelpReveal: function (helpModal_id) {
            $(".reveal_help").prop('id', helpModal_id);
            $("#" + helpModal_id).foundation('open');
        },

        fillHelpReveal: function (whatHelp) {
            $.post(baseUrl + '/timesheet/helpMenu', {helpIdentifier: whatHelp}, function (helpHtml) {
                $("#helpContainer").html(helpHtml);
            })
            .fail (function(response) {
              console.error("Help menu ajax failed!");
              console.log(response);
            });
        }
    };

    /*** Ajax functions ***/

    /* $.post(baseUrl + "/timesheet/currentUser") //get current from timesheet controller
     .done(function(currentUser) {
     user = jQuery.parseJSON(currentUser); // current user uid: "##", privilege: true/false
     //console.log(user);
     console.log(user.privilege);
     })
     .fail(function(currentUser){
     console.log("Ajax to get the current user failed");
     console.log(currentUser);
     })
     .always(function(){
     console.log("Ajax for getting current user finished");
     });
     */

    var increment = 0;
    function semesterExceptionPost(addDateException) {
        $(".loadActivityICO").toggle();

        $.post(baseUrl + '/timesheet/addExceptionDate', {addException: addDateException})
                .done(function (response) {

                    var addExceptionClone = $(".addExceptions").clone();
                    var removeButton = '<div class="large-2 columns"><button class="delete buttonMarginTop default button" type="submit" name="exceptionID" value="exceptionID">Remove</button></div>';

                    addExceptionClone.find(".sectionTitle").remove();
                    addExceptionClone.find(".noReplaceDateContainer").remove();
                    addExceptionClone.find(".semesterReplaceWith").removeClass('semesterReplaceWith');
                    addExceptionClone.find("#addExceptionContainer").replaceWith(removeButton);

                    $($('form').last()).after(addExceptionClone); //inserts after last form

                    var clonedException = "clonedException" + increment;
                    $('form').last().attr('class', clonedException);

                    var clonedExceptionClass = $('form').last(); //returns the last form, which can be used as the form class
                    disableFields(clonedExceptionClass, ".dateField");

                })
                .fail(function (response) {
                    alert("Failed to submit. Reload the page and try again or contact LSS and ask them to check the console log for any errors");
                    console.log("Failed to add Exception");
                    console.log(response);
                })
                .always(function () {
                    $(".loadActivityICO").toggle();
                    console.log("Ajax completed for exception");
                });

        increment++;
    }

    function removeExceptionAjax(dbFormException, removeException) {
        $.post(baseUrl + '/timesheet/removeExceptionDate', {exceptionID: removeException})
                .done(function () {
                    dbFormException.remove();
                })
                .fail(function (response) {
                    console.log("Ajax for remove exception failed. Response:");
                    console.log(response);
                })
                .always(function (response) {
                    $(".loadActivityICO").toggle();
                    //console.log(response);
                    console.log("Ajax for remove exception finished");
                });
    }

    function viewExceptionAnnouncementAjax(exceptID) {
        $.post(baseUrl + '/timesheet/viewExceptionAnnouncement', {exceptionID: exceptID})
                .done(function (announcementViewResponse) {
                    $("#editExcpAnnView").html(announcementViewResponse);
                })
                .fail(function (announcementViewResponse) {
                    console.log("Ajax for view exception announcement failed. Response:");
                    console.log(announcementViewResponse);
                })
                .always(function (announcementViewResponse) {
                    $(".loadActivityICO").toggle();
                    //console.log(response);
                    console.log("Ajax for view exception announcement finished");
                });
    }

    function semesterRevealAjax(id) {
        $.post(baseUrl + "/timesheet/viewEditSemester", {semesterID: id}, function (response) {
            $("#editSemesterReveal").html(response);
        })
                .done(function () {
                    insertDatePicker(".dateField");

                    $(".noreplaceDate").on('change', function () {
                        $(".semesterReplaceWith").datepicker('disable');
                        var boolCheck = $('input[type=checkbox]').prop('checked');

                        if (boolCheck) {
                            disableFields(".addExceptions", ".semesterReplaceWith");
                        } else {
                            $(".semesterReplaceWith").datepicker('enable');
                            enableFields(".addExceptions", ".semesterReplaceWith");
                        }
                    });
                })
                .fail(function (response) {
                    console.log("Ajax Failed for the semester reveal. Response:");
                    console.log(response);
                })
                .always(function () {
                    $(".loadActivityICO").toggle();
                    console.log("Ajax for the semester reveal completed");
                });
    }

    function semesterScheduleEditAjax(scheduledID) {
        $.post(baseUrl + "/timesheet/viewEditSchedule", {emplScheduledID: scheduledID}, function (schEditForm_response) {
            $("#editSchReveal").html(schEditForm_response);
        })
                .done(function () {
                    insertDatePicker(".dateField");
                    insertDatePicker(".recursiveDateField");
                    insertTimepicker(".startTime");
                    insertTimepicker(".endTime");
                })
                .fail(function (schEditForm_response) {
                    console.log("Ajax for semester schedule edit reveal failed. Response:");
                    console.log(schEditForm_response);
                })
                .always(function () {
                    $(".loadActivityICO").toggle();
                    console.log("Ajax for semester schedule edit reveal finished");
                });
    }

    function editDayScheduleAjax(daySchData) {
        $.post(baseUrl + "/timesheet/viewEditDaySchedule", {emplDaySchData: daySchData}, function (schEditForm_response) {
            if(daySchData.signedInBool == "false")  {
              $("#editDaySchReveal").html(schEditForm_response);
            } else {
              //console.log(daySchData.signedInBool);
              $("#editSignedInSchReveal").html(schEditForm_response);
            }
        })
                .done(function () {
                    insertDatePicker(".dateField");
                    insertTimepicker(".startTime");
                    insertTimepicker(".endTime");
                })
                .fail(function (schEditForm_response) {
                    console.log("Ajax for day schedule edit reveal failed. Response:");
                    console.log(schEditForm_response);
                })
                .always(function () {
                    $(".loadActivityICO").toggle();
                    console.log("Ajax for day schedule edit reveal finished");
                });
    }

    function setInTimesheetAjax(idObj) {
        $.post(baseUrl + "/timesheet/viewSetInTimesheet", {emplID_schID: idObj}, function (setInTimesheetView) {
            $("#myTest").html(setInTimesheetView);
        })
                .done(function () {
                    $("#revealDaySchEditForm").prop('class', "small reveal");
                    $("#editShiftHelp").remove(); //Need to remove help menu of editShift, else it will appear in the set in timesheet form
                    insertDatePicker(".dateField");
                    insertTimepicker(".startTime");
                    insertTimepicker(".endTime");
                })
                .fail(function (setInTimesheetView) {
                    console.log("Ajax for set in timesheet failed. Response:");
                    console.log(setInTimesheetView);
                })
                .always(function () {
                    $(".loadActivityICO").toggle();
                    console.log("Ajax for set in timesheet finished");
                });
    }

    function tSheetEditFormAjax(shiftID) {
        $.post(baseUrl + "/timesheet/viewEditTimesheet", {shiftWorkedID: shiftID}, function (tSheetEditForm_response) {
            $("#editTSheetReveal").html(tSheetEditForm_response);
        })
                .done(function () {
                    //remove link to go back to schedule
                    if(document.location.href.indexOf('statusIndex') == -1) {
                        $("#backToEditSch").remove();
                    }
                    insertDatePicker(".dateField");
                    insertTimepicker(".startTime");
                    insertTimepicker(".endTime");
                })
                .fail(function (tSheetEditForm_response) {
                    console.log("Ajax for edit timesheet reveal failed. Response:");
                    console.log(tSheetEditForm_response);
                })
                .always(function () {
                    $(".loadActivityICO").toggle();
                    console.log("Ajax for edit timesheet reveal finished");
                });
    }

    function scheduleSubstituteAjax(arrayOfIds) {
        $.post(baseUrl + "/timesheet/scheduleSubstitute", {emplID_schID: arrayOfIds}, function (response) {
            window.location = baseUrl + "/timesheet/statusindex/" + response;
        })
                .fail(function (response) {
                    console.log("Failed to communicate IDs. Response:");
                    console.log(response);
                })
                .always(function () {
                    $(".loadActivityICO").toggle();
                });
    }

    /*End of functions*/


    /*** Help Menu Ini ***/

    $(document).on('click', ".helpMenu", function () {
        var whatHelp = $(this).attr('data-what-help');
        Help.openHelpReveal(whatHelp);
        Help.fillHelpReveal(whatHelp);

    });

    $("#iniHelp").on('click', function () {
        if ($(".helpMenu").is(":visible")) {
            $("#iniHelp").html('<img src="/LibServices/assets/ess_assets/img/helpICO.png">Show help');
        } else {
            $("#iniHelp").html('<img src="/LibServices/assets/ess_assets/img/helpICO.png">Hide help');
        }
        $(".helpMenu").toggle();
        $('table.stickyHeader').trigger('reflow');
    });


    /*** Hide tables with #, representing undefined columns ***/

    if (($('table').length) && $("#theadi").val() !== undefined) {
        //alert($('th').index("#theadi"));
        //$('th:nth-child(1)').hide();
        $('td:nth-child(1),th:nth-child(1)').hide();
        $(".theadi").hide();
    }


    /*** Hide activity icon for all pages ***/

    $(".loadActivityICO").hide();


    /*** Add form confirm: Applied to all type="submit" elements ***/

    $(document).on('submit', 'form[data-confirm]', function (e) {
        if (!confirm($(this).data('confirm'))) {
            e.stopImmediatePropagation(); //stops bubbling up the document; no other event handelrs will be triggered
            e.preventDefault(); //prevents event handlers of the button to trigger
        }
    });


    /*** Add datepickers and timepickers ***/

    $("#srchWeekDate").datepicker({
        dateFormat: 'yy-mm-dd',
        onSelect: function () {
            var dateSelectedFormat = $.datepicker.formatDate("yy-mm-dd", $(this).datepicker("getDate")); //First parameter formats, second gets selected date from datepicker
            var newURL = "";
            var currentURL = window.location.href;

            if (currentURL.indexOf("20") !== -1) { //Logic: 20 is used because the year 2017, 2018... etc, will appear in url.
                var noDateURL = currentURL.substring(0, currentURL.indexOf("20")); //Remove all characters after 20
                newURL = noDateURL + dateSelectedFormat;
                window.location = newURL;
            } else {
                newURL = currentURL + "/" + dateSelectedFormat;
                window.location = newURL;
            }
        }
    });

    insertDatePicker(".dateField");
    insertDatePicker(".recursiveDateField");
    insertTimepicker(".startTime");
    insertTimepicker(".endTime");


    /*** start time of day ***/

    setInterval(showTime, 1000); //Refresh timer every 1000ms


    /*** Add floathead to tables ***/

    if ((window.location.href.indexOf("manageEmployee") !== -1) || (window.location.href.indexOf("adminPanel") !== -1)) {
        //If the height of the table is greater than 350px, set height to 350px and overflow (height and overflow are not set before this runs).
        if ($(".table-wrapper").height() > 450) {
            $(".table-wrapper").css({"height": "450px"});
            $(".table-wrapper").css({"overflow-y": "scroll"});
            addFloatThead("table.stickyHeader", ".table-wrapper");  //Initialize inner floatThead on existing tables
        }
    } else { //Window scroll floatthead
        var $table = $('table.stickyHeader');
        $table.floatThead({
            responsiveContainer: function ($table) {
                return $table.closest('.table-scroll');
            }
        });
    }


    /*** Visitted tabs remembered **/

    var selectTab = sessionStorage.getItem('focusedTab');
    if ((selectTab != null) && ((window.location.href.indexOf("manageEmployee") !== -1) || (window.location.href.indexOf("adminPanel") !== -1))) {
        //alert(selectTab);
        $("#" + selectTab).click();
        $('table.stickyHeader').trigger('reflow'); //Foundation tabs hide content, which interferes with attaching floathead event handlers. Refresh floatthead when content becomes visible (when a tab is clicked)
    } else {
        sessionStorage["focusedTab"] = null;
    }

    //Direct user to the correct tab: When view link is clicked from the scheduleIndex, or timesheetIndex, set the session variable equal to the corresponding tab. The tab id names are located in the manageEmployee view
    $(".referenceForTabFocus").on('click', function () {
        if (window.location.href.indexOf('scheduleIndex') !== -1) {
            sessionStorage["focusedTab"] = "schedule_tab1";
        } else {
            sessionStorage["focusedTab"] = "timesheet_tab2";
        }
    });

    $(".tabs-title").on('click', function () {
        var $idOfTabLink = $(this).find('a').prop('id');
        sessionStorage["focusedTab"] = $idOfTabLink;
        $('table.stickyHeader').trigger('reflow');
    });


    /*** Disable or enable recursive field based on checkbox ***/

    $(document).on('click', ".isRecursiveCheck", function () {
        if ($(this).is(':checked')) {
            enableFields($(this).closest('form'), ".recursiveDateField");
        } else {
            disableFields($(this).closest('form'), ".recursiveDateField");
        }
    });


    /*** Semester ***/

    $("#activeSemesterPicker").change(function () {
        var semesterID = $(this).val();
        var emplID = $("#emplID").val();
        window.location = baseUrl + "/timesheet/setSemester/" + semesterID + "/" + emplID;
    });

    $(document).on('click', ".semesterReveal", function () {
        $(".loadActivityICO").toggle();
        var id = $(this).closest('td').children('span.shiftID').text();
        //console.log(id);
        semesterRevealAjax(id);
    });


    /*** Exceptions ***/

    $(document).on('click', ".editExcpAnnReveal", function () {
        var exceptID = $(this).closest('form').find("input[name*='exceptionID']").val();
        viewExceptionAnnouncementAjax(exceptID);
    });

    $(document).on('click', "#addException", function (e) {
        e.preventDefault();
        var addDateException = $(".addExceptions").serializeArray();
        semesterExceptionPost(addDateException);
    });

    $(document).on('click', ".removeException", function (e) {
        e.preventDefault();
        $(".loadActivityICO").toggle();
        var dbFormException = $(this).closest('form');
        var removeException = dbFormException.find('input').last().val();
        removeExceptionAjax(dbFormException, removeException);
    });

//Announcement exception

    /*$(document).on('click', ".viewException", function() {

     });
     */
    /*** Reveal semester schedule edit form ***/

    $(document).on('click', ".revealSchEditForm", function () {
        $(".loadActivityICO").toggle();
        var scheduledID = $(this).closest('td').children('span.shiftID').text(); //the span nearest to the clicked link
        semesterScheduleEditAjax(scheduledID);
    });


    /*** Reveal day schedule edit form ***/

    $(document).on('click', ".revealDaySchEditForm, .revealEditSignedInSch", function () {
        $(".loadActivityICO").toggle();
        var daySchData;
        if($(this).attr('id') == "backToEditSch") {
          daySchData = {scheduledID:$("input[name=scheduleID]").val(), signedInBool:"false" } //the span nearest to the clicked link
          console.log( $("input[name=scheduleID]").val() );
        } else if($(this).attr('class') == "revealDaySchEditForm") {
          daySchData = {scheduledID:$(this).closest('td').children('span.shiftID').text(), signedInBool:"false" } //the span nearest to the clicked link
          //console.log($(this).attr('class'));
        } else {
          daySchData = {scheduledID:$("input[name=scheduleID]").val(), signedInBool:"true" } //the span nearest to the clicked link
          //console.log($(this).attr('class'));
        }
          editDayScheduleAjax(daySchData);
    });


    /*** Reveal day schedule edit setInTimesheet ***/

    $(document).on('click', "#markTimesheet", function () {
        //var caID = $( "input[name=caID]" ).val();
        //var id = $("input[name=id]").val();
        $(".loadActivityICO").toggle();
        var idObj = {id: $("input[name=id]").val(), caID: $("input[name=caID]").val()};
        setInTimesheetAjax(idObj);
    });


    /*** Reveal status edit form ***/

    $(document).on('click', ".revealTSheetForm", function () {
        $(".loadActivityICO").toggle();
        var shiftID = $(this).closest('td').children('span.shiftID').text(); //the span nearest to the clicked link
        tSheetEditFormAjax(shiftID);
    });


    /***ALL AUTOCOMPLETE BELOW***/

    if (isAdmin === "true") {

        var users = [""];
        var usersNoID = [""];
        var usersID = [""];

        // Arrays used for all autocomplete

        $.post(baseUrl + "/timesheet/allUsers") //get all users from timesheet controller
                .done(function (response_users) {
                    $(".loadActivityICO").toggle();
                    users = jQuery.parseJSON(response_users); // all users, includes name, ID and email
                    
                    for (var i = 0; i < users.length; i++) {
                        usersNoID[i] = users[i].substr(0, users[i].lastIndexOf(",")); //all users with name and email, but without ID
                        usersID[i] = users[i].substr(users[i].lastIndexOf(",") + 1); //only user IDs in this array
                    }
                })
                .fail(function (response_users) {
                    console.log("Ajax to get all users failed");
                    console.log(response_users);
                })
                .always(function () {
                    $(".loadActivityICO").toggle();
                    console.log("Ajax for getting all users finished");
                });


        /*** Autcomplete for Employee page (manageEmployee view) ***/
        //autocomplete: when input is focused, apply autocomplete ui
        $(document).on('focus', "#emplSrchInpt", function () {
            $("#users").autocomplete({
                source: usersNoID,
                //When autocomplete option is selected, the select below will trigger.
                /*  select: function(event, ui) {
                 //For testing purposes: This alert outputs a number representing the position of the selected user in the array usersNoID. Currently, if I selected Anderson, it will alert 6, because Anderson is in position 7, since arrays start at 0.
                 alert($.inArray(ui.item.value, usersNoID));
                 }
                 */
            });
        });

        var selectedIndex = -2;
        //When a user is selected from the autocomplete list, selectedIndex will equal the index in the array for that user. This index corresponds to the user id
        $(document).on('autocompleteselect', "#emplSrchInpt", function (event, ui) {
            selectedIndex = ($.inArray(ui.item.value, usersNoID)); //Example, if it was Anderson, who currently lies in index 7 in the usersNoID array, selectedIndex will equal 6.
            //alert($.inArray(ui.item.value, usersNoID)); //Testing selectedIndex, this should print 6, by example above.
            //alert(usersID[selectedIndex]); //This should print the correct id for the user selected from the autocomplete field

            /*** After user was selected from autocomplete ***/

            var selectedEmpl = usersID[selectedIndex];
            var emplID = selectedEmpl.substr(1);  //Issue example: emplID returns +19, instead of 19. This line removes the +.
            window.location = baseUrl + "/timesheet/manageEmployee/" + emplID; //Redirect with employee id to given page
        });


        /*** Autocomplete for edit shift (substitute) ***/

        //autocomplete: when input is focused, apply autocomplete ui
        $(document).on('focus', ".editSrchInpt", function () {
            $("#subsitute").autocomplete({
                source: usersNoID,
            });
        });

        var selectedIndex = -2;
        $(document).on('autocompleteselect', ".editSrchInpt", function (event, ui) {
            var substituteConfirm = confirm("The selected employee will subsitute this shift(s) for the current scheduled employee. Do you wish to proceed?");
            if (substituteConfirm == true) {
                $(".loadActivityICO").show();
                selectedIndex = ($.inArray(ui.item.value, usersNoID));
                var selectedEmpl = usersID[selectedIndex];
                var arrayOfIds = {id: $("input[name='id']").val(), schedule_substitute_employee_id: selectedEmpl.substr(1), substituteID: $(this).prop('id')};
                scheduleSubstituteAjax(arrayOfIds);
            }
        });
    }
});//end of file
