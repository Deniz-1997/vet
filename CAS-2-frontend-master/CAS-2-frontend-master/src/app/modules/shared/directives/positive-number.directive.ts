import {Directive, ElementRef, HostListener, Input, Optional} from '@angular/core';
import {NgControl} from '@angular/forms';



@Directive({
  selector: '[appPositiveNumber]'
})
export class PositiveNumberDirective {

  private specialKeys: Array<string> = ['Backspace', 'Tab', 'End', 'Home', '-', 'Enter', 'ArrowLeft', 'ArrowRight', 'Meta'];

  constructor(private el: ElementRef, @Optional() private control: NgControl) {
  }

  @HostListener('keydown', ['$event'])

  onKeyDown(event: KeyboardEvent): boolean {
    if (this.specialKeys.indexOf(event.key) === 4) {
      return false;
    }

    if (this.specialKeys.indexOf(event.key) !== -1 || event.ctrlKey || event.metaKey) {
      return true;
    }
  }

  @HostListener('document:mouseup', ['$event'])

  onMouseUp(e: Event): void {
    this.control.control.setValue(+Math.abs(this.el.nativeElement.value).toFixed(2));
  }
}
