<?php
$_['heading_title_avatar']   = 'Аватар';
$_['entry_comment']          = 'Ваш отзыв:';
$_['text_customer_enter']    = 'Войти';
$_['text_welcome']           = ' или <a href="%s">зарегистрироваться</a>';
$_['text_wait']           	 = 'Подождите...';
$_['text_all_begin']         = 'Все ';
$_['text_all_end']  	     = '...';
$_['text_anonymus']          = 'Гость';
$_['text_sc_loading']        = 'Загружается';
$_['text_avatar'] 			 = 'Аватар';
$_['url_module_text']        = 'Модуль iБлог';
$_['url_blog_text']          = 'Категории блога';
$_['url_record_text']        = 'Записи';
$_['url_comment_text']       = 'Отзывы';
$_['url_forum_buy_text']     = 'Получить тех. поддержку';
$_['url_forum_update_text']  = 'Получить обновления';
$_['url_forum_text']         = 'Тех. поддержка модуля';
$_['url_forum_site_text']    = 'Форум';
$_['url_opencartadmin_text'] = 'О модуле';
$_['url_forum_buy']          = 'https://opencartforum.com/files/file/2371-seo-cms-top-2-blog-novosti-otzyvy-galereya-formy/';
$_['url_forum']              = 'https://opencartforum.com/topic/45395-seo-cms-top-2-blog-novosti-otzyvy-galereya-formy/';
$_['url_opencartadmin']      = 'https://opencartadmin.com';
$_['url_avatar_text'] 		 = 'Изменить аватар';
$_['button_continue']        = "Далее";
$_['entry_ans']              = 'Ваш ответ:';
$_['entry_rating']           = 'Оцените публикацию: ';
$_['entry_rating_review']    = 'Дайте оценку: ';
$_['entry_captcha_title']   = 'Тест на &laquo;человечность&raquo;';
$_['entry_captcha']         = 'Введите последовательность символов,<br>которые вы видите на картинке:';
$_['text_success']      = 'Спасибо, мы перезвоним вам по указанному телефону и указанное время';
$_['entry_minus']           = 'Ужасно';
$_['entry_bad']             = 'Плохо';
$_['entry_normal']          = 'Удовлетворительно';
$_['entry_good']            = 'Хорошо';
$_['entry_exelent']			= 'Отлично';

$_['button_continue']        = 'Продолжить';
$_['error_text']        = 'Текст отзыва должен быть от 3 до 1000 символов!';
$_['text_or_email']       = "Или e-mail для подписки";
$_['text_access_denided']  = "У вас нет прав доступа, войдите под своим логином или зарегистрируйтесь";
$_['text_upload_allowed']     = 'Разрешенные для загрузки типы файлов:';
$_['text_avatar_dim']     = 'Аватар будет иметь размер:';
$_['text_upload']     = 'Файл успешно загружен';
$_['entry_avatar_delete']		= 'Аватар удален';
$_['text_signer']       = 'Подписаться на новые отзывы';
$_['text_limit']           = 'На странице:';
$_['text_sort']            = 'Сортировать по:';

$_['text_category_record']            = 'Категория: ';
$_['text_category']            = 'Категория товара: ';
$_['text_author']            = 'Автор: ';

$_['entry_captcha_title']   = 'Тест на &laquo;человечность&raquo;';
$_['entry_captcha_update']  = 'Обновить';
$_['entry_captcha']         = 'Введите последовательность символов,<br>которые вы видите на картинке:';
$_['text_note']             = '<span style="color: #FF0000;">Внимание:</span> HTML не поддерживается! Используйте обычный текст.';
$_['entry_captcha_update']  = 'Обновить';

$_['text_buy']     = 'Покупал на сайте.';
$_['text_buyproduct']     = 'Купил этот товар.';
$_['text_registered']     = 'Зарегистрирован.';

$_['error_filename']     = 'Не правильное имя файла';
$_['error_filetype']     = 'Не правильный тип файла';
$_['error_upload']     = 'Ошибка загрузки файла';

$_['text_for_category']     = 'По категориям';
$_['text_for_childcategory']     = 'с подкатегориями';
$_['text_for_desc']     = 'по описанию';
$_['text_for_search']     = 'Поиск';


if (!isset($_['text_separator'])) {
	$_['text_separator']        = ' &raquo; ';
}

$_['text_customer_enter']   = 'Войти';
$_['text_welcome']          = ' или <a href="%s">зарегистрироваться</a>';
$_['text_search']           = 'Поиск';
$_['text_ans']              = 'Варианты ответов ';
$_['text_youans']           = 'Свой вариант ответа ';
$_['text_writeans']         = 'Оставить свой вариант ответа ';
$_['text_signer_answer']	= 'Получать ответы';

