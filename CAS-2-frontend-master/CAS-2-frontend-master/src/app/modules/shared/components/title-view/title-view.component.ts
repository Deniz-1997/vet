import {Component, ContentChild, Input, OnInit, TemplateRef} from '@angular/core';

@Component({
  selector: 'app-title-view',
  templateUrl: './title-view.component.html',
  styleUrls: ['./title-view.component.css']
})
export class TitleViewComponent implements OnInit {

  @Input() public class = 'mt-3 mb-2';
  @Input() title: string;
  @ContentChild('actionsTemplate', {static: true}) actionsTemplate: TemplateRef<any>;

  constructor() {
  }

  ngOnInit(): void {
  }

}
