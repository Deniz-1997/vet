import { Component, Input, OnInit } from '@angular/core';

@Component({
  selector: 'app-icon-notifications',
  templateUrl: './icon.component.html',
  styleUrls: ['./icon.component.css']
})
export class IconNotificationComponent implements OnInit {

  @Input() animate: boolean = false;

  constructor() { }

  ngOnInit() {
  }

}
