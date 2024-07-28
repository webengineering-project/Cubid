<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="/">
        <html>
            <head>
                <title>User List</title>
                <link rel="stylesheet" href="../components/navbar.css"/>
                <link rel="stylesheet" href="user_search.css"/>
                <script src="user_search.js" defer="defer"/>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
            </head>
            <body>
                <div class="container">
                    <h1>Users</h1>
                    <div class="search-container">
                        <input type="text" id="search-input" placeholder="Search by username..."/>
                        <select id="game-filter">
                            <option value="-1">Don't filter by game</option>
                            <xsl:for-each select="/data/games/game">
                                <option value="{id}">
                                    <xsl:value-of select="name"/>
                                </option>
                            </xsl:for-each>
                        </select>
                    </div>
                    <div class="result-count">
                        <xsl:value-of select="count(data/users/user)"/> results found
                    </div>
                    <div class="user-list">
                        <xsl:for-each select="data/users/user">
                            <div class="user-card">
                            <img src="/assets/img/user/user_bg_3.jpg" class="user-bg"/>
                            <div class="user-card-text">
                                <img class="user-image">
                                    <xsl:attribute name="src">
                                        <xsl:value-of select="image"/>
                                    </xsl:attribute>
                                </img>
                                <div class="user-info">
                                    <p>
                                        <strong>Username:</strong>
                                        <xsl:value-of select="username"/>
                                    </p>
                                    <p>
                                        <strong>Email:</strong>
                                        <xsl:value-of select="email"/>
                                    </p>
                                    <p>
                                        <strong>Location:</strong>
                                        <xsl:value-of select="location"/>
                                    </p>
                                    <p>
                                        <strong>About Me:</strong>
                                        <xsl:choose>
                                            <xsl:when test="string-length(about_me) &gt; 20">
                                                <xsl:value-of select="concat(substring(about_me, 1, 20), '...')"/>
                                            </xsl:when>
                                            <xsl:otherwise>
                                                <xsl:value-of select="about_me"/>
                                            </xsl:otherwise>
                                        </xsl:choose>
                                    </p>
                                    <div class="user-games">
                                        <xsl:for-each select="user_games/user_game">
                                            <div class="game">
                                                <p>
                                                    <xsl:value-of select="id"/>
                                                </p>
                                            </div>
                                        </xsl:for-each>
                                    </div>
                                </div>
                                <button class="visit_button" onclick="redirectToProfile('{email}')">Visit
                                    Profile
                                </button>
                            </div>
                            </div>
                        </xsl:for-each>
                    </div>
                </div>
                <script src="user_search.js"></script>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
