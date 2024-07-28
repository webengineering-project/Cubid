<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <!-- Root template -->
    <xsl:template match="/">
        <html>
            <head>
                <title>Kalender</title>
                <link rel="stylesheet" href="../components/navbar.css"/>
                <link rel="stylesheet" href="calendar.css"/>
                <link rel="stylesheet" href="../settings_nav.css"/>
                <link rel="stylesheet" href="../css/event_form.css"/>
                <link rel="stylesheet"
                      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
                <script src="calendar.js" defer="defer"/>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
            </head>
            <body>
                <div id="event-form" class="event-form" style="display: none;">
                    <div id="event-form-overlay">
                        <div id="event-form-overlay-header">
                            <h2 id="event-form-overlay-header-title">Event hinzufügen</h2>
                            <button class="cancel-button" onclick="toggleEventForm()">Abbrechen</button>
                        </div>
                        <form action="index.php" method="POST" id="formSender">
                            <label for="title">Titel:</label>
                            <input type="text" id="title" name="title" required="true"/>

                            <label for="description">Beschreibung:</label>
                            <textarea id="description" name="description"></textarea>

                            <label for="location">Ort:</label>
                            <input type="text" id="location" name="location"/>

                            <label for="date">Datum:</label>
                            <input type="date" class="form-control" id="datePicker" name="date" required="true"/>

                            <label for="start-time">Startzeit:</label>
                            <select id="start-time" name="start-time" required="true"></select>

                            <label for="end-time">Endzeit:</label>
                            <select id="end-time" name="end-time" required="true"></select>

                            <div id="private-box" class="checkbox-container">
                                <label for="is-private">Privat:</label>
                                <input type="checkbox" id="is-private" name="is-private" value="1" class="checkbox"/>
                            </div>

                            <div id="additional-fields">
                                <label for="send-email">E-Mail an Teilnehmer senden:</label>
                                <input type="text" id="email-search" placeholder="Search for email..."/>
                                <select id="send-email" name="send-email[]" multiple="multiple">
                                    <xsl:for-each select="data/users/user">
                                        <option>
                                            <xsl:value-of select="email"/>
                                        </option>
                                    </xsl:for-each>
                                </select>

                                <label for="game-filter">Spiel:</label>
                                <select id="game-filter" name="game-filter">
                                    <xsl:for-each select="data/games/game">
                                        <option value="{id}">
                                            <xsl:value-of select="name"/>
                                        </option>
                                    </xsl:for-each>
                                </select>

                                <div id="email-box" class="checkbox-container">
                                    <label for="send-email-checkbox">E-Mail an Teilnehmer senden:</label>
                                    <input type="checkbox" id="send-email-checkbox" value="true"
                                           name="send-email-checkbox" class="checkbox"/>
                                </div>
                            </div>
                            <input type="submit" value="Hinzufügen" style="margin-top: 10px;" id="createButton"/>
                            <div id="eventUpdateButtons">
                                <input type="button" value="Aktualisieren" id="updateButton"/>
                                <input type="button" value="Löschen" id="deleteButton"/>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="container">
                    <div class="event-container-wrapper">
                        <div class="section inbox">
                            <h1>Kalender</h1>
                            <div class="inbox-container">
                                <h3>Heute</h3>
                                <xsl:for-each select="data/events_today/event">
                                    <div class="event-text-container">
                                        <div class="event-text-point">
                                            <div class="event-text-point-inner"></div>
                                        </div>
                                        <div class="event-text">
                                            <xsl:value-of select="begin"/> :
                                            <xsl:value-of select="end"/>
                                            <br/>
                                            <xsl:value-of select="title"/>
                                        </div>
                                    </div>
                                </xsl:for-each>
                            </div>
                        </div>
                        <div class="event-container" onclick="toggleEventForm()">
                            <p>Ereignis hinzufügen</p>
                            <div class="event-button">
                                <span>+</span>
                            </div>
                        </div>
                    </div>

                    <div class="calendar-container">
                        <div class="section calendar">
                            <div id="calendar-controls">
                                <i id="calendar-button-prev-week" class="fas fa-arrow-left"></i>
                                <div id="week-count">
                                    <p id="week-counter">KW 01</p>
                                </div>
                                <i id="calendar-button-current-week" class="fa-solid fa-calendar-days"></i>
                                <i id="calendar-button-next-week" class="fas fa-arrow-right"></i>
                            </div>

                            <div id="calendar">
                                <div class="timeline">
                                    <div class="spacer"></div>
                                    <div class="time-marker">00:00</div>
                                    <div class="time-marker">01:00</div>
                                    <div class="time-marker">02:00</div>
                                    <div class="time-marker">03:00</div>
                                    <div class="time-marker">04:00</div>
                                    <div class="time-marker">05:00</div>
                                    <div class="time-marker">06:00</div>
                                    <div class="time-marker">07:00</div>
                                    <div class="time-marker">08:00</div>
                                    <div class="time-marker">09:00</div>
                                    <div class="time-marker">10:00</div>
                                    <div class="time-marker">11:00</div>
                                    <div class="time-marker">12:00</div>
                                    <div class="time-marker">13:00</div>
                                    <div class="time-marker">14:00</div>
                                    <div class="time-marker">15:00</div>
                                    <div class="time-marker">16:00</div>
                                    <div class="time-marker">17:00</div>
                                    <div class="time-marker">18:00</div>
                                    <div class="time-marker">19:00</div>
                                    <div class="time-marker">20:00</div>
                                    <div class="time-marker">21:00</div>
                                    <div class="time-marker">22:00</div>
                                    <div class="time-marker">23:00</div>
                                </div>

                                <div class="days">
                                    <!-- Iterate through each day -->
                                    <xsl:for-each select="data/events/day">
                                        <div class="day">
                                            <div class="date">
                                                <p class="date-day">
                                                    <xsl:value-of select="@name"/> <!-- Output day abbreviation -->
                                                </p>
                                                <p class="date-num" id="date-{position()}">
                                                    <xsl:number count="day" level="any"/>
                                                </p>
                                            </div>
                                            <div class="events">
                                                <!-- Iterate through each event -->
                                                <xsl:for-each select="event">
                                                    <div id="id:{id}"
                                                         class="event start-{begin} end-{end} color-{color}"
                                                         data-title="{title}"
                                                         data-description="{description}"
                                                         data-private="{private}"
                                                         data-game="{game}"
                                                         data-location="{location}"
                                                         data-isowner="{isowner}"
                                                         data-date="{date}">
                                                        <p class="title">
                                                            <xsl:value-of select="title"/>
                                                        </p>
                                                        <p class="time">
                                                            <xsl:value-of
                                                                    select="concat(substring(begin, 1, 2), ':', substring(begin, 4, 2), ' - ', substring(end, 1, 2), ':', substring(end, 4, 2), ' Uhr')"/>
                                                        </p>
                                                    </div>
                                                </xsl:for-each>
                                            </div>
                                        </div>
                                    </xsl:for-each>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <script src="calendar.js"></script>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
