$(document).ready(function () {


  $(document).on('click', '.page-lnk-open', function (e) {
    e.preventDefault();
    $('.page-lnk').fadeToggle()
  });

  // placeholder close
  $('input,textarea').focus(function () {
    $(this).data('placeholder', $(this).attr('placeholder'))
    $(this).attr('placeholder', '');
  });
  $('input,textarea').blur(function () {
    $(this).attr('placeholder', $(this).data('placeholder'));
  });


  // роскрытие меню
  $('.menu-column__slider-h').each(function () {
    if (!$(this).next('ul').length) {
      $(this).parent().addClass('menu-column__slider-st');
    }
  });
  $(document).on('click', '.menu-column__slider-h', function (e) {
    if ($(this).next('ul').length) {
      e.preventDefault();
      if ($(this).parent().hasClass('active')) {
        $(this).parent().toggleClass('active').find('.menu-column__slider-lnk').slideUp();
      } else {
        $('.menu-column__slider').removeClass('active');
        $('.menu-column__slider-lnk').slideUp();
        $(this).parent().toggleClass('active').find('.menu-column__slider-lnk').slideDown();
      }
    }
  });

  // Выбор даты
  $(".datepicker").datepicker({
    dateFormat: 'd MM yy'
  }).datepicker("setDate", new Date());

  // Селект
  $("select").each(function () {
    if (!$(this).hasClass('select-check')) {
      $(this).selectmenu({
        open: function (event, ui) {
          const width = event.currentTarget.clientWidth;
          $('.ui-menu').css('width', width);
        }
      })
        .selectmenu("menuWidget")
        .addClass("overflow");
    }
  });
  $(window).load(function () {
    $('select').each(function () {
      const _this = $(this),
        width = _this.parent().width();
      if (width > 30) {
        _this.parent().css('width', width);
        _this.parents('.form-span').css('width', width + 20);
      }
    });
  });

  $('[data-fancybox]').fancybox({
    touch: false,
    autoFocus: false
  });
  $(document).on('click', '[data-fancybox-delet]', function (e) {
    e.preventDefault();
    $.fancybox.close();
    $.fancybox.open({
      src: $(this).attr('href'),
      opts: {
        touch: false,
        autoFocus: false
      }
    });
  });

  // slider
  $(document).on('click', '.slider__head a', function (e) {
    e.preventDefault();
    if ($(this).parent().hasClass('active')) {
      $(this).parent().removeClass('active').next().slideUp();
    } else {
      $('.slider__head').removeClass('active');
      $('.slider__body').slideUp();
      $(this).parent().addClass('active').next().slideDown();
    }
  });

  // scroll
  $('.popup-wr__table').mCustomScrollbar();
  $('.table-hor').mCustomScrollbar({
    axis: "x",
    advanced: {autoExpandHorizontalScroll: true}
  });


  $(document).on('click', '.fade-head', function (e) {
    e.preventDefault();
    if ($(this).hasClass('active')) {
      $(this).removeClass('active').next().slideUp();
    } else {
      $('.fade-head').removeClass('active');
      $('.fade-body').slideUp();
      $(this).addClass('active').next().slideDown();
    }
  });

  // mask jquery.inputmask.bundle
  $('.time-mask').inputmask("99 : 99");


  // следим за отмеченостью чеков
  $('[data-checked]').each(function () {
    const id = $(this).data('checked');
    if ($(this).is(":checked")) {
      $(id).show()
    } else {
      $(id).hide()
    }
  });
  $('[data-checked]').each(function () {
    $(this).change(function () {
      const id = $(this).data('checked');
      if ($(this).is(":checked")) {
        $(id).show()
      } else {
        $(id).hide()
      }
    });
  });

  $('.form-map').each(function () {
    let row = $(this).parents('.form-row');
    if (!row.hasClass('form-row--column')) {
      row.addClass('form-row--block');
    }
  });

  // selectmenu checkbox
  function selectWidth() {
    $('.ui-selectmenu-js').each(function () {
      let _this = $(this);
      _this.css({'width': 'auto', 'position': 'absolute'});
      let width = _this.parent().width();
      _this.css({'width': width, 'position': 'relative'});
    });
    $('select').each(function () {
      let _this = $(this),
        width = _this.parent().width();
      if (width > 30) {
        _this.parent().css('width', width);
        _this.parents('.form-span').css('width', width + 20);
      }
    });
  };
  $(window).load(selectWidth);
  $(window).resize(selectWidth);
  $(document).on('click', '.ui-selectmenu-js', function (e) {
    e.preventDefault();
    $(this).next().css('width', $(this).outerWidth());
    if ($(this).hasClass('active')) {
      $(this).removeClass('active').next().fadeOut();
    } else {
      $('.fade-head').removeClass('active');
      $('.fade-body').slideUp();
      $(this).addClass('active').next().fadeIn();
    }
  });
  $(document).on('click', '.ui-selectmenu-popup--color .ui-selectmenu-popup__item', function (e) {
    e.preventDefault();
    $(this).parent().fadeOut();
    $(this).parents('.ui-selectmenu-body').find('.ui-selectmenu-js').removeClass('active');
    let name = $(this).text();
    $(this).parents('.ui-selectmenu-body').find('.ui-selectmenu-text').text(name);
  });


  // status select
  $(document).on('click', '.status-select__head', function (e) {
    e.preventDefault();
    if ($(this).hasClass('active')) {
      $(this).removeClass('active').next().fadeOut();
    } else {
      $('.status-select__head').removeClass('active');
      $('.status-select__body').fadeOut();
      $(this).addClass('active').next().fadeIn();
    }
  });
  $(document).on('click', '.status-select__item a', function (e) {
    e.preventDefault();
    $('.status-select__head').removeClass('active');
    $('.status-select__body').fadeOut();
    let color = $(this).data('color');
    $(this).parents('.status-select').find('.status-select__head').text($(this).text()).attr('data-color', color)
  });

  // ввод только цифр
  $('.inp-namber').each(function () {
    let _this = $(this),
      max = _this.data('max'),
      min = _this.data('min'),
      maskCode = "+79{" + min + "," + max + "}";
    _this.inputmask({
      mask: maskCode,
      placeholder: ""
    });
  });

  // Год
  $('.input-year').inputmask("9999");


  let markerUrl = document.location.pathname;
  let urlName = markerUrl.slice(8);
  console.log(urlName);
  let urlHref = '.page-lnk a[href="' + urlName + '"]';
  if (!$(urlHref).length) {
    urlName = markerUrl.slice(1);
    urlHref = '.page-lnk a[href="' + urlName + '"]';
  }
  $(urlHref).addClass('active');


  // левое меню (сворачивание)
  $('.body-column').append("<div class='menu-column__pointer'><a href='#'></a></div>");
  $(document).on('click', '.menu-column__pointer a', function (e) {
    e.preventDefault();
    if ($(this).hasClass('active')) {
      $(this).removeClass('active');
      $('.menu-column').removeClass('active');
    } else {
      $(this).addClass('active');
      $('.menu-column').addClass('active');
    }
  });

  // btn-act
  $(document).on('click', '.btn-act', function (e) {
    e.preventDefault();
    $(this).toggleClass('active')
  });

  // расширенный поиск
  $(document).on('click', '.popup-wr__lnk-extended', function (e) {
    e.preventDefault();
    $('.popup-wr__lnk-extended').fadeToggle();
    $('.search-detals').fadeToggle();
    selectWidth();
  });
  $(document).on('click', '.search-detals__close a', function (e) {
    e.preventDefault();
    $('.popup-wr__lnk-extended').fadeToggle();
    $('.search-detals').fadeToggle();
  });


  // закрытие попапа
  $(document).on('click', '.btn-close-popup', function (e) {
    e.preventDefault();
    // $(this).parents('.fancybox-content').fancybox.getInstance().close();
  });


  // генерация селекта в список
  $('.select-check').each(function () {
    let _this = $(this),
      option = _this.find('option'),
      HTMLlist = '';
    for (let i = 0, l = option.length; i < l; i += 1) {
      let active = option.eq(i),
        text = active.text(),
        id = active.attr('value'),
        check = active.data('check'),
        checked = '';
      if (check == true) {
        checked = 'checked'
      }
      HTMLlist += '<div class="ui-selectmenu-popup__item">' +
        '<div class="check-st">' +
        '<input id="' + id + '" type="checkbox" ' + checked + '>' +
        '<label for="' + id + '">' + text + '</label>' +
        '</div>' +
        '</div>';
    }
    ;
    let placeholder = _this.attr('placeholder'),
      HTML = '<span id="" class="ui-selectmenu-js ui-selectmenu-button ui-selectmenu-button-closed ui-corner-all ui-button ui-widget">' +
        '<span class="ui-selectmenu-icon ui-icon ui-icon-triangle-1-s"></span>' +
        '<span class="ui-selectmenu-text">' + placeholder + '</span>' +
        '</span>' +
        '<div class="ui-selectmenu-popup ui-selectmenu-check" style="min-width: 342px">' +
        // '<div class="ui-selectmenu-popup__header">Можно выбрать несколько</div>' +
        '<div class="ui-selectmenu-popup__search">' +
        '<input type="text" class="inp-st" placeholder="Поиск">' +
        '</div>' +
        '<div class="ui-selectmenu-popup__list">' +
        HTMLlist +
        '</div>' +
        '</div>';
    $(HTML).insertAfter(_this);
  });

  // поиск по селекту
  $(document).on('keyup', '.ui-selectmenu-popup__search .inp-st', function (e) {
    let _this = $(this),
      q = new RegExp(_this.val(), 'ig'),
      body = _this.parents('.ui-selectmenu-body'),
      option = body.find('option'),
      HTMLlist = '';
    for (let i = 0, l = option.length; i < l; i += 1) {
      let active = option.eq(i),
        text = active.text(),
        id = active.attr('value'),
        check = active.attr('data-check'),
        checked = '';
      if (check == 'true') {
        checked = 'checked'
      }
      if (text.match(q)) {
        HTMLlist += '<div class="ui-selectmenu-popup__item">' +
          '<div class="check-st">' +
          '<input id="' + id + '" type="checkbox" ' + checked + '>' +
          '<label for="' + id + '">' + text + '</label>' +
          '</div>' +
          '</div>';
      }
    }
    ;
    body.find('.ui-selectmenu-popup__list').remove();
    HTMLlist = '<div class="ui-selectmenu-popup__list">' +
      HTMLlist +
      '</div>';
    let serach = body.find('.ui-selectmenu-popup__search');
    $(HTMLlist).insertAfter(serach);
    body.find('.ui-selectmenu-popup__list').mCustomScrollbar();
  });

  // счледим за чекбоксом ui-selectmenu-check
  $(document).on('change', '.ui-selectmenu-check input', function (e) {
    let _this = $(this),
      id = _this.attr('id'),
      select = _this.parents('.ui-selectmenu-body').find('[value="' + id + '"]');
    if (_this.prop("checked")) {
      select.addClass('active');
      select.attr('data-check', 'true')
    } else {
      select.removeClass('active');
      select.attr('data-check', 'false')
    }
  });


  // скрол
  $('.ui-selectmenu-popup__list').mCustomScrollbar();


  //Роказываем карту по клику
  $(document).on('click', '.map-show', function (e) {
    e.preventDefault();
    let _this = $(this),
      txtShow = 'Показать карту',
      txtHide = 'Скрыть карту';
    if (_this.hasClass('active')) {
      _this.text(txtShow).removeClass('active').parent().next().removeClass('active').css('display', 'none');
    } else {
      _this.text(txtHide).addClass('active').parent().next().addClass('active').css('display', 'table');
    }
  });

  // Замена авы
  $(document).on('click', '.ava-edit', function (e) {
    e.preventDefault();
    $(this).next().trigger('click')
  });

  //  открытие попапы событий
  $(document).on('click', '.menu-column__ms-ico a', function (e) {
    e.preventDefault();
    let _this = $(this),
      _popup = $('.menu-column__ms-popup'),
      html = _this.html();
    if (_this.hasClass('active')) {
      _this.removeClass('active');
      _popup.fadeOut();
    } else {
      let top = _this.offset().top - 10,
        left = _this.offset().left - 14;
      _popup.css({'top': top, 'left': left}).fadeIn();
      _this.addClass('active');
      _popup.find('.popup-close').remove();
      _popup.prepend('<a href="" class="popup-close">' + html + '</a>');
    }
  });
  $(document).on('click', '.popup-close', function (e) {
    e.preventDefault();
    $('.menu-column__ms-popup').fadeOut();
    $('.menu-column__ms-ico a').removeClass('active');
  });

  //
  $(document).on('click', '.menu-column__user-menu-ico a', function (e) {
    e.preventDefault();
    let _this = $(this);
    if (_this.hasClass('active')) {
      _this.removeClass('active');
      $('.menu-column__user-menu-dr').fadeOut();
    } else {
      let top = _this.offset().top + 17,
        left = _this.offset().left - 25;
      $('.menu-column__user-menu-dr').css({'top': top, 'left': left}).fadeIn();
      _this.addClass('active');
    }
  });
  $(document).on('click', '.menu-column__user-menu-dr .menu-column__user-menu-ico a', function (e) {
    e.preventDefault();
    $('.menu-column__user-menu-ico a').removeClass('active');
    $('.menu-column__user-menu-dr').fadeOut();
  });

  if ($('.menu-column__user-menu-ico a').length) {
    $(window).scroll(function () {
      if ($('.menu-column__user-menu-dr:visible').length || $('.menu-column__ms-popup:visible').length) {
        $('.menu-column__user-menu-dr').hide();
        $('.menu-column__user-menu-ico a').removeClass('active');
        $('.menu-column__ms-popup').hide();
        $('.menu-column__ms-ico a').removeClass('active');
      }
    })
  }
  ;

  // Уведомления
  $(document).on('click', '.notification__body-left a', function (e) {
    e.preventDefault();
    $(this).parents('.notification__body').next().slideToggle();
  });


  // Изменение цветов
  $(document).on('click', '.ui-selectmenu-popup--color .ui-selectmenu-popup__item', function (e) {
    e.preventDefault();
    let nameColor = $(this).data('color');
    $('body').attr('data-color', nameColor);
    $.cookie('colorPage', nameColor);
  });
  let colorPage = $.cookie('colorPage');
  if (colorPage != 'undefined') {
    $('body').attr('data-color', colorPage);
    $('.' + colorPage).addClass('active');
  }
  ;


  // Переход по атрибуду data-href
  $(document).on('click', '[data-href]', function (e) {
    e.preventDefault();
    document.location.href = $(this).data('href');
  });


  if ($('.page-404__wr').length) {
    fix404();
    $(window).resize(fix404)
  }
  ;


  // Сообщения
  function ms(text, cl) {
    $('.page-message-wr').remove();
    let message = '<div class="page-message-wr">' +
      '<div class="page-message ' + cl + '">' +
      '<div class="page-message-txt">' + text + '</div><a href="" class="page-message__close"></a>' +
      '</div>' +
      '</div>';
    $('.body-column').prepend(message);
    $('.page-message').css('top', '-100px').animate({'top': -10}, 400);
  };
  $(document).on('click', '.page-message__close', function (e) {
    e.preventDefault();
    $('.page-message-wr').fadeOut();
    setTimeout("$('.page-message-wr').remove()", 1000);
  });
  $(document).on('click', '.ms-ok', function (e) {
    e.preventDefault();
    ms('Пароль успешно изменен', 'message-ok');
  });
  $(document).on('click', '.ms-info', function (e) {
    e.preventDefault();
    ms('Информационное сообщение', 'message-info');
  });
  $(document).on('click', '.ms-error', function (e) {
    e.preventDefault();
    ms('Ошибка изменения пароля', 'message-error');
  });

  $('.animal-photo-owl').owlCarousel({
    loop: true,
    nav: true,
    dots: false,
    items: 1
  });

  $(document).on('click', '.animal-photo-close', function (e) {
    e.preventDefault();
    $(this).parent().remove();
  });


});

