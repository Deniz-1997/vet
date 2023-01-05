import {Component, ContentChild, HostBinding, Input, OnInit, TemplateRef} from '@angular/core';

@Component({
  selector: 'app-row',
  templateUrl: './component.html',
})
export class GridRowComponent implements OnInit {
  @Input() class: string;
  @ContentChild('rowContent', {static: true}) rowContent: TemplateRef<any>;
  @HostBinding('attr.class') elementClass = 'row mb-3';

  constructor() {
  }

  ngOnInit() {
    if (this.class !== undefined) {
      this.elementClass += ' ' + this.class;
    }
  }
}
