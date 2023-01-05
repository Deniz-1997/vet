import {Component, ElementRef, HostListener, OnInit, ViewChild} from '@angular/core';

declare var $: any;

@Component({
  selector: 'app-lk-main-colorpicker',
  templateUrl: './colorpicker.component.html',
  styleUrls: ['./colorpicker.component.css']
})

export class ColorpickerComponent implements OnInit {
  @ViewChild('selectBody', {static: true}) selectBody: ElementRef;
  @ViewChild('selectMenu', {static: true}) selectMenu: ElementRef;
  @ViewChild('selectColor', {static: true}) selectColor: ElementRef;

  constructor() {
  }

  ngOnInit() {
    this.selectMenu.nativeElement.addEventListener('click', function (e) {
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
    this.selectColor.nativeElement.addEventListener('click', function (e) {
      e.preventDefault();

    });
    this.selectColor.nativeElement
      .querySelectorAll('.ui-selectmenu-popup__item')
      .forEach(el => {
        el.addEventListener('click', function (e) {
          e.preventDefault();
          $(this).parent().fadeOut();
          $(this).parents('.ui-selectmenu-body').find('.ui-selectmenu-js').removeClass('active');
          const name = $(this).text();
          $(this).parents('.ui-selectmenu-body').find('.ui-selectmenu-text').text(name);
          const nameColor = $(this).data('color');
          $('body').attr('data-color', nameColor);
          $.cookie('colorPage', nameColor);
        });
      });
  }

  @HostListener('mouseup', ['$event'])
  onClick(e: Event): void {
    const container5 = $('.ui-selectmenu-body');
    if (!container5.is(e.target) && container5.has(e.target).length === 0) {
      $('.ui-selectmenu-js').removeClass('active');
      $('.ui-selectmenu-popup').fadeOut(100);
    }
  }
}
