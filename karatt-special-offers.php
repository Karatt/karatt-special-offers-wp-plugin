<?php
/**
 * Plugin Name: Karatt Special Offers
 * Plugin URI:
 * Description: Возможность создать неограниченное количество Special offers с атрибутами
 * Version: 1.0
 * Author: Karatt
 * Author URI: https://karatt.by
 *
 * Text Domain: Идентификатор перевода, указывается в load_plugin_textdomain()
 * Domain Path: Путь до файла перевода. Нужен если файл перевода находится не в той же папке, в которой находится текущий файл.
 *              Например, .mo файл находится в папке myplugin/languages, а файл плагина в myplugin/myplugin.php, тогда тут указываем "/languages"
 *
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * Network:     Укажите "true" для возможности активировать плагин по все сети сайтов (для Мультисайтовой сборки).
 */

/**
 * Запускаем функцию активации плагина со всеми настройками
 * Активируем плагин Karatt Special Offers
 */
function karatt_plugin_install() {

	karatt_sp_offers_options();
	karatt_setup_post_type();

	/** Сбрасываем настройки ЧПУ, чтобы они пересоздались с новыми данными */
	flush_rewrite_rules();

}
register_activation_hook( __FILE__, 'karatt_plugin_install' );

/**
 * Тип записи не регистрируется, а значит он автоматически удаляется - его не нужно удалять как-то еще.
 */
function karatt_plugin_deactivation() {

	/** Сбрасываем настройки ЧПУ, чтобы они пересоздались с новыми данными */
	flush_rewrite_rules();

}
register_deactivation_hook( __FILE__, 'karatt_plugin_deactivation' );

/**
 * Добавляем опции 'spo_options' для плагина:
 *  -символ валюты
 */
function karatt_sp_offers_options() {

	$spo_options_arr = array(
		'currency_sign' => '$',
	);
	update_option( 'spo_options', $spo_options_arr, false );

}

/**
 * Регистрируем таксонимию "spesial-offers
 * Регистрируем тип записи "offers"
 * Добавляем свой размер для миниатюр
 */
function karatt_setup_post_type() {

	/** Свой размер миниатюр */
	if ( function_exists( 'add_image_size' ) ) {
		add_image_size( 'sp-offers-thumb', 300, 200 );
	}
	register_taxonomy(
		'special-offers',
		array( 'offers' ),
		array(
			'label'             => _( 'Раздел спецпредложений', 'karatt-sp-offers' ),
			'labels'            => array(
				'name'              => __( 'Разделы спецпредложений', 'karatt-sp-offers' ),
				'singular_name'     => __( 'Раздел спецпредложения', 'karatt-sp-offers' ),
				'search_items'      => __( 'Искать раздел спецпредложения', 'karatt-sp-offers' ),
				'all_items'         => __( 'Все разделы спецпредложений', 'karatt-sp-offers' ),
				'parent_item'       => __( 'Родит. раздел спецпредложения', 'karatt-sp-offers' ),
				'parent_item_colon' => __( 'Родит. раздел спецпредложения:', 'karatt-sp-offers' ),
				'edit_item'         => __( 'Ред. Раздел вопроса', 'karatt-sp-offers' ),
				'update_item'       => __( 'Обновить раздел спецпредложения', 'karatt-sp-offers' ),
				'add_new_item'      => __( 'Добавить раздел спецпредложения', 'karatt-sp-offers' ),
				'new_item_name'     => __( 'Новый раздел спецпредложения', 'karatt-sp-offers' ),
				'menu_name'         => __( 'Разделы спецпредложений', 'karatt-sp-offers' ),
			),
			'description'       => __( 'Рубрики для раздела спецпредложений', 'karatt-sp-offers' ),
			/** описание таксономии */
			'public'            => true,
			'show_in_nav_menus' => false,
			/** равен аргументу public */
			'show_ui'           => true,
			/** равен аргументу public */
			'show_tagcloud'     => false,
			/** равен аргументу show_ui */
			'hierarchical'      => true,
			'rewrite'           => array(
				'slug'         => 'sp-offers',
				'hierarchical' => true,
				'with_front'   => false,
				'feed'         => false,
			),
			'show_admin_column' => true, /** Позволить или нет авто-создание колонки таксономии в таблице ассоциированного типа записи. (с версии 3.5) */
		)
	);

	register_post_type(
		'offers',
		array(
			'labels'               => array(
				'name'               => __( 'Специальные предложения', 'karatt-sp-offers' ),
				'singular_name'      => __( 'Специальное предложение', 'karatt-sp-offers' ),
				'add_new'            => __( 'Добавить новое', 'karatt-sp-offers' ),
				'add_new_item'       => __( 'Добавить новое спецпредложение', 'karatt-sp-offers' ),
				'edit_item'          => __( 'Редактировать спецпредложение', 'karatt-sp-offers' ),
				'new_item'           => __( 'Новое спецпредложение', 'karatt-sp-offers' ),
				'view_item'          => __( 'Посмотреть спецпредложение', 'karatt-sp-offers' ),
				'search_items'       => __( 'Найти спецпредложение', 'karatt-sp-offers' ),
				'not_found'          => __( 'Спецпредложений не найдено', 'karatt-sp-offers' ),
				'not_found_in_trash' => __( 'В корзине спецпредложений не найдено', 'karatt-sp-offers' ),
				'menu_name'          => __( 'Спецпредложения', 'karatt-sp-offers' ),
			),
			'public'               => true,
			'publicly_queryable'   => true,
			'show_ui'              => true,
			'show_in_menu'         => true,
			'query_var'            => true,
			'rewrite'              => true,
			'capability_type'      => 'post',
			'has_archive'          => true,
			'hierarchical'         => false,
			'menu_icon'            => 'dashicons-carrot',
			'menu_position'        => null,
			'register_meta_box_cb' => 'karatt_spo_register_meta_box', // meta box для спецпредложений
			'supports'             => array( 'title', 'editor', 'author', 'thumbnail' ),
			'taxonomies'           => array( 'special-offers' ),
		)
	);

}
add_action( 'init', 'karatt_setup_post_type' );

