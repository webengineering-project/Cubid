<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="/">

        <div class="profile_box">
            <img src="{userdata/current/image}" alt="Profile Picture" class="profile_image_large"/>
            <span class="profile_name">
                <xsl:value-of select="userdata/current/name"/>
            </span>
        </div>

        <xsl:choose>
            <xsl:when test="/userdata/users/sessionuser/email != /userdata/current/email">
                <xsl:choose>
                    <xsl:when test="/userdata/current/receive_friend_requests = 1">
                        <form action="handlefriend.php" method="POST">
                            <xsl:choose>
                                <xsl:when test="/userdata/current/pendingRequest = 'true'">
                                    <button class="friendship_button_revoke" name="revokeFriendRequest"
                                            value="{/userdata/current/email}">
                                        Freundschaftsanfrage zurückziehen
                                    </button>
                                </xsl:when>
                                <xsl:otherwise>
                                    <xsl:choose>
                                        <xsl:when
                                                test="/userdata/current/friends/friend[email = /userdata/users/sessionuser/email]">
                                            <button class="friendship_button" name="removeFriend"
                                                    value="{/userdata/current/email}">
                                                Freund entfernen
                                            </button>
                                        </xsl:when>
                                        <xsl:otherwise>
                                            <button class="friendship_button" name="addFriend"
                                                    value="{/userdata/current/email}">
                                                Als Freund hinzufügen
                                            </button>
                                        </xsl:otherwise>
                                    </xsl:choose>
                                </xsl:otherwise>
                            </xsl:choose>
                        </form>
                    </xsl:when>
                    <xsl:otherwise>
                        <form action="handlefriend.php" method="POST">
                            <xsl:choose>
                                <xsl:when
                                        test="/userdata/current/friends/friend[email = /userdata/users/sessionuser/email]">
                                    <button class="friendship_button" name="removeFriend"
                                            value="{/userdata/current/email}">
                                        Freund entfernen
                                    </button>
                                </xsl:when>
                                <xsl:otherwise>
                                    Dieser Nutzer möchte keine Freundschaftsanfragen erhalten.
                                </xsl:otherwise>
                            </xsl:choose>
                        </form>
                    </xsl:otherwise>
                </xsl:choose>


            </xsl:when>
        </xsl:choose>

        <div class="profile_box">
            <div class="profile_info_box">
                <span class="profile_text_topic">Spiele</span>
                <br/>
                <hr/>
                <br/>
                <div id="game-tags-container">
                    <xsl:choose>
                        <xsl:when test="userdata/current/game=''">
                            <span class="profile_text">
                                Wählen Sie Spiele in Ihren Einstellungen aus.
                            </span>

                        </xsl:when>
                        <xsl:otherwise>
                            <xsl:for-each select="userdata/current/game/name">
                                <div class="game-tag">
                                    <xsl:value-of select="."/>
                                    <div class="game-tag-details">
                                        <b>
                                            <xsl:value-of select="."/>
                                        </b>
                                    </div>
                                </div>

                            </xsl:for-each>
                        </xsl:otherwise>
                    </xsl:choose>
                </div>
            </div>

            <div class="profile_info_box">
                <span class="profile_text_topic">Email</span>
                <br/>
                <hr/>
                <span class="profile_text">
                    <xsl:value-of select="userdata/current/email"/>
                </span>
                <br/>
            </div>

            <div class="profile_info_box">
                <span class="profile_text_topic">Name</span>
                <br/>
                <hr/>
                <span class="profile_text">
                    <xsl:value-of select="userdata/current/last_name"/>
                </span>
                ,
                <span class="profile_text">
                    <xsl:value-of select="userdata/current/first_name"/>
                </span>
                aka
                <span class="profile_text">
                    <xsl:value-of select="userdata/current/name"/>
                </span>
                <br/>
            </div>

            <div class="profile_info_box">
                <span class="profile_text_topic">Standort</span>
                <br/>
                <hr/>
                <span class="profile_text">
                    <xsl:value-of select="userdata/current/location"/>
                </span>
                <br/>
            </div>

            <div class="profile_info_box">
                <span class="profile_text_topic">Über mich</span>
                <br/>
                <hr/>
                <span class="profile_text">
                    <xsl:value-of select="userdata/current/about_me"/>
                </span>
                <br/>
            </div>

            <div class="profile_info_box">
                <div class="profile_info_box">
                    <span class="profile_text_topic">Freunde</span>
                    <br/>
                    <hr/>
                    <br/>
                    <div class="friend_profile_container">

                        <xsl:choose>

                            <xsl:when test="userdata/current/friends = ''">

                                <span>
                                    Ich habe keine Freunde buhuhuhu :,(
                                </span>
                            </xsl:when>
                            <xsl:otherwise>

                                <xsl:for-each select="userdata/current/friends/friend">
                                    <xsl:choose>

                                        <xsl:when test="email != /userdata/users/sessionuser/email">
                                            <div class="friend_profile" onclick='redirectToProfile("{email}")'>
                                                <img src="{image}" class="friend_profile_img"/>
                                                <span>
                                                    <xsl:value-of select="username"></xsl:value-of>
                                                </span>
                                            </div>
                                        </xsl:when>
                                        <xsl:otherwise>
                                            <div class="friend_profile">
                                                <img src="{image}" class="friend_profile_img"/>
                                                <span>
                                                    <xsl:value-of select="username"></xsl:value-of>
                                                </span>
                                            </div>
                                        </xsl:otherwise>
                                    </xsl:choose>
                                </xsl:for-each>
                            </xsl:otherwise>
                        </xsl:choose>
                    </div>

                    <br/>
                </div>
            </div>
        </div>
    </xsl:template>
</xsl:stylesheet>

