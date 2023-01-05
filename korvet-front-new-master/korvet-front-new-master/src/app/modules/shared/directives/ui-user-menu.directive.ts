import {Directive, ElementRef, HostListener} from '@angular/core';

declare var $: any;

@Directive({
  selector: '[appUiUserMenu]'
})
export class UiUserMenuDirective {

  constructor(private element: ElementRef) {
  }

  @HostListener('document:mouseup', ['$event']) onMouseUp(e: Event): void {
    const container8 = $('#user-menu.menu-column__user-menu-dr, .menu-column__user-menu-ico a');
    if (!container8.is(e.target) && container8.has(e.target).length === 0) {
      $('#user-menu.menu-column__user-menu-dr').fadeOut();
      $('#user-menu.menu-column__user-menu-ico a').removeClass('active');
    }
  }

  @HostListener('click', ['$event']) onClick(event: Event): void {
    event.preventDefault();
    const _this = $(this.element.nativeElement);
    if (_this.hasClass('active')) {
      _this.removeClass('active');
      $('#user-menu.menu-column__user-menu-dr').fadeOut();
    } else {
      const top = _this.offset().top + 17,
        left = _this.offset().left - 25;
      $('#user-menu.menu-column__user-menu-dr').css({'top': top, 'left': left}).fadeIn();
      _this.addClass('active');
    }
  }

}