/**
 * Создаем страницу настроек в меню Спецпредложений
 */
function karatt_add_options_page() {

	add_submenu_page( 'edit.php?post_type=offers', __( 'Настройки спецпредложений', 'karatt-sp-offers' ), __( 'Настройки и описание', 'karatt-sp-offers' ), 'manage_options', 'spo-page-options', 'karatt_spo_page_options' );

}
add_action( 'admin_menu', 'karatt_add_options_page' );

/**
 * Создаем страницу настроек плагина
 */
function karatt_spo_page_options() {

	/** Массив опций плагина */
	$spo_options_arr = get_option( 'spo_options' );

	/** Символ валюты спецпредложений */
	$spo_currency_sign = $spo_options_arr['currency_sign'];
	?>

	<div class="wrap">
		<h2><?php _e( 'Страница настроек спецпредложений', 'karatt-sp-offers' ); ?></h2>

		<form method="post" action="options.php">
			<?php settings_fields( 'karatt-spo-settings-group' ); ?>
			<table class="form-table">
				<tr valign="top">
				<th scope="row"><?php _e( 'Символ валюты', 'karatt-sp-offers' ); ?></th>
				<td><input type="text" name="spo_options[currency_sign]" value="<?php echo esc_attr( $spo_currency_sign ); ?>" size="1" maxlength="10" /></td>
				</tr>
			</table>

			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e( 'Сохранить изменения', 'karatt-sp-offers' ); ?>" />
			</p>

		</form>
	</div>

	<?php

}

/**
 * Регистрируем настроки плагина
 */
function karatt_spo_register_settings() {

	/** Массив настроек */
	$option_group = 'karatt-spo-settings-group';
	$defaults     = array(
		'type'              => 'string',
		'group'             => $option_group,
		'description'       => '',
		'sanitize_callback' => 'karatt_sanitize_options',
		'show_in_rest'      => false,
	);
	register_setting( $option_group, 'spo_options', $defaults );

}
add_action( 'admin_init', 'karatt_spo_register_settings' );

/**
 * Функция для очистки значений настроек перед сохранением в базу
 */
function karatt_sanitize_options( $options ) {

	$options['currency_sign'] = ( ! empty( $options['currency_sign'] ) ) ? sanitize_text_field( $options['currency_sign'] ) : '';

	return $options;

}

/**
 * Регистрируем для спецпредложений meta box
 * на странице редактирования
 */
function karatt_spo_register_meta_box() {

	add_meta_box( 'karatt-sp-offers-meta', __( 'Атрибуты спецпредложений', 'karatt-sp-offers' ), 'karatt_spo_meta_box', 'offers', 'side', 'default' );

}

/**
 * Создаем сам meta box для спецпредложений
 */
