import {AfterViewInit, Directive, ElementRef, EventEmitter, Input, Optional, Output} from '@angular/core';
import {FormControl, NgControl, NgModel} from '@angular/forms';
import {Observable} from 'rxjs';

declare var $: any;

@Directive({
  selector: '[k-ui-select]',
  providers: [NgModel],

})

export class UiSelectDirective implements AfterViewInit {
  isInit = false;
  @Input() formControl!: FormControl;
  @Output() select: EventEmitter<any> = new EventEmitter();
  @Input() waitOptions!: Observable<any[]>;
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

  ngAfterViewInit() {
    const that = this;

    $(this.element).selectmenu({
      open: (event: any, ui: any) => {
        const width = event.currentTarget.clientWidth;
        $('.ui-menu').css('width', width);
      },
      change: function (event: any, ui: any) {
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
        that.select.emit(val);
        return false;
      }
    });

    if (this.waitOptions) {
      this.waitOptions.subscribe(
        (r) => {
          if (this.isInit && r.length > 0) {
            setTimeout(function () {
              that.onRefresh();
            }, 50);

          }
        }
      );
    }
    this.isInit = true;
  }

  onRefresh() {
    return $(this.element).selectmenu('refresh');
  }
}
