<?php

    /**
     *    IGbookDB
     *        содержит основные методы для работы с Гостевой книгой
     */
    interface IGbookDB
    {
        /**
         *    Добавление новой записи в Гостевую книгу
         *
         * @param string $name  - имя пользователя
         * @param string $email - адрес электропочты пользователя
         * @param string $msg   - текст послания пользователя
         *
         * @return boolean - результат успех/ошибка
         */
        function savePost($name, $email, $msg);

        /**
         *    Выборка всех записей из Гостевой книги
         *
         * @return array - результат выборки в виде массива
         */
        function getAll();

        /**
         *    Удаление записи из Гостевой книги
         *
         * @param integer $id - идентификатор удаляемой записи
         *
         * @return boolean - результат успех/ошибка
         */
        function deletePost($id);
    }

?>