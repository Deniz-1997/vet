import {Component, EventEmitter, Input, OnDestroy, OnInit, Output} from '@angular/core';
import {AbstractControl, FormControl} from '@angular/forms';
import {Observable, Subject} from 'rxjs';
import {debounceTime, distinctUntilChanged, takeUntil} from 'rxjs/operators';
import {CrudType} from 'src/app/common/crud-types';
import {NotifyService} from '../../../../services/notify.service';
import {PricePipe} from '../../pipes/price.pipe';
import {DaDataService} from '../../../../services/daData.service';
import {DadataSuggestion} from '../../../../models/dadata/suggestions.models';
import {DadataResponse} from '../../../../models/dadata/dadata-response.models';
import {select, Store} from '@ngrx/store';
import {CrudState} from '../../../../api/api-connector/crud/crud-store.service';

export class AutocompleteModel {
  id?: number;
  surname?: string;
  name?: string;
  patronymic?: string;
}


@Component({
  selector: 'app-dadata',
  templateUrl: './dadata.component.html',
  styleUrls: ['./dadata.component.css']
})

export class DadataComponent implements OnInit, OnDestroy {
  @Input() type: CrudType;
  @Input() control: FormControl | AbstractControl | null;
  @Input() options?: Observable<Array<AutocompleteModel>>;
  @Output() selected: EventEmitter<any> = new EventEmitter();
  @Output() selectedGender: EventEmitter<any> = new EventEmitter();
  @Input() placeholder: string;
  @Input() required?: false;
  @Input() disabled?: false;
  @Input() onChange?: EventEmitter<any> = new EventEmitter();
  @Input() parts: string;
  @Input() field: string;
  @Input() label: string;
  loading = false;
  token: string;
  filteredOptions = [];
  loading$: Observable<boolean>;
  private destroy$ = new Subject<any>();
  private isInit = false;
  data: Array<DadataSuggestion> = [];


  constructor(
    private pricePipe: PricePipe,
    private notify: NotifyService,
    private store: Store<CrudState>,
    private dadata: DaDataService) {
  }

  ngOnInit(): void {
  }

  onSelected(): void {
    this.selected.emit(this.control.value);
  }

  onFocus(): void {
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
  }

  ngOnDestroy(): void {
    this.destroy$.next();
  }

  private filterAsync(value: any): any {
    this.loading = true;
    this.filteredOptions = [];
    this.dadata.daDataFunc(value, this.parts).subscribe((y: DadataResponse) => {
      this.data = y.suggestions;
      for (const a in this.data) {
        if (Object.keys(this.data[a]).length !== 0) {
          const data = this.data[a]['data'][this.field];
          if (this.field === 'name') {
            this.selectedGender.emit(this.data[a]['data']['gender']);
          }
          this.filteredOptions.push(data);
        }
      }
    });
    this.loading = false;
    return this.filteredOptions;
  }
}
