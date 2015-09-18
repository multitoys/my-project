<?php
    function smarty_function_cs_show_main_page($params, &$smarty)
{
    $query = "SELECT aux_page_text_ru AS text_ru FROM SC_aux_pages WHERE aux_page_slug = 'portfolio'";
    $res = mysql_query($query) or die(mysql_error() . "<br>$query");
    $row = mysql_fetch_object($res);
    if ($row) {
        $portfolio = $row->text_ru;
    } else $portfolio = 'Для вставки здесь текста добавьте информационную страницу с id = portfolio';
    $query = "SELECT aux_page_text_ru AS text_ru FROM SC_aux_pages WHERE aux_page_slug = 'about_nonreg'";
    $res = mysql_query($query) or die(mysql_error() . "<br>$query");
    $row = mysql_fetch_object($res);
    if ($row) {
        $about_text = $row->text_ru;
    } else $about_text = 'Для вставки здесь текста добавьте информационную страницу с id = about_nonreg';
    $query = "SELECT aux_page_text_ru AS text_ru FROM SC_aux_pages WHERE aux_page_slug = 'sotr_nonreg'";
    $res = mysql_query($query) or die(mysql_error() . "<br>$query");
    $row = mysql_fetch_object($res);
    if ($row) {
        $sotr_text = $row->text_ru;
    } else $sotr_text = 'Для вставки здесь текста добавьте информационную страницу с id = sotr_nonreg';
    $query = "SELECT * FROM SC_news_table ORDER BY priority, add_stamp DESC LIMIT 20";
    $res = mysql_query($query) or die(mysql_error() . "<br>$query");
    $news_text = '<h2>Новости</h2><p></p>';
    $no = 0;

    while ($row = mysql_fetch_object($res)) {
        $no++;
        $news_text .= "<h3>$row->title</h3><span class=news_date>$row->add_date</span>$row->textToPublication<div><a href='/blog/$row->NID/'>Подробнее</a></div>";
        $news_text .= ($no < 20) ? "<hr>" : '';
    }
    $text = "<div class=header_portfolio>$portfolio</div><hr><div class=header_about>$about_text</div><hr><div class=header_sotr>$sotr_text</div><hr><div class=header_menu_news>
$news_text</div>";
    return $text;
}