function karatt_spo_meta_box( $post ) {

	datepicker_js(); // подключаем DataPicker для спецпредложений

	/** Добавим символы валюты */
	$spo_options_arr   = get_option( 'spo_options' );
	$spo_currency_sign = $spo_options_arr['currency_sign'];

	/** получаем произвольные поля спецпредложения */
	$spo_meta = get_post_meta( $post->ID, '_karatt_spo_data', true );

	$regular_price  = ( ! empty( $spo_meta['price'] ) ) ? $spo_meta['price'] : '';
	$special_price  = ( ! empty( $spo_meta['sprice'] ) ) ? $spo_meta['sprice'] : '';
	$date_of_action = ( ! empty( $spo_meta['date_of_action'] ) ) ? $spo_meta['date_of_action'] : '';

	/** nonce field для безопасности */
	wp_nonce_field( 'meta-box-save', 'karatt-sp-offers' );

	/** Выводим поля атрибутов */
	$spo_meta_box = '';
	$spo_meta_box .= '<table>';
	$spo_meta_box .= '<tr>';
	$spo_meta_box .= '<td>' . __( 'Регулярная цена', 'karatt-sp-offers' ) . ':</td><td><input type="text" name="sp_offer[price]" value="' . esc_attr( $regular_price ) . '" size="5"> ' . esc_html( $spo_currency_sign ) . '</td>';
	$spo_meta_box .= '</tr><tr>';
	$spo_meta_box .= '<td>' . __( 'Специальная цена', 'karatt-sp-offers' ) . ':</td><td><input type="text" name="sp_offer[sprice]" value="' . esc_attr( $special_price ) . ' " size="5"> ' . esc_html( $spo_currency_sign ) . '</td>';
	$spo_meta_box .= '</tr><tr>';
	$spo_meta_box .= '<td>' . __( 'Дата действия', 'karatt-sp-offers' ) . ':</td><td><input type="text" class="datepicker" name="sp_offer[date_of_action]" value="' . esc_attr( $date_of_action ) . '" size="10"></td>';
	$spo_meta_box .= '</tr>';
	$spo_meta_box .= '</table>';

	echo $spo_meta_box;

}

/**
 * Инициализируем DataPicker в админке при редактировании спецпредложений
 */
function datepicker_js() {

	if ( is_admin() ) {
		// подключаем все необходимые скрипты: jQuery, jquery-ui, datepicker
		wp_enqueue_script( 'jquery-ui-datepicker' );

		// подключаем нужные css стили
		wp_enqueue_style( 'jqueryui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css', false, null );

		// инициализируем datepicker
		add_action( 'admin_footer', 'init_datepicker', 99 ); // для админки
	}

	function init_datepicker() {

		?>
		<script type="text/javascript">
		jQuery(document).ready(function($){
			'use strict';
			// настройки по умолчанию. Их можно добавить в имеющийся js файл,
			// если datepicker будет использоваться повсеместно на проекте и предполагается запускать его с разными настройками
			$.datepicker.setDefaults({
				closeText: 'Закрыть',
				prevText: '<Пред',
				nextText: 'След>',
				currentText: 'Сегодня',
				monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
				monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн','Июл','Авг','Сен','Окт','Ноя','Дек'],
				dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
				dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
				dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
				weekHeader: 'Нед',
				dateFormat: 'dd-mm-yy',
				firstDay: 1,
				showAnim: 'slideDown',
				isRTL: false,
				showMonthAfterYear: false,
				yearSuffix: ''
			} );

			// Инициализация
			$('input[name*="date"], .datepicker').datepicker({ dateFormat: 'dd-mm-yy' });
			// можно подключить datepicker с доп. настройками так:
			/*
			$('input[name*="date"]').datepicker({
				dateFormat : 'yy-mm-dd',
				onSelect : function( dateText, inst ){
				// функцию для поля где указываются еще и секунды: 000-00-00 00:00:00 - оставляет секунды
				var secs = inst.lastVal.match(/^.*?\s([0-9]{2}:[0-9]{2}:[0-9]{2})$/);
				secs = secs ? secs[1] : '00:00:00'; // только чч:мм:сс, оставим часы минуты и секунды как есть, если нет то будет 00:00:00
				$(this).val( dateText +' '+ secs );
				}
			});
			*/
		});
		</script>
		<?php
	}

}

/**
 * Созраняем данные с meta box при сохранении спецпредложения
 */
function karatt_spo_save_meta_box( $post_id ) {

	/** Проверяем тип записи и что данные заполнены */
	if ( get_post_type( $post_id ) == 'offers' && isset( $_POST['sp_offer'] ) ) {

		/** Если установлено автосохранение, то пропускаем сохранение данных */
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		/** Проверяем nonce field для безопасности */
		wp_verify_nonce( 'meta-box-save', 'karatt-sp-offers' );

		/** Массив переданных атрибутов */
		$spo_data = $_POST['sp_offer'];

		/** Используем array_map() для "очистки" значений */
		$spo_data = array_map( 'sanitize_text_field', $spo_data );

		/** Сохраняем данные из meta box как metadata спецпредложений */
		update_post_meta( $post_id, '_karatt_spo_data', $spo_data );

	}

}
add_action( 'save_post', 'karatt_spo_save_meta_box' );

