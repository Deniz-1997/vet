import {Component, ContentChild, HostBinding, Input, OnInit, TemplateRef} from '@angular/core';

@Component({
  selector: 'k-breadcrumbs-item, [k-breadcrumbs-item]',
  templateUrl: './breadcrumbs-item.component.html',
})
export class BreadcrumbsItemComponent implements OnInit {
  @ContentChild('itemsList', {static: true}) itemsList!: TemplateRef<any> | null;

  @Input('items') items!: any[];
  @Input() showDivider: boolean = false;

  @HostBinding('class')
  elementClass: Array<string> = [];

  constructor() {
  }

  ngOnInit(): void {
  }
}
