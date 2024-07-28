<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:strip-space elements="*"/>

    <xsl:template match="/">

        <html>
            <body id="profile-content">

                <div class="profile_box">
                    <img src="{userdata/current/image}" alt="Profile Picture" class="profile_image_large"/>
                    <span class="profile_name">
                        <xsl:value-of select="userdata/current/name"/>
                    </span>
                    <div id="profile_box_buttons">
                        <button id="changePicture">Profilbild ändern</button>
                        <button id="deletePicture">Profilbild löschen</button>
                    </div>
                </div>

                <div id="pictureForm" class="event-form" style="display: none;">
                    <div id="event-form-overlay">
                        <div id="event-form-overlay-header">
                            <h2>Profilbild ändern</h2>
                            <button class="close"
                                    onclick="document.getElementById('pictureForm').style.display = 'none'">
                                Schließen
                            </button>
                        </div>
                        <div id="event-form-overlay-body">
                            <form action="updatePicture.php" method="POST" id="pictureForm" class="picture_form">
                                <label for="picture">Link zum Profilbild:</label>
                                <input type="url" id="pictureUrl" name="pictureUrl" required="true"/>
                                <input type="submit" class="profilePicSubmit">Aktualisieren</input>
                            </form>
                        </div>
                    </div>
                </div>

                <div id="event-form" class="event-form" style="display: none;">
                    <div id="event-form-overlay">
                        <div id="event-form-overlay-header">
                            <h2>Wähle deine Spiele</h2>

                        </div>
                        <div id="event-form-overlay-body">
                            <div id="select-game">
                                <div class="tagList hide-scrollbar">

                                    <xsl:for-each select="userdata/games/name">
                                        <div id="Id_of_game:{.}Unselected" class="game-tag" name="gameLi"
                                             onclick="selection(this)" style="display:  ">
                                            <xsl:value-of select="."></xsl:value-of>
                                        </div>

                                    </xsl:for-each>
                                </div>
                                <input type="text" id="search_input" placeholder="Tippe zum suchen" oninput="search()"
                                       autocomplete="off"></input>
                            </div>
                            <img src="/assets/icons/left-right-arrow.svg" alt="left-right-arrow"
                                 style="width: 2rem; height: 2rem; margin: 1rem;"/>
                            <div id="unselect-game">
                                <xsl:for-each select="userdata/games/name">
                                    <div class="game-tag" id="Id_of_game:{.}Selected" onclick="selection(this)"
                                         name="gameSelected" style="display:  none">
                                        <xsl:value-of select="."></xsl:value-of>
                                    </div>
                                </xsl:for-each>
                            </div>
                        </div>

                        <div id="button-container-form">
                            <form action="updategametags.php" method="POST" class="profileEdit-overlay-form">
                                <xsl:variable name="ownedTags" select="userdata/current/game"/>
                                <button id="selectedTags" name="selectedTags" value="{$ownedTags}">Änderungen speichern</button>
                            </form>
                            <button class="cancel-button" onclick="toggleEventForm()">Abbrechen</button>
                        </div>
                    </div>
                </div>

                <div class="profile_box">
                    <div class="profile_info_box">
                        <span class="profile_text_topic">Spiele</span>
                        <br/>
                        <hr/>
                        <br/>
                        <div id="game-tags-container">
                            <xsl:for-each select="userdata/current/game/name">

                                <div class="game-tag" name="ownedGame">
                                    <xsl:value-of select="."/>
                                    <div class="game-tag-details">
                                        <b>
                                            <xsl:value-of select="."/>
                                        </b>
                                    </div>
                                </div>

                            </xsl:for-each>
                            <div class="game-tag" onclick="toggleEventForm()">
                                +
                            </div>
                        </div>

                    </div>

                    <form action="updateprofile.php" method="POST">
                        <div class="profile_info_box">
                            <span class="profile_text_topic">Name</span>
                            <br/>
                            <hr/>
                            <div class="name_fields">
                                <input type="text" value="{userdata/current/first_name}" name="first_name"
                                       class="profile_input"/>
                                <input type="text" value="{userdata/current/last_name}" name="last_name"
                                       class="profile_input"/>
                            </div>
                        </div>

                        <div class="profile_info_box">
                            <span class="profile_text_topic">Standort</span>
                            <br/>
                            <hr/>
                            <input type="text" value="{userdata/current/location}" name="location"
                                   class="profile_input"/>
                            <br/>
                        </div>

                        <div class="profile_info_box">
                            <span class="profile_text_topic">Über mich</span>
                            <br/>
                            <hr/>
                            <textarea rows="10" name="about_me" class="about_me_profile_input">
                                <xsl:choose>
                                    <xsl:when test="string-length(userdata/current/about_me) != 0">
                                        <xsl:value-of select="userdata/current/about_me"/>
                                    </xsl:when>
                                </xsl:choose>
                            </textarea>
                            <br/>
                        </div>
                        <br/>
                        <div class="button-container">
                            <input class="button" type="submit" value="Änderungen speichern"/>
                        </div>
                    </form>
                </div>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
