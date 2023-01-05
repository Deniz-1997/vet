import {Component, ContentChild, ElementRef, EventEmitter, HostListener, Input, OnInit, Output, TemplateRef} from '@angular/core';
import {NgForOfContext} from '@angular/common';
import {select, Store} from '@ngrx/store';
import {CrudType} from 'src/app/common/crud-types';
import {CrudDataType, CrudDataInterface} from 'src/app/api/api-connector/crud/crud.config';
import {getCrudModelData, getCrudModelGetListLoading, getCrudModelAppendListLoading} from 'src/app/api/api-connector/crud/crud.selectors';

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
    addLink?: string[] | string,
    buttons?: { title: string, action: string }[],
  };
  @Input() loading = false;
  @Input() appendLoading = false;
  @Input() items: CrudDataType[] | CrudDataInterface[] = [];
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
  isMobile = false;

  @ContentChild('itemTemplate', {static: true}) itemTemplate: TemplateRef<NgForOfContext<CrudDataInterface | CrudDataType>>;
  @ContentChild('headTemplate', {static: true}) headTemplate: TemplateRef<ElementRef>;
  @ContentChild('actionsTemplate', {static: true}) titleActionsTemplate: TemplateRef<ElementRef>;
  @ContentChild('itemTemplateGrid', {static: true}) itemTemplateGrid: TemplateRef<NgForOfContext<CrudDataInterface | CrudDataType>>;

  constructor(private store: Store<CrudType>) {
  }

  ngOnInit(): void {
    this.isMobile = window.innerWidth <= 800;
    if (this.type) {
      this.store.pipe(select(<any>getCrudModelData, {type: this.type}))
        .subscribe((items: any[]) => {
          this.items = items;
        });
      this.store.pipe(select(<any>getCrudModelGetListLoading, {type: this.type}))
        .subscribe((loading: boolean) => (this.loading = loading));
      this.store.pipe(select(<any>getCrudModelAppendListLoading, {type: this.type}))
        .subscribe((loading: boolean) => (this.appendLoading = loading));
    }
  }

  getRowCount() {
    return Math.min(this.limit, (this.totalCount || 0) - (this.items.length || 0));
  }

  settingPageSize(size) {
    this.setPageSize.emit(size);
  }

  onResize(event) {
    this.isMobile = event;
  }
}
