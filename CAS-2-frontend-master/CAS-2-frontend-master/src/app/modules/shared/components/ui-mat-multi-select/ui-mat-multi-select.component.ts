import {Component, ViewChild, ElementRef, OnInit, Input, Output, EventEmitter} from '@angular/core';
import {AbstractControl, FormControl} from '@angular/forms';
import {Observable} from 'rxjs';
import {map, startWith} from 'rxjs/operators';
import {GroupModel} from '../../../../models/group.models';

@Component({
  selector: 'app-ui-mat-multi-select',
  templateUrl: './ui-mat-multi-select.component.html',
  styleUrls: ['./ui-mat-multi-select.component.css'],
})
export class UiMatMultiSelectComponent implements OnInit {
  @ViewChild('search') searchTextBox: ElementRef;

  @Input() selectFormControl: FormControl | AbstractControl | null;
  searchTextboxControl = new FormControl();
  selectedValues = [];
  @Output() closeSchedule: EventEmitter<any> = new EventEmitter();
  @Input() placeholder = '';
  @Input() data = [];
  @Input() property: any;
  @Input() label: string;

  filteredOptions: Observable<Array<any>>;

  ngOnInit(): void {
    this.filteredOptions = this.searchTextboxControl.valueChanges
      .pipe(
        startWith<string>(''),
        map(name => this._filter(name))
      );
  }

  private _filter(name: string): any {
    const filterValue = name.toLowerCase();
    this.setSelectedValues();
    this.selectFormControl.patchValue(this.selectedValues);
    const filteredList = this.data.filter(option => option.name.toLowerCase().includes(filterValue));
    return filteredList;
  }

  selectionChange(event: any): void {
    if (event.isUserInput && event.source.selected === false) {
      let index = -1;
      if (event.source.value['id']) {
        const elements = this.selectedValues.filter(n => n.id === event.source.value['id']);
        if (elements.length) {
          index = this.selectedValues.indexOf(elements[0]);
        }
      }
      else {
        index = this.selectedValues.indexOf(event.source.value);
      }
      this.selectedValues.splice(index, 1);
    }
  }

  openedChange(e: any): void {
    this.searchTextboxControl.patchValue('');
    if (e === true) {
      this.searchTextBox.nativeElement.focus();
    }
    if (e === false) {
      this.closeSchedule.emit(false);
    }
  }


  clearSearch(event: any): void {
    event.stopPropagation();
    this.searchTextboxControl.patchValue('');
  }


  setSelectedValues(): void {
    if (this.selectFormControl.value && this.selectFormControl.value.length > 0) {
      this.selectFormControl.value.forEach((e) => {
        if (this.selectedValues.indexOf(e) === -1) {
          this.selectedValues.push(e);
        }
      });
    }
  }

  compareFn(o1: GroupModel, o2: GroupModel): boolean {
    return o1 && o2 ? o1.name === o2.name : o2 === o2;
  }
  onDeselectAll(): void {
    this.selectFormControl.patchValue([]);
    this.selectedValues = [];

  }
}
