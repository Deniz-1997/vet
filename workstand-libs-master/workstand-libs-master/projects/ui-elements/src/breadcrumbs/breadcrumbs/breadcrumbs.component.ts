import {Component, ContentChild, OnInit, TemplateRef} from '@angular/core';

@Component({
  selector: 'k-breadcrumbs, [k-breadcrumbs]',
  templateUrl: './breadcrumbs.component.html',
})
export class BreadcrumbsComponent implements OnInit {
  @ContentChild('content', {static: true}) content!: TemplateRef<any> | null;

  classes = ['krv-breadcrumbs', 'theme--light'];

  constructor() {
  }

  ngOnInit(): void {
  }
}
