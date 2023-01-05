import {AfterViewInit, ChangeDetectorRef, Component, ElementRef, Input, OnChanges, OnDestroy} from '@angular/core';
import {FormControl, NgControl} from '@angular/forms';
import * as _ from 'lodash';
import {Subscription} from 'rxjs';

declare var $: any;

export interface OptionType {
  title: string;
  value: any;
  id?: any;
  name?: string;
  disabled?: boolean;
}

@Component({
  selector: '[app-ui-select-field]',
  templateUrl: './ui-select-field.component.html',
  styleUrls: ['./ui-select-field.component.css']
})
export class UiSelectFieldComponent implements AfterViewInit, OnChanges, OnDestroy {

  @Input() addNone = true;
  @Input() nullTitle = 'Не выбран';
  @Input() excludeChosen = false;
  titleControl: FormControl = null;
  nameControl: FormControl = null;
  private onSelectChange = false;
  private subscription: Subscription;

  constructor(
    private element: ElementRef,
    private control: NgControl,
    private cdr: ChangeDetectorRef,
  ) {
  }

  _options: OptionType[];

  get options() {
    return this._options;
  }

  /*@ViewChild('selectElement')
  private element: ElementRef;*/

  @Input() set options(options) {
    // const ops = options ? options.filter(o => o.id !== 1) : [];
    const ops = options || [];
    if (options && this.control && (this.titleControl || this.nameControl) && ops.every(o => o.id !== this.control.control.value)) {
      const option = {
        id: this.control.control.value,
        name: this.titleControl.value || this.nameControl.value,
        disabled: true
      };
      this._options = <OptionType[]>[...ops, option];
    } else {
      this._options = ops;
    }
  }

  ngAfterViewInit() {
    const parent = this.control.control.parent;
    // if (parent instanceof FormGroup) {
    //   parent.addControl('name', new FormControl());
    //   parent.addControl('title', new FormControl());
    //   this.titleControl = parent.get('title') as FormControl;
    //   this.nameControl = parent.get('name') as FormControl;
    // }
    this.subscription = new Subscription();
    $(this.element.nativeElement).selectmenu({
      select: (event, ui) => {
        if (this.control && this.control.control) {
          this.onSelectChange = true;
          this.control.control.setValue(
            (_.isNil(ui['item']['value']) || ui['item']['value'] === 'null') ?
              null :
              (
                _.isNaN(+ui['item']['value']) ?
                  ui['item']['value'] : +ui['item']['value']
              )
          );
        }
      }
    }).addClass('overflow');
    if (!$(this.element.nativeElement).hasClass('select-check')) {
      $(this.element.nativeElement).selectmenu({
        select: (event, ui) => {
          if (this.control && this.control.control) {
            this.onSelectChange = true;
            this.control.control.setValue(
              (_.isNil(ui['item']['value']) || ui['item']['value'] === 'null') ?
                null :
                (
                  _.isNaN(+ui['item']['value']) ?
                    ui['item']['value'] : +ui['item']['value']
                )
            );
          }
        },
        open: (event, ui) => {
          const width = event.currentTarget.clientWidth;
          $('.ui-menu').css('width', width);
        }
      });
    }
    if (this.control && this.control.control) {
      this.subscription.add(
        this.control.control.valueChanges.subscribe(value => {
          if (!this.onSelectChange) {
            this.refreshValue(value);
          } else {
            this.onSelectChange = false;
          }
        })
      );
    }
    this.cdr.detectChanges();
  }

  ngOnChanges(changes): void {
    if (changes['options']) {
      this.options = changes['options'].currentValue;
      this.refreshValue(this.control.control.value || 'null');
    }
  }

  ngOnDestroy(): void {
    this.subscription.unsubscribe();
  }

  disabledOption(option: OptionType): boolean {
    if (option.disabled) {
      return option.disabled;
    }
    if (!this.excludeChosen) {
      return null;
    }
    const value = option['id'] || option['value'];
    return value === this.control.control.value ? true : null;
  }

  private refreshValue(value) {
    setTimeout(() => {
      $(this.element.nativeElement).val(value);
      $(this.element.nativeElement).selectmenu('refresh');
    });
  }
}
