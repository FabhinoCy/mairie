fetch("https://calendrier.api.gouv.fr/jours-feries/metropole.json")
    .then((response) => response.json())
    .then(function (data) {
        console.log(data)
    })


$(function () {
    var parametreFilter = "x";

    $("#calendar").fullCalendar({
        header: {
            left: "prev, next today",
            center: "title",
            right: "month, agendaWeek, agendaDay"
        },
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
            // your event source
            function (start, end, timezone, callback) {
                fetch("https://calendrier.api.gouv.fr/jours-feries/metropole.json")
                    .then((response) => response.json())
                    .then(function (data) {
                        let holidays = [];

                        for (const day in data) {
                            console.log(`${day}: ${data[day]}`);
                            holidays.push({
                                title: data[day],
                                start: day,
                                allDay: true
                            });
                        }
                        callback(holidays);
                    });
            },
            {
                url: "toto",
                type: "GET",
                data: {
                    param: parametreFilter
                }
            }
        ]
    });
});