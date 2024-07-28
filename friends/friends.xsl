<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="/">
        <div>
            <h1>Freunde verwalten</h1>
            <p class="description">
                Nehme Frendschaftsanfragen an oder lehne sie ab und verwalte hier deine Freunde hier.
            </p>
        </div>
        <div class="profile_box">
            <form action="updatesettings.php" method="POST">
                <div class="profile_info_box">
                    <span class="profile_text_topic">Freunde</span>
                    <br/>
                    <hr/>
                    <div class="friends">
                        <xsl:for-each select="data/friends/friend">
                            <div class="friend_profile" onclick='redirectToProfile("{email}")'>
                                <img src="{image}" class="friend_profile_img"/>
                                <span>
                                    <xsl:value-of select="username"></xsl:value-of>
                                </span>
                            </div>
                        </xsl:for-each>
                    </div>
                    <br/>
                </div>

                <div class="profile_info_box">
                    <span class="profile_text_topic">Anfragen</span>
                    <br/>
                    <hr/>
                    <xsl:for-each select="data/friend_requests/request">
                        <div class="request-item">
                            <p>
                                <xsl:value-of select="email"/>
                            </p>

                            <!--- Leave this first <form> in because of a weird bug with xslt !-->
                            <form id="placeholderForm" action="deny.php" method="POST" class="hidden_form">
                                <input type="hidden" name="email"/>
                            </form>

                            <form id="denyForm" action="deny.php" method="POST">
                                <input id="acceptInput" type="hidden" name="email" value="{email}"/>
                                <button type="submit" class="deny-button">Ablehnen</button>
                            </form>

                            <form id="acceptForm" action="accept.php" method="POST">
                                <input id="denyInput" type="hidden" name="email" value="{email}"/>
                                <button type="submit" class="accept-button">Annehmen</button>
                            </form>
                        </div>
                    </xsl:for-each>

                    <br/>
                </div>
            </form>
        </div>
    </xsl:template>
</xsl:stylesheet>
