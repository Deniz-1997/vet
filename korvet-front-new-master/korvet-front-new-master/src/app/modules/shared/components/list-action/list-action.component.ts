import {Component, Input, OnInit} from '@angular/core';
import {AbstractResources} from '../../../../resources/abstract-resources';

@Component({
  selector: 'app-list-action',
  templateUrl: './list-action.component.html',
  styleUrls: ['./list-action.component.css']
})
export class ListActionComponent implements OnInit {
  @Input() options: { title: string, api: AbstractResources };
  constructor() { }

  ngOnInit() {
  }

}
