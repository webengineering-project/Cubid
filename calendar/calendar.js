function toggleEventForm(event) {
    var titleInput = document.getElementById('title');
    var descriptionInput = document.getElementById('description');
    var locationInput = document.getElementById('location');
    var dateInput = document.getElementById('datePicker');
    var startTimeInput = document.getElementById('start-time');
    var endTimeInput = document.getElementById('end-time');
    var isPrivateCheckbox = document.getElementById('is-private');
    var sendEmailCheckbox = document.getElementById('send-email');
    var gameSelect = document.getElementById('game-filter');

    titleInput.value = "";
    descriptionInput.textContent = "";
    locationInput.value = "";

    startTimeInput.value = "";
    endTimeInput.value = "";
    isPrivateCheckbox.checked = false;
    gameSelect.value = 0;
    dateInput.value = "";
    document.getElementById('additional-fields').style.display = 'block';

    if (event === undefined) {
        document.getElementById('updateButton').style.display = 'none';
        document.getElementById('deleteButton').style.display = 'none';
        document.getElementById('createButton').style.display = 'block';
        titleInput.disabled = false;
        descriptionInput.disabled = false;
        locationInput.disabled = false;
        dateInput.disabled = false;
        startTimeInput.disabled = false;
        endTimeInput.disabled = false;
        isPrivateCheckbox.disabled = false;
        document.getElementById('send-email-checkbox').disabled = false;
        sendEmailCheckbox.disabled = false;
        gameSelect.disabled = false;

        var form = document.getElementById('event-form');
        var container = document.querySelector('.event-container');
        var button = document.querySelector('.event-button');
        var computedStyle = window.getComputedStyle(form);
        var body = document.body;

        if (computedStyle.display === 'none' || computedStyle.display === '') {
            form.style.display = 'flex';
            container.classList.add('open');
            button.classList.add('open');
            body.style.overflow = 'hidden';
        } else {
            form.style.display = 'none';
            container.classList.remove('open');
            button.classList.remove('open');
            body.style.overflowY = 'scroll';
        }
    } else {
        document.getElementById('createButton').style.display = 'none';
        const updateButton = document.getElementById('updateButton');
        updateButton.style.display = 'block';
        const deleteButton = document.getElementById('deleteButton');
        deleteButton.style.display = 'block';

        document.getElementById("event-form-overlay-header-title").textContent = "Event bearbeiten";


        var begin = event.className.split('start-')[1].split(' ')[0];
        var end = event.className.split('end-')[1].split(' ')[0];
        var color = event.className.split('color-')[1].split(' ')[0];
        var title = event.dataset.title;
        var description = event.dataset.description;
        var location = event.dataset.location;
        var private = event.dataset.private;
        var sendEmail = event.dataset.sendEmail;
        var gameId = event.dataset.game;
        var isOwner = event.dataset.isowner;
        var date = event.dataset.date;

        console.log("Owner: " + isOwner);
        if (isOwner === "false"){
            document.getElementById('deleteButton').style.display = 'none';
            document.getElementById('updateButton').style.display = 'none';
            titleInput.disabled = true;
            descriptionInput.disabled = true;
            locationInput.disabled = true;
            dateInput.disabled = true;
            startTimeInput.disabled = true;
            endTimeInput.disabled = true;
            isPrivateCheckbox.disabled = true;
            document.getElementById('send-email-checkbox').disabled = true;
            sendEmailCheckbox.disabled = true;
            gameSelect.disabled = true
        } else {
            document.getElementById('deleteButton').style.display = 'block';
            document.getElementById('updateButton').style.display = 'block';
            titleInput.disabled = false;
            descriptionInput.disabled = false;
            locationInput.disabled = false;
            dateInput.disabled = false;
            startTimeInput.disabled = false;
            endTimeInput.disabled = false;
            isPrivateCheckbox.disabled = false;
            document.getElementById('send-email-checkbox').disabled = false;
            sendEmailCheckbox.disabled = false;
            gameSelect.disabled = false;
        }

        var form = document.getElementById('event-form');
        titleInput.value = title;
        descriptionInput.textContent = description;
        locationInput.value = location;

        startTimeInput.value = begin;
        endTimeInput.value = end;
        isPrivateCheckbox.checked = private === '1';
        gameSelect.value = gameId;
        dateInput.value = date;

        if (isPrivateCheckbox.checked) {
            document.getElementById('additional-fields').style.display = 'none';
        }

        console.log(title, location);
        var container = document.querySelector('.event-container');
        var button = document.querySelector('.event-button');
        var computedStyle = window.getComputedStyle(form);
        var body = document.body;

        if (computedStyle.display === 'none' || computedStyle.display === '') {
            form.style.display = 'flex';
            container.classList.add('open');
            button.classList.add('open');
            body.style.overflow = 'hidden';
        } else {
            form.style.display = 'none';
            container.classList.remove('open');
            button.classList.remove('open');
            body.style.overflowY = 'scroll';
        }
        const formSender = document.getElementById('formSender');
        formSender.method = 'POST';


        const start = document.createElement('input');
        start.type = 'hidden';
        start.name = 'id';
        start.value = event.id.split(':')[1];
        formSender.appendChild(start);

        const weekstartInputHidden = document.createElement('input');
        weekstartInputHidden.type = 'hidden';
        weekstartInputHidden.name = 'weekstart';
        weekstartInputHidden.value = formatDateToString(weekDates[0]);
        formSender.appendChild(weekstartInputHidden);

        updateButton.addEventListener('click', function () {

            // Add all necessary inputs dynamically
            const eventIdInput = document.createElement('input');
            eventIdInput.type = 'hidden';
            eventIdInput.name = 'event_id';
            eventIdInput.value = event.id.split(':')[1]; // Assuming event.id contains the event ID
            formSender.appendChild(eventIdInput);

            const titleInputHidden = document.createElement('input');
            titleInputHidden.type = 'hidden';
            titleInputHidden.name = 'title';
            titleInputHidden.value = titleInput.value;
            formSender.appendChild(titleInputHidden);

            const descriptionInputHidden = document.createElement('input');
            descriptionInputHidden.type = 'hidden';
            descriptionInputHidden.name = 'description';
            descriptionInputHidden.value = descriptionInput.value;
            formSender.appendChild(descriptionInputHidden);

            const locationInputHidden = document.createElement('input');
            locationInputHidden.type = 'hidden';
            locationInputHidden.name = 'location';
            locationInputHidden.value = locationInput.value;
            formSender.appendChild(locationInputHidden);

            const startTimeInputHidden = document.createElement('input');
            startTimeInputHidden.type = 'hidden';
            startTimeInputHidden.name = 'start-time';
            startTimeInputHidden.value = startTimeInput.value;
            formSender.appendChild(startTimeInputHidden);

            const endTimeInputHidden = document.createElement('input');
            endTimeInputHidden.type = 'hidden';
            endTimeInputHidden.name = 'end-time';
            endTimeInputHidden.value = endTimeInput.value;
            formSender.appendChild(endTimeInputHidden);

            const isPrivateInputHidden = document.createElement('input');
            isPrivateInputHidden.type = 'hidden';
            isPrivateInputHidden.name = 'is-private';
            isPrivateInputHidden.value = isPrivateCheckbox.checked ? '1' : '0';
            formSender.appendChild(isPrivateInputHidden);

            const sendEmailInputHidden = document.createElement('input');
            sendEmailInputHidden.type = 'hidden';
            sendEmailInputHidden.name = 'send-email';
            sendEmailInputHidden.value = sendEmailCheckbox.checked ? '1' : '0';
            formSender.appendChild(sendEmailInputHidden);

            const gameSelectHidden = document.createElement('input');
            gameSelectHidden.type = 'hidden';
            gameSelectHidden.name = 'game-filter';
            gameSelectHidden.value = gameSelect.value;
            formSender.appendChild(gameSelectHidden);

            const dateInputHidden = document.createElement('input');
            dateInputHidden.type = 'hidden';
            dateInputHidden.name = 'date';
            dateInputHidden.value = dateInput.value;
            formSender.appendChild(dateInputHidden);

            formSender.action = 'update.php';
            formSender.submit();
        });

        deleteButton.addEventListener('click', function () {
            formSender.action = 'delete.php';
            formSender.submit();
        });
    }
}

