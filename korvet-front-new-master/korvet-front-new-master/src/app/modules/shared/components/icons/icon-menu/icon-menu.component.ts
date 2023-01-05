import {Component, Input, OnInit} from '@angular/core';

@Component({
  selector: 'app-icon-menu',
  templateUrl: './icon-menu.component.html',
  styleUrls: ['./icon-menu.component.css']

})
export class IconMenuComponent implements OnInit {
  @Input() color = '#605f73';

  constructor() {
  }

  ngOnInit() {
  }

}
