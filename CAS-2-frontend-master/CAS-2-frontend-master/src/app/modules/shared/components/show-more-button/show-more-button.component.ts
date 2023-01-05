import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {CrudType} from '../../../../common/crud-types';
import {select, Store} from '@ngrx/store';
import {getCrudModelData} from '../../../../api/api-connector/crud/crud.selectors';
import {CrudDataInterface, CrudDataType} from '../../../../api/api-connector/crud/crud.config';

@Component({
  selector: 'app-show-more-button',
  templateUrl: './show-more-button.component.html',
  styleUrls: ['./show-more-button.scss']
})
export class ShowMoreButtonComponent implements OnInit {
  @Input() limit;
  @Input() length;
  @Input() offset;
  @Input() items: Array<CrudDataType> | Array<CrudDataInterface> = [];
  @Input() type: CrudType;
  @Input() appendLoading;
  @Input() totalCount;
  @Output() outAppend = new EventEmitter<{ limit: number, offset: number }>();

  constructor(private store: Store<CrudType>) {
  }

  ngOnInit(): void {
    if (this.type) {
      this.store.pipe(select(getCrudModelData as any, {type: this.type}))
        .subscribe((items: Array<any>) => {
          this.items = items;
        });
    }
  }

  getRowCount(): number {
    return Math.min(this.limit, (this.totalCount || 0) - (this.items.length || 0));
  }
}
