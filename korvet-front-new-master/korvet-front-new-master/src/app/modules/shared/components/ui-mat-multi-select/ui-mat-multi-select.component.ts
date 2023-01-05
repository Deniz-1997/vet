import {Component, ViewChild, ElementRef, OnInit, Input, Output, EventEmitter, HostListener, ChangeDetectorRef} from '@angular/core';
import {AbstractControl, FormControl} from '@angular/forms';
import {MatSelectionListChange} from '@angular/material/list';
import {Params} from '@angular/router';
import {Store} from '@ngrx/store';
import {debounceTime, distinctUntilChanged} from 'rxjs/operators';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {CrudType} from 'src/app/common/crud-types';

@Component({
  selector: 'app-ui-mat-multi-select',
  templateUrl: './ui-mat-multi-select.component.html',
  styleUrls: ['./ui-mat-multi-select.component.css'],
})
export class UiMatMultiSelectComponent implements OnInit {
  @ViewChild('search') searchTextBox: ElementRef;
  @ViewChild('resultInput', {read: ElementRef}) resultInput: ElementRef;
  @ViewChild('panel', {read: ElementRef}) panel: ElementRef;

  @Input() addFilter: any;
  @Input() selectFormControl: FormControl | AbstractControl | null;
  @Input() placeholder = 'Выберите значения';
  @Input() data = [];
  @Input() type: CrudType;
  searchTextboxControl = new FormControl();
  dataLoading = false;
  selectedValues = new Array<any>();
  panelOpened = false;
  resultInputValue = '';

  @HostListener('document:click', ['$event.target'])
  onGlobalClick(target): void {
    if (this.panelOpened) {
      this.changeDetector.detectChanges();
      if (this.resultInput.nativeElement.contains(target) || target.innerText === 'close' 
      || (target as HTMLElement).className === 'mat-list-text' || (target as HTMLElement).className === 'mat-list-item-content') {
        return;
      }
      if (!this.panel.nativeElement.contains(target)) {
        this.panelOpened = false;
      }
    }
  }

  constructor(
    protected store: Store<CrudState>,
    private changeDetector: ChangeDetectorRef
  ) {
    this.searchTextboxControl.valueChanges.pipe(
      debounceTime(1000),
      distinctUntilChanged()
    ).subscribe(_ => {
      this.loadProducts();
    });
  }

  ngOnInit() {
    if ((!this.data || this.data.length === 0) && this.type) {
      this.loadProducts();
    }

    if (this.selectFormControl.value && this.selectFormControl.value.length > 0) {
      this.selectedValues = this.selectFormControl.value;
      this.setInputString();
    }
  }

  selectionChange(event: MatSelectionListChange) {
    if (event.options[0].selected && !this.selectedValues.find(n => n.id === event.options[0].value['id'])) {
      this.selectedValues.push(event.options[0].value);
    }
    if (!event.options[0].selected) {
      const item = this.selectedValues.find(n => n.id === event.options[0].value['id']);
      if (item) {
        const index = this.selectedValues.indexOf(item);
        this.selectedValues.splice(index, 1);
      }
    }
    this.setInputString();
  }

  private setInputString(): void {
    this.resultInputValue = '';
    for (const item of this.selectedValues) {
      this.resultInputValue += item['name'] + '; ';
    }
    this.setFormControlValue();
  }

  isOptionSelected(option): boolean {
    return this.selectedValues.find(n => n.id === option['id']) ? true : false;
  }

  onDeselectAll() {
    this.selectedValues = new Array<any>();
    this.setInputString();
    this.data = new Array<any>();
    this.loadProducts();
  }

  private setFormControlValue() {
    this.selectFormControl.setValue(this.selectedValues);
  }

  openPanel() {
    this.panelOpened = !this.panelOpened;
    if (this.panelOpened) {
      this.changeDetector.detectChanges();
      this.searchTextBox.nativeElement.focus();
    }
  }

  clearSearch($event): void {
    this.searchTextboxControl.setValue(null);
  }

  private loadProducts() {
    let filter: Params = {};
    if (this.addFilter) {
      filter = Object.assign({}, this.addFilter);
    }
    if (this.searchTextboxControl.value) {
      filter['~name'] = this.searchTextboxControl.value;
    }
    this.dataLoading = true;
    this.store.dispatch(new LoadGetListAction({
      type: this.type,
      params: {
        fields: {0: 'id', 1: 'name'},
        order: {name: 'ASC'},
        filter: filter,
        offset: 0,
        limit: 30
      },
      onSuccess: (res) => {
        if (res.status && res.response && res.response.items) {
          this.data = res.response.items;
        }
        this.dataLoading = false;
      },
      onError: _ => {
        this.dataLoading = false;
      }
    }));
  }
}
