import {Directive, ElementRef, HostListener, Input, Optional, OnInit} from '@angular/core';
import {NgControl} from '@angular/forms';
declare var $: any;

@Directive({
  selector: '[k-ui-mask-number]'
})
export class UiMaskNumberDirective implements OnInit {

  @Input() maskNumberMin = 0;
  @Input() maskNumberMax = 10;

  constructor(private element: ElementRef, @Optional() private control: NgControl) {
    // .inp-namber
  }

  ngOnInit() {
    $(this.element.nativeElement).inputmask({
      mask: '9{' + this.maskNumberMin + ',' + this.maskNumberMax + '}',
      placeholder: '',
      onKeyValidation: function (key: any, result: any) {
        if (this.control && result) {
          this.control.control.setValue($(this).val());
        }
      }
    });
  }

  @HostListener('blur', ['$event']) private onChange(e: Event): void {
    this.control.control!.setValue(this.element.nativeElement.value);
  }
}