function fix404() {
  $('.page-404__wr').css('height', $(window).height())
}


$(document).mouseup(function (e) {
  let container = $(".time-counter__wr");
  if (!container.is(e.target) && container.has(e.target).length === 0) {
    $('.time-counter__popup').fadeOut(100);
    $('.js-clock-popup').removeClass('active')
  }
  ;

  let container2 = $(".account__notice");
  if (!container2.is(e.target) && container2.has(e.target).length === 0) {
    $('.notice__wr').fadeOut(100);
    $('.account__notice-ico').removeClass('active')
  }
  ;

  let container3 = $(".account__notice, .head-menu__open");
  if (!container3.is(e.target) && container3.has(e.target).length === 0) {
    $('.head-menu__wr').fadeOut(100);
    $('.head-menu__open').removeClass('active')
  }
  ;

  let container4 = $(".profile-counter, .profile-counter__popup");
  if (!container4.is(e.target) && container4.has(e.target).length === 0) {
    $('.profile-counter__popup').fadeOut(100);
  }
  ;


  let container5 = $(".ui-selectmenu-body");
  if (!container5.is(e.target) && container5.has(e.target).length === 0) {
    $('.ui-selectmenu-js').removeClass('active');
    $('.ui-selectmenu-popup').fadeOut(100);
  }
  ;
  let container6 = $(".status-select");
  if (!container6.is(e.target) && container6.has(e.target).length === 0) {
    $('.status-select__head').removeClass('active');
    $('.status-select__body').fadeOut(100);
  }
  ;

  let container7 = $(".menu-column__ms-popup, .menu-column__ms-ico");
  if (!container7.is(e.target) && container7.has(e.target).length === 0) {
    $('.menu-column__ms-popup').fadeOut();
    $('.menu-column__ms-ico a').removeClass('active');
  }
  ;

  let container8 = $(".menu-column__user-menu-dr, .menu-column__user-menu-ico a");
  if (!container8.is(e.target) && container8.has(e.target).length === 0) {
    $('.menu-column__user-menu-dr').fadeOut();
    $('.menu-column__user-menu-ico a').removeClass('active');
  }
  ;
});



