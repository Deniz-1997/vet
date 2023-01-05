import {Component, Input, OnInit} from '@angular/core';
import {WildAnimalModel} from '../../../../../../models/wild/wild-animal.models';

@Component({
  selector: 'app-wild-animal-history',
  templateUrl: './history.component.html'
})
export class HistoryComponent implements OnInit {
  @Input() item: WildAnimalModel;
  @Input() loading: boolean;
  c = '#';
  g = '22';
  d = 'demo';

  constructor() {
  }

  ngOnInit() {
  }

}
