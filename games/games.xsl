<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="/">
        <h1>Spiele</h1>
        <div class="cardWrapper">
            <xsl:for-each select="/data/games/game">
                <div class="card" onclick="showOverlay('{id}', '{name}', '{description}', '{description_long}')">
                    <img src="{link}" alt="{name}"/>
                    <div class="card-text">
                        <xsl:value-of select="name"/>
                    </div>
                </div>
            </xsl:for-each>
            <div id="event-form">
                <div class="event-form-overlay">
                    <button class="close" onclick="document.getElementById('event-form').style.display = 'none'">
                        Schließen
                    </button>
                    <div id="overlay-text"></div>
                </div>
            </div>
        </div>
    </xsl:template>
</xsl:stylesheet>

