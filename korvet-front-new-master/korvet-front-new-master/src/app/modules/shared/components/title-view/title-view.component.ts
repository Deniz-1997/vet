import { Component, ContentChild, Input, OnInit, TemplateRef } from '@angular/core';

@Component({
  selector: 'app-title-view',
  templateUrl: './title-view.component.html',
  styleUrls: ['./title-view.component.css']
})
export class TitleViewComponent implements OnInit {

  @Input() title: string;
  @ContentChild('actionsTemplate', {static: true}) actionsTemplate: TemplateRef<any>;

  constructor() { }

  ngOnInit() {
  }

}
