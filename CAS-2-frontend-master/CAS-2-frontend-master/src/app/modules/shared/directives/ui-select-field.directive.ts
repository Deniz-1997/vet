import {AfterViewInit, Directive, ElementRef, OnDestroy, Optional, Renderer2} from '@angular/core';
import {NgControl} from '@angular/forms';

declare var $: any;

@Directive({
  selector: '[appUiSelectField]'
})
export class UiSelectFieldDirective implements AfterViewInit, OnDestroy {
  private onSelectChange = false;

  constructor(
    private element: ElementRef,
    private renderer: Renderer2,
    @Optional() private control: NgControl
  ) {
    if (!$(this.element.nativeElement).hasClass('select-check')) {
      $(this.element.nativeElement).selectmenu({
        select: (event, ui) => {
          if (this.control && this.control.control) {
            this.onSelectChange = true;
            this.control.control.setValue(ui['item']['value']);
          }
        }
      });
    }
  }

  ngAfterViewInit(): void {
    if (this.control && this.control.control) {
      this.control.control.valueChanges.subscribe(value => {
        if (!this.onSelectChange) {
          $(this.element.nativeElement).selectmenu('refresh');
        } else {
          this.onSelectChange = false;
        }
      });
    }
  }

  ngOnDestroy(): void {
    // this.changes.disconnect();
  }

}