document.getElementById('is-private').addEventListener('change', function () {
    var privateCheckbox = document.getElementById('is-private');
    var additionalFields = document.getElementById('additional-fields');

    if (privateCheckbox.checked) {
        additionalFields.style.display = 'none';
    } else {
        additionalFields.style.display = 'block';
    }
});

function populateTimeOptions() {
    var timeIntervals = [];
    for (var h = 0; h < 24; h++) {
        for (var m = 0; m < 60; m += 15) {
            var hour = (h < 10) ? '0' + h : h;
            var minute = (m < 10) ? '0' + m : m;
            timeIntervals.push(hour + '-' + minute);
        }
    }

    var startTimeSelect = document.getElementById('start-time');
    var endTimeSelect = document.getElementById('end-time');

    timeIntervals.forEach(function (time) {
        var option = document.createElement('option');
        option.value = time;
        option.text = time;
        startTimeSelect.appendChild(option);

        var optionClone = option.cloneNode(true);
        endTimeSelect.appendChild(optionClone);
    });
}

document.addEventListener('DOMContentLoaded', function () {
    var emailSearchInput = document.getElementById('email-search');
    var selectElement = document.getElementById('send-email');
    var options = selectElement.options;

    emailSearchInput.addEventListener('input', function () {
        var searchValue = emailSearchInput.value.toLowerCase();
        for (var i = 0; i < options.length; i++) {
            var email = options[i].text.toLowerCase();
            if (email.indexOf(searchValue) > -1) {
                options[i].style.display = '';
            } else {
                options[i].style.display = 'none';
            }
        }
    });

    const formSender = document.getElementById('formSender');
    formSender.addEventListener('submit', checkForm);

});