/**
 * Регистрируем shortcode для спецпредложений
 */
function karatt_spo_shortcode( $atts, $content = null ) {

	global $post;

	extract( shortcode_atts( array(
		'offer' => '',
	), $atts ) );

	$offer = '<div data-offer-id="' . $offer . '" class="spo-offer"></div>';

	/** возвращаем shortcode с блоком спецпредложения */
	return $offer;

}
add_shortcode( 'sp-offer', 'karatt_spo_shortcode' );

/**
 * Подключаем локализацию ajax в самом конце подключаемых к выводу скриптов, чтобы скрипт
 * 'jquery', к которому мы подключаемся, точно был добавлен в очередь на вывод.
 * Заметка: код можно вставить в любое место functions.php темы
 */
function karatt_ajax_data() {

	/**
	 * Первый параметр 'jquery' означает, что код будет прикреплен к скрипту с ID 'jquery'
	 * 'jquery' должен быть добавлен в очередь на вывод, иначе WP не поймет куда вставлять код локализации
	 * Заметка: обычно этот код нужно добавлять в functions.php в том месте где подключаются скрипты, после указанного скрипта
	 */
	wp_localize_script(
		'jquery',
		'spo_ajax',
		array(
			'url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('spo_ajax_nonce'),
		)
	);

	$inview_script_url = plugins_url( '/assets/jquery.inview.js', __FILE__ );
	$script_url = plugins_url( '/assets/spo-script.js', __FILE__ );
	$css_url = plugins_url( '/assets/spo-css.css', __FILE__ );

	wp_enqueue_script( 'inview', $inview_script_url, array( 'jquery' ), '1.0', true );
	wp_enqueue_script( 'spo-script', $script_url, array( 'jquery' ), '1.0', true );
	wp_enqueue_style( 'spo-css', $css_url, array(), '1.0' );

}
add_action( 'wp_enqueue_scripts', 'karatt_ajax_data', 99 );

/**
 * Подключаем AJAX обработчики, только когда в этом есть смысл
 */
if ( wp_doing_ajax() ) {

	add_action( 'wp_ajax_load_sp_offer', 'karatt_action_load_sp_offer' );
	add_action( 'wp_ajax_nopriv_load_sp_offer', 'karatt_action_load_sp_offer' );

}

/**
 * Отдаем по AJAX запросу одно спецпредложение
 */
function karatt_action_load_sp_offer() {

	if ( ! wp_verify_nonce( $_POST['nonce_code'], 'spo_ajax_nonce' ) ) {
		die( 'Stop!' );
	}

	/**
	 * Получаем спецпредложение по ID
	 */
	if ( !empty( $_POST['offer_id'] ) ) {

		$offer_id = intval( $_POST['offer_id'] );

		ob_start();
		$offer = get_post( $offer_id );

		/** Получаем массив с опциями с символом валюты из настроек плагина */
		$spo_options_arr = get_option( 'spo_options' );
		$currency_sign = $spo_options_arr['currency_sign'];

		/** Получаем массив с metadata  спецпредложения */
		$spo_data = get_post_meta( $offer->ID, '_karatt_spo_data', true );
		$regular_price = ( ! empty( $spo_data['price'] ) ) ? $spo_data['price'] . $currency_sign : '';
		$special_price = ( ! empty( $spo_data['sprice'] ) ) ? $spo_data['sprice'] . $currency_sign : '';
		$date_of_action = ( ! empty( $spo_data['date_of_action'] ) ) ? $spo_data['date_of_action'] : '';

		?>

			<div class="spo-row">
				<div class="spo-col-4">
					<?php echo get_the_post_thumbnail( $offer->ID, 'sp-offers-thumb' ) ?>
				</div>
				<div class="spo-col-8">
					<p class="spo-h3"><?php echo esc_html( $offer->post_title ); ?></p>
					<div class="spo-meta">
						<p>
							<span><?php _e( 'Регулярная цена', 'karatt-sp-offers' ); ?>:</span>
							<span><?php echo esc_html( $regular_price ); ?></span>
						</p>
						<p>
							<span><?php _e( 'Специальная цена', 'karatt-sp-offers' );?>:</span>
							<span><?php echo esc_html( $special_price ); ?></span>
						</p>
						<p>
							<span><?php _e( 'Действует до', 'karatt-sp-offers' ); ?>:</span>
							<span><?php echo esc_html( $date_of_action ); ?></span>
						</p>
					</div>
					<div><?php echo esc_html( $offer->post_content ); ?></div>
				</div>
			</div>

		<?php
		ob_get_flush();

	}

}