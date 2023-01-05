import {Component, Input, OnInit} from '@angular/core';

@Component({
  selector: 'k-divider',
  templateUrl: './divider.component.html',
})
export class DividerComponent implements OnInit {

  @Input() inset: boolean = false;

  @Input() vertical: boolean = false;

  classes = {}

  constructor() {
  }

  ngOnInit(): void {
    this.classes = {
      'krv-divider': true,
      'theme--light': true,
      'krv-divider--inset': this.inset,
      'krv-divider--vertical': this.vertical,

    }
  }
}