if (SC_VERSION > 15) {
	$image_envelope = '<i class="fa fa-envelope" aria-hidden="true"></i>';
} else {
	$image_envelope = '';
}


$_['text_signer_answer_email']= 'на e-mail <span class="no-public">(не публикуется)</span> '.$image_envelope;
$_['signer_answer_require'] = 'Заполните поле: '.$_['text_signer_answer'];
$_['text_unpublic']			= ' <span class="no-public">(Не публикуется)</span>';
$_['text_buy']     			= 'Покупал на сайте.';
$_['text_buyproduct']     	= 'Купил этот товар.';
$_['text_registered']     	= 'Зарегистрирован.';
$_['text_buy_ghost']     	= 'Гость.';
$_['text_admin']    	 	= 'Администратор.';
$_['error_reg']             = '<div>Отзывы могут оставлять только зарегистрированные пользователи.<br>Пожалуйста <a href="%s">зарегистрируйтесь</a></div>';
$_['error_text']            = 'Текст отзыва должен быть от 3 до 1000 символов!';
$_['error_rating']          = 'Пожалуйста, выберите оценку!';
$_['error_captcha']         = 'Код, указанный на картинке, введен неверно!';
$_['text_review_karma'] 	= 'Отзыв полезен? ';
$_['text_review_yes'] 		= 'Да';
$_['text_review_no'] 		= 'Нет';
$_['text_reply_button']     = 'Ответить';
$_['text_write']            = 'Оставить отзыв';
$_['text_write_review']     = 'Оставить отзыв';
$_['text_error_email']		= 'Не правильный e-mail';
$_['text_no_comments']      = 'Ещё никто не оставил отзывов.';
$_['text_edit_button']      = 'Редактировать';
$_['text_delete_button']    = 'Удалить';
$_['text_share']            = 'Поделиться';
$_['text_success']          = 'Спасибо за Ваш отзыв. Он отправлен администратору на утверждение.';
$_['text_success_now']      = 'Спасибо за Ваш отзыв.';
$_['text_voted']            = 'Вы уже голосовали!';
$_['text_vote_reg']         = 'Для того чтобы проголосовать вы должны войти под своими учетными данными или зарегистрироваться';
$_['text_vote_self']        = 'За свои отзывы голосовать нельзя';
$_['text_vote_blog_minus']  = 'Не нравится';
$_['text_vote_blog_plus']   = 'Нравится';
$_['text_all']              = 'Всего';
$_['text_voted_blog_plus']  = 'Вы проголосовали положительно.';
$_['text_voted_blog_minus'] = 'Вы проголосовали отрицательно.';
$_['text_vote_will_reg']    = 'Голосовать могут только зарегистрированные пользователи';
$_['text_upload']           = 'Файл успешно загружен на сервер!';
$_['text_wait']             = 'Подождите пожалуйста!';

$_['text_anonymus']         = 'Гость';
$_['text_sorting_desc']     = 'новые cверху';
$_['text_sorting_asc']      = 'старые cверху';
$_['text_rollup']           = 'свернуть ветку';
$_['text_rollup_down']      = 'развернуть ветку';
$_['entry_name']            = 'Ваше имя:';
$_['entry_comment']         = 'Ваш отзыв:';

$_['entry_addfields_begin'] = 'Укажите: ';
$_['entry_addfields_end']   = ' товара';
$_['entry_ans']             = 'Ваш ответ:';
$_['entry_rating']          = 'Оцените публикацию: ';
$_['entry_rating_review']   = 'Дайте оценку: ';
$_['entry_minus']           = 'Ужасно';
$_['entry_bad']             = 'Плохо';
$_['entry_normal']          = 'Удовлетворительно';
$_['entry_good']            = 'Хорошо';
$_['entry_exelent']			= 'Отлично';
$_['entry_captcha_update']  = 'Обновить';
$_['entry_sorting']         = 'Упорядочить отзывы';
$_['entry_sortingans']      = 'Упорядочить ответы';

$_['error_validate']      = 'Доступ закрыт';

$_['text_sc_stat_reviews']      = 'отзывов';
$_['text_sc_stat_answer']      = 'ответов';
$_['text_sc_stat_ratings']      = 'оценок';
$_['text_sc_stat_good']      = 'положительных';
$_['text_sc_stat_rate_text'] = 'Полезность отзывов';
$_['text_sc_stat_rate']      = 'голосов';
