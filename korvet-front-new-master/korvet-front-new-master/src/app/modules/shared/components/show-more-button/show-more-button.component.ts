import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {CrudType} from '../../../../common/crud-types';
import {select, Store} from '@ngrx/store';
import {CrudDataType, CrudDataInterface} from 'src/app/api/api-connector/crud/crud.config';
import {getCrudModelData} from 'src/app/api/api-connector/crud/crud.selectors';

@Component({
  selector: 'app-show-more-button',
  templateUrl: './show-more-button.component.html'
})
export class ShowMoreButtonComponent implements OnInit {
  @Input() limit;
  @Input() length;
  @Input() offset;
  @Input() items: CrudDataType[] | CrudDataInterface[] = [];
  @Input() type: CrudType;
  @Input() appendLoading;
  @Input() totalCount;
  @Output() outAppend = new EventEmitter<{ limit: number, offset: number }>();

  constructor(private store: Store<CrudType>) {
  }

  ngOnInit() {
    if (this.type) {
      this.store.pipe(select(<any>getCrudModelData, {type: this.type}))
        .subscribe((items: any[]) => {
          this.items = items;
        });
    }
  }

  getRowCount() {
    return Math.min(this.limit, (this.totalCount || 0) - (this.items.length || 0));
  }
}
