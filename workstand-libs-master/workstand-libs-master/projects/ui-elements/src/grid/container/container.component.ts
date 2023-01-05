import {Component, ContentChild, HostBinding, Input, OnInit, TemplateRef} from '@angular/core';

@Component({
  selector: 'k-container, [k-container]',
  template: `
    <div [className]="elementClass">
      <ng-template *ngTemplateOutlet="templateRef;"></ng-template>
    </div>
   `,
})
export class ContainerComponent implements OnInit {
  @ContentChild(TemplateRef)
  templateRef!: TemplateRef<any>;

  @Input() fluid: boolean = false;

  elementClass: string = 'container';

  constructor() {
  }

  ngOnInit() {
    if (this.fluid) {
      this.elementClass = 'container--fluid';
    }
  }
}