//Check if start time is before end time and date is in the future
function checkForm(event) {
    if (document.getElementById('start-time').value >= document.getElementById('end-time').value) {
        event.preventDefault();
        alert('Endzeitpunkt muss nach Startzeitpunkt liegen!');
    }
    if (new Date(document.getElementById('datePicker').value).setHours(0,0,0,0) < new Date().setHours(0,0,0,0)) {
        event.preventDefault();
        alert('Das Datum darf nicht in der Vergangenheit liegen!');
    }
}


window.onload = populateTimeOptions;

const daysInWeek = 7;
const currentWeekDates = getCurrentWeekDates();
let weekDates;

document.addEventListener('DOMContentLoaded', function () {
    if (!window.location.href.includes('weekStart')) {
        console.log("No week start in URL");
        weekDates = currentWeekDates;
        updateCalendarDates(currentWeekDates);
    } else {
        console.log(window.location.href.split('weekStart=')[1]);
        weekDates = getWeekDaysByStart(window.location.href.split('weekStart=')[1]);
        updateCalendarDates(weekDates);
    }

    //Prepare Click Event for click on displayed events

    const events = document.querySelectorAll('.event');
    events.forEach(event => {
        event.addEventListener('click', function () {
            console.log("Event clicked: " + event.id);
            console.log(event);
            toggleEventForm(event);
        });
    });
});

document.getElementById("calendar-button-prev-week").addEventListener("click", function () {
    weekDates = getPreviousWeekByCurrentStart(weekDates[0])
    updateCalendarDates(weekDates);
    console.log(weekDates)
    loadEvents();
});

document.getElementById("calendar-button-next-week").addEventListener("click", function () {
    weekDates = getNextWeekByCurrentStart(weekDates[0]);
    updateCalendarDates(weekDates);
    loadEvents();
});

document.getElementById("calendar-button-current-week").addEventListener("click", function () {
    weekDates = currentWeekDates;
    updateCalendarDates(weekDates);
    loadEvents();
})

function loadEvents() {

    console.log("asdf" + formatDateToString(weekDates[0]))
    const form = document.createElement('form');
    form.method = 'GET';
    form.action = 'index.php';

    // Ein unsichtbares Eingabefeld erstellen
    const start = document.createElement('input');
    start.type = 'hidden';
    start.name = 'weekStart';
    start.value = formatDateToString(weekDates[0]);

    // Eingabefeld zum Formular hinzufügen
    form.appendChild(start);

    // Formular zum Dokument hinzufügen und absenden
    document.body.appendChild(form);
    form.submit();
}


function getCurrentWeekDates() {
    const now = new Date();
    const dayOfWeek = now.getDay();
    const numDay = now.getDate();

    const startOfWeek = new Date(now);
    startOfWeek.setDate(numDay - dayOfWeek + (dayOfWeek === 0 ? -6 : 1));

    const weekDates = [];
    for (let i = 0; i < daysInWeek; i++) {
        const nextDate = new Date(startOfWeek);
        nextDate.setDate(startOfWeek.getDate() + i);
        weekDates.push(nextDate);
    }
    return weekDates;
}

function updateCalendarDates(dates) {
    document.getElementById("week-counter").textContent = "KW " + getKW();
    dates.forEach((date, index) => {
        document.getElementById(`date-${index + 1}`).textContent = date.getDate();
    });
}

function getWeekDaysByStart(startDateString) {
    const startDate = formatStringToDate(startDateString);
    const weekDays = [];

    for (let i = 0; i < 7; i++) { // 0 to 6 to include the start date and the next 6 days
        const nextDate = new Date(startDate);
        nextDate.setDate(startDate.getDate() + i);
        weekDays.push(nextDate);
    }

    return weekDays;
}


function getNextWeekByCurrentStart(startDate) {
    const nextWeekStartDate = new Date(startDate);
    nextWeekStartDate.setDate(startDate.getDate() + 7); // Move to the same day of the next week

    const nextWeekDays = [];
    for (let i = 0; i < 7; i++) {
        const nextDate = new Date(nextWeekStartDate);
        nextDate.setDate(nextWeekStartDate.getDate() + i);
        nextWeekDays.push(nextDate);
    }

    return nextWeekDays;
}

function getPreviousWeekByCurrentStart(startDate) {
    const previousWeekStartDate = new Date(startDate);
    previousWeekStartDate.setDate(startDate.getDate() - 7); // Move to the same day of the previous week

    const previousWeekDays = [];
    for (let i = 0; i < 7; i++) {
        const previousDate = new Date(previousWeekStartDate);
        previousDate.setDate(previousWeekStartDate.getDate() + i);
        previousWeekDays.push(previousDate);
    }

    return previousWeekDays;
}

function getKW() {
    const startDate = weekDates[0];
    const startOfYear = new Date(startDate.getFullYear(), 0, 1);
    const weekNumber = Math.ceil(((startDate - startOfYear) / 86400000 + startOfYear.getDay() + 1) / 7);

    return weekNumber;
}

function formatDateToString(date) {
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
    const year = date.getFullYear();
    return `${day}.${month}.${year}`;
}

function formatStringToDate(dateString) {
    console.log(dateString);
    const [day, month, year] = dateString.split('.').map(Number);
    return new Date(year, month - 1, day); // Months are zero-based in JavaScript
}