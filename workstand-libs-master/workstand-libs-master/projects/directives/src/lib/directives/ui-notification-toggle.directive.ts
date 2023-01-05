import {Directive, ElementRef, HostListener} from '@angular/core';

declare var $: any;

@Directive({
  selector: '[k-ui-notification-toggle]'
})
export class UiNotificationToggleDirective {

  constructor(
    private element: ElementRef
  ) {
  }

  @HostListener('document:mouseup', ['$event']) onMouseUp(e: Event): void {
    const _this = $(this.element.nativeElement);
    const container7 = $('.menu-column__ms-popup, .menu-column__ms-ico');
    if (!container7.is(e.target) && container7.has(e.target).length === 0) {
      $('.menu-column__ms-popup').fadeOut();
      _this.removeClass('active');
    }
  }

  @HostListener('click', ['$event']) onClick(event: Event): void {
    event.preventDefault();
    const _this = $(this.element.nativeElement);
    let leftMarg = 14;
    if ($('.menu-column').hasClass('active')) {
      leftMarg = 14;
    }
    if (_this.hasClass('active')) {
      _this.removeClass('active');
      $('.menu-column__ms-popup').fadeOut();
    } else {
      const top = _this.offset().top - 10 - window.scrollY,
        left = _this.offset().left - leftMarg;
      $('.menu-column__ms-popup').css({'top': top, 'left': left}).fadeIn();
      _this.addClass('active');
    }
  }
}
