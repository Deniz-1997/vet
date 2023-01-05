import {AfterViewInit, Directive, ElementRef, EventEmitter, Input, Optional, Output} from '@angular/core';
import {FormControl, NgControl, NgModel} from '@angular/forms';
import {Observable} from 'rxjs';

declare var $: any;

@Directive({
  selector: '[appUiSelect]',
  providers: [NgModel],

})

export class UiSelectDirective implements AfterViewInit {
  isInit = false;
  @Input() formControl: FormControl;
  @Output() selected: EventEmitter<any> = new EventEmitter();
  @Input() waitOptions: Observable<Array<any>>;
  private element: HTMLSelectElement;

  constructor(private elRef: ElementRef,
              private model: NgModel,
              @Optional() private control: NgControl
  ) {
    this.element = elRef.nativeElement;
    $(this.element).addClass('overflow');
  }

  /*@HostListener('ngModelChange') onNgModelChange($event) {
    console.log('$event', $event);
  }*/

  @Input()
  set refresh(isRefresh: boolean) {
    if (isRefresh) {
      $(this.element).selectmenu('refresh');
    }
  }

  ngAfterViewInit(): void {
    const that = this;

    $(this.element).selectmenu({
      open: (event, ui) => {
        const width = event.currentTarget.clientWidth;
        $('.ui-menu').css('width', width);
      },
      change: (event, ui) => {
        let val = ui.item.value.split(':');
        if (val.length > 1) {
          val = parseInt($.trim(ui.item.value.split(':')[1]), 10);
        } else if (ui.item.value === 'null') {
          val = null;
        } else if (!isNaN(val)) {
          val = parseInt($.trim(ui.item.value), 10);
        } else {
          val = val[0]; // if string
        }

        if (that.control && that.control.control) {
          that.control.control.setValue(val);
        }
        if (that.model.valueAccessor) {
          that.model.valueAccessor.writeValue(val);
        }
        that.selected.emit(val);
        return false;
      }
    });

    if (this.waitOptions) {
      this.waitOptions.subscribe(
        (r) => {
          if (this.isInit && r.length > 0) {
            setTimeout(() => {
              that.onRefresh();
            }, 50);

          }
        }
      );
    }
    this.isInit = true;
  }

  onRefresh(): any {
    return $(this.element).selectmenu('refresh');
  }
}
