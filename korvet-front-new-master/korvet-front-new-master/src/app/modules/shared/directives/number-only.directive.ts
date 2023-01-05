import { Directive, ElementRef, HostListener, Input } from '@angular/core';

@Directive({
  selector: '[appNumberOnly]'
})
export class NumberOnlyDirective {

  @Input() numberType: 'float' | 'integer' = 'float';

  private regexFloat: RegExp = new RegExp(/^-?[0-9]+(\.[0-9]*){0,1}$/g);
  private regexInteger: RegExp = new RegExp(/^-?[0-9]+([0-9]*){0,1}$/g);
  private specialKeys: Array<string> = ['Backspace', 'Tab', 'End', 'Home', '-', 'Enter', 'ArrowLeft', 'ArrowRight', 'Meta'];

  constructor(private el: ElementRef) {
  }

  getRegExByNumberType() {
    switch (this.numberType) {
      case 'float':
        return this.regexFloat;
      case 'integer':
        return this.regexInteger;
      default:
        return this.regexFloat;
    }
  }

  @HostListener('keydown', ['$event'])

  onKeyDown(event: KeyboardEvent) {
    if (this.specialKeys.indexOf(event.key) !== -1 || event.ctrlKey || event.metaKey) {
      return true;
    }
    if ((this.el.nativeElement.value as string).includes(',')) {
      this.el.nativeElement.value = (this.el.nativeElement.value as string).replace(',', '.');
    }
    const current: string = this.el.nativeElement.value;
    const next: string = current.concat(event.key === ',' ? '.' : event.key);
    if (next && !String(next).match(this.getRegExByNumberType())) {
      event.preventDefault();
    }
  }

  @HostListener('paste', ['$event'])
  onPaste(event: ClipboardEvent) {
    const clipboardData = event.clipboardData || window['clipboardData'];
    const pastedText = clipboardData.getData('text');
    if (pastedText && !String(pastedText).match(this.getRegExByNumberType())) {
      event.preventDefault();
    }
  }
}
