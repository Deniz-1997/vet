import {Component, EventEmitter, Input, OnDestroy, OnInit, Output} from '@angular/core';
import {Observable} from 'rxjs';
import {distinctUntilChanged} from 'rxjs/operators';
import {FormControl} from '@angular/forms';

declare var $: any;

@Component({
  selector: 'app-ui-select',
  templateUrl: './ui-select.component.html',
  styleUrls: ['./ui-select.component.css']
})
export class UiSelectComponent implements OnInit, OnDestroy {
  @Input() control: FormControl;
  @Input() getterName: string | null = null;
  @Input() options: Observable<any[]>;
  @Output() selected: EventEmitter<any> = new EventEmitter();
  bto = null;

  private subscription;

  constructor() {
  }

  ngOnInit() {
    this.control.valueChanges.subscribe(value => {
      this.refresh();
    });
    if (this.options && this.options.pipe) {
      this.subscription = this.options.pipe(distinctUntilChanged()).subscribe(
        () => {
          this.refresh();
        });
    }
  }

  refresh() {
    /*не лечиться в директиве почему-то*/
    if (this.bto) {
      clearTimeout(this.bto);
    }
    this.bto = setTimeout(() => {
      $('app-ui-select select').selectmenu('refresh');
    }, 200);
  }

  onSelected() {
    this.selected.emit();
  }

  ngOnDestroy() {
    if (this.subscription) {
      this.subscription.unsubscribe();
    }
  }

  getValueName(option: any) {
    if (this.getterName !== null && option[this.getterName]) {
      return option[this.getterName]();
    }
    return option.name;
  }
}
