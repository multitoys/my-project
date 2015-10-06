<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output
            method="html"
            doctype-public="-//W3C//DTD HTML 4.01//EN"
            doctype-system="http://www.w3.org/TR/html4/strict.dtd"
            indent="yes"/>

    <!--
    Шаблон корневого элемента
    -->
    <xsl:template match="/">
        <html>
            <head>
                <title>Наши книги</title>
                <style type="text/css">
                    * {margin:0px; padding: 0px }
                    h1 {padding: 10px; text-align:center; background-color:#ccf }
                    table { margin: 10px; border-collapse: collapse }
                    td { border: 1px solid gray; padding: 5px }
                    thead td { text-align:center; background-color:#ccf; font-weight:bold }
                    #colTitle { width: 300px }
                    #colAutor { width: 300px }
                    #colPubYear { width: 100px }
                    #colPrice { width: 100px }
                    .expenceBook td { background-color: yellow }
                </style>
            </head>
            <body>
                <h1>Наши книги</h1>
                <table>
                    <thead>
                        <td id="colTitle">Наименование</td>
                        <td id="colAutor">Автор</td>
                        <td id="colPubYear">Год издания</td>
                        <td id="colPrice">Цена</td>
                    </thead>
                    <tbody>
                        <xsl:apply-templates select="/catalog/book"/>
                    </tbody>
                </table>
            </body>
        </html>
    </xsl:template>
    <!--
    Шаблон отрисовки книги стоимостью менее 200 руб.
    -->
    <xsl:template match="book[price &lt; 200]">
        <tr>
            <xsl:apply-templates select="./*"/>
        </tr>
    </xsl:template>
    <!--
    Шаблон отрисовки книги стоимостью более 200 руб.
    -->
    <xsl:template match="book[price &gt; 200]">
        <tr class="expenceBook">
            <xsl:apply-templates select="./*"/>
        </tr>
    </xsl:template>
    <!--
    Шаблон отрисовки дочерних элементов книги
    -->
    <xsl:template match="book/*">
        <td>
            <xsl:value-of select="."/>
        </td>
    </xsl:template>
</xsl:stylesheet>