import {Directive, ElementRef, HostListener, Input} from '@angular/core';

@Directive({
  selector: '[appNumeric]'
})

export class NumericDirective {

  @Input() decimals = 0;
  @Input() signed = false;
  private specialKeys = [
    'Backspace', 'Tab', 'End', 'Home', 'ArrowLeft', 'ArrowRight', 'Delete'
  ];

  constructor(private el: ElementRef) {
  }

  private static check(value: string, decimals: number, signed: boolean): RegExpMatchArray|string {
    let regExpString: any = /^\d+$/;
    if (decimals <= 0) {
      if (signed) {
        regExpString = /^-?\d+$/;
      }
    } else {
      regExpString = '^((\\d+(\\,?\\d{0,' + decimals + '})?)|(\\d*(\\,?\\d{1,' + decimals + '})))$';
      if (signed) {
        regExpString = '^-?((\\d+(\\,?\\d{0,' + decimals + '})?)|-?(\\d*(\\,?\\d{1,' + decimals + '})))$';
      }
    }
    return value === '-' ? value : String(value).match(new RegExp(regExpString));
  }

  @HostListener('keydown', ['$event'])
  onKeyDown(event: KeyboardEvent): void {
    if (this.specialKeys.indexOf(event.key) !== -1) {
      return;
    }
    // Do not use event.keycode this is deprecated.
    // See: https://developer.mozilla.org/en-US/docs/Web/API/KeyboardEvent/keyCode
    const current: string = this.el.nativeElement.value;
    const next: string = current.concat(event.key);
    if (next && !NumericDirective.check(next, this.decimals, this.signed)) {
      event.preventDefault();
    }
  }
}
