import {Component, ContentChild, HostBinding, Input, OnInit, TemplateRef} from '@angular/core';

@Component({
  selector: 'app-row',
  templateUrl: './component.html',
})
export class GridRowComponent implements OnInit {
  @Input() class: string;
  @ContentChild('rowContent', {static: true}) rowContent: TemplateRef<any>;
  @HostBinding('class')
  elementClass = 'row pb-2';

  constructor() {
  }

  ngOnInit(): void {
    if (this.class !== undefined) {
      this.elementClass += ' ' + this.class;
    }
  }
}
