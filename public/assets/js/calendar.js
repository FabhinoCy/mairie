document.addEventListener('DOMContentLoaded', function () {

    let calendarEl = document.getElementById('calendar');
    let calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        locale: 'fr',
        eventSources: [
            {
                url: fc_event_url,
                method: "POST",
                extraParams: {
                    filters: JSON.stringify({})
                },
                failure: () => {
                    // alert("There was an error while fetching FullCalendar!");
                },
            },
            function (info, successCallback, failureCallback) {
                let events = [];
                fetch("https://calendrier.api.gouv.fr/jours-feries/metropole.json")
                    .then((response) => response.json())
                    .then((data) => {
                        for (const day in data) {
                            events.push({
                                title: data[day],
                                start: day,
                                color: "#4B9CA1",
                                textColor: "white",
                            });
                        }
                        successCallback(events);
                    }).catch((error) => {
                        failureCallback();
                    }
                );
            }
        ]
    });
    calendar.render();
});