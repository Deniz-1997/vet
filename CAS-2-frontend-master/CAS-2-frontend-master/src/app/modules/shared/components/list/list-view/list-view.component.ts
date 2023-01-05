import {Component, ContentChild, ElementRef, EventEmitter, Input, OnInit, Output, TemplateRef} from '@angular/core';
import {NgForOfContext} from '@angular/common';
import {select, Store} from '@ngrx/store';
import {CrudType} from 'src/app/common/crud-types';
import {
  getCrudModelAppendListLoading,
  getCrudModelData,
  getCrudModelGetListLoading
} from '../../../../../api/api-connector/crud/crud.selectors';
import {CrudDataInterface, CrudDataType} from '../../../../../api/api-connector/crud/crud.config';

@Component({
  selector: 'app-list-view',
  templateUrl: './list-view.component.html',
  styleUrls: ['./list-view.component.css']
})
export class ListViewComponent implements OnInit {

  @Input() emptyOptions: {
    elementName?: string,
    title?: string,
    subtitle?: string,
    addLinInvisible?: boolean,
    addLink?: Array<string> | string,
    buttons?: Array<{ title: string, action: string }>,
  };
  @Input() loading = false;
  @Input() appendLoading = false;
  @Input() items: Array<CrudDataType> | Array<CrudDataInterface> = [];
  @Input() limit = 0;
  @Input() offset = 0;
  @Input() totalCount: number;
  @Input() search: string;
  @Input() title: string;
  @Output() outAppend = new EventEmitter<{ limit: number, offset: number }>();
  @Output() outEmptyClick = new EventEmitter<{ action: string }>();
  @Input() type: CrudType;
  @Input() pageSize;
  @Output() setPageSize: EventEmitter<any> = new EventEmitter();

  pageSizeOptions = [20, 40, 60];

  @ContentChild('itemTemplate', {static: true}) itemTemplate: TemplateRef<NgForOfContext<CrudDataInterface | CrudDataType>>;
  @ContentChild('headTemplate', {static: true}) headTemplate: TemplateRef<ElementRef>;
  @ContentChild('actionsTemplate', {static: true}) titleActionsTemplate: TemplateRef<ElementRef>;
  @ContentChild('itemTemplateGrid', {static: true}) itemTemplateGrid: TemplateRef<NgForOfContext<CrudDataInterface | CrudDataType>>;

  constructor(private store: Store<CrudType>) {
  }

  ngOnInit(): void {
    if (this.type) {
      this.store.pipe(select(getCrudModelData as any, {type: this.type}))
        .subscribe((items: Array<any>) => {
          this.items = items;
        });
      this.store.pipe(select(getCrudModelGetListLoading as any, {type: this.type}))
        .subscribe((loading: boolean) => (this.loading = loading));
      this.store.pipe(select(getCrudModelAppendListLoading as any, {type: this.type}))
        .subscribe((loading: boolean) => (this.appendLoading = loading));
    }
  }

  getRowCount(): number {
    return Math.min(this.limit, (this.totalCount || 0) - (this.items.length || 0));
  }

  settingPageSize(size: any): void {
    this.setPageSize.emit(size);
  }
}
