<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="/">
        <div>
            <h1>Einstellungen</h1>
            <p class="description">
                Hier kannst du deine Account-Einstellungen vornehmen, insbesondere bezüglich der Sicherheit und
                Privatsphäre.
            </p>
        </div>
        <div class="profile_box">
            <form action="updatesettings.php" method="POST">
                <div class="profile_info_box">
                    <span class="profile_text_topic">Benutzername ändern</span>
                    <br/>
                    <hr/>
                    <input type="text" value="{data/username}" name="username" class="profile_input"/>
                    <br/>
                </div>

                <!-- Passwort ändern -->
                <div class="profile_info_box">
                    <span class="profile_text_topic">Passwort ändern</span>
                    <br/>
                    <hr/>
                    <input type="password" name="password" class="profile_input" placeholder="Neues Passwort"/>
                    <br/>
                </div>


                <!-- Profil-Sichtbarkeit -->
                <div class="profile_info_box">
                    <span class="profile_text_topic">Profil-Sichtbarkeit</span>
                    <br/>
                    <hr/>
                    <select name="profile_visibility" class="profile_input">
                        <option value="public">
                            <xsl:if test="data/profile_visibility = 'public'">
                                <xsl:attribute name="selected">selected</xsl:attribute>
                            </xsl:if>
                            Öffentlich
                        </option>
                        <option value="friends_only">
                            <xsl:if test="data/profile_visibility = 'friends_only'">
                                <xsl:attribute name="selected">selected</xsl:attribute>
                            </xsl:if>
                            Nur Freunde
                        </option>
                        <option value="private">
                            <xsl:if test="data/profile_visibility = 'private'">
                                <xsl:attribute name="selected">selected</xsl:attribute>
                            </xsl:if>
                            Privat
                        </option>
                    </select>
                </div>

                <!-- Emails bekommen ja/nein -->
                <div class="profile_info_box">
                    <span class="profile_text_topic">Anfragen verwalten</span>
                    <br/>
                    <hr/>
                    <div class="profile_checkbox_container">
                        <input type="checkbox" value="true" name="receive_emails" class="checkbox_input">
                            <xsl:if test="data/receive_mails = '1'">
                                <xsl:attribute name="checked">checked</xsl:attribute>
                            </xsl:if>
                        </input>
                        <span class="profile_text_topic">Emails bekommen</span>
                        <br/>
                    </div>
                </div>

                <!-- Freundschaftsanfragen bekommen ja/nein -->
                <div class="profile_checkbox_container">
                    <input type="checkbox" name="receive_friend_requests" class="checkbox_input">
                        <xsl:if test="data/receive_friend_requests = '1'">
                            <xsl:attribute name="checked">checked</xsl:attribute>
                        </xsl:if>
                    </input>
                    <span class="profile_text_topic">Freundschaftsanfragen bekommen</span>
                    <br/>
                </div>

                <div class="button-container">
                    <input class="button" type="submit" value="Änderungen speichern"/>
                </div>
            </form>
        </div>
    </xsl:template>
</xsl:stylesheet>
