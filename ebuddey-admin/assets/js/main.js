$(function(){

    var currentDate; // Holds the day clicked when adding a new event
    var currentEvent; // Holds the event object when editing an event
    
    var base_url='http://localhost/fullcalendar/'; // Here i define the base_url

    // Fullcalendar
    $('#calendar').fullCalendar({
        header: {
            left: 'prev, next, today',
            center: 'title',
             right: 'month, basicWeek, basicDay'
        },
        // Get all events stored in database
        eventLimit: true, // allow "more" link when too many events
        events: adminurl+'buskerspod/getEvents',
        selectable: true,
        selectHelper: true,
        editable: true, // Make the event resizable true           
            select: function(start, end) {
                
                $('#startDate').val(moment(start).format('YYYY-MM-DD'));
                $('#endDate').val(moment(start).format('YYYY-MM-DD'));
                 // Open modal to add event
            modal({
                // Available buttons when adding
                buttons: {
                    add: {
                        id: 'add-event', // Buttons id
                        css: 'btn-success', // Buttons class
                        label: 'Add' // Buttons label
                    }
                },
                title: 'Add Event' // Modal title
            });
            }, 
           
         eventDrop: function(event, delta, revertFunc,start,end) {  
            
            start = event.start.format('YYYY-MM-DD');
            if(event.end){
                end = event.end.format('YYYY-MM-DD');
            }else{
                end = start;
            }         
                       
               $.post(adminurl+'buskerspod/dragUpdateEvent',{                            
                id:event.id,
                start : start,
                end :end
            }, function(result){
                $('.alert').addClass('alert-success').text('Event updated successfuly');
                hide_notify();
            });
          },
          eventResize: function(event,dayDelta,minuteDelta,revertFunc) { 
                    
            start = event.start.format('YYYY-MM-DD');
            if(event.end){
                end = event.end.format('YYYY-MM-DD');
            }else{
                end = start;
            }         
                       
            $.post(adminurl+'buskerspod/dragUpdateEvent',{
                id:event.id,
                start : start,
                end :end
            }, function(result){
                $('.alert').addClass('alert-success').text('Event updated successfuly');
                hide_notify();

            });
          },
          
        // Event Mouseover
        eventMouseover: function(calEvent, jsEvent, view){

            var tooltip = '<div class="event-tooltip">' + calEvent.message + '</div>';
            $("body").append(tooltip);

            $(this).mouseover(function(e) {
                $(this).css('z-index', 10000);
                $('.event-tooltip').fadeIn('500');
                $('.event-tooltip').fadeTo('10', 1.9);
            }).mousemove(function(e) {
                $('.event-tooltip').css('top', e.pageY + 10);
                $('.event-tooltip').css('left', e.pageX + 20);
            });
        },
        eventMouseout: function(calEvent, jsEvent) {
            $(this).css('z-index', 8);
            $('.event-tooltip').remove();
        },
        // Handle Existing Event Click
        eventClick: function(calEvent, jsEvent, view) {
            // Set currentEvent variable according to the event clicked in the calendar
            currentEvent = calEvent;

            // Open modal to edit or delete event
            modal({
                // Available buttons when editing
                buttons: {
                    delete: {
                        id: 'delete-event',
                        css: 'btn-danger',
                        label: 'Delete'
                    },
                    update: {
                        id: 'update-event',
                        css: 'btn-success',
                        label: 'Update'
                    }
                },
                title: 'Edit Event "' + calEvent.title + '"',
                event: calEvent
            });
        }

    });

    // Prepares the modal window according to data passed
    function modal(data) {
        // Set modal title
        $('.modal-title').html(data.title);
        // Clear buttons except Cancel
        $('.modal-footer button:not(".btn-default")').remove();
        // Set input values
        console.log(data);
        $('#partner_id').val(data.event ? data.event.partner_id : '');        
        $('#message').val(data.event ? data.event.message : '');
        $('#startDate').val(data.event ? data.event.start.format('YYYY-MM-DD') : '');
        $('#endDate').val(data.event ? data.event.start.format('YYYY-MM-DD') : '');
        $('#startTime').val(data.event ? data.event.message : '');
        $('#endTime').val(data.event ? data.event.message : '');
        // Create Butttons
        $.each(data.buttons, function(index, button){
            $('.modal-footer').prepend('<button type="button" id="' + button.id  + '" class="btn ' + button.css + '">' + button.label + '</button>')
        })
        //Show Modal
        $('.event_modal').modal('show');
    }

    // Handle Click on Add Button
    $('.event_modal').on('click', '#add-event',  function(e){
        if(validator(['partner_id', 'message'])) {
            $.post(adminurl+'buskerspod/addEvent', {
                partner_id: $('#partner_id').val(),
                host_id: $('#host_id').val(),
                startDate: $('#startDate').val(),
                endDate: $('#endDate').val(),
                startTime: $('#startTime').val(),
                endTime: $('#endTime').val(),
                message: $('#message').val()
            }, function(result){
                $('.alert').addClass('alert-success').text('Event added successfuly');
                $('.event_modal').modal('hide');
                $('#calendar').fullCalendar("refetchEvents");
                hide_notify();
            });
        }
    });
    // Handle click on Update Button
    $('.event_modal').on('click', '#update-event',  function(e){
        if(validator(['partner_id', 'message'])) {
            $.post(adminurl+'buskerspod/updateEvent', {
                id: currentEvent._id,
                partner_id: $('#partner_id').val(),
                host_id: $('#host_id').val(),
                startDate: $('#startDate').val(),
                endDate: $('#endDate').val(),
                startTime: $('#startTime').val(),
                endTime: $('#endTime').val(),
                message: $('#message').val()
            }, function(result){
                $('.alert').addClass('alert-success').text('Event updated successfuly');
                $('.event_modal').modal('hide');
                $('#calendar').fullCalendar("refetchEvents");
                hide_notify();
                
            });
        }
    });

    // Handle Click on Delete Button
    $('.event_modal').on('click', '#delete-event',  function(e){
        $.get(adminurl+'buskerspod/deleteEvent?id=' + currentEvent._id, function(result){
            $('.alert').addClass('alert-success').text('Event deleted successfully !');
            $('.event_modal').modal('hide');
            $('#calendar').fullCalendar("refetchEvents");
            hide_notify();
        });
    });

    function hide_notify()
    {
        setTimeout(function() {
            $('.alert').removeClass('alert-success').text('');
        }, 2000);
    }

    // Dead Basic Validation For Inputs
    function validator(elements) {
        var errors = 0;
        $.each(elements, function(index, element){
            if($.trim($('#' + element).val()) == '') errors++;
        });
        if(errors) {
            $('.error').html('Please insert title and message');
            return false;
        }
        return true;
    }
});