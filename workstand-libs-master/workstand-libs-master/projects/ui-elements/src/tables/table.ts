import {
  ContentChild, Directive,
  Input,
  OnInit,
  TemplateRef,
  ViewChild
} from "@angular/core";
import {MatTable} from "@angular/material/table";

export const isBoolean = (val: any) => 'boolean' === typeof val;

@Directive()
export class MainTable implements OnInit {
  @ViewChild(MatTable) matTable!: MatTable<any>;
  @ContentChild('tableHeaderContent', {static: true}) tableHeaderContent!: TemplateRef<any> | null;
  @ContentChild('tableRowContent', {static: true}) tableRowContent!: TemplateRef<any> | null;

  isDoneRenderRows = false;

  @Input() dataSource: any[] = [];
  @Input() rowIdName!: string;
  @Input() maxWidthInput!: string
  @Input() form: any;
  @Input() table!: any;
  @Input() showFooter?: boolean;
  @Input() showTotalRow?: boolean;
  @Input() headersName!: string[];
  @Input() headers!: any[];
  @Input() headersArray: Array<Array<any>> = new Array<Array<any>>();
  @Input() columns!: any[];

  constructor() {
  }

  ngOnInit(): void {
  }

  trackByFn(index: any, item: any) {
    return index;
  }
}
