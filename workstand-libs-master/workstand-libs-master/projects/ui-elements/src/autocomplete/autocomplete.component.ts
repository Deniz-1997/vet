import {Component, EventEmitter, Input, OnDestroy, OnInit, Output} from '@angular/core';
import {AbstractControl, FormControl} from '@angular/forms';
import {Subject} from 'rxjs';
import {debounceTime, distinctUntilChanged, takeUntil} from 'rxjs/operators';



export class AutocompleteModel {
  id!: number;
  name!: string;
  address!: string;
}

@Component({
  selector: 'k-autocomplete',
  templateUrl: './autocomplete.component.html',
  styleUrls: ['./autocomplete.component.css'],
})

export class AutocompleteComponent implements OnInit, OnDestroy {
  @Input() label!: string;
  @Input() placeholder!: string;
  @Input() field = 'name';

  @Input() control!: FormControl | AbstractControl;

  @Output() selected: EventEmitter<any> = new EventEmitter();
  @Output() asyncValue: EventEmitter<any> = new EventEmitter();

  @Input() disabled?: false;
  @Input() required?: false;
  private isInit = false;

  @Input() filteredOptions!: Array<AutocompleteModel>;
  private destroy$ = new Subject<any>();


  constructor() {
  }

  ngOnInit(): void {
  }

  onSelected(): void {
    this.selected.emit(this.control.value);
  }

  onBlur(): void {
    if (typeof this.control.value === 'string') {
      this.control.setValue(null);
      this.selected.emit();
    }
  }

  onFocus(): any {
    if (!this.isInit) {
      this.control.valueChanges
        .pipe(
          debounceTime(1000),
          distinctUntilChanged(),
          takeUntil(this.destroy$)
        )
        .subscribe(value => {
          if (!value || value.length < 2) {
            this.selected.emit();
            return this.filterAsync('');
          } else if (value && value.length > 1 && typeof value === 'string') {
            return this.filterAsync(value);
          }
        });
      this.isInit = true;
    }
    if (this.control.value) {
      this.filterAsync(this.control.value);
    } else {
      this.filterAsync('');
    }
  }

  displayPetType(value: any): string {
    if (value && typeof value === 'object') {
      return value[this.field];
    } else if (value && typeof value === 'string') {
      return value;
    } else {
      return '';
    }
  }

  private filterAsync(value: any): any {
    this.asyncValue.emit(value);
  }

  ngOnDestroy(): void {
    this.destroy$.next();
  }
}
