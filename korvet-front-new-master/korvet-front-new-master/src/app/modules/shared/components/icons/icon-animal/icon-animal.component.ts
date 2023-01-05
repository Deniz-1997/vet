import {Component, Input, OnInit} from '@angular/core';

@Component({
  selector: 'app-icon-animal',
  templateUrl: './icon-animal.component.html',
  styleUrls: ['./icon-animal.component.css'],
})
export class IconAnimalComponent implements OnInit {
  @Input() type: string;
  steps = '7px';
  show = false;

  constructor() {
  }

  ngOnInit() {

  }

  expand() {
    if (!this.show) {
     return  this.show = true;
    }
    this.show = false;

  }

}
