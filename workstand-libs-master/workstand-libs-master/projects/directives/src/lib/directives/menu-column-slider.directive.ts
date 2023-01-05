import { Directive, ElementRef, HostListener } from '@angular/core';
declare var $: any;

@Directive({
  selector: '[k-menu-column-slider]'
})
export class MenuColumnSliderDirective {

  constructor(private element: ElementRef) { }

  @HostListener('click', ['$event']) onClick(e: Event): void {
    if (!$('.menu-column').hasClass('active')) {
      e.preventDefault();
      if ($(this.element.nativeElement).parent().hasClass('active')) {
        $(this.element.nativeElement).parent().toggleClass('active').find('.menu-column__slider-lnk').slideUp();
      } else {
        $('.menu-column__slider').removeClass('active');
        $('.menu-column__slider-lnk').slideUp();
        $(this.element.nativeElement).parent().toggleClass('active').find('.menu-column__slider-lnk').slideDown();
      }
    }
  }
}
